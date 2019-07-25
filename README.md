# Hydration Mapping

[![Build Status](https://travis-ci.org/Stratadox/HydrationMapping.svg?branch=master)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Coverage Status](https://coveralls.io/repos/github/Stratadox/HydrationMapping/badge.svg?branch=master)](https://coveralls.io/github/Stratadox/HydrationMapping?branch=master)
[![Infection Minimum](https://img.shields.io/badge/msi-100-brightgreen.svg)](https://travis-ci.org/Stratadox/HydrationMapping)
[![PhpStan Level](https://img.shields.io/badge/phpstan-7-brightgreen.svg)](https://travis-ci.org/Stratadox/HydrationMapping)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Stratadox/HydrationMapping/?branch=master)
[![Maintainability](https://api.codeclimate.com/v1/badges/cc2585ce303967dd4c7d/maintainability)](https://codeclimate.com/github/Stratadox/HydrationMapping/maintainability)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydration-mapping/v/stable)](https://packagist.org/packages/stratadox/hydration-mapping)
[![License](https://poser.pugx.org/stratadox/hydration-mapping/license)](https://packagist.org/packages/stratadox/hydration-mapping)

[![Implements](https://img.shields.io/badge/inferfaces-github-blue.svg)](https://github.com/Stratadox/HydrationMappingContracts)
[![Latest Stable Version](https://poser.pugx.org/stratadox/hydration-mapping-contracts/v/stable)](https://packagist.org/packages/stratadox/hydration-mapping-contracts)
[![License](https://poser.pugx.org/stratadox/hydration-mapping-contracts/license)](https://packagist.org/packages/stratadox/hydration-mapping-contracts)

Mappings for hydration purposes.

Maps array or array-like data structures to object properties, in order to 
assemble the objects that model a business domain.

## Installation

Install using composer:

`composer require stratadox/hydration-mapping`

## Purpose

These mapping objects define the relationship between an object property and the
source of the data.

## Typical Usage

Typically, hydration mappings are given to [`Mapped`](https://github.com/Stratadox/Hydrator/blob/master/src/Mapping.php)[`Hydrator`](https://github.com/Stratadox/Hydrator) instances.
Together they form a strong team that solves a single purpose: mapping data to an object graph.

For example:
```php
$hydrator = Mapping::for(ObjectHydrator::default(), Properties::map(
    StringValue::inProperty('title'),
    IntegerValue::inProperty('rating'),
    StringValue::inPropertyWithDifferentKey('isbn', 'id')
));

$book = new Book;
$hydrator->writeTo($book, [
    'title'  => 'This is a book.',
    'rating' => 3,
    'isbn'   => '0000000001'
]);
```

More often, the mapped hydrator is given to a [`deserializer`](https://github.com/Stratadox/Deserializer):
```php
$deserialize = ObjectDeserializer::using(
    Instantiator::forThe(Book::class),
    Mapping::for(ObjectHydrator::default(), Properties::map(
        StringValue::inProperty('title'),
        IntegerValue::inProperty('rating'),
        StringValue::inPropertyWithDifferentKey('isbn', 'id')
    )
);

$book = $deserialize->from([
   'title'  => 'This is a book.',
   'rating' => 3,
   'isbn'   => '0000000001'
]);
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

#### Basic Validation

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

Input to a `BooleanValue` must either be 0, 1 or already boolean typed.
Custom true/false values can be provided as optional parameters:
```php
$myProperty = BooleanValue::inProperty('foo', ['yes', 'y'], ['no', 'n']);
```

#### Nullable- and Mixed values

Each of the above mappings can be made *nullable* by wrapping the mapping with
`CanBeNull`.

For example, instead of `IntegerValue::inProperty('foo')`, the `foo` property 
can be made *nullable* with: `CanBeNull::or(IntegerValue::inProperty('foo'))`.

In the same style, mixed value types can be configured. To map a value that 
could be either an int or a float, as numeric PHP values are often found, 
`CanBeInteger` can be used: `CanBeInteger::or(FloatValue::inProperty('foo')))`.
This mapping will first check if the value can safely be transformed into an
integer, and fall back to a floating point value. Non-numeric values will result
in an exception, denoting where and why the input data could not be mapped.

These mixed mapping can be combined (as is customary for [decorators](https://sourcemaking.com/design_patterns/decorator))
to produce, for instance, mapping configurations that first attempt to map the 
value to a boolean, otherwise as an integer, if that cannot be done to cast it 
to a floating point, and if all else fails, make it a string:
```php
$theProperty = CanBeBoolean::or(
    CanBeInteger::or(
        CanBeFloat::or(
            StringValue::inProperty('bar')
        )
    ), ['TRUE'], ['FALSE']
);
```

### Relationship Mapping

Relationships can be mapped with a monogamous `HasOne*` or polygamist `HasMany*` 
mapping.

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
    - Usage:
    `HasOneNested::inProperty('name', $deserializer)` 
    - Use when the property name and data key are the same.
- `inPropertyWithDifferentKey`
    - Usage: 
    `HasOneNested::inPropertyWithDifferentKey('friends', 'contacts', $deserializer)`
    - Use when the data key differs from the property name.

In this context, the term `key` refers to the key of the associative array from
which the object data is mapped, also known as `offset`, `index` or `position`.

#### Nested vs Embedded

For `*Embedded` classes, there is no `inPropertyWithDifferentKey`. Instead of
relying on an embedded array in the key, they are given the original input array
and compose their attributes from one or more of its values.

##### Has One

`HasOne*`-type relationships are each given an object that [`Deserializes`](https://github.com/Stratadox/DeserializerContracts/blob/master/src/Deserializes.php) 
the related instance.

A `HasOneNested` receives the value that was found in the original input for the 
given `key`. This value must be an array, presumably associative.

`HasOneEmbedded` mappings take a different approach: they produce a new object
from the data in the original input array. This approach is useful when mapping,
for example, [embedded values](https://martinfowler.com/eaaCatalog/embeddedValue.html).

##### Has Many

A `HasMany*` relation requires one object that `Deserializes` the collection, 
and one that `Deserializes` the items.

This approach allows for a lot of freedom in the way collections are mapped.
The available [deserializers](https://github.com/Stratadox/Deserializer) can map 
the collection either as plain array or to a custom collection object.

While `HasManyNested` maps the array associated with its `key` into a collection
of objects, the `HasManyEmbedded` is used when the input array itself consists
of a list of scalars. The latter is mostly useful within nested structures.

These deserializers may in turn use mapped hydrator instances. The combination  
is able to map entire structures of objects in all kinds and shapes.

##### Proxies

[`Proxies`](https://github.com/Stratadox/Proxy) are used to allow for lazy 
loading. Rather than deserializers, they take a factory to create objects that, 
in turn, load the "real" object in place of the proxy whenever called upon.

Lazy has-one relations can be mapped with the `HasOneProxy` mapping.
Lazy has-many relationships have the option to be normally lazy, or extra lazy.
For extra lazy relations, the `HasManyProxies` mapping is used. When the 
relation is "regular" lazy, it is mapped as `HasOneProxy`, where "one" refers to
one collection.

The latter only works when the collection is contained in a collection object.
In cases where objects that are contained in an array should be lazy-loaded, a
`HasManyProxies` mapping should be used, where each proxy is configured to load
the entire array when called upon. 

Using this mechanism, both lazy and extra-lazy loading is supported through any 
type of collection, whether it be an array or a collection object.

#### Bidirectional

Bidirectional `one-to-many` and `one-to-one` relationships can be mapped using 
the `HasBackReference` mapping.

This mapping acts as an observer to the hydrator for the owning side, assigning
the reference of the "owner" object to the given property.

### Advanced validation

Advanced input validation can be applied with `Check`. A `Check` will produce 
the value of the mapping if the [specification](https://github.com/Stratadox/Specification)
is satisfied with it, or throw an exception otherwise.

For example, a check on whether a rating is between 1 and 5 might look like this:
```php
Check::thatIt(
    IsNotLess::than(1)->and(IsNotMore::than(5)),
    IntegerValue::inProperty('rating')
)
```
The constraints themselves implement the (minimal) interface [`Satisfiable`](https://github.com/Stratadox/SpecificationInterfaces/blob/master/src/Satisfiable.php),
which mandates only the method `isSatisfiedBy($input)`.

The recommended way to implement custom constraints is by extending the abstract 
[`Specification`](https://github.com/Stratadox/Specification/blob/master/src/Specification.php) class:
```php
use Stratadox\Specification\Specification;

class IsNotLess extends Specification
{
    private $minimum;

    private function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public static function than(int $minimum): self
    {
        return new self($minimum);
    }

    public function isSatisfiedBy($number): bool
    {
        return $number >= $this->minimum;
    }
}
```
Or by using the [`Specifying`](https://github.com/Stratadox/Specification/blob/master/src/Specifying.php)
trait:
```php
use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;

class IsNotMore implements Specifies
{
    use Specifying;

    private $maximum;

    private function __construct(int $maximum)
    {
        $this->maximum = $maximum;
    }

    public static function than(int $maximum): Specifies
    {
        return new IsNotMore($maximum);
    }

    public function isSatisfiedBy($number): bool
    {
        return $number <= $this->maximum;
    }
}
```

### Default values

To honour the PHP spirit, a class is available that loads a default value rather
than propagating the exception: `Defaults::to(-1, IntegerValue::inProperty('foo'))`

### Extension

The `ClosureResult` mapping provides an easy extension point.
It takes in an anonymous function as constructor parameter.
This function is called with the input data to produce the mapped result.

For additional extension power, custom mapping can be produced by implementing 
the `MapsProperty` interface.

# Hydrate

This package is part of the [Hydrate Module](https://github.com/Stratadox/Hydrate).

The `Hydrate` module is an umbrella for several hydration-related packages.
Together, they form a powerful toolset for converting input data into an object 
structure.

Although these packages are designed to work together, they can also be used 
independently.
The only hard dependencies of this `HydrationMapping` module are a collections 
library and a set of packages dedicated only to interfaces.
