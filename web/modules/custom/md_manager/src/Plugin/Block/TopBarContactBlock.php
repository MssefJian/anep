<?php declare(strict_types=1);

namespace Drupal\md_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\image\Entity\ImageStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a contact information.
 *
 * @Block(
 *   id = "md_manager_topbar_contact",
 *   admin_label = @Translation("Topbar contact"),
 *   category = @Translation("Custom"),
 * )
 */
class TopBarContactBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new TopBarContactBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *    The config factory service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array
  {
    $config = $this->configFactory->getEditable('md_manager.settings');
    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $contact = [
      'address' => $config->get($lang . '.address'),
      'phone' => $config->get($lang . '.phone'),
      'email' => $config->get($lang . '.email'),
    ];
    return [
      '#theme' => 'top_bar',
      '#contact' => $contact
    ];

  }
}
