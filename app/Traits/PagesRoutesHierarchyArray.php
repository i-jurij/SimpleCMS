<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;

trait PagesRoutesHierarchyArray
{
    protected array $routes = [];
    protected array $routes_tree = [];

    public function list_of_routes_by_middleware_userstatus(): array
    {
        $res = [];
        $routes = Route::getRoutes()->getRoutesByMethod()['GET'];
        $admin_routes = [];
        $moder_routes = [];
        $user_routes = [];
        foreach ($routes as $route) {
            $middleware = $route->middleware();
            for ($i = 0; $i < count($middleware); ++$i) {
                if ($middleware[$i] == 'isadmin') {
                    array_push($admin_routes, $route->getName());
                }
                if ($middleware[$i] == 'ismoder') {
                    array_push($moder_routes, $route->getName());
                }
                if ($middleware[$i] == 'isuser') {
                    array_push($user_routes, $route->getName());
                }
            }
        }
        $res = ['admin' => $admin_routes, 'moder' => $moder_routes, 'user' => $user_routes];

        return $res;
    }

    public function routs_hierarchie(): array
    {
        $res = [];
        foreach ($this->list_of_routes_by_middleware_userstatus() as $key => $value) {
            $tree = [];
            foreach ($value as $path) {
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

            $res[$key] = $tree;
        }

        return $res;
    }

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
