<?php namespace xp\sql;

use rdbms\DBConnection;
use util\Properties;

abstract class Connection {

  /** Factory method */
  public static function to(string $input, Properties $p): self {
    if (strstr($input, '://')) {
      return new ConnectionTo($input);
    } else {
      return new NamedConnection($p, $input);
    }
  }

  /** Establishes a connection */
  public abstract function establish(): DBConnection;
}