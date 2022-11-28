@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.preview') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid d-print-none">
    	<a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
		<h2>
	        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
	        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}.</small>
	        @if ($crud->hasAccess('list'))
	          <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
	        @endif
	    </h2>
    </section>
@endsection

@section('content')
<div style="width: 65%;">
	<div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="nav-item">
                <a href="#tab_customer-info" aria-controls="tab_customer-info" role="tab" tab_name="customer-info" data-toggle="tab" class="nav-link active" aria-selected="true">
                    User info
                </a>
            </li>
            <li role="presentation" class="nav-item">
                <a href="#tab_sells" aria-controls="tab_sells" role="tab" tab_name="sells" data-toggle="tab" class="nav-link" aria-selected="false">
                    Sell histories
                </a>
            </li>
        </ul>

        {{-- Default box --}}
        @php
            $customer = $crud->entry;
            $profile = $customer->getProfile();
            $sells = $customer->getSells();
        @endphp
        
        <div class="bg-white pt-1 pb-2" style="border-left: 1px solid #d9e2ef;
            border-right: 1px solid #d9e2ef;
            border-bottom: 1px solid #d9e2ef;
            border-bottom-left-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
            width: 100%;">
            <div role="tabpanel" class="tab-pane  active" id="tab_customer-info" style="width: 100%;">
                <div class="pl-3 py-2 d-flex" style="width: 100%;">
                    <span style="width: 100%;">
                        <img src="{{ URL($profile->url) }}" alt="Image not found." style="height: 80px; width: 80px; border-radius: 50%;">
                    </span>
                </div>
                <div class="pl-3 py-2 d-flex" style="width: 100%;">
                    <strong style="width: 30%;">Name</strong>
                    <span class="text-start" style="width: 5%;">:</span>
                    <span style="width: 65%;">{{ $customer->name }}</span>
                </div>
                <div class="pl-3 py-2 d-flex" style="width: 100%;">
                    <strong style="width: 30%;">Email</strong>
                    <span class="text-start" style="width: 5%;">:</span>
                    <span style="width: 65%;">
                        <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                    </span>
                </div>
                <div class="pl-3 py-2 d-flex" style="width: 100%;">
                    <strong style="width: 30%;">Phone</strong>
                    <span class="text-start" style="width: 5%;">:</span>
                    <span style="width: 65%;">{{ $customer->phone }}</span>
                </div>
                <div class="pl-3 py-2 d-flex" style="width: 100%;">
                    <strong style="width: 30%;">Address</strong>
                    <span class="text-start" style="width: 5%;">:</span>
                    <span style="width: 65%;">{{ $customer->address }}</span>
                </div>
                <div class="pl-3 py-2 d-flex" style="width: 100%;">
                    <strong style="width: 30%;">Created at</strong>
                    <span class="text-start" style="width: 5%;">:</span>
                    <span style="width: 65%;">{{ $customer->created_at }}</span>
                </div>
                <div class="pl-3 py-2 d-flex" style="width: 100%;">
                    <strong style="width: 30%;">Updated at</strong>
                    <span class="text-start" style="width: 5%;">:</span>
                    <span style="width: 65%;">{{ $customer->updated_at }}</span>
                </div>
                <div class="d-flex justify-content-start mt-1 ml-1">
                    @include('crud::inc.button_stack', ['stack' => 'line'])
                </div>
            </div>

            <div role="tabpanel" class="tab-pane px-3 py-2" id="tab_sells" style="width: 100%;">
                @if (count($sells) > 0)
                    <table style="width: 100%; white-space: nowrap;">
                        <thead style="width: 100%;">
                            <tr style="width: 100%;">
                                <th style="width: 27%;">Seller</th>
                                <th class="pl-2 py-1" style="width: 20%;">Amount items</th>
                                <th class="pl-2 py-1" style="width: 15%;">Amount</th>
                                <th class="pl-2 py-1" style="width: 12%;">Discount</th>
                                <th class="pl-2 py-1" style="width: 19%;">Final amount</th>
                                <th class="pl-2 py-1" style="width: 7%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sells as $item)
                                <tr>
                                    <td class="py-2">{{ $item->getCustomer()->name }}</td>
                                    <td class="pl-2 py-2" >{{ count($item->getDetails()) }}</td>
                                    <td class="pl-2 py-2" >$ {{ $item->amount }}</td>
                                    <td class="pl-2 py-2" >$ {{ $item->discount }}</td>
                                    <td class="pl-2 py-2" >$ {{ $item->amount_after_discount }}</td>
                                    <td class="pl-2 py-2" >
                                        <a href="{{ backpack_url('order/'.$item->id.'/show') }}" class="d-flex align-items-center" style="font-size: 14px; text-decoration: none;">
                                            <em class="la la-eye mr-1"></em>
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center mt-3">No any order histories.</p>
                @endif
            </div>
        </div>
	</div>
</div>
@endsection

@push('after_styles')
    <style>
        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
        }
    </style>
@endpush