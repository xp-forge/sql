<?php namespace xp\sql;

interface Display {

  /** Renders record */
  public function render(array $record): string;
}