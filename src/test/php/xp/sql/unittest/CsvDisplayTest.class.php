<?php namespace xp\sql\unittest;

use xp\sql\CsvDisplay;

class CsvDisplayTest extends \unittest\TestCase {
  private $fixture;

  /** @return void */
  public function setUp() {
    $this->fixture= new CsvDisplay();
  }

  #[@test]
  public function record() {
    $this->assertEquals(
      '1;"Test"',
      $this->fixture->render(['id' => 1, 'name' => 'Test'])
    );
  } 
}