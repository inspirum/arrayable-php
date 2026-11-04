# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).


## [Unreleased](https://github.com/inspirum/arrayable-php/compare/v1.3.0...master)
### Added
- Added optional `$depth` parameter (default `512`) to `Convertor::toArray()` and `to_array()` that throws `RuntimeException` when data are nested deeper, like `json_encode`
- Documented `$limit` and `$depth` parameters in README
### Changed
- **Breaking**: Added `toArray()` to [**Model**](./src/Model.php), [**Collection**](./src/Collection.php) and [**ListCollection**](./src/ListCollection.php) interfaces
- **Breaking**: Added `getItems()` to [**Collection**](./src/Collection.php) and [**ListCollection**](./src/ListCollection.php) interfaces
- **Breaking**: `Convertor::toArray()` throws `InvalidArgumentException` instead of `UnexpectedValueException` for invalid `$limit`
- **Breaking**: [**BaseListCollection**](./src/BaseListCollection.php) throws `OutOfRangeException` when an item is set at a non-integer offset or past the end of the list
- [**BaseListCollection**](./src/BaseListCollection.php) implements [**ListCollection**](./src/ListCollection.php)
### Fixed
- Fixed `$collection[] = $item` storing the item under an empty string key in [**BaseCollection**](./src/BaseCollection.php)
- Fixed [**BaseListCollection**](./src/BaseListCollection.php) to stay a `list` after `unset` and when built from a non-list array
- Fixed `.gitattributes` export-ignore paths so `phpcs.xml` and `phpstan.neon` are not shipped in the package
- Fixed outdated `is_arrayable` examples in README


## [v1.3.0 (2024-09-05)](https://github.com/inspirum/arrayable-php/compare/v1.2.0...v1.3.0)
### Added
- Support `list` collections
  - Added [**ListCollection**](./src/ListCollection.php) interface
  - Added [**BaseListCollection**](./src/BaseListCollection.php) abstract class


## [v1.2.1 (2023-09-10)](https://github.com/inspirum/arrayable-php/compare/v1.2.0...v1.2.1)
### Fixed
- Fixed `isArrayable` for `stdClass`


## [v1.2.0 (2023-03-19)](https://github.com/inspirum/arrayable-php/compare/v1.1.0...v1.2.0)
### Added
- Added support for **PHP 8.2**
### Removed
- Removed support for **PHP 8.0**


## [v1.1.0 (2022-07-22)](https://github.com/inspirum/arrayable-php/compare/v1.0.0...v1.1.0)
### Added
- Added [**Collection**](./src/Collection.php) interface
- Added [**Model**](./src/Model.php) interface


## v1.0.0 (2022-07-04) 
### Added
- Added [**Arrayable**](./src/Arrayable.php) interface
- Added [**Convertor**](./src/Convertor.php) helper class
- Added [**BaseCollection**](./src/BaseCollection.php) abstract class
- Added [**BaseModel**](./src/BaseModel.php) abstract class
- Added [**\Arrayable**](./core/Arrayable.php) interface without namespace
- Added [**is_arrayable**](./core/helpers.php) function without namespace
- Added [**to_array**](./core/helpers.php) function without namespace
