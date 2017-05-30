<?php namespace xp\sql;

use util\Objects;

/**
 * Displays records vertically
 *
 * ```
 * id: 1
 * name: "Test"
 * last_login: 2017-05-30 11:16:00+0200
 * ```
 */
class VerticalDisplay implements Display {

  /** Renders record */
  public function render(array $record): string {
    $r= '';
    foreach ($record as $key => $value) {
      $r.= $key.': '.Objects::stringOf($value)."\n";
    }
    return $r;
  }
}