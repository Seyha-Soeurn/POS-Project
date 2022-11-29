@extends(backpack_view('blank'))

@php
    $countProducts = count(\App\Models\Product::all());
    $countUsers = count(\App\Models\User::all());
    $countCustomers = count(\App\Models\Customer::all());
    $countSuppliers = count(\App\Models\Supplier::all());

    $widgets['before_content'][] = [
        'type'    => 'div',
        'class'   => 'row mb-4',
        'content' => [
            [ 
                'type' => 'card',
                'wrapper' => ['class'=> 'col-lg-3 col-md-4 col-sm-6 text-center'],
                'class' => 'bg-success font-weight-bold rounded',
                'content' => [
                    'header' => 'Users',
                    'body'   => $countUsers,
                ]
            ],

            [ 
                'type' => 'card',
                'wrapper' => ['class'=> 'col-lg-3 col-md-4 col-sm-6 text-center'],
                'class' => 'bg-primary font-weight-bold rounded',
                'content' => [
                    'header' => 'Customers',
                    'body'   => $countCustomers,
                ]
            ],

            [ 
                'type' => 'card',
                'wrapper' => ['class'=> 'col-lg-3 col-md-4 col-sm-6 text-center'],
                'class' => 'bg-warning font-weight-bold rounded',
                'content' => [
                    'header' => 'Suppliers',
                    'body'   => $countSuppliers,
                ]
            ],

            [ 
                'type' => 'card',
                'wrapper' => ['class'=> 'col-lg-3 col-md-4 col-sm-6 text-center'],
                'class' => 'bg-info font-weight-bold rounded',
                'content' => [
                    'header' => 'Products',
                    'body'   => $countProducts,
                ]
            ],
        ]
    ];

    $widgets['before_content'][] = [
        'type'    => 'div',
        'class'   => 'row',
        'content' => [
            [
                'type' => 'chart',
                'controller' => \App\Http\Controllers\Admin\Charts\WeeklySellsChartController::class,
                'wrapper' => ['class'=> 'col-lg-6 col-md-6 col-sm-12'],
                'content' => [
                    'header' => 'Weekly Sells',
                ]
            ],

            [
                'type' => 'chart',
                'controller' => \App\Http\Controllers\Admin\Charts\WeeklyPurchasesChartController::class,
                'wrapper' => ['class'=> 'col-lg-6 col-md-6 col-sm-12'],
                'content' => [
                    'header' => 'Weekly Purchases',
                ]
            ],
        ]
    ];
@endphp

@section('content')
@endsection
