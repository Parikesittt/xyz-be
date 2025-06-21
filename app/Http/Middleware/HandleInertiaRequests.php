<?php

namespace App\Http\Middleware;

use App\Models\Master\Production\COD\COD_warehouse;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;
use BalajiDharma\LaravelMenu\Models\Menu;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $user_warehouse_id = !empty($request->user()) ? $request->user()->warehouse_id : NULL;

        // if(!empty($user_warehouse_id)){
        //     $warehouse_name = COD_warehouse::join("lara_users","lara_users.warehouse_id","=","warehouse.id")->whereRaw("warehouse.id = $user_warehouse_id")->selectRaw("warehouse.*")->first()->name;
        // } else {
        //     $warehouse_name = NULL;
        // }

        return array_merge(parent::share($request), [
            //session
            'session' => [
                'status' 	
                    => fn () => $request->session()->get('status'),
                                'success'   => fn () => $request->session()->get('success'),
                                'error'    	
                    => fn () => $request->session()->get('error'),
            ],
            //user authenticated
            'auth' => [
                'user' => $request->user(),
                'warehouse' => !empty($warehouse_name) ? $warehouse_name : 'Unknown',
                'permissions'   => $request->user() ? $request->user()->getPermissionArray() : []
            ],
            //route
            'route' => function () use ($request) {
                return [
                    'params' => $request->route()->parameters(),
                    'query' => $request->all(),
                ];
            },
            'url'=> [
                'path' => env('APP_URL'),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
                'danger' => fn () => $request->session()->get('danger'),
            ],
            'navigation' => [
                'menu' => Menu::getMenuTree('admin')
            ]
        ]);
    }
}
