<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait PagesRoutesHierarchyArray
{
    protected array $routes = [];
    protected array $routes_tree = [];

    public function list_of_routes(string $name): void
    {
        foreach (Route::getRoutes()->getRoutesByMethod()['GET'] as $route) {
            if (strpos($route->getName(), $name) !== false) {
                $this->routes[] = $route->getName();
            }
        }
    }

    public function ierarch(): void
    {
        $tree = [];
        foreach ($this->routes as $path) {
            $tmp = &$tree;
            $pathParts = explode('.', rtrim($path, '.'));
            foreach ($pathParts as $pathPart) {
                if (!array_key_exists($pathPart, $tmp)) {
                    $tmp[$pathPart] = [];
                }
                $tmp = &$tmp[$pathPart];
            }
            $tmp = $path;
        }

        $this->routes_tree = $tree;
    }

    public function get_pages_menu($name = 'main'): array
    {
        $this->list_of_routes($name);
        $this->ierarch();

        return $this->routes_tree;
    }
}
