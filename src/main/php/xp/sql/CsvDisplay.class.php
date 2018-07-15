<?php namespace xp\sql;

use util\Date;

/**
 * Displays records as CSV
 *
 * ```
 * 1;"Test";2017-05-30 11:16:00+0200
 * ```
 */
class CsvDisplay extends Display {
  private $separator;

  static function __static() { }

  /** Creates a new display with a given separator */
  public function __construct(string $separator= ';') {
    $this->separator= $separator;
  }

  /** Renders record */
  public function render(array $record): string {
    $r= [];
    foreach ($record as $value) {
      if ($value instanceof Date) {
        $r[]= $value->toString();
      } else if (is_string($value)) {
        $r[]= '"'.strtr($value, ['"' => '""', "\r" => '', "\n" => '']).'"';
      } else {
        $r[]= $value;
      }
    }
    return implode($this->separator, $r);
  }
}