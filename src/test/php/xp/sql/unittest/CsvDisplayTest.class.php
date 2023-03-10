<?php namespace xp\sql\unittest;

use test\{Assert, Before, Test};
use xp\sql\CsvDisplay;

class CsvDisplayTest {
  private $fixture;

  #[Before]
  public function fixture() {
    $this->fixture= new CsvDisplay();
  }

  #[Test]
  public function record() {
    Assert::equals(
      '1;"Test"',
      $this->fixture->render(['id' => 1, 'name' => 'Test'])
    );
  }

  #[Test]
  public function quoted() {
    Assert::equals(
      '1;"""Test"""',
      $this->fixture->render(['id' => 1, 'name' => '"Test"'])
    );
  }
}