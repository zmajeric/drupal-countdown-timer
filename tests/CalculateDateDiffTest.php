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

  public function testEventToday() {
    /** @var CalculateDateDiff $dateDiffService */
    $dateDiffService = \Drupal::service('calculate_date_diff');
    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2021-12-12T19:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);

    $expectedOutput = 'This event is happening today.';
    $this->assertEquals($expectedOutput, $output);
  }

  public function testEventInPast() {
    $expectedOutput = 'This event already passed.';

    /** @var CalculateDateDiff $dateDiffService */
    $dateDiffService = \Drupal::service('calculate_date_diff');
    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2021-12-08T19:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);

    $this->assertEquals($expectedOutput, $output);
  }

  public function testEventInPast_Multiple() {
    $expectedOutput = 'This event already passed.';

    /** @var CalculateDateDiff $dateDiffService */
    $dateDiffService = \Drupal::service('calculate_date_diff');
    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2021-12-08T19:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);
    $this->assertEquals($expectedOutput, $output);

    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2000-12-08T19:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);
    $this->assertEquals($expectedOutput, $output);

    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2003-12-08T19:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);
    $this->assertEquals($expectedOutput, $output);

    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('1995-12-08T00:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);
    $this->assertEquals($expectedOutput, $output);

    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2015-12-08T24:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);
    $this->assertEquals($expectedOutput, $output);
  }

  public function testEventInFuture() {
    $expectedOutput = '4 days left until event starts.';

    /** @var CalculateDateDiff $dateDiffService */
    $dateDiffService = \Drupal::service('calculate_date_diff');
    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2021-12-16T19:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);

    $this->assertEquals($expectedOutput, $output);
  }

  public function testEventInFuture_DifferenceInHours() {

    /** @var CalculateDateDiff $dateDiffService */
    $dateDiffService = \Drupal::service('calculate_date_diff');

    $referenceTime = new DateTime('2021-12-11T10:00:00');
    $eventTime = new DateTime('2021-12-16T03:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);

    $expectedOutput = '4 days left until event starts.';
    $this->assertEquals($expectedOutput, $output);
  }

  public function testEventInFuture_1year() {
    $expectedOutput = '369 days left until event starts.';

    /** @var CalculateDateDiff $dateDiffService */
    $dateDiffService = \Drupal::service('calculate_date_diff');
    $referenceTime = new DateTime('2021-12-12T10:00:00');
    $eventTime = new DateTime('2022-12-16T19:00:00');
    $output = $dateDiffService->calculateReferenceTime($referenceTime, $eventTime);

    $this->assertEquals($expectedOutput, $output);
  }

}
