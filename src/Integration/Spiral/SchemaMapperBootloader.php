<?php

declare(strict_types=1);

namespace LLM\Agents\JsonSchema\Mapper\Integration\Spiral;

use CuyZ\Valinor\Cache\FileSystemCache;
use LLM\Agents\JsonSchema\Mapper\MapperBuilder;
use LLM\Agents\JsonSchema\Mapper\SchemaMapper;
use LLM\Agents\Tool\SchemaMapperInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\Environment\AppEnvironment;
use Spiral\JsonSchemaGenerator\Generator;

final class SchemaMapperBootloader extends Bootloader
{
    public function defineSingletons(): array
    {
        return [
            SchemaMapperInterface::class => static function (
                DirectoriesInterface $dirs,
                AppEnvironment $env,
                Generator $generator,
            ) {
                $mapper = (new MapperBuilder(
                    cache: match ($env) {
                        AppEnvironment::Production => new FileSystemCache(
                            cacheDir: $dirs->get('runtime') . 'cache/valinor',
                        ),
                        default => null,
                    },
                ))->build();

                return new SchemaMapper(
                    generator: $generator,
                    mapper: $mapper,
                );
            },
        ];
    }
}