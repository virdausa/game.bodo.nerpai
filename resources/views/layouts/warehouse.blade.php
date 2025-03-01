@extends('layouts.base', [
    'navbar_left' => [
        'navbar-warehouse-switcher',
    ],
    'navbar_right' => [
        'navbar-company-switcher',
    ],
    'sidebar' => [
        'Dashboard' => [
            'icon' => 'icon-sidebar',
            'route' => "dashboard-warehouse",
            'text' => 'Dashboard Warehouse',
        ],
        'Inventories' => [
            'dropdown_id' => 'inventories',
            'dropdown_text' => 'Inventories',
            'dropdown_items' => [
                'warehouse_inventories' => [
                    'icon' => 'icon-checklist-paper',
                    'route' => "warehouse_inventories.index",
                    'text' => 'Inventories',
                ],
                'warehouse_inbounds' => [
                    'icon' => 'icon-checklist-paper',
                    'route' => "warehouse_inbounds.index",
                    'text' => 'Inbounds',
                ],
                'warehouse_outbounds' => [
                    'icon' => 'icon-checklist-paper',
                    'route' => "warehouse_outbounds.index",
                    'text' => 'Outbounds',
                ],
            ]
        ],
        'Warehouse Access' => [
            'dropdown_id' => 'warehouse-access',
            'dropdown_text' => 'Warehouse Access',
            'dropdown_items' => [
                'warehouse_roles' => [
                    'icon' => 'icon-checklist-paper',
                    'route' => "dashboard-warehouse",
                    'text' => 'Warehouse Roles',
                ],
                'warehouse_permissions' => [
                    'icon' => 'icon-checklist-paper',
                    'route' => "dashboard-warehouse",
                    'text' => 'Warehouse Permissions',
                ],
            ]
        ],
        'Exit' => [
            'icon' => 'icon-arrow-right',
            'route' => "warehouses.exit",
            'route_params' => 'warehouses.index',
            'text' => 'Exit Warehouse',
        ],
    ]
])

@section('main-content')
    {{ $slot }}
    @include('layouts.footer')
@endsection