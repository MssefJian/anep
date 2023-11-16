<?php

use Drupal\Core\Form\FormStateInterface;
/**
 * @file
 * bootstrap.inc
 *
 * Contains theme preprocessors to bootstrapize components.
 */

use Drupal\Component\Utility\SafeMarkup;
use Drupal\Core\Url;
use \Drupal\Core\Link;
use \Drupal\user\Entity\User;

require_once dirname(__FILE__) . '/seaside_admin.inc';

/**
 * Implements hook_preprocess_html().
 */
function seaside_admin_preprocess_html(array &$variables) {

  if($variables['logged_in']) {
    $user_id = $variables['user']->getAccount()->id();

    user_cookie_save(['id' => $user_id]);
  }

  // Setup the theme basics.
  seaside_admin_get_theme($variables);
  $color_profile = theme_get_setting('seaside_admin_color_profile');
  if (!empty($_COOKIE['Drupal_visitor_theme_profile'])) {
    $variables['attributes']['class'][] = 'profile-' . $_COOKIE['Drupal_visitor_theme_profile'];
  } elseif (!empty($color_profile)) {
    $variables['attributes']['class'][] = 'profile-' . $color_profile;
  }
  else {
    $variables['attributes']['class'][] = 'profile-brighton';
  }

  // Add our own class to the body.
  $variables['attributes']['class'][] = 'seaside-admin';

  // Add an extra class to the body when on the administration page.
  $current_path = \Drupal::service('path.current')->getPath();
  if ($current_path == '/admin/config') {
    $variables['attributes']['class'][] = 'seaside-admin-config-page';
  }

  // Add current path to body classes.
  $system_path = \Drupal::service('path.current')->getPath();
  $extra_classes = explode('/', $system_path);
  array_walk($extra_classes, function (&$item) {
    $item = 'path-' . $item;
  });
  array_shift($extra_classes);

  // Add the body classes to the attributes array.
  if (isset($variables['attributes']['class']) && is_array($variables['attributes']['class'])) {
    $variables['attributes']['class'] = array_merge($variables['attributes']['class'], $extra_classes);
  }
  elseif (isset($variables['attributes']['class']) && is_a($variables['attributes']['class'], 'Drupal\Core\Template\AttributeArray')) {
    $variables['attributes']['class'] = array_merge($variables['attributes']['class']->value(), $extra_classes);
  }
  else {
    $variables['attributes']['class'] = $extra_classes;
  }

  // Change the mime type of the favicon to make it work in all browsers.
  $favicon = theme_get_setting('favicon');
  $type = 'image/x-icon';
  $variables['#attached']['html_head_link'][] = array(
    array(
      'rel'  => 'shortcut icon',
      'href' => $favicon['url'],
      'type' => $type,
    ),
  );
  $theme = \Drupal::theme()->getActiveTheme();

  $variables['#attached']['drupalSettings']['currentThemePath'] = $theme->getPath();
}

/**
 * Implements hook_preprocess_block().
 *
 * Clean up the div classes for blocks
 */
function seaside_admin_preprocess_block(array &$variables) {
  // Replace first occurance of block- to clean up the ID's.
  if (substr($variables['attributes']['id'], 0, 6) === 'block-') {
    $variables['attributes']['id'] = substr($variables['attributes']['id'], 6);
  }

  $theme = \Drupal::theme()->getActiveTheme()->getName();
  if (strstr($variables['attributes']['id'], $theme)) {
    $variables['attributes']['id'] = str_replace($theme . '-', '', $variables['attributes']['id']);
  }
}

/**
 * Override or insert variables into the node templates.
 *
 * @param array $variables
 *   An array of variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function seaside_admin_preprocess_node(array &$variables, $hook) {
  /** @var \Drupal\node\Entity\Node $node */
  $node = $variables['node'];
  $function = __FUNCTION__ . '_' . $node->bundle();
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}

/**
 * Override or insert variables into the field templates.
 *
 * @param array $variables
 *   An array of variables to pass to the theme template.
 * @param string $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function seaside_admin_preprocess_field(&$variables) {
  $element = $variables['element'];

  // There's some overhead in calling check_plain() so only call it if the label
  // variable is being displayed. Otherwise, set it to NULL to avoid PHP
  // warnings if a theme implementation accesses the variable even when it's
  // supposed to be hidden. If a theme implementation needs to print a hidden
  // label, it needs to supply a preprocess function that sets it to the
  // sanitized element title or whatever else is wanted in its place.
  $variables['label_hidden'] = ($element['#label_display'] == 'hidden');
  if (empty($variables['label'])) {
    $variables['label'] = $variables['label_hidden'] ? NULL : SafeMarkup::checkPlain($element['#title']);
  }
}

/**
 * Implements hook_preprocess_breadcrumb().
 */
function seaside_admin_preprocess_breadcrumb(&$variables) {
  $breadcrumb = &$variables['breadcrumb'];

  // Retrieve the breadcrumb settings.
  $show_breadcrumb_home = theme_get_setting('seaside_admin_breadcrumb_home');
  $add_trailing = theme_get_setting('seaside_admin_breadcrumb_trailing');
  $add_title = theme_get_setting('seaside_admin_breadcrumb_title');

  $variables['trailing'] = $add_trailing;

  // Optionally get rid of the homepage link.
  if (!$show_breadcrumb_home) {
    array_shift($breadcrumb);
  }

  if ($add_title && !empty($breadcrumb)) {
    $request = \Drupal::request();
    $route_match = \Drupal::routeMatch();
    $page_title = \Drupal::service('title_resolver')
      ->getTitle($request, $route_match->getRouteObject());

    if (!empty($page_title)) {
      $breadcrumb[] = array(
        'text' => $page_title,
      );
    }
  }
}

/**
 * Implements hook_page_attachments_alter().
 *
 * - Add meta tags to the html header.
 */
function seaside_admin_page_attachments_alter(&$variables) {
  // Add viewport element.
  $viewport = theme_get_setting('seaside_admin_viewport');
  if (!empty($viewport)) {
    $user_scalable = theme_get_setting('seaside_admin_viewport_user_scaleable');
    $initial_scale = theme_get_setting('seaside_admin_viewport_initial_scale');
    $minimum_scale = theme_get_setting('seaside_admin_viewport_minimum_scale');
    $maximum_scale = theme_get_setting('seaside_admin_viewport_maximum_scale');
    $content = "width=device-width, initial-scale=" . $initial_scale . ", minimum-scale=" . $minimum_scale . ", maximum-scale=" . $maximum_scale . ", user-scalable=" . $user_scalable;

    $element = array(
      array(
        '#tag'        => 'meta',
        '#attributes' => array(
          'name'    => 'viewport',
          'content' => $content,
        ),
      ),
      'viewport',
    );
    $variables['#attached']['html_head'][] = $element;
  }

  // Add cleartype.
  $cleartype = theme_get_setting('seaside_admin_cleartype');
  if (!empty($cleartype)) {
    $element = array(
      array(
        '#tag'        => 'meta',
        '#attributes' => array(
          'name'    => 'http-equiv',
          'content' => 'on',
        ),
      ),
      'cleartype',
    );

    $variables['#attached']['html_head'][] = $element;
  }
}

/**
 * Implements hook_form_alter().
 */
function seaside_admin_form_alter(&$form, FormStateInterface $form_state, $form_id) {
  switch ($form_id) {
    case 'user_login_form':
      $form['#attached']['library'][] = 'seaside_admin/user_login_hint';
      Drupal::service('page_cache_kill_switch')->trigger();

      $user_pic = '';
      $user_name = '';
      $internal_link = '<a href="#" class="hide-hint">' . t('Click here') . '</a>';

      if(isset($_COOKIE['Drupal_visitor_id']) && $_COOKIE['Drupal_visitor_id'] != 0) {
        $user = User::load($_COOKIE['Drupal_visitor_id']);

        // Check for user profile pic.  If nothing exists go to gravatar.
        $image_source = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->get('mail')->value))) . '?s=80&r=pg&d=mp';

        if(!$user->get('user_picture')->isEmpty()) {
          $pic_uri = $user->get('user_picture')->entity->getFileUri();
          $pic_url = \Drupal::service('file_url_generator')->generateAbsoluteString($pic_uri);

          $image_source = $pic_url;
        }

        $user_pic = $image_source;
        $user_name = $user->getAccountName();
      }

      if(!empty($user_name)) {
        $return_visit = '<div class="hint"><img src="'. $user_pic .' " /><p>' . t('Welcome back, ') . $user_name . '<br />' . t('Not you? ') . $internal_link . '</p></div>';

        $form['#attached']['drupalSettings']['imageMarkup'] = $return_visit;
        $form['#attached']['drupalSettings']['username'] = $user_name;
      }

      $form['name']['#title_display'] = 'invisible';
      $form['name']['#attributes'] = array(
        'placeholder' => $form['name']['#title']->render(),
      );

      unset($form['name']['#description']);
      $form['pass']['#title_display'] = 'invisible';
      $form['pass']['#attributes'] = array(
        'placeholder' => $form['pass']['#title']->render(),
      );
      unset($form['pass']['#description']);
      break;

    case 'user_pass':
      $form['mail']['#prefix'] = '<p class="description">';

      $form['name']['#title_display'] = 'invisible';
      $form['name']['#attributes'] = array(
        'placeholder' => $form['name']['#title']->render(),
      );
      break;

    case 'user_register_form':
      $form['account']['name']['#title_display'] = 'invisible';
      $form['account']['name']['#attributes'] = array(
        'placeholder' => $form['account']['name']['#title']->render(),
      );

      $form['account']['mail']['#title_display'] = 'invisible';
      $form['account']['mail']['#attributes'] = array(
        'placeholder' => $form['account']['mail']['#title']->render(),
      );

      break;
  }
}
