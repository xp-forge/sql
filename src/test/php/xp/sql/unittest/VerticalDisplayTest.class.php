<?php namespace xp\sql\unittest;

use test\{Assert, Before, Test};
use xp\sql\VerticalDisplay;

class VerticalDisplayTest {
  private $fixture;

  #[Before]
  public function fixture() {
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