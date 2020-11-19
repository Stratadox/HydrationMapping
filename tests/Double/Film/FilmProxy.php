<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Film;

use Stratadox\Proxy\Proxy;
use Stratadox\Proxy\Proxying;

final class FilmProxy extends Film implements Proxy
{
    use Proxying;

    public function name(): string
    {
        return $this->_load()->name();
    }

    public function thumbnail(): string
    {
        return $this->_load()->thumbnail();
    }

    public function rating(): ?int
    {
        return $this->_load()->rating();
    }

    public function rate(int $rating): void
    {
        $this->_load()->rate($rating);
    }
}
