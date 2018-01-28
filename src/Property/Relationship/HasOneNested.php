<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property\Relationship;

use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapping\Property\FromSingleKey;
use Throwable;

/**
 * Maps a nested data structure to a has-one relation in an object property.
 *
 * @package Stratadox\Hydrate
 * @author Stratadox
 */
final class HasOneNested extends FromSingleKey
{
    private $hydrate;

    protected function __construct(string $name, string $dataKey, Hydrates $hydrator)
    {
        $this->hydrate = $hydrator;
        parent::__construct($name, $dataKey);
    }

    public static function inProperty(
        string $name,
        Hydrates $hydrator
    ) : self
    {
        return new self($name, $name, $hydrator);
    }

    public static function inPropertyWithDifferentKey(
        string $name,
        string $key,
        Hydrates $hydrator
    ) : self
    {
        return new self($name, $key, $hydrator);
    }

    /** @inheritdoc @return mixed|object */
    public function value(array $data, $owner = null)
    {
        try {
            return $this->hydrate->fromArray($this->my($data));
        } catch (Throwable $exception) {
            throw ObjectMappingFailed::tryingToMapItem($this, $exception);
        }
    }
}
