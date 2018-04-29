<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Scalar;

use function is_null;
use Stratadox\HydrationMapping\ExposesDataKey;

/**
 * Decorates scalar type declaration with a nullable property.
 *
 * @package Stratadox\Hydrate
 * @author  Stratadox
 */
final class CanBeNull implements ExposesDataKey
{
    private $or;

    private function __construct(ExposesDataKey $mapping)
    {
        $this->or = $mapping;
    }

    /**
     * Creates a new nullable type wrapper.
     *
     * @param ExposesDataKey $mapping    The mapping to decorate.
     * @return self                      The custom truth boolean mapping.
     */
    public static function or(ExposesDataKey $mapping): self
    {
        return new self($mapping);
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        if (is_null($data[$this->or->key()])) {
            return null;
        }
        return $this->or->value($data, $owner);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->or->name();
    }

    /** @inheritdoc */
    public function key(): string
    {
        return $this->or->key();
    }
}
