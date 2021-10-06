<?php

namespace Drupal\countdown_timer\Plugin\Block;

use DateTime;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;

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
class CountdownBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var Entity/Node $node */
    $node = \Drupal::routeMatch()->getParameter('node');
    if ( $node == NULL || $node->getType() != 'event') {
      // This is fail safe check in case the block on Drupal admin side is not set to show only on
      // Event content types.
      return;
    }

    $eventDateTimeString = $node->get('field_date')->getValue()[0]['value'];
    $eventDateTime = new DateTime($eventDateTimeString);
    $dtDifference = $eventDateTime->diff(new DateTime());

    if ($dtDifference->invert === 0) {
      return [
        '#markup' => $this->t('This event already passed.'),
      ];
    }

    if ($dtDifference->days < 1) {
      return [
        '#markup' => $this->t('This event is happening today.'),
      ];
    } else {
      return [
        '#markup' => $this->t($dtDifference->days . ' days left until event starts.'),
      ];
    }
  }


  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
