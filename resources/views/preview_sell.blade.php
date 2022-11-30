@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.add') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid">
	  <h2>
        <span class="text-capitalize">Sells</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')
    <div class="bg-white card p-0" style="width: 70%;">
        @php
            $sell = $crud->entry;
            $customer = $sell->getCustomer();
            $seller = $sell->getSeller();
            $details = $sell->getDetails();
        @endphp
        <div class="d-flex p-2 pt-3" style="width: 100%;">
            <div style="width: 50%;">
                <p class="m-0 mb-1 font-weight-bold">Customer:</p>
                <p class="m-0 p-0">{{ $customer->name }}</p>
            </div>
            <div style="width: 50%;">
                <p class="m-0 mb-1 font-weight-bold">Seller:</p>
                <p class="m-0 p-0">{{ $seller->name }}</p>
            </div>
        </div>

        <div class="mt-3" style="width: 100%;">
            <table style="width: 100%;">
                <thead class="bg-primary">
                    <tr>
                        <th class="pl-2 py-1 m-0" style="width: 27%;">Item</th>
                        <th class="pl-2 py-1 m-0" style="width: 12%;">Price</th>
                        <th class="pl-2 py-1 m-0" style="width: 12.5%;">Quantity</th>
                        <th class="pl-2 py-1 m-0" style="width: 16%;">Amount</th>
                        <th class="pl-2 py-1 m-0" style="width: 14.5%;">Discount</th>
                        <th class="pl-2 py-1 m-0" style="width: 18%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Show owned items --}}
                    @php
                        $productsDiscount = 0;
                    @endphp

                    @foreach ($details as $index => $item)
                        @php
                            $productsDiscount += $item->discount;
                        @endphp
                        <tr data-product_id="{{ $item->getRelatedProduct()->id }}">
                            <td class="pl-2 py-2 m-0">{{ $item->getRelatedProduct()->name }}</td>
                            <td class="pl-2 py-2 m-0">$ {{ $item->getRelatedProduct()->price }}</td>
                            <td class="pl-2 py-2 m-0">{{ $item->quantity }}</td>
                            <td class="pl-2 py-2 m-0">$ {{ $item->getRelatedProduct()->price * $item->quantity }}</td>
                            <td class="pl-2 py-2 m-0">$ {{ $item->discount }}</td>
                            <td class="pl-2 py-2 m-0">$ {{ ($item->getRelatedProduct()->price * $item->quantity) - $item->discount }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="rounded-bottom mt-4" style="height: 22%; border-top: solid 1px #7c69ef;">
            <div class="d-flex px-3 pt-3 pb-1 justify-content-between align-items-center">
                <p class="p-0 m-0">Total item(s): <span class="ml-2">{{ count($details) }}</span></p>
                <p class="p-0 m-0 text-right">Total amount: <span class="ml-2">$ {{ $sell->amount }}</span></p>
            </div>
            <div class="d-flex justify-content-between align-items-center px-3 py-1">
                <p class="p-0 m-0 d-flex align-items-center">
                    Extra discount:
                    <span class="ml-2">$ {{ $sell->discount > 0 ? $sell->discount - $productsDiscount : 0 }}</span>
                </p>
                <p class="p-0 m-0 text-right">Total discount: <span class="ml-2">$ {{ $sell->discount }}</span></p>
            </div>
            <div class="px-3 pt-1">
                <p class="text-right text-primary" style="font-weight: 600;">Amount to pay: <span class="ml-2">$ {{ $sell->amount_after_discount }}</span></p>
            </div>
            <div class="d-flex justify-content-end mr-2 mb-4">
                @include('crud::inc.button_stack', ['stack' => 'line'])
            </div>
        </div>
    </div>
@endsection
