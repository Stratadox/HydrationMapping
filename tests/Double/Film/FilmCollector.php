<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Film;

final class FilmCollector
{
    /** @var string */
    private $name;
    /** @var FilmCollection */
    private $filmCollection;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->filmCollection = new FilmCollection();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function filmCollection(): FilmCollection
    {
        return $this->filmCollection;
    }

    public function add(Film $film): void
    {
        $this->filmCollection = $this->filmCollection->add($film);
    }
}
