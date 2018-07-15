<?php namespace xp\sql;

interface Command {

  /** Executes this command */
  public function execute(): int;
}