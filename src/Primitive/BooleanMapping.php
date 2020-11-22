<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

use Stratadox\HydrationMapping\Mapping;
use function assert;
use function in_array;

final class BooleanMapping extends PrimitiveMapping
{
    /** @var mixed[] */
    private $truths = [true, 1, '1'];
    /** @var mixed[] */
    private $falsehoods = [false, 0, '0'];

    public static function custom(
        string $name,
        array $truths,
        array $falsehoods
    ): Mapping {
        $instance = parent::inProperty($name);

        assert($instance instanceof self);
        $instance->truths = $truths;
        $instance->falsehoods = $falsehoods;

        return $instance;
    }

    public function value(array $data, $owner = null): bool
    {
        $value = $this->my($data);
        if (in_array($value, $this->truths, true)) {
            return true;
        }
        if (in_array($value, $this->falsehoods, true)) {
            return false;
        }
        throw BooleanMappingFailure::unrecognised(
            $value,
            $this,
            $this->truths,
            $this->falsehoods
        );
    }
}
