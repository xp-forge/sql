SQL Subcommand change log
=========================

## ?.?.? / ????-??-??

## 0.5.0 / 2018-09-23

* Added compatibility with xp-framework/rdbms 12.0.0 - @thekid

## 0.4.0 / 2018-06-15

* Implemented interactive SQL shell. Type `xp sql [connection]` to launch
  the shell. Run with `rlwrap` to add readline support
  (@thekid)

## 0.3.0 / 2017-07-15

* Added PHP 7.2 compatibility - @thekid

## 0.2.0 / 2017-05-30

* Added ability to use named connection from a per-user configuration
  file `connections.ini`. This file can be found in:
  - %LOCALAPPDATA%/Xp-forge.sql/ on Windows
  - $XDG_CONFIG_HOME/xp-forge.sql/ inside an XDG environment
  - $HOME/.xp-forge.sql/ otherwise
  (@thekid)

## 0.1.0 / 2017-05-30

* Hello World! First release - @thekid