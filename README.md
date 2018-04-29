# Hydration Mapping

[![Build Status](https://travis-ci.org/Stratadox/HydrationMapping.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapping/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapping?branch=master)
[![Infection Minimum](https://img.shields.io/badge/msi-100-brightgreen.svg)](https://travis-ci.org/Stratadox/HydrationMapping)
[![PhpStan Level](https://img.shields.io/badge/phpstan-7-brightgreen.svg)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/cc2585ce303967dd4c7d/maintainability)](https://codeclimate.com/github/Stratadox/HydrationMapping/maintainability)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydration-mapping/v/stable)](https://packagist.org/packages/stratadox/hydration-mapping)
[![License](https://poser.pugx.org/stratadox/hydration-mapping/license)](https://packagist.org/packages/stratadox/hydration-mapping)

[![Implements](https://img.shields.io/badge/inferfaces-github-blue.svg)](https://github.com/Stratadox/HydrationMapperContracts)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydration-mapping-contracts/v/stable)](https://packagist.org/packages/stratadox/hydration-mapping-contracts)
[![License](https://poser.pugx.org/stratadox/hydration-mapping-contracts/license)](https://packagist.org/packages/stratadox/hydration-mapping-contracts)

Mappings for hydration purposes; maps array or array-like data structures to 
object properties, in order to assemble the objects that model a business domain.

## Installation

Install using composer:

`composer require stratadox/hydration-mapping`

## Purpose

These mapping objects define the relationship between an object property and the
source of the data.

## Typical Usage

Typically, hydration mappings are given to [`MappedHydrator`](https://github.com/Stratadox/Hydrator/blob/master/src/MappedHydrator.php) instances.
Together they form a strong team that solves a single purpose: mapping data to an object graph.

For example:
```php
$hydrator = MappedHydrator::forThe(Book::class, Properties::map(
    StringValue::inProperty('title'),
    IntegerValue::inProperty('rating'),
    StringValue::inPropertyWithDifferentKey('isbn', 'id')
));
```

## Mapping

Three types of property mappings are available:
- Scalar mappings
- Relationship mappings
- Extension points

### Scalar Mapping

Scalar typed properties can be mapped using the `*Value` classes.
The following scalar mappings are available:
- `BooleanValue`
- `FloatValue`
- `IntegerValue`
- `StringValue`
- `NullValue`

Scalar mappings are created through the named constructors:
- `inProperty`
    - Usage: `IntegerValue::inProperty('amount')` 
    - Use when the property name and data key are the same.
- `inPropertyWithDifferentKey`
    - Usage: `BooleanValue::inPropertyWithDifferentKey('isBlocked', 'is_blocked')`
    - Use when the data key differs from the property name.

When appropriate, these mappings validate the input before producing a value.
For instance, the `IntegerValue` mapping checks that:
- The input value is formatted as an integer number
- The value does not exceed the integer boundaries

This process can be skipped by using the `Casted*` mappings instead.
They provide a minor speed bonus at the cost of decreased integrity.
`Casted*` mappings are available as:
- `CastedFloat`
- `CastedInteger`

To skip the entire typecasting process, the `OriginalValue` mapping can be used.

Input to a `BooleanValue` must either be numeric or already boolean.
Numeric input larger than zero becomes `true`, zero or less becomes `false`.
Non-numeric strings can be mapped to boolean using the `CustomTruths` wrapper.

Each of the above mappings can be made *nullable* by wrapping the mapping with
`CanBeNull`.

For example, instead of `IntegerValue::inProperty('foo')`, the `foo` property 
can be made *nullable* with: `CanBeNull::or(IntegerValue::inProperty('foo'))`

### Relationship Mapping

Relationships can be mapped with a monogamous `HasOne*` or polygamist `HasMany*` mapping.

Each of these are connected to the input data in one of three ways:
- As `*Embedded` values (for loading from tabular data)
- As `*Nested` data structures (for loading from a json structure)
- As `*Proxies` (for loading lazily)

This boils down to the following possibilities:
- `HasManyEmbedded`
- `HasManyNested`
- `HasManyProxies`
- `HasOneEmbedded`
- `HasOneNested`
- `HasOneProxy`

Relationship mappings are created through the named constructors:
- `inProperty`
    - Usage: `HasOneEmbedded::inProperty('name', $hydrator)` 
    - Use when the property name and data key are the same.
- `inPropertyWithDifferentKey`
    - Usage: `HasManyNested::inPropertyWithDifferentKey('friends', 'contacts', $collection, $item)`
    - Use when the data key differs from the property name.

For `*Embedded` classes, there is no `inPropertyWithDifferentKey`

`HasOne*`-type relationships need an object that [`Hydrates`](https://github.com/Stratadox/HydratorContracts/blob/master/src/Hydrates.php) the related instance.
A `HasMany*` relation require one object that `Hydrates` the collection, and one that `Hydrates` the items.

These hydrators may in turn be `MappedHydrator` instances.
 

An exception to the above are `*Proxy` mappings.
Rather than a hydrator for the related instances, they require a builder that [`ProducesProxies`](https://github.com/Stratadox/ProxyContracts/blob/master/src/ProducesProxies.php).

#### Bidirectional

Bidirectional relationships can be mapped using the `HasBackReference` mapping.
This mapping acts as an observer to the hydrator for the owning side.

Only `one-to-many` and `one-to-one` bidirectional mappings are currently supported.

### Extension

The `ClosureResult` mapping provides an easy extension point.
It takes in an anonymous function as constructor parameter.
This function is called with the input data to produce the mapped result.

For additional extension power, custom mapping can be produced by implementing the `MapsProperty` interface.

# Hydrate

This package is part of the [Hydrate Module](https://github.com/Stratadox/Hydrate).

The `Hydrate` module is an umbrella for several hydration-related packages.
Together, they form a powerful toolset for converting input data into an object structure.

Although these packages are designed to work together, they can also be used independently.
The only hard dependencies of this `HydrationMapping` module are a collections library and a set of packages dedicated only to interfaces.
