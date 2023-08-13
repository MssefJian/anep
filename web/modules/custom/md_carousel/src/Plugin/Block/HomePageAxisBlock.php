<?php declare(strict_types=1);

namespace Drupal\md_carousel\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a home page axis block.
 *
 * @Block(
 *   id = "md_carousel_home_page_axis",
 *   admin_label = @Translation("Home page axis"),
 *   category = @Translation("Custom"),
 * )
 */
class HomePageAxisBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The vocabulary name
   */
  protected string $vocabularyName = 'axis';

  /**
   * Constructs a new HomePageAxisBlock instance.
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

  /**
   * {@inheritdoc}
   */
  public function build(): array
  {
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadTree($this->vocabularyName);

    $term_entities = [];
    foreach ($terms as $term) {
      $term_entity = $this->entityTypeManager->getStorage('taxonomy_term')->load($term->tid);
      if ($term_entity) {
        $term_entities[] = $term_entity;
      }
    }

    $axis = [];
    foreach ($term_entities as $term) {
      $tmp['title'] = $term->get('name')->value;
      $image_icon = $term->get('field_icon');
      // Check if the image field has a value.
      if (!$image_icon->isEmpty()) {
        $styled_image_url = ImageStyle::load('taxo_thumb')->buildUrl($image_icon->entity->getFileUri());
        $tmp['icon'] = $styled_image_url;
      }
      $axis[] = $tmp;
    }
    return [
      '#theme' => 'home_axis',
      '#axis' => $axis
    ];

  }
}
