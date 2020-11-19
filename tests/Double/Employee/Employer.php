<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Employee;

final class Employer
{
    /** @var string */
    private $name;
    /** @var Employee[] */
    private $employees;

    public function name(): string
    {
        return $this->name;
    }

    /** @return Employee[] */
    public function employees(): array
    {
        return $this->employees;
    }
}
