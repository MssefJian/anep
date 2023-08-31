<?php

namespace Drupal\md_manager\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class OrganigramController extends ControllerBase
{

  protected $entityTypeManager;

  protected $languageManager;


  public function __construct(EntityTypeManagerInterface $entityTypeManager, LanguageManagerInterface $languageManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->languageManager = $languageManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('language_manager')
    );
  }
  /**
   * Display the custom text page.
   *
   * @return array
   *   A render array containing the static text.
   */
  public function build() {

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
      if ($block->hasTranslation($language->getId())) {
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
}
