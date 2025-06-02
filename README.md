### Hexlet tests and linter status:
[![Actions Status](https://github.com/kov-ekate/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/kov-ekate/php-project-48/actions)
[![Build Status](https://github.com/kov-ekate/php-project-48/actions/workflows/CI.yml/badge.svg)](https://github.com/kov-ekate/php-project-48/actions/workflows/CI.yml)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=kov-ekate_php-project-48&metric=coverage)](https://sonarcloud.io/summary/new_code?id=kov-ekate_php-project-48)
# Gendiff

## Description
Gendiff - utility that determines the difference between two data structures.
### Utility capabilities
* Support for different input formats: json, yaml.
* Generating a report in the forms: stylish, plain text, json.

## Demonstration
### Stylish format:
https://asciinema.org/a/WYAvqawvg8p7JnmilgjCkkPn5
https://asciinema.org/a/lquP7MLaIf8zLCId5vmnhfOCH
https://asciinema.org/a/sA1vUSyprqdPGgdyfWimFFWIX
### Plain format:
https://asciinema.org/a/VPtQFrhp4k37rzWl2ndCwzkSi
### Json format:
https://asciinema.org/a/IflXD8bNTt5SOMyHyWTkbz19e

## Minimum requirements
* PHP 8.3+
* Composer

## Installation

1. Clone the repository:

```bash
git clone git@github.com:kov-ekate/php-project-48.git
```

2. Install require:

```bash
make install
```

## Start
 ```bash
./bin/gendiff <pathToFile1> <pathToFile2> # For stylish format
./bin/gendiff --format plain <pathToFile1> <pathToFile2> # For plain format
./bin/gendiff --format json <pathToFile1> <pathToFile2> # For json format
```
