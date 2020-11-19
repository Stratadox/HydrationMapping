<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Film;

use Stratadox\ImmutableCollection\Appending;
use Stratadox\ImmutableCollection\ImmutableCollection;

final class FilmCollection extends ImmutableCollection
{
    use Appending;

    public function __construct(Film ...$films)
    {
        parent::__construct(...$films);
    }

    public function current(): Film
    {
        return parent::current();
    }

    public function offsetGet($index): Film
    {
        return parent::offsetGet($index);
    }
}
