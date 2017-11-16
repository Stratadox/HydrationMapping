<?php

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function in_array;
use function is_bool;
use function is_numeric;
use Stratadox\Hydration\Mapping\Property\UnmappableProperty;

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
        parent::__construct($name, $dataKey);
        if (isset($truths)) {
            $this->truths = $truths;
        }
        if (isset($falsehoods)) {
            $this->falsehoods = $falsehoods;
        }
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
        if (is_numeric($value)) {
            return $value > 0;
        }
        return in_array(strtolower($value), $this->truths);
    }

    private function isConsideredFalse($value) : bool
    {
        if (is_numeric($value)) {
            return $value <= 0;
        }
        return in_array(strtolower($value), $this->falsehoods);
    }
}
