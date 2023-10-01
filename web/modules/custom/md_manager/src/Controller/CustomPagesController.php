<?php

namespace Drupal\md_manager\Controller;

use Drupal;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CustomPagesController extends ControllerBase
{

  protected $entityTypeManager;

  protected $languageManager;

  const TYPE_ORGANIGRAMME = 'organigramme';
  const TAXONOMY_TERM = 'taxonomy_term';

  const FIELD_IMAGE = 'field_o_image';

  const IMAGE_STYLE = 'organigramme90x90';

  const TYPE_TIMELINE = 'timeline';

  const TYPE_BASIC = 'basic';

  const TYPE_STATES = 'direction_regional';


  public function __construct(EntityTypeManagerInterface $entityTypeManager, LanguageManagerInterface $languageManager)
  {
    $this->entityTypeManager = $entityTypeManager;
    $this->languageManager = $languageManager;
  }

  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('language_manager')
    );
  }

  /**
   * Display the Organigramme page.
   *
   * @return array
   *   A render array containing the static text.
   */
  public function organigramme()
  {

    $language = $this->languageManager->getCurrentLanguage();
    $currentLang = $language->getId();

    //Format org

    $terms = $this->entityTypeManager->getStorage(self::TAXONOMY_TERM)
      ->loadTree(self::TYPE_ORGANIGRAMME);

    $orgChartData = $orgChartNodes = [];
    foreach ($terms as $key => $term) {
      $termEntity = $this->entityTypeManager->getStorage(self::TAXONOMY_TERM)->load($term->tid);
      $parentId = $termEntity->get('parent')->target_id;
      $orgChartNodes[] = [
        'id' => $termEntity->id(),
        'title' => '',
        'name' => $termEntity->get('name')->value,
        'height' => 80,
        'width' => 300,
        /*'dataLabels' => [
        'enabled' => true,
        'style' => [
          'fontSize' => '15px'
        ]
      ]*/
      ];
      $imageField = $termEntity->get(self::FIELD_IMAGE);

      $styledImageUrl = NULL;
      // Check if the image field has a value.
      if (!$imageField->isEmpty()) {
        $styledImageUrl = ImageStyle::load(self::IMAGE_STYLE)->buildUrl($imageField->entity->getFileUri());
      }
      $orgChartNodes[$key]['image'] = $styledImageUrl ?? $this->getDefaultFieldValue(
        self::TAXONOMY_TERM, self::TYPE_ORGANIGRAMME, self::FIELD_IMAGE, self::IMAGE_STYLE
      );

      if (!$termEntity->get('field_color')->isEmpty()) {
        $orgChartNodes[$key]['color'] = $termEntity->get('field_color')->color_pickr;
      }
      if (!$termEntity->get('field_level')->isEmpty()) {
        $orgChartNodes[$key]['level'] = intval($termEntity->get('field_level')->value);
      }
      if (!$termEntity->get('field_offset')->isEmpty()) {
        $orgChartNodes[$key]['offset'] = $termEntity->get('field_offset')->value.'%';
      }

      if ($parentId != '0') {
        $levels[] = $parentId;
        // Construct the "from" and "to" entries.
        $parentEntity = $this->entityTypeManager->getStorage('taxonomy_term')->load($parentId);
        $orgChartData[] = [$parentEntity->id(), $term->tid];
      }
    }

    //END Format org

    $data = $this->getBlockType(self::TYPE_ORGANIGRAMME);

    return [
      '#theme' => 'organigramme',
      '#currentLang' => $currentLang,
      '#content' => $data['content'] ?? NULL,
      '#attached' => [
        'library' => [
          'md_manager/libs',
          'md_manager/organigramme'
        ],
        'drupalSettings' => [
          'mdManager' => [
            'orgChartData' => $orgChartData,
            'orgChartNodes' => $orgChartNodes,
            'levels' => count(array_unique($levels))
          ],
        ],
      ]
    ];
  }

  /**
   * Get default field value
   *
   * @param $entityTypeId
   * @param $bundle
   * @param $field
   * @param $imageStyle
   * @return Drupal\Core\GeneratedUrl|string
   */
  private function getDefaultFieldValue($entityTypeId, $bundle, $field, $imageStyle): Drupal\Core\GeneratedUrl|string
  {
    $field_info = FieldConfig::loadByName($entityTypeId, $bundle, $field);
    $image_uuid = $field_info->getSetting('default_image')['uuid'];
    $image = Drupal::service('entity.repository')->loadEntityByUuid('file', $image_uuid);
    $imageUri = $image->getFileUri();
    return ImageStyle::load($imageStyle)->buildUrl($imageUri);
  }

  /**
   * Display Presentation page
   * @return array
   *    A render array containing the static text.
   */
  public function presentation()
  {

    $langcode = $this->languageManager->getCurrentLanguage()->getId();

    $data = $this->getBlockType(self::TYPE_TIMELINE);
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'timeline')
      ->condition('langcode', $langcode, '=')
      ->condition('status', 1)
      ->sort('field_year', 'DESC')
      ->accessCheck(FALSE)
      ->execute();


// Load the node entities using the node IDs.
    $nodes = Node::loadMultiple($query);
    $timeLines = [];
    foreach ($nodes as $node) {

      $translation = $node->hasTranslation($langcode);
      $tmp['title'] = $translation ? $node->getTranslation($langcode)->get('title')->value : $node->getTitle();
      $tmp['year'] = $translation ? $node->getTranslation($langcode)->get('field_year')->value : $node->get('field_year')->value;
      $timeLines[] = $tmp;
    }

    return [
      '#theme' => 'timeline',
      '#timeLines' => $timeLines,
      '#data' => $data,
      '#attached' => [
        'library' => ['md_manager/timeline']
      ]
    ];
  }

  /**
   * Display human Resources page
   * @return array
   *    A render array containing data of the page.
   */
  public function humanResources()
  {

    $data = $this->getBlockType(self::TYPE_BASIC);
    $langcode = $this->languageManager->getCurrentLanguage()->getId();
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'human_ressources')
      ->condition('langcode', $langcode, '=')
      ->condition('status', 1)
      ->accessCheck(FALSE)
      ->execute();


// Load the node entities using the node IDs.
    $nodes = Node::loadMultiple($query);
    $humanResources = [];
    foreach ($nodes as $key => $node) {
      $translation = $node->hasTranslation($langcode);
      $tmp['id'] = 'DataChart-' . $key;
      $tmp['title'] = $translation ? $node->getTranslation($langcode)->get('title')->value : $node->getTitle();
      //$tmp['dataChart'] = $translation ? $node->getTranslation($langcode)->get('field_year')->value : $node->get('field_year')->value;
      $paragraph_field = $node->get('field_distribution'); // Replace 'field_paragraph_field_name' with the actual field name.

      foreach ($paragraph_field as $item) {
        // Iterate through each paragraph item.
        $paragraph_item = $item->entity;

        // Retrieve the values of the paragraph fields.
        $tmp['dataChart'][] = [
          'name' => $paragraph_item->get('field_description')->value,
          'y' => intval($paragraph_item->get('field_repartition_pourcent')->value)
        ];


      }
      $humanResources[] = $tmp;
      unset($tmp);
    }

    return [
      '#theme' => 'human_resources',
      '#humanResources' => $humanResources,
      '#data' => $data,
      '#attached' => [
        'library' => [
          'md_manager/human_resources'
        ],
        'drupalSettings' => [
          'mdManager' => [
            'rh' => $humanResources,
          ],
        ],
      ]
    ];
  }

  /**
   * Display human Resources page
   * @return array
   *    A render array containing data of the page.
   */
  public function states()
  {

    $paragraph_field = $this->getBlockType(self::TYPE_STATES);

    foreach ($paragraph_field['direction'] as $item) {
      // Iterate through each paragraph item.
      $paragraph_item = $item->entity;

      // Retrieve the values of the paragraph fields.
      $tmp['title'] = !empty($paragraph_item) ? $paragraph_item->get('field_title')->value : '';
      $dr = !empty($paragraph_item) ? $paragraph_item->get('field_direction_regional') : '';
      foreach ($dr as $subDr) {
        $subParagraphItem = $subDr->entity;
        $tmp['subRegion'][] = [
          'title' => $subParagraphItem->get('field_sub_region')->getString(),
          'path' => $subParagraphItem->get('field_path')->value
        ];
        //$tmp['titles'][] = $subParagraphItem->get('field_sub_region')->getValue();
        //$tmp['path'][] = $subParagraphItem->get('field_path')->value;
      }
      $data[] = $tmp;
      unset($tmp);
    }
    $pathArray = array_reduce($data, function ($carry, $item) {
      if (isset($item['subRegion'])) {
        // Extract "path" from subRegion array
        $subRegionPaths = array_column($item['subRegion'], 'path');
        $carry = array_merge($carry, $subRegionPaths);
      }
      return $carry;
    }, []);

    return [
      '#theme' => 'states',
      '#paths' => $pathArray,
      '#data' => $data,
      '#attached' => [
        'library' => [
          'md_manager/states'
        ],
      ]
    ];
  }

  public function extractPath($item)
  {
    if (isset($item['path'])) {
      return $item['path'];
    }
  }

  /**
   * @param string $type
   * @return array
   */
  private function getBlockType(string $type): array
  {
    $language = $this->languageManager->getCurrentLanguage();
    $currentLang = $language->getId();
    $query = $this->entityTypeManager->getStorage('block_content')->getQuery()
      ->condition('type', $type)
      ->condition('langcode', $currentLang)
      ->accessCheck(FALSE)
      ->range(0, 1);
    $block_ids = $query->execute();

    $content = $content2 = $title = $direction = NULL;
    if (!empty($block_ids)) {
      $block_id = reset($block_ids);
      $block = $this->entityTypeManager->getStorage('block_content')->load($block_id);
      if ($block->hasTranslation($currentLang)) {
        $translatedBlock = $block->getTranslation($currentLang);
        $title = $translatedBlock->get('info')->value;
        $content = $type !== 'direction_regional' ? $translatedBlock->get('body')->value : '';
        $content2 = $type == 'basic' ? $translatedBlock->get('field_description_2')->value : '';
        $direction = $type == 'direction_regional' ? $translatedBlock->get('field_direction') : '';
      } else {
        $title = $block->get('info')->value;
        $content = $type !== 'direction_regional' ? $block->get('body')->value : '';
        $content2 = $type == 'basic' ? $block->get('field_description_2')->value : '';
        $direction = $type == 'direction_regional' ? $block->get('field_direction') : '';

      }
    }
    /* Test */
    $direction = $type == 'direction_regional' ? $block->get('field_direction') : '';
    return [
      'title' => $title,
      'content' => $content,
      'content2' => $content2,
      'direction' => $direction
    ];

  }
}
