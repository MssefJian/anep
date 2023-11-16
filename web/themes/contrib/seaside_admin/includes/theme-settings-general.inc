<?php
/**
 * @file
 * General theme settings elements.
 */

use Drupal\Core\Form\FormState;
use Drupal\Core\Url;

/**
 * Alter the theme settings form to add our own settings.
 *
 * @param array $form
 *   The form array passed by reference.
 * @param FormState $form_state
 *   The form state array.
 */
function seaside_admin_form_system_theme_settings_alter(array &$form, FormState $form_state) {
  $form['seaside_admin_settings'] = array(
    '#type'   => 'vertical_tabs',
    '#weight' => -10,
    '#prefix' => '<h3>' . t('Configuration') . '</h3>',
  );

  seaside_admin_theme_settings_general($form, $form_state);
}

/**
 * Setup the theme settings form.
 */
function seaside_admin_theme_settings_general(&$form, &$form_state) {
  _seaside_admin_theme_get_colors($form);
  _seaside_admin_theme_get_breadcrumb($form);
}

/**
 * Layout form fields.
 */
function _seaside_admin_theme_get_colors(&$form) {
  $form['seaside_admin_color'] = array(
    '#type'  => 'details',
    '#title' => t('Color settings'),
    '#group' => 'seaside_admin_settings',
  );

  $form['seaside_admin_color']['seaside_admin_color_profile'] = array(
    '#type'          => 'select',
    '#title'         => t('Color profiles.'),
    '#default_value' => theme_get_setting('seaside_admin_color_profile'),
    '#options'       => array(
      'brighton' => t('Brighton'),
      'san-francisco'     => t('San Francisco'),
      'bondi'     => t('Bondi'),
    ),
  );
}

/**
 * Breadcrumb form fields.
 */
function _seaside_admin_theme_get_breadcrumb(&$form) {
  $form['seaside_admin_breadcrumb'] = array(
    '#type'  => 'details',
    '#title' => t('Breadcrumb settings'),
    '#group' => 'seaside_admin_settings',
  );
  $form['seaside_admin_breadcrumb']['breadcrumb_options'] = array(
    '#type'   => 'container',
    '#states' => array(
      'invisible' => array(
        ':input[name="seaside_admin_breadcrumb"]' => array('value' => 'no'),
      ),
    ),
  );
  $form['seaside_admin_breadcrumb']['breadcrumb_options']['seaside_admin_breadcrumb_home'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Show home page link in breadcrumb'),
    '#default_value' => theme_get_setting('seaside_admin_breadcrumb_home'),
  );
  $form['seaside_admin_breadcrumb']['breadcrumb_options']['seaside_admin_breadcrumb_trailing'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append a separator to the end of the breadcrumb'),
    '#default_value' => theme_get_setting('seaside_admin_breadcrumb_trailing'),
    '#description'   => t('Useful when the breadcrumb is placed just before the title.'),
    '#states'        => array(
      'disabled' => array(
        ':input[name="seaside_admin_breadcrumb_title"]' => array('checked' => TRUE),
      ),
    ),
  );
  $form['seaside_admin_breadcrumb']['breadcrumb_options']['seaside_admin_breadcrumb_title'] = array(
    '#type'          => 'checkbox',
    '#title'         => t('Append the content title to the end of the breadcrumb'),
    '#default_value' => theme_get_setting('seaside_admin_breadcrumb_title'),
    '#description'   => t('Useful when the breadcrumb is not placed just before the title.'),
  );
}

/**
 * A helper function to create a h3 tag.
 *
 * @param string $value
 *   The translated value for in the h3.
 *
 * @return string
 *   The h3 tag.
 */
function _seaside_admin_theme_create_prefix($value) {
  $meta_generator = array(
    '#type'  => 'html_tag',
    '#tag'   => 'h3',
    '#value' => $value,
  );

  return \Drupal::service('renderer')->render($meta_generator);
}
