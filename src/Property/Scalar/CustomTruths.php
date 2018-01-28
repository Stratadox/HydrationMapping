<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function in_array;
use Stratadox\Hydration\MapsProperty;

/**
 * Decorates @see BooleanValue with custom true/false declarations.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class CustomTruths implements MapsProperty
{
    private $for;
    private $truths;
    private $falsehoods;

    private function __construct(
        BooleanValue $mapping,
        array $truths,
        array $falsehoods
    ) {
        $this->for = $mapping;
        $this->truths = $truths;
        $this->falsehoods = $falsehoods;
    }

    public static function forThe(
        BooleanValue $mapping,
        array $truths,
        array $falsehoods
    ) : self
    {
        return new self($mapping, $truths, $falsehoods);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null) : bool
    {
        if (in_array($data[$this->for->key()], $this->truths)) {
            return true;
        }
        if (in_array($data[$this->for->key()], $this->falsehoods)) {
            return false;
        }
        return $this->for->value($data, $owner);
    }

    /** @inheritdoc */
    public function name() : string
    {
        return $this->for->name();
    }
}
