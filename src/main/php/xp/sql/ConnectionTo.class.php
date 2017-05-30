<?php namespace xp\sql;

use rdbms\{DriverManager, DBConnection};

class ConnectionTo extends Connection {
  private $dsn;

  /** Creates a new dsnd connection */
  public function __construct(string $dsn) {
    $this->dsn= $dsn;
  }

  /** Establishes a connection */
  public function establish(): DBConnection {
    return DriverManager::getConnection($this->dsn);
  }
}