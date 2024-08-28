<?php

declare(strict_types=1);

namespace LLM\Agents\JsonSchema\Mapper;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder as BaseMapperBuilder;
use Psr\SimpleCache\CacheInterface;

final readonly class MapperBuilder
{
    public function __construct(
        private ?CacheInterface $cache = null,
    ) {}

    public function build(): TreeMapper
    {
        $builder = (new BaseMapperBuilder())
            ->enableFlexibleCasting()
            ->allowPermissiveTypes();

        if ($this->cache) {
            $builder = $builder->withCache($this->cache);
        }

        return $builder->mapper();
    }
}
