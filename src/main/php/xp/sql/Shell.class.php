<?php namespace xp\sql;

use rdbms\{SQLException, SQLConnectException, SQLConnectionClosedException};
use util\cmd\Console;
use util\profiling\Timer;

/** Starts an interactive SQL shell */
class Shell implements Command {
  private $connection;

  public function __construct(Connection $connection) {
    $this->connection= $connection;
  }

  private function prompt($dsn, $db) {
    return strtr("\e[31;1m\$D://\$u@\$h \e[36;1m\$d\e[0m [\e[32;1m%s\e[0m]\n\$", [
      '$D' => $dsn->getDriver(),
      '$u' => $dsn->getUser(),
      '$h' => $dsn->getHost(),
      '$d' => $db
    ]);
  }

  public function execute(): int {
    $timer= new Timer();
    $timer->start();

    $conn= $this->connection->establish();
    $conn->connections->automatic(false)->reconnect(0);
    try {
      $conn->connect();
    } catch (SQLConnectException $e) {
      Console::writeLine("\e[31m*** ", $e->compoundMessage(), "\e[0m");
      return 1;
    }

    Console::writeLinef("\e[32m@%s - (%.2f sec)\e[0m", $conn->toString(), $timer->elapsedTime());
    Console::writeLine('Type "exit" to end shell');
    Console::writeLine();

    $dsn= $conn->getDSN();
    $prompt= $this->prompt($dsn, $db= $dsn->getDatabase());
    while (null !== ($sql= Console::readLine(sprintf($prompt, $conn->hashCode())))) {
      if (false === ($p= strrpos($sql, ';'))) {
        $display= Display::named('vert');
      } else {
        $display= Display::named(trim(strtr(strtolower(substr($sql, $p + 1)), ['-m' => ''])) ?: 'vert');
        $sql= substr($sql, 0, $p);
      }

      $sql= trim($sql);
      if ('' === $sql) {
        continue;
      } else if ('exit' === $sql) {
        break;
      } else if (1 === sscanf($sql, 'use %s', $use)) {
        try {
          $conn->selectdb($use);
          $prompt= $this->prompt($dsn, $db= $use);
          Console::writeLine();
        } catch (SQLException $e) {
          Console::writeLine("\e[31m*** ", $e->compoundMessage(), "\e[0m");
        }
        continue;
      }

      query: try {
        $timer->start();
        $q= $conn->query($sql);
        if ($q->isSuccess()) {
          Console::writeLinef('Query OK, %d rows affected (%.2f sec)', $q->affected(), $timer->elapsedTime());
        } else {
          $rows= 0;
          while ($record= $q->next()) {
            Console::writeLine($display->render($record));
            $rows++;
          }
          Console::writeLinef('%d rows in set (%.2f sec)', $rows, $timer->elapsedTime());
        }
        $q->close();
        Console::writeLine();
      } catch (SQLConnectionClosedException $e) {
        Console::writeLine("\e[31mConnection closed ([", $e->getErrorcode(), "]: '", $e->getMessage(), "'), reconnecting...\e[0m");
        $conn->close();
        $conn->connect();
        try {
          $conn->selectdb($db);
        } catch (SQLException $ignored) {
          $prompt= $this->prompt($dsn, $db= $dsn->getDatabase());
        }
        goto query;
      } catch (SQLException $e) {
        Console::writeLine("\e[31m*** ", $e->compoundMessage(), "\e[0m");
      }
    }

    $conn->close();
    Console::writeLine();
    return 0;
 }
}