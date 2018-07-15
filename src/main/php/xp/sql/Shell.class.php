<?php namespace xp\sql;

use util\cmd\Console;

/** Starts an interactive SQL shell */
class Shell implements Command {
  private $connection;

  public function __construct(Connection $connection) {
    $this->connection= $connection;
  }

  public function execute(): int {
    Console::$err->writeLine('Not yet implemented');
    return 255;
  }
}