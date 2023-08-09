<?php declare(strict_types=1);

namespace Drupal\md_carousel\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;

/**
 * Provides a home page slider block.
 *
 * @Block(
 *   id = "md_carousel_home_page_slider",
 *   admin_label = @Translation("Home page slider"),
 *   category = @Translation("Custom"),
 * )
 */
class HomePageSliderBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build(): array
  {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'home_page_caroussel')
      ->condition('status', 1)
      ->accessCheck(FALSE)
      ->execute();

    $node_ids = ($query);

// Load the node entities using the node IDs.
    $nodes = Node::loadMultiple($node_ids);
    $slide = [];
    foreach ($nodes as $node) {
      // Do something with each node, e.g., access fields or display data.
      $tmp['title'] = $node->getTitle();
      $image_field = $node->get('field_image');
      // Check if the image field has a value.
      if (!$image_field->isEmpty()) {
        $styled_image_url = ImageStyle::load('home_slide_1905x435')->buildUrl($image_field->entity->getFileUri());
        $tmp['image'] = $styled_image_url;
      }
      $slide[] = $tmp;

    }
    return [
      '#theme' => 'home_slide',
      '#slide' => $slide,
      '#attached' => ['library' => 'anep/slide']
    ];

  }
}
