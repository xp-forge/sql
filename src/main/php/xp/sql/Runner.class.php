<?php namespace xp\sql;

use io\streams\Streams;
use lang\{XPClass, Environment, IllegalArgumentException};
use rdbms\{DriverManager, DriverImplementationsProvider, SQLException, DefaultDrivers};
use util\cmd\Console;
use util\profiling\Timer;
use util\{Date, Properties};

/**
 * Runs SQL statements
 * ========================================================================
 *
 * - Execute a single SQL statement and print the results
 *   ```sh
 *   $ xp sql 'sqlite://./test.db' 'select * from test'
 *   ```
 * - Change output mode by appending *-m* and one of *csv*, *vert*
 *   ```sh
 *   $ xp sql 'sqlite://./test.db' 'select * from test;-m csv'
 *   ```
 * - Read SQL statement from standard input using "-"
 *   ```sh
 *   $ cat statement.sql | xp sql 'sqlite://./test.db' -
 *   ```
 * - Use named connections as configured in `connections.ini`.
 *   ```sh
 *   $ xp sql dev-db 'select * from account where id = 1'
 *   ```
 *
 * The file `connections.ini` is per-user and can be found in one of:
 *
 * - *%LOCALAPPDATA%/Xp-forge.sql/* on Windows
 * - *$XDG_CONFIG_HOME/xp-forge.sql/* inside an XDG environment
 * - *$HOME/.xp-forge.sql/* otherwise
 *
 * Invoking without arguments shows a list of available drivers.
 */
class Runner {
  private static $display;

  static function __static() {
    self::$display= [
      'vert' => new VerticalDisplay(),
      'csv'  => new CsvDisplay(';')
    ];
  }

  /** Shows driver */
  private static function drivers(DriverImplementationsProvider $provider): int {
    Console::writeLine("\e[33m@", typeof($provider)->getClassLoader(), "\e[0m");
    Console::writeLine("\e[1mAvailable drivers via ", nameof($provider), "\e[0m");
    Console::writeLine(str_repeat('═', 72));
    Console::writeLine();

    // Load all implementations
    foreach ($provider->drivers() as $driver) {
      foreach ($provider->implementationsFor($driver) as $impl) {
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

  /** Starts an interactive SQL shell */
  private static function interactive(Connection $connect): int {
    Console::$err->writeLine('Not yet implemented');
    return 255;
  }

  /** Executes SQL statements; stops on first statement causing an error. */
  private static function execute(Connection $connect, array $statements): int {
    $conn= $connect->establish();
    $timer= new Timer();

    foreach ($statements as $statement) {
      if ('-' === $statement) {
        $sql= Streams::readAll(Console::$in->getStream());
      } else {
        $sql= $statement;
      }

      if (false === ($p= strrpos($sql, ';'))) {
        $mode= 'vert';
      } else {
        $mode= trim(strtr(strtolower(substr($sql, $p + 1)), ['-m' => ''])) ?: 'vert';
        if (!isset(self::$display[$mode])) {
          throw new IllegalArgumentException('No such display mode "'.$mode.'"');
        }
        $sql= substr($sql, 0, $p);
      }

      try {
        $timer->start();
        $q= $conn->query($sql);
        if ($q->isSuccess()) {
          Console::$err->writeLinef('Query OK, %d row(s) affected (%.2f sec)', $q->affected(), $timer->elapsedTime());
        } else {
          $rows= 0;
          while ($record= $q->next()) {
            Console::writeLine(self::$display[$mode]->render($record));
            $rows++;
          }
          Console::$err->writeLinef('%d row(s) in set (%.2f sec)', $rows, $timer->elapsedTime());
        }
        $q->close();
      } catch (SQLException $e) {
        Console::$err->writeLine("\e[31m*** ", $e->compoundMessage(), "\e[0m");
        return 1;
      }
    }
    return 0;
  }

  /** Entry point */
  public static function main(array $args): int {
    $config= Environment::configDir('xp-forge.sql');
    $p= new Properties($config.'connections.ini');

    // Create config file if not existant
    if (!$p->exists()) {
      Console::writeLine('Creating configuration file ', $p->getFileName());
      is_dir($config) || mkdir($config);
      file_put_contents($p->getFileName(), 
        "; xp-forge/sql configuration; uses following format\n".
        ";\n".
        "; name=\"driver://user:password@host[:port]/database\"\n"
      );
    }

    switch (sizeof($args)) {
      case 0: return self::drivers(new DefaultDrivers());
      case 1: return self::interactive(Connection::to($args[0], $p));
      default: return self::execute(Connection::to($args[0], $p), array_slice($args, 1));
    }
  }
}
