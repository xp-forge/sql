<?php namespace xp\sql\unittest;

use unittest\Test;
use xp\sql\VerticalDisplay;

class VerticalDisplayTest extends \unittest\TestCase {
  private $fixture;

  /** @return void */
  public function setUp() {
    $this->fixture= new VerticalDisplay();
  }

  #[Test]
  public function record() {
    $this->assertEquals(
      "id: 1\n".
      "name: \"Test\"\n",
      $this->fixture->render(['id' => 1, 'name' => 'Test'])
    );
  } 
}