# Hydrofon

Equipment booking system.

[![Actions Status](https://github.com/mikaeljorhult/hydrofon/workflows/CI/badge.svg)](https://github.com/mikaeljorhult/hydrofon/actions)
[![License](https://img.shields.io/badge/license-MIT-428f7e.svg)](http://mikaeljorhult.mit-license.org)

## Requirements
Hydrofon is built on Laravel 11 and therefore inherit [its requirements](https://laravel.com/docs/11.x/deployment#server-requirements). It also
makes use of Common Table Expressions for some features so the chosen database must support that as well. 

PHP: 8.2+

Database:
- MySQL 8.0+
- MariaDB 10.3+
- PostgreSQL 10.0+
- SQLite 3.35.0+
- SQL Server 2017+

## Installation
1. Clone this repository with to your local machine or a web server.
2. Run `composer install` to install the PHP dependencies.
3. Run `composer setup` to setup the application.
4. Optional: Run `php artisan hydrofon:init` to add an administrator account.

## Terminology

| Word       | Definition                                                          |
|------------|---------------------------------------------------------------------|
| Booking    | Reservation of one resource between two timestamps.                 |
| Bucket     | Collection of resources that are interchangable.                    |
| Category   | Grouping of resources that will be displayed together.              |
| Group      | Link that determines which resources should be visible to the user. |
| Identifier | String of characters that a user can be identified by.              |
| Resource   | An item that can be reserved.                                       |

## Development
| Command                       | Description                                                         |
|-------------------------------|---------------------------------------------------------------------|
| `php artisan test --parallel` | Run test suite with parallel execution.                             |
| `./vendor/bin/pint`           | Check and fix code style.                                           |
| `npm run build`               | Build frontend assets.                                              |

## License
Hydrofon is released under the [MIT license](http://mikaeljorhult.mit-license.org).
