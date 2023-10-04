<?php

namespace Drupal\md_manager\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ContactConfigForm extends ConfigFormBase
{
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contact_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['md_manager.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('md_manager.settings');

    $languages = \Drupal::languageManager()->getLanguages();
    foreach ($languages as $language) {
      $langcode = $language->getId();
      if ($langcode !== 'en') {
        $form[$langcode] = [
          '#type' => 'details',
          '#title' => $this->t('Settings for @lang', ['@lang' => $language->getName()]),
          '#open' => FALSE, // Fieldset starts open.
        ];

        $form[$langcode]['address_'.$langcode] = [
          '#type' => 'textfield',
          '#title' => $this->t('Address'),
          '#default_value' => $config->get($langcode . '.address'),
        ];

        $form[$langcode]['phone_'.$langcode] = [
          '#type' => 'tel',
          '#title' => $this->t('Phone'),
          '#default_value' => $config->get($langcode . '.phone'),
        ];

        $form[$langcode]['fax_'.$langcode] = [
          '#type' => 'tel',
          '#title' => $this->t('Fax'),
          '#default_value' => $config->get($langcode . '.fax'),
        ];

        $form[$langcode]['email_'.$langcode] = [
          '#type' => 'email',
          '#title' => $this->t('Email'),
          '#default_value' => $config->get($langcode . '.email'),
        ];
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('md_manager.settings');

    $languages = \Drupal::languageManager()->getLanguages();
    foreach ($languages as $language) {
      $langcode = $language->getId();

      $config->set($langcode . '.address', $form_state->getValue('address_'.$langcode))
        ->set($langcode . '.phone', $form_state->getValue('phone_'.$langcode))
        ->set($langcode . '.fax', $form_state->getValue('fax_'.$langcode))
        ->set($langcode . '.email', $form_state->getValue('email_'.$langcode));
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }
}
