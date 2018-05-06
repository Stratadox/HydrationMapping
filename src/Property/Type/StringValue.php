<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Type;

/**
 * Maps string-like input to a string property in an object property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class StringValue extends Scalar
{
    /** @inheritdoc */
    public function value(array $data, $owner = null): string
    {
        return (string) $this->my($data);
    }
}
