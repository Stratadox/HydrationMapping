<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

use Stratadox\Hydration\Mapping\AssertKey;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;

abstract class PrimitiveMapping implements Mapping
{
    /** @var string */
    private $name;

    final private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function inProperty(string $name): Mapping
    {
        return new static($name);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws MappingFailure
     */
    final protected function my(array $data)
    {
        AssertKey::exists($this, $data, $this->name);
        return $data[$this->name];
    }
}
