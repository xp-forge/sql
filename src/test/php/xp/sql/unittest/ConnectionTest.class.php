<?php namespace xp\sql\unittest;

use unittest\Test;
use util\Properties;
use xp\sql\{Connection, ConnectionTo, NamedConnection};

class ConnectionTest extends \unittest\TestCase {
  private $prop;

  /** @return void */
  public function setUp() {
    $this->prop= new Properties();
  }

  #[Test]
  public function to_dsn() {
    $this->assertInstanceOf(ConnectionTo::class, Connection::to('sqlite://./test.db', $this->prop));
  }

  #[Test]
  public function to_named() {
    $this->assertInstanceOf(NamedConnection::class, Connection::to('dev-db', $this->prop));
  } 
}