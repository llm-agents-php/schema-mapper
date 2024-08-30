<?php

declare(strict_types=1);

namespace LLM\Agents\JsonSchema\Mapper\Integration\Laravel;

use CuyZ\Valinor\Cache\FileSystemCache;
use CuyZ\Valinor\Mapper\TreeMapper;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LLM\Agents\JsonSchema\Mapper\MapperBuilder;
use LLM\Agents\JsonSchema\Mapper\SchemaMapper;
use LLM\Agents\Tool\SchemaMapperInterface;

final class SchemaMapperServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            SchemaMapperInterface::class,
            SchemaMapper::class,
        );

        $this->app->singleton(
            TreeMapper::class,
            static fn(
                MapperBuilder $builder,
            ) => $builder->build(),
        );

        $this->app->singleton(
            MapperBuilder::class,
            static fn(
                Application $app,
            ) => new MapperBuilder(
                cache: match (true) {
                    $app->environment('prod') => new FileSystemCache(
                        cacheDir: $app->storagePath('cache/valinor'),
                    ),
                    default => null,
                },
            ),
        );
    }
}