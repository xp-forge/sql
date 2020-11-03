<?php namespace xp\sql\unittest;

use unittest\Assert;
use unittest\Test;
use xp\sql\VerticalDisplay;

class VerticalDisplayTest {
  private $fixture;

  /** @return void */
  #[Before]
  public function setUp() {
    $this->fixture= new VerticalDisplay();
  }

  #[Test]
  public function record() {
    Assert::equals(
      "id: 1\n".
      "name: \"Test\"\n",
      $this->fixture->render(['id' => 1, 'name' => 'Test'])
    );
  } 
}