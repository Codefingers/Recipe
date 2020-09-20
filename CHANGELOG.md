# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2020-09-20


## [1.0.1] - 2020-09-20

### Fixed

- The release version of the App to be correctly reflected


## [1.0.0] - 2020-09-20


### Added 

- Step API for creating, reading, deleting and updating
- Migration for the Step table
- Seeding capabilities for the seed table


### Changed

- Ingredient API to no longer return a recipe object on the `GET` endpoint, on this route `/api/ingredient/{id}`
- Recipe `GET` endpoint now returns related steps, on this route `/api/recipe/{id}`

## [0.2.0] - 2020-07-12


### Added

- Ingredient API for creating, reading, deleting and updating 


## [0.1.0] - 2020-06-20

### Added

- Basic Laravel framework
- Recipe API for creating, reading, deleting and updating
- Github actions and basic build pipeline which includes
 
  - Composer install
  - PhpStan
  - PhpUnit
