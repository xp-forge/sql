<?php namespace xp\sql;

use lang\{XPClass, Environment, IllegalArgumentException};
use rdbms\{DriverManager, SQLException, DefaultDrivers};
use util\Properties;
use util\cmd\Console;

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
 * - Open an interactive shell, adding readline support via `rlwrap`
 *   ```sh
 *   $ rlwrap xp sql dev-db
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
      case 0: return (new Drivers(new DefaultDrivers()))->execute();
      case 1: return (new Shell(Connection::to($args[0], $p)))->execute();
      default: return (new Statements(Connection::to($args[0], $p), array_slice($args, 1)))->execute();
    }
  }
}
