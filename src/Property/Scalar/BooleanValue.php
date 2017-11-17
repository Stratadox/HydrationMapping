<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function in_array;
use function is_bool;
use function is_numeric;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;
use function strtolower;

/**
 * Maps boolean-like input to a boolean property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
class BooleanValue extends Scalar
{
    private $truths = ['true', 'yes', 'y',];
    private $falsehoods = ['false', 'no', 'n'];

    public function __construct(string $name, string $dataKey, array $truths = null, array $falsehoods = null)
    {
        $this->truths = $truths ?: $this->truths;
        $this->falsehoods = $falsehoods ?: $this->falsehoods;
        parent::__construct($name, $dataKey);
    }

    public static function withCustomTruth(string $name, array $truths, array $falsehoods) : Scalar
    {
        return new static($name, $name, $truths, $falsehoods);
    }

    public function value(array $data, $owner = null) : bool
    {
        $value = $this->my($data);
        if (is_bool($value)) {
            return $value;
        }
        if (is_numeric($value)) {
            return $value > 0;
        }
        // @todo refactor to decorator
        if ($this->isConsideredTrue($value)) {
            return true;
        }
        if ($this->isConsideredFalse($value)) {
            return false;
        }
        throw UnmappableProperty::itMustBeConvertibleToBoolean($this, $value);
    }

    private function isConsideredTrue($value) : bool
    {
        return in_array(strtolower($value), $this->truths);
    }

    private function isConsideredFalse($value) : bool
    {
        return in_array(strtolower($value), $this->falsehoods);
    }
}
