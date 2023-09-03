<?php

namespace Drupal\md_manager\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CustomPagesController extends ControllerBase
{

  protected $entityTypeManager;

  protected $languageManager;

  const TYPE_ORGANIGRAMME = 'organigramme';

  const TYPE_TIMELINE = 'timeline';

  const TYPE_BASIC = 'basic';


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
    $data = $this->getBlockType(self::TYPE_ORGANIGRAMME);

    return [
      '#theme' => 'organigramme',
      '#currentLang' => $currentLang,
      '#content' => $data['content'] ?? NULL,
    ];
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
      $tmp['id'] = 'DataChart-'.$key;
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

    $content = $content2 = $title = '';
    if (!empty($block_ids)) {
      $block_id = reset($block_ids);
      $block = $this->entityTypeManager->getStorage('block_content')->load($block_id);
      if ($block->hasTranslation($currentLang)) {
        $translatedBlock = $block->getTranslation($currentLang);
        $title = $translatedBlock->get('info')->value;
        $content = $translatedBlock->get('body')->value;
        $content2 = $type == 'basic' ? $translatedBlock->get('field_description_2')->value : '';
      } else {
        $title = $block->get('info')->value;
        $content = $block->get('body')->value;
        $content2 = $type == 'basic' ? $block->get('field_description_2')->value : '';
      }
    }
    return [
      'title' => $title,
      'content' => $content,
      'content2' => $content2
    ];

  }
}
