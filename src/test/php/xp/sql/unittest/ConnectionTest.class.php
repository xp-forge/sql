<?php namespace xp\sql\unittest;

use unittest\{Assert, Before, Test};
use util\Properties;
use xp\sql\{Connection, ConnectionTo, NamedConnection};

class ConnectionTest {
  private $prop;

  #[Before]
  public function prop() {
    $this->prop= new Properties();
  }

  #[Test]
  public function to_dsn() {
    Assert::instance(ConnectionTo::class, Connection::to('sqlite://./test.db', $this->prop));
  }

  #[Test]
  public function to_named() {
    Assert::instance(NamedConnection::class, Connection::to('dev-db', $this->prop));
  } 
}