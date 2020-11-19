<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Film;

use Stratadox\Deserializer\Deserializer;
use Stratadox\Proxy\ProxyLoader;

final class FilmLoader implements ProxyLoader
{
    /** @var int */
    private $loaded = 0;
    /** @var Deserializer */
    private $deserializer;
    /** @var array[] */
    private $filmData;

    public function __construct(Deserializer $deserializer, array ...$films)
    {
        $this->deserializer = $deserializer;
        $this->filmData = $films;
    }

    public function loadTheInstance(array $data): object
    {
        $this->loaded++;
        return $this->deserializer->from($this->filmData[$data['offset'] ?? 0]);
    }

    public function loaded(): int
    {
        return $this->loaded;
    }
}
