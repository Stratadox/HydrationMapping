<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Thing;

use Stratadox\Specification\Specification;
use function strlen;

final class IsLonger extends Specification
{
    /** @var int */
    private $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    public static function than(int $length): self
    {
        return new self($length);
    }

    public function isSatisfiedBy($object): bool
    {
        return $object instanceof Name
            && strlen((string) $object) > $this->length;
    }
}
