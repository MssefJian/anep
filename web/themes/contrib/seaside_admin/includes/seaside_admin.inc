<?php

/**
 * @file
 * Contains functions needed for the theme.
 */

global $_seaside_admin_path_to_core;
$_seaside_admin_path_to_core = \Drupal::service('extension.list.theme')->getPath('seaside_admin');

/**
 * Get theme functionalities.
 */
function seaside_admin_get_theme(&$variables) {
  seaside_admin_load_debuggers($variables);
  seaside_admin_add_libraries($variables);
}

/**
 * Get the path to the static files folder in the theme.
 */
function seaside_admin_get_staticpath($addprefix = FALSE, $theme_key = 'seaside_admin') {
  $ret = '';
  if ($addprefix) {
    $ret = '/';
  }
  $ret .= \Drupal::service('extension.list.theme')->getPath($theme_key) . '/static/';
  return $ret;
}

/**
 * Add libraries to the theme.
 */
function seaside_admin_add_libraries(&$variables) {
  $enquire = theme_get_setting('seaside_admin_enquire_js');
  if (!empty($enquire)) {
    $variables['page']['#attached']['library'][] = 'seaside_admin/enquire';
  }

  $transit = theme_get_setting('seaside_admin_transit_js');
  if (!empty($transit)) {
    $variables['page']['#attached']['library'][] = 'seaside_admin/transit';
  }

  $placeholder = theme_get_setting('seaside_admin_placeholder_js');
  if (!empty($placeholder)) {
    $variables['page']['#attached']['library'][] = 'seaside_admin/placeholder';
  }

  $prettify = theme_get_setting('seaside_admin_prettify_js');
  if (!empty($prettify)) {
    $variables['page']['#attached']['drupalSettings']['seaside_admin']['prettify_hide_upload'] = theme_get_setting('seaside_admin_prettify_js_hide_upload');
    $variables['page']['#attached']['drupalSettings']['seaside_admin']['prettify_add_remove'] = theme_get_setting('seaside_admin_prettify_js_add_remove');
    $variables['page']['#attached']['library'][] = 'seaside_admin/prettify';
  }
}

/**
 * Load debugging helper files.
 */
function seaside_admin_load_debuggers(&$variables) {
  $debug = FALSE;
  // Load window size bookmarklet.
  if (theme_get_setting('seaside_admin_show_window_size') === 1) {
    $debug = TRUE;
    $variables['page']['#attached']['library'][] = 'seaside_admin/windowsize';
  }
  if (theme_get_setting('seaside_admin_show_grid') === 1) {
    $debug = TRUE;
    $variables['page']['#attached']['library'][] = 'seaside_admin/grid';
  }
  if ($debug) {
    \Drupal::messenger()->addWarning(t('Please turn off Forty Two debuggers on production websites.'));
  }
}
