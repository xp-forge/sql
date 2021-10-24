SQL subcommand
==============

[![Build status on GitHub](https://github.com/xp-forge/sql/workflows/Tests/badge.svg)](https://github.com/xp-forge/sql/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-forge/sql/version.png)](https://packagist.org/packages/xp-forge/sql)

SQL command line tool using XP database drivers.

## Installation

```bash
$ composer global require xp-forge/sql 'dev-master'
```

## Usage
```bash
$ xp help sql
@FileSystemCL<./src/main/php>
Runs SQL statements
════════════════════════════════════════════════════════════════════════

> Execute a single SQL statement and print the results

  $ xp sql 'sqlite://./test.db' 'select * from test'

> Change output mode by appending -m and one of csv, vert

  $ xp sql 'sqlite://./test.db' 'select * from test;-m csv'

> Read SQL statement from standard input using "-"

  $ cat statement.sql | xp sql 'sqlite://./test.db' -

> Use named connections as configured in connections.ini.

  $ xp sql dev-db 'select * from account where id = 1'


The file connections.ini is per-user and can be found in one of:

> %LOCALAPPDATA%/Xp-forge.sql/ on Windows
> $XDG_CONFIG_HOME/xp-forge.sql/ inside an XDG environment
> $HOME/.xp-forge.sql/ otherwise

Invoking without arguments shows a list of available drivers.
```

*The `-m [vert,csv]` syntax is inspired by SQSH, see http://manpages.ubuntu.com/manpages/precise/man1/sqsh.1.html*

## Drivers

```bash
$ xp sql
@FileSystemCL<./src/main/php>
Available drivers via rdbms.DefaultDrivers
════════════════════════════════════════════════════════════════════════

> mysql+x: rdbms.mysqlx.MySqlxConnection
  Connection to MySQL Databases

> mysql+std: rdbms.mysql.MySQLConnection
  Connection to MySQL Databases via ext/mysql

> sybase+x: rdbms.tds.SybasexConnection
  Connection to Sybase Databases via TDS 5.0

> mssql+x: rdbms.tds.MsSQLxConnection
  Connection to MSSQL Databases via TDS 7.0

> sqlite+3: rdbms.sqlite3.SQLite3Connection
  Connection to SQLite 3.x Databases via ext/sqlite3
```

## Examples

```bash
$ xp sql 'sqlite://./test.db' 'create table test (
  id integer primary key autoincrement,
  name varchar
)'
Query OK, 0 rows affected (0.02 sec)

$ xp sql 'sqlite://./test.db' 'insert into test (name) values ("Timm")'
Query OK, 1 rows affected (0.02 sec)

$ xp sql 'sqlite://./test.db' 'select * from test where id = 1'
id: 1
name: "Timm"

1 rows in set (0.00 sec)
```