<?php namespace xp\sql;

use io\streams\Streams;
use lang\IllegalAccessException;
use rdbms\SQLException;
use util\cmd\Console;
use util\profiling\Timer;

/* Executes SQL statements; stops on first statement causing an error. */
class Statements implements Command {
  private static $display;
  private $connection, $statements;

  static function __static() {
    self::$display= [
      'vert' => new VerticalDisplay(),
      'csv'  => new CsvDisplay(';')
    ];
  }

  public function __construct(Connection $connection, array $statements) {
    $this->connection= $connection;
    $this->statements= $statements;
  }

  public function execute(): int {
    $conn= $this->connection->establish();
    $timer= new Timer();

    foreach ($this->statements as $statement) {
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
}