<?php

declare(strict_types=1);

namespace Modules\Core\Architecture;

use FilesystemIterator;
use Hybridly\Architecture\Component;
use Hybridly\Architecture\ComponentLoader;
use Hybridly\Architecture\ComponentType;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use const GLOB_ONLYDIR;

/**
 * Registers module views and layouts under a flat `{module}::{name}` identifier.
 *
 * Hybridly ships a ModulesComponentLoader, but it builds the identifier from the whole path
 * below the module directory, which would turn `users::list-users` into
 * `users::resources.views.list-users`. This loader keeps the identifiers the module
 * service providers used to register by hand.
 */
final readonly class ModuleComponentLoader implements ComponentLoader
{
    private const array VIEW_SUFFIXES = ['.view.vue', '.view.tsx'];

    private const array LAYOUT_SUFFIXES = ['.layout.vue', '.layout.tsx'];

    /** @return Component[] */
    public function load(): array
    {
        $components = [];

        foreach ($this->moduleDirectories() as $moduleDirectory) {
            $namespace = str(basename($moduleDirectory))->kebab()->toString();

            $components = [
                ...$components,
                ...$this->loadComponents("{$moduleDirectory}/Resources/Views", $namespace, ComponentType::VIEW),
                ...$this->loadComponents("{$moduleDirectory}/Resources/Layouts", $namespace, ComponentType::LAYOUT),
            ];
        }

        return $components;
    }

    /** @return string[] */
    private function moduleDirectories(): array
    {
        return glob(base_path('modules/*'), GLOB_ONLYDIR) ?: [];
    }

    /** @return Component[] */
    private function loadComponents(string $directory, string $namespace, ComponentType $type): array
    {
        if (! is_dir($directory)) {
            return [];
        }

        $suffixes = $type === ComponentType::VIEW ? self::VIEW_SUFFIXES : self::LAYOUT_SUFFIXES;
        $components = [];

        /** @var SplFileInfo $file */
        foreach ($this->files($directory) as $file) {
            $path = str($file->getPathname())->replace('\\', '/')->toString();

            if (! str($path)->endsWith($suffixes)) {
                continue;
            }

            $components[] = new Component(
                type: $type,
                path: str($path)->chopStart(base_path())->ltrim('/')->toString(),
                identifier: $namespace.'::'.$this->identifier($directory, $path, $suffixes),
            );
        }

        return $components;
    }

    /** @return iterable<SplFileInfo> */
    private function files(string $directory): iterable
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
        );
    }

    /** @param string[] $suffixes */
    private function identifier(string $directory, string $path, array $suffixes): string
    {
        return str($path)
            ->chopStart(str($directory)->replace('\\', '/')->toString())
            ->ltrim('/')
            ->chopEnd($suffixes)
            ->explode('/')
            ->map(static fn (string $segment): string => str($segment)->kebab()->toString())
            ->join('.');
    }
}
