<?php namespace xp\sql;

use lang\XPClass;
use rdbms\{DriverManager, DriverImplementationsProvider};
use util\cmd\Console;

/** Shows drivers */
class Drivers implements Command {
  private $provider;

  public function __construct(DriverImplementationsProvider $provider) {
    $this->provider= $provider;
  }

  public function execute(): int {
    Console::writeLine("\e[33m@", typeof($this->provider)->getClassLoader(), "\e[0m");
    Console::writeLine("\e[1mAvailable drivers via ", nameof($this->provider), "\e[0m");
    Console::writeLine(str_repeat('═', 72));
    Console::writeLine();

    // Load all implementations
    foreach ($this->provider->drivers() as $driver) {
      foreach ($this->provider->implementationsFor($driver) as $impl) {
        XPClass::forName($impl);
      }
    }

    // Iterate over registered
    $registered= DriverManager::getInstance();
    foreach ($registered->drivers as $driver => $impl) {
      Console::writeLine("\e[33;1m>\e[0m \e[35;1m", $driver, "\e[0m: ", $impl->getName());

      $comment= $impl->getComment();
      Console::writeLine('  ', substr($comment, 0, strcspn($comment, "\n")));
      Console::writeLine();
    }
    return 0;
  }
}