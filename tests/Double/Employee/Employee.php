<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Employee;

final class Employee
{
    /** @var string */
    private $name;
    /** @var Employer */
    private $employer;

    public function name(): string
    {
        return $this->name;
    }

    public function employer(): Employer
    {
        return $this->employer;
    }
}
