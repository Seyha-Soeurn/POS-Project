@extends(backpack_view('blank'))

{{-- @php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.add') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $defaultBreadcrumbs;
@endphp --}}

{{-- @section('header')
    <section class="container-fluid">
	  <h2>
        <span class="text-capitalize">Sells</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection --}}

@section('content')

    <div class="mt-2">

        @include('crud::inc.grouped_errors')

        <form method="post"
        action="{{ url($crud->route) }}"
        @if ($crud->hasUploadFields('create'))
        enctype="multipart/form-data"
        @endif
        >
        {!! csrf_field() !!}

            <div class="d-flex mb-2" style="height: 80vh;">
                <div class="mr-2 py-2 rounded bg-white justify-content-start"
                style="width: 70%; border: 1px solid rgba(0,40,100,.12); box-sizing: border-box;">
                    <div class="d-flex">
                        @php
                            $fields = $crud->fields();
                        @endphp
                        @foreach ($fields as $field)
                            <div class="w-50">
                                @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-primary px-2" style="font-size: 14px;">
                        <div class="d-flex justify-content-between">
                            <p class="py-1 m-0" style="width: 30%;">Item</p>
                            <p class="py-1 m-0" style="width: 14%;">Price</p>
                            <p class="py-1 m-0" style="width: 10%;">Quantity</p>
                            <p class="py-1 m-0" style="width: 9.5%;">Discount</p>
                            <p class="py-1 m-0" style="width: 16%;">Amount</p>
                            <p class="py-1 m-0" style="width: 16%;">Total</p>
                        </div>
                    </div>
                </div>
                
                <div class="rounded p-2 h-100 bg-white"
                style="width: 30%; border: 1px solid rgba(0,40,100,.12);">
                    <div class="text-right" style="height: 7.5%;">
                        <input class="form-control form-control-sm" type="text" placeholder="Search..." style="font-size: 14px;">
                    </div>
                    <div class="scroll-box" style="height: 92.5%;  overflow-y: auto;">
                        <div class="product-container d-flex">
                            @foreach ($products as $product)
                                <div class="rounded product-card mb-2" style="width: 48.8%; box-sizing: border-box; font-size: 14px;">
                                    <p class="pr-card-header px-1">$ {{ $product->price }}</p>
                                    <img class="pr-card-img" src="{{ $product->images->first->url ? URL($product->images->first()->url) : URL('images/default_product_image.png') }}" style="width: 100%; height: 70px; box-sizing: border-box;" alt="Image not found.">
                                    <span class="pr-card-footer">
                                        <p class="px-1 p-0 m-0 mt-1 text-center text-primary">({{ $product->product_code }})</p>
                                        <div class="pr-name-wrapper px-1">
                                            <p class="pr-name">
                                            {{ $product->name }}
                                            </p>
                                        </div>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @include('admin.orders.list_products')
            </div>

            @include('crud::inc.form_save_buttons')
        </form>
    </div>

@endsection

@push('after_styles')
    <style>
        .scroll-box::-webkit-scrollbar-track {
            background-color: rgb(197, 197, 197);
            border-radius: 5px;
        }
        .scroll-box::-webkit-scrollbar {
            width: 5px;
        }
        .scroll-box::-webkit-scrollbar-thumb {
            background-color: #7c69ef;
            border-radius: 5px;
        }

        /* Product card */
        .product-container {
            justify-content: space-between;
            flex-wrap: wrap;
            box-sizing: border-box;
        }
        .product-card {
            height: max-content;
            background: #eceff3;
            cursor: pointer;
            border: 1px solid rgba(0,40,100,.12);
        }
        .product-card:hover {
            background: #e6e9ed;
        }
        .pr-card-header,
        .pr-card-footer {
            padding: 0;
            margin: 0;
            text-align: center;
            padding: 3px 0;
        }
        .pr-card-footer {
            font-weight: 600;
        }

        /* Product name responsive */
        .pr-name{
            text-align: center;
            display:inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .pr-name-wrapper, .pr-name{
            -webkit-transition: 3.3s;
            -moz-transition: 3.3s;
            transition: 3.3s;
            
            -webkit-transition-timing-function: linear;
            -moz-transition-timing-function: linear;
            transition-timing-function: linear;
        }
        .pr-name-wrapper{
            overflow: hidden;
            width: 100%;
            height: 30px;
        }
        .pr-name{
            margin-left: 0em;
        }
        .pr-name-wrapper:hover .pr-name{
            margin-left: -300px;
        }
    </style>
@endpush

