<?php

declare(strict_types=1);

namespace LLM\Agents\JsonSchema\Mapper\Integration\Spiral;

use CuyZ\Valinor\Cache\FileSystemCache;
use CuyZ\Valinor\Mapper\TreeMapper;
use LLM\Agents\JsonSchema\Mapper\MapperBuilder;
use LLM\Agents\JsonSchema\Mapper\SchemaMapper;
use LLM\Agents\Tool\SchemaMapperInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\Environment\AppEnvironment;

final class SchemaMapperBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            SchemaMapperInterface::class => SchemaMapper::class,

            TreeMapper::class => static fn(
                MapperBuilder $builder,
            ) => $builder->build(),
            MapperBuilder::class => static fn(
                DirectoriesInterface $dirs,
                AppEnvironment $env,
            ) => new MapperBuilder(
                cache: match ($env) {
                    AppEnvironment::Production => new FileSystemCache(
                        cacheDir: $dirs->get('runtime') . 'cache/valinor',
                    ),
                    default => null,
                },
            ),
        ];
    }
}