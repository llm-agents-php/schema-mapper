<?php

declare(strict_types=1);

namespace LLM\Agents\JsonSchema\Mapper\Integration\Laravel;

use CuyZ\Valinor\Cache\FileSystemCache;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LLM\Agents\JsonSchema\Mapper\MapperBuilder;
use LLM\Agents\JsonSchema\Mapper\SchemaMapper;
use LLM\Agents\Tool\SchemaMapperInterface;
use Spiral\JsonSchemaGenerator\Generator;

final class SchemaMapperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            SchemaMapperInterface::class,
            static function (
                Application $app,
            ) {
                $mapper = (new MapperBuilder(
                    cache: match (true) {
                        $app->environment('prod') => new FileSystemCache(
                            cacheDir: $app->storagePath('cache/valinor'),
                        ),
                        default => null,
                    },
                ))->build();

                return new SchemaMapper(
                    generator: $app->get(Generator::class),
                    mapper: $mapper,
                );
            },
        );
    }
}