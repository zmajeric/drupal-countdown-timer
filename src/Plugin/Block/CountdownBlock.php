<?php

namespace Drupal\countdown_timer\Plugin\Block;

use DateTime;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\countdown_timer\Services\CalculateDateDiff;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Countdown' Block.
 *
 * @Block(
 *   id = "countdown_block",
 *   admin_label = @Translation("Countdown block"),
 *   category = @Translation("Countdown block"),
 *   context = {
 *     "node" = @ContextDefinition("entity:node", required = FALSE)
 *   },
 *   context_definitions = {
 *     "node" = @ContextDefinition("entity:node", label = @Translation("Node"))
 *   }
 * )
 *
 */
class CountdownBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var CalculateDateDiff
   */
  private $dateDiffCalculator;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, $dateCalculator) {
    parent::__construct($configuration,$plugin_id,$plugin_definition);
    $this->dateDiffCalculator = $dateCalculator;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('calculate_date_diff')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node == NULL || $node->getType() != 'event') {
      // This is fail safe check in case the block on Drupal admin side is not set to show only on
      // Event content types.
      return;
    }

    $eventDateTimeString = $node->get('field_date')->getValue()[0]['value'];
    $eventDateTime = new DateTime($eventDateTimeString);

    return [
      '#markup' => $this->t($this->dateDiffCalculator->calculate($eventDateTime)),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }
}
