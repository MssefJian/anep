<?php declare(strict_types=1);
namespace Drupal\md_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\image\Entity\ImageStyle;
use Drupal\field\Entity\FieldConfig;
use Drupal\node\Entity\Node;
use Drupal;
/**
 * Provides a 'CustomOrganigrammeBlock' block.
 *
 * @Block(
 *  id = "custom_organigramme_bloc01",
 *  admin_label = @Translation("Custom Organigramme Block 022"),
 * )
 */
class OrganigrammeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  const TYPE_ORGANIGRAMME = 'organigramme';
  const TAXONOMY_TERM = 'taxonomy_term';
  const FIELD_IMAGE = 'field_o_image';

  const IMAGE_STYLE = 'organigramme90x90';
      /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The vocabulary name
   */
  protected string $vocabularyName = 'organigramme';

  /**
   * Constructs a new HomePageAxisBlockInterne instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

    /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  public function build() {
    $language = \Drupal::languageManager()->getCurrentLanguage();
    $currentLang = $language->getId();

    //Format org

    $terms = $this->entityTypeManager->getStorage(self::TAXONOMY_TERM)
      ->loadTree(self::TYPE_ORGANIGRAMME);

    $orgChartData = $orgChartNodes = [];
    foreach ($terms as $key => $term) {
      $termEntity = $this->entityTypeManager->getStorage(self::TAXONOMY_TERM)->load($term->tid);
      $termEntity = \Drupal::service('entity.repository')->getTranslationFromContext($termEntity, $language->getId());
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

    // $data = $this->getBlockType(self::TYPE_ORGANIGRAMME);

    return [
      '#theme' => 'organigramme',
      '#currentLang' => $currentLang,
  //    '#content' => $data['content'] ?? NULL,
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

}
