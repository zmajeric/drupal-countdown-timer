<?php

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\countdown_timer\Services\CalculateDateDiff;
use Drupal\Tests\UnitTestCase;

class CalculateDateDiffTest extends UnitTestCase {

  protected function setUp() {
    parent::setUp();

    $calculateDateDiffService = new CalculateDateDiff();

    $container = new ContainerBuilder();

    Drupal::setContainer($container);
    $container->set('calculate_date_diff', $calculateDateDiffService);

  }

  /**
   * Checks if the service is created in the Drupal context.
   */
  public function testMyServiceGitCommands() {
    $this->assertNotNull(\Drupal::service('calculate_date_diff'));
  }

  public function testEventInPast() {


  }


}
