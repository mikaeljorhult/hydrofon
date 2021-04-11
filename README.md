# Hydrofon

Equipment booking system.

[![Actions Status](https://github.com/mikaeljorhult/hydrofon/workflows/CI/badge.svg)](https://github.com/mikaeljorhult/hydrofon/actions)
[![License](https://img.shields.io/badge/license-MIT-428f7e.svg)](http://mikaeljorhult.mit-license.org)

## Installation
Clone the repository to your web server and install it using Composer:
```
composer install
```

## Terminology
| Word       | Definition |
| ---------- | ---------- |
| Booking    | Reservation of one resource between two timestamps. |
| Bucket     | Collection of resources that are interchangable. |
| Category   | Grouping of resources that will be displayed together. |
| Group      | Link that determines which resources should be visible to the user. |
| Identifier | String of characters that a user can be identified by. |
| Resource   | An item that can be reserved. |

## License
Hydrofon is released under the [MIT license](http://mikaeljorhult.mit-license.org).
