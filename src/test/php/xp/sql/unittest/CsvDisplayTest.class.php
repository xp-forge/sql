<?php namespace xp\sql\unittest;

use unittest\Assert;
use unittest\Test;
use xp\sql\CsvDisplay;

class CsvDisplayTest {
  private $fixture;

  /** @return void */
  #[Before]
  public function setUp() {
    $this->fixture= new CsvDisplay();
  }

  #[Test]
  public function record() {
    Assert::equals(
      '1;"Test"',
      $this->fixture->render(['id' => 1, 'name' => 'Test'])
    );
  } 
}