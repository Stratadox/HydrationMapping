<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Transform;

use Stratadox\Hydration\Mapping\AssertKey;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use function is_array;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use const JSON_ERROR_NONE;

final class JsonTransform implements Mapping
{
    /** @var string */
    private $key;
    /** @var Mapping */
    private $mapping;

    private function __construct(string $key, Mapping $mapping)
    {
        $this->key = $key;
        $this->mapping = $mapping;
    }

    public static function fromKey(string $key, Mapping $mapping): Mapping
    {
        return new self($key, $mapping);
    }

    public function name(): string
    {
        return $this->mapping->name();
    }

    public function value(array $data, $owner = null)
    {
        AssertKey::exists($this, $data, $this->key);
        return $this->mapping->value($this->decode($data[$this->key]), $owner);
    }

    /** @throws MappingFailure */
    private function decode(string $json): array
    {
        $value = json_decode($json, true);
        if (is_array($value)) {
            return $value;
        }
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw JsonTransformationFailure::detected(
                json_last_error_msg(),
                $this->key,
                $this->name()
            );
        }
        throw JsonTransformationFailure::cannotBeScalar(
            $value,
            $this->key,
            $this->name()
        );
    }
}
