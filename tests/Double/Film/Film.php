<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Film;

class Film
{
    /** @var string */
    private $name;
    /** @var string */
    private $thumbnail;
    /** @var int|null */
    private $rating;

    public function __construct(string $name, string $thumbnail)
    {
        $this->name = $name;
        $this->thumbnail = $thumbnail;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function thumbnail(): string
    {
        return $this->thumbnail;
    }

    public function rating(): ?int
    {
        return $this->rating;
    }

    public function rate(int $rating): void
    {
        $this->rating = $rating;
    }
}
