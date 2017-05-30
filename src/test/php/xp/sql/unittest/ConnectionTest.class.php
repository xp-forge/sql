<?php namespace xp\sql\unittest;

use xp\sql\{Connection, ConnectionTo, NamedConnection};
use util\Properties;

class ConnectionTest extends \unittest\TestCase {
  private $prop;

  /** @return void */
  public function setUp() {
    $this->prop= new Properties();
  }

  #[@test]
  public function to_dsn() {
    $this->assertInstanceOf(ConnectionTo::class, Connection::to('sqlite://./test.db', $this->prop));
  }

  #[@test]
  public function to_named() {
    $this->assertInstanceOf(NamedConnection::class, Connection::to('dev-db', $this->prop));
  } 
}