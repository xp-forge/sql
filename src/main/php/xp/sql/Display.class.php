<?php namespace xp\sql;

abstract class Display {
  private static $display;

  static function __static() {
    self::$display= [
      'vert' => new VerticalDisplay(),
      'csv'  => new CsvDisplay(';')
    ];
  }

  /** Factory method */
  public static function named(string $name): self {
    if (!isset(self::$display[$name])) {
      throw new IllegalArgumentException('No such display mode "'.$name.'"');
    }
    return self::$display[$name];
  }

  /** Renders record */
  public abstract function render(array $record): string;
}