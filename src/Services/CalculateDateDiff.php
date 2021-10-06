<?php

namespace Drupal\countdown_timer\Services;

use DateTime;

class CalculateDateDiff {

  /**
   * Calculate difference in date time from "now" to provided datetime
   *
   * @param DateTime $date
   * @return array
   */
  public function calculate(DateTime $date) : string {
    $dtDifference = $date->diff(new DateTime());
    return $this->parseDateDifference($dtDifference);
  }

  /**
   * Calculate difference in date time from $referenceTime to $date
   *
   * @param DateTime $referenceTime
   * @param DateTime $date
   * @return array
   */
  public function calculateReferenceTime(DateTime $referenceTime, DateTime $date) : string {
    $dtDifference = $date->diff($referenceTime);
    return $this->parseDateDifference($dtDifference);
  }

  /**
   * Parse dateDifference from DateInterval
   *
   * @param \DateInterval $dtDifference
   * @return string
   */
  private function parseDateDifference(\DateInterval $dtDifference) : string{
    if ($dtDifference->invert === 0) {
      return 'This event already passed.';
    }

    if ($dtDifference->days < 1) {
      return 'This event is happening today.';
    } else {
      return $dtDifference->days . ' days left until event starts.';
    }
  }
}
