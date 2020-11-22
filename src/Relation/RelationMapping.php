<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use Stratadox\Deserializer\Deserializer;
use Stratadox\HydrationMapping\Mapping;
use Throwable;

final class RelationMapping implements Mapping
{
    /** @var string */
    private $name;
    /** @var Deserializer */
    private $deserialize;

    private function __construct(string $name, Deserializer $deserializer)
    {
        $this->name = $name;
        $this->deserialize = $deserializer;
    }

    /**
     * Creates a new has-one mapping.
     *
     * @param string       $name         The name of the property.
     * @param Deserializer $deserializer The deserializer for the related object.
     * @return Mapping                   The has-one mapping.
     */
    public static function inProperty(
        string $name,
        Deserializer $deserializer
    ): Mapping {
        return new self($name, $deserializer);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->name;
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        try {
            return $this->deserialize->from($data);
        } catch (Throwable $exception) {
            throw RelationMappingFailure::encountered($this, $exception);
        }
    }
}
