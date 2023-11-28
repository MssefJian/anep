<?php declare(strict_types = 1);

namespace Drupal\md_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;

/**
 * Provides a custom footer menu block.
 *
 * @Block(
 *   id = "md_manager_custom_footer_menu",
 *   admin_label = @Translation("Custom footer menu"),
 *   category = @Translation("Custom"),
 * )
 */
final class CustomFooterMenuBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {

    $menu = $this->buildMenuTreeWithChildren('main');

    $build['content'] = [
      '#theme' => 'footer_menu',
      '#menu' => $menu,
    ];
    return $build;
  }


  /**
   * Build an array of menu parent items and their children.
   *
   * @param string $menu_name
   *   The machine name of the menu.
   *
   * @return array
   *   An array of menu items with children.
   */
  protected function buildMenuTreeWithChildren($menu_name) {
    $menu_tree_service = \Drupal::service('menu.link_tree');

    // Load the menu tree.
    $parameters = new MenuTreeParameters();
    $parameters->onlyEnabledLinks();
    $tree = $menu_tree_service->load($menu_name, $parameters);

    $tree = array_filter($tree, function ($item) {
      return !empty($item->subtree);
    });
    // Build the menu tree with children.
    $menu_tree = [];



    foreach ($tree as $item) {
      $menu_tree_item = $this->buildMenuWithChildren($item, $menu_tree_service);
      if (!empty($menu_tree_item)) {
        $menu_tree[] = $menu_tree_item;
      }
    }

    return $menu_tree;
  }

  /**
   * Recursively build a menu item with its children.
   *
   * @param \Drupal\Core\Menu\MenuLinkInterface $menu_item
   *   The menu item to process.
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_tree_service
   *   The menu tree service.
   *
   * @return array
   *   An array representing the menu item and its children.
   */
  protected function buildMenuWithChildren($menu_item, MenuLinkTreeInterface $menu_tree_service): array {
    $menu_tree_item = [
        'title' => $menu_item->link->getTitle(),
        'url' => $menu_item->link->getUrlObject()->toString(),
    ];

    if (!empty($menu_item->subtree)) {
        $menu_tree_item['children'] = [];

        // Sort the subtree by weight.
        uasort($menu_item->subtree, function($a, $b) {
            return $a->link->getWeight() - $b->link->getWeight();
        });

        foreach ($menu_item->subtree as $child_item) {
            $child_tree_item = $this->buildMenuWithChildren($child_item, $menu_tree_service);
            if (!empty($child_tree_item)) {
                $menu_tree_item['children'][] = $child_tree_item;
            }
        }
    }

    return $menu_tree_item;
}


}
