<?php declare(strict_types=1);
namespace Drupal\md_manager\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\image\Entity\ImageStyle;
/**
 * Provides a 'CustomOrganigrammeBlock' block.
 *
 * @Block(
 *  id = "custom_organigramme_bloc01",
 *  admin_label = @Translation("Custom Organigramme Block 022"),
 * )
 */
class OrganigrammeBlock extends BlockBase implements ContainerFactoryPluginInterface {

  const TYPE_ORGANIGRAMME = 'organigramme';
      /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The vocabulary name
   */
  protected string $vocabularyName = 'organigramme';

  /**
   * Constructs a new HomePageAxisBlockInterne instance.
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

  public function build() {
    $language = \Drupal::languageManager()->getCurrentLanguage();
    $currentLang = $language->getId();

    //Format org

    $terms = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadTree('organigramme');

    $orgChartData = $orgChartNodes = [];
    foreach ($terms as $key => $term) {
      $termEntity = $this->entityTypeManager->getStorage('taxonomy_term')->load($term->tid);
      $parentId = $termEntity->get('parent')->target_id;
      $orgChartNodes[] = [
        'id' => $termEntity->id(),
        'title' => '',
        'name' => $termEntity->get('name')->value,
        'image' => 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg',
        'height' => 80,
        'width' => 300,
        /*'dataLabels' => [
        'enabled' => true,
        'style' => [
          'fontSize' => '15px'
        ]
      ]*/
      ];
      if (!$termEntity->get('field_level')->isEmpty()) {
        $orgChartNodes[$key]['level'] = intval($termEntity->get('field_level')->value);
      }
      if (!$termEntity->get('field_offset')->isEmpty()) {
        $orgChartNodes[$key]['offset'] = $termEntity->get('field_offset')->value.'%';
      }

      if ($parentId != '0') {
        $levels[] = $parentId;
        // Construct the "from" and "to" entries.
        $parentEntity = $this->entityTypeManager->getStorage('taxonomy_term')->load($parentId);
        $orgChartData[] = [$parentEntity->id(), $term->tid];
      }
    }

    //END Format org

    //$data = $this->getBlockType(self::TYPE_ORGANIGRAMME);

    return [
      '#theme' => 'organigramme_block',
      '#currentLang' => $currentLang,
      //'#content' => $data['content'] ?? NULL,
      '#attached' => [
        'library' => [
          'md_manager/libs',
          'md_manager/organigramme'
        ],
        'drupalSettings' => [
          'mdManager' => [
            'orgChartData' => $orgChartData,
            'orgChartNodes' => $orgChartNodes,
            'levels' => count(array_unique($levels))
          ],
        ],
      ]
    ];
  }
}
