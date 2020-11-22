<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping;

use Stratadox\HydrationMapping\Mapping;

final class FixedMapping implements Mapping
{
    /** @var string */
    private $name;
    /** @var mixed */
    private $value;

    /** @param mixed $value */
    private function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return Mapping
     */
    public static function inProperty(string $name, $value): Mapping
    {
        return new self($name, $value);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(array $data, $owner = null)
    {
        return $this->value;
    }
}
