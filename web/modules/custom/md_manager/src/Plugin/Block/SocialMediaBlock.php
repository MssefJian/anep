<?php declare(strict_types=1);

namespace Drupal\md_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;

/**
 * Provides a home page slider block.
 *
 * @Block(
 *   id = "md_manager_social_media",
 *   admin_label = @Translation("Social links"),
 *   category = @Translation("Custom"),
 * )
 */
class SocialMediaBlock extends BlockBase
{

  /**
   * {@inheritdoc}
   */
  public function build(): array
  {
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'social_media')
      ->condition('status', 1)
      ->accessCheck(FALSE)
      ->execute();

    $node_ids = ($query);

// Load the node entities using the node IDs.
    $nodes = Node::loadMultiple($node_ids);
    $social = [];
    foreach ($nodes as $node) {
      // Do something with each node, e.g., access fields or display data.
      $tmp['title'] = $node->getTitle();
      $tmp['link'] = $node->get('field_link')->value;
      $social[] = $tmp;

    }
    return [
      '#theme' => 'social_links',
      '#social' => $social
    ];

  }
}
