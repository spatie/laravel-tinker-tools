<?php

namespace Spatie\TinkerTools;

class ShortClassNames
{
    /** @var \Illuminate\Support\Collection */
    public $classes = [];

    public static function register(string $classMapPath = null)
    {
        $classMap = $classMapPath ?? base_path('vendor/composer/autoload_classmap.php');

        (new static($classMapPath))->registerAutoloader();
    }

    public function __construct(string $classMapPath)
    {
        $classFiles = include $classMapPath;

        $this->classes = collect($classFiles)
            ->map(function (string $path, string $fqcn) {
                $name = last(explode('\\', $fqcn));

                return compact('fqcn', 'name');
            })
            ->filter()
            ->values();
    }

    public function registerAutoloader()
    {
        spl_autoload_register([$this, 'aliasClass']);
    }

    public function aliasClass($findClass)
    {
        $class = $this->classes->first(function ($class) use ($findClass) {
            return $class['name'] === $findClass;
        });

        if (! $class) {
            return;
        }

        class_alias($class['fqcn'], $class['name']);
    }
}
