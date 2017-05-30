<?php namespace xp\sql;

use util\Properties;
use rdbms\{DriverManager, DBConnection};
use lang\IllegalArgumentException;

class NamedConnection extends Connection {
  private $prop, $name;

  /** Creates a new named connection */
  public function __construct(Properties $prop, string $name) {
    $this->prop= $prop;
    $this->name= $name;
  }

  /** Establishes a connection */
  public function establish(): DBConnection { 
    if (null === ($dsn= $this->prop->readString(null, $this->name, null))) {
      throw new IllegalArgumentException('No connection named '.$this->name.' in '.$p->getFilename());
    }
    return DriverManager::getConnection($dsn);
  }
}