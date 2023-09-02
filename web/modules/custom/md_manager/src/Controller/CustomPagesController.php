<?php

namespace Drupal\md_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CustomPagesController extends ControllerBase
{

  protected $entityTypeManager;

  protected $languageManager;


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
    $query = $this->entityTypeManager->getStorage('block_content')->getQuery()
      ->condition('type', 'organigramme')
      ->condition('langcode', $currentLang)
      ->accessCheck(FALSE)
      ->range(0, 1);
    $block_ids = $query->execute();

    $content = '';

    if (!empty($block_ids)) {
      $block_id = reset($block_ids);
      $block = $this->entityTypeManager->getStorage('block_content')->load($block_id);
      if ($block->hasTranslation($currentLang)) {
        $translatedBlock = $block->getTranslation($currentLang);
        $content = $translatedBlock->get('body')->value;
      } else {
        $content = $block->get('body')->value;
      }
    }


    return [
      '#theme' => 'organigramme',
      '#currentLang' => $currentLang,
      '#content' => $content ?? NULL,
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
      '#attached' => [
        'library' => ['md_manager/timeline']
      ]
    ];
  }
}
