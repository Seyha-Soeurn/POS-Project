@extends(backpack_view('blank'))

{{-- @php
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
@endsection --}}

@section('content')

    <div class="mt-2">
        {{-- @dd($entry) --}}
        {{-- Url and Backpack Url --}}
        <strong id="store-urls" data-url="{{ url('/') }}" data-backpack-url="{{ backpack_url('/') }}" style="display: none;"></strong>

        @include('crud::inc.grouped_errors')

        <form method="post"
        action="{{ $crud->entry ? url($crud->route . '/' . $crud->entry->id) : url($crud->route) }}"
        >
            {!! csrf_field() !!}
            @if ($crud->entry)
                <input type="hidden" name="_method" value="PUT">
                {{-- <input type="hidden" name="id" value="{{ $crud->entry->id }}"> --}}
            @endif
            <input type="hidden" name="_http_referrer" value="{{ url($crud->route) }}">

            <div class="d-flex mb-2" style="height: 80vh;">
                <div class="mr-1 pt-2 rounded bg-white justify-content-start"
                style="width: 60%; height: 100%; border: 1px solid rgba(0,40,100,.12); box-sizing: border-box;">
                    <div class="d-flex" style="height: 17%;">
                        {{-- Organize fields --}}
                        @php
                            $fields = $crud->fields();
                        @endphp
                        @foreach ($fields as $field)
                            @if ($field['name'] == 'customer_id' || $field['name'] == 'seller_id')
                                <div class="w-50">
                                    @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
                                </div>
                            @elseif ($field['name'] != 'details')
                                @if ($field['name'] == 'id')
                                    <input type="hidden" name="{{ $field['name'] }}" value="{{ $crud->entry->id }}">
                                @else
                                    <input type="hidden" name="{{ $field['name'] }}" value="0">
                                @endif
                            @endif
                        @endforeach
                    </div>

                    {{-- Added data --}}
                    <div class="scroll-box" style="font-size: 14px; height: 61%; overflow-y: auto;">
                        <table>
                            <thead class="bg-primary">
                                <tr>
                                    <th class="pl-2 py-1 m-0" style="width: 27%;">Item</th>
                                    <th class="pl-2 py-1 m-0" style="width: 11%;">Price(<span class="text-warning">$</span>)</th>
                                    <th class="pl-2 py-1 m-0" style="width: 12.5%;">Quantity</th>
                                    <th class="pl-2 py-1 m-0" style="width: 15%;">Amount(<span class="text-warning">$</span>)</th>
                                    <th class="pl-2 py-1 m-0" style="width: 14.5%;">Discount(<span class="text-warning">$</span>)</th>
                                    <th class="pl-2 py-1 m-0" style="width: 14%;">Total(<span class="text-warning">$</span>)</th>
                                    <th class="pl-2 py-1 pr-2 m-0" style="width: 6%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Show owned items --}}

                                @if ($crud->entry)
                                    @php
                                        $productsDiscount = 0;
                                    @endphp
                                    @foreach ($crud->entry->details as $index => $item)
                                        @php
                                            $productsDiscount += $item->discount;
                                        @endphp
                                        <tr data-product_id="{{ $item->getRelatedProduct()->id }}">
                                            <td>{{ $item->getRelatedProduct()->name }}</td>
                                            <td id="each-product-price">{{ $item->getRelatedProduct()->price }}</td>
                                            <td id="each-product-quantity" class="quan-field-container">
                                                <input type="number" min="1" minlength="1" value="{{ $item->quantity }}" id="each-quantity-field">
                                            </td>
                                            <td id="each-product-amount">{{ $item->getRelatedProduct()->price * $item->quantity }}</td>
                                            <td id="each-product-discount">
                                                <input type="number" min="0" value="{{ $item->discount }}">
                                            </td>
                                            <td id="each-product-total-amount">{{ ($item->getRelatedProduct()->price * $item->quantity) - $item->discount }}</td>
                                            <td id="each-product-action">
                                                <em id="btn-delete-item" class="la la-trash" title="Remove"></em>
                                                <input type="hidden" name="{{ 'details['.$index.'][id]' }}" value="{{ $item->id }}">
                                                <input type="hidden" name="{{ 'details['.$index.'][product_id]' }}" value="{{ $item->product_id }}" class="store-index" data-index="{{ $index }}">
                                                <input type="hidden" name="{{ 'details['.$index.'][quantity]' }}" value="{{ $item->quantity }}" id="store-item-quantity" value="{{ $item->quantity }}">
                                                <input type="hidden" name="{{ 'details['.$index.'][discount]' }}" value="{{ $item->discount }}" id="store-item-discount" value="{{ $item->discount }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="ounded-bottom" style="height: 22%; border-top: solid 1px #7c69ef;">
                        <div class="d-flex px-2 pt-2 pb-1 justify-content-between align-items-center">
                            <p class="p-0 m-0">Total item: <span id="total-item">0</span></p>
                            <p class="p-0 m-0 text-right">Total amount: $ <span id="amount">0</span></p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center px-2 py-1">
                            <p class="p-0 m-0 d-flex align-items-center">
                                Extra discount($):
                                <input id="extra-discount" value="{{ $crud->entry ? ($crud->entry->discount > 0 ? $crud->entry->discount - $productsDiscount : "") : "" }}" class="ml-2" style="width: 80px; height: 25px; outline: none; font-size: 14px;" type="number" min="0">
                            </p>
                            <p class="p-0 m-0 text-right">Total discount: $ <span id="discount">0</span></p>
                        </div>
                        <div class="px-2 pt-1">
                            <p class="text-right text-primary" style="font-weight: 600;">Amount to pay: $ <span id="amount_after_discount">0</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="rounded h-100 bg-white"
                style="width: 40%; border: 1px solid rgba(0,40,100,.12); padding: 6px;">
                    <div class="text-right" style="height: 7.3%;">
                        <input id="search-product" class="form-control form-control-sm" type="text" placeholder="Search..." style="font-size: 14px;">
                    </div>
                    <div class="d-flex justify-content-between" style="width: 100%; height: 92.7%;">
                        <div id="category-container" class="scroll-box-hidden d-flex flex-column pt-1" style="height: 100%; width: 35%; overflow-y: auto;">
                            {{-- Categories show --}}
                            <button type="button" id="btn-show-all" class="btn btn-sm mb-1 btn-cate" data-category-id="All">All</button>
                            @foreach ($categories as $category)
                                <button type="button" id="btn-cate-filter" class="btn btn-sm mb-1 btn-cate" data-category-id="{{ $category->id }}">{{ $category->name }}</button>
                            @endforeach
                        </div>
                        <div class="scroll-box pt-1" style="height: 100%; width: 63.6%; overflow-y: auto;">
                            <div class="product-container d-flex">
                                {{-- Show product list --}}
                            </div>
                        </div>
                    </div>
                </div>
                @include('admin.orders.list_products')
            </div>

            @include('crud::inc.form_save_buttons')
        </form>
    </div>

@endsection

@push('after_scripts')
    <script>
        var url = $( 'strong#store-urls' ).attr('data-url');
        var backpack_url = $( 'strong#store-urls' ).attr('data-backpack-url');


        /***** List products *****/

        // Create card to display product
        function updateProductCards(products) {
            $( 'div.product-container' ).empty();
            if (products.length > 0) {
                $( 'div.product-container' ).height('max-content');
                products.forEach( product => {
                    let card = $("<div>").attr('id', 'each-product-display').attr('data-product-id', product['id']).attr('class', 'product-card').appendTo('div.product-container');
                    $("<p>").text('$ '+product['price']).attr('class', 'pr-card-header px-1').appendTo(card);
                    $("<img>").attr('src', product['images'].length > 0 ? url+'/'+product['images'][0]['url'] : url+'/images/default_product_image.png').attr('class', 'pr-card-img').appendTo(card);
                    $("<p>").text('('+product['product_code']+')').attr('class', 'px-1 p-0 m-0 mt-1 text-center text-primary pr-card-footer').appendTo(card);
                    $("<p>").text(product['name']).attr('class', 'pr-name').appendTo(card);
                });
            }
            if ($.trim($( 'div.product-container' ).html()) == '') {
                $( 'div.product-container' ).height('100%');
                let emptyContainer = $("<div>").attr('class', 'item-empty-container').appendTo('div.product-container');
                $("<p>").text('No product found.').attr('class', 'item-empty').appendTo(emptyContainer);
            }
        }

        // Get list product and display
        function getListProduct() {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: 'get',
                url: backpack_url + '/products',
                success: function (response) {
                    if(response.success) {
                        updateProductCards(response.data);
                    } else {
                        new Noty({
                            type: 'error',
                            text: 'Get list product error.'
                        }).show();
                    }
                },
                error: function() {
                    new Noty({
                            type: 'error',
                            text: "Get list product error."
                    }).show();
                }
            });
        }


        var selectedCategory = null;

        // Update buttons filter by category
        function updateBtnCategoryFilter() {
            if (selectedCategory) {
                $( 'button#btn-cate-filter' ).each(function(index, element) {
                    if ($(element).attr('data-category-id') == selectedCategory ) {
                        $( 'button#btn-show-all' ).attr('class', 'btn btn-sm mb-1 btn-cate');
                        $(element).attr('class', 'btn btn-sm mb-1 btn-cate-selected')
                    } else {
                        $(element).attr('class', 'btn btn-sm mb-1 btn-cate')
                    }
                })
            } else {
                $( 'button#btn-cate-filter' ).each(function(index, element) {
                    $(element).attr('class', 'btn btn-sm mb-1 btn-cate')
                })
                $( 'button#btn-show-all' ).attr('class', 'btn btn-sm mb-1 btn-cate-selected');
            }
        }
        // Filter product with category and search keyword
        function filterProduct() {
            let searchKeyword = $( 'input#search-product' ).val();

            if (searchKeyword || selectedCategory) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'post',
                    url: backpack_url + '/products/filter',
                    data: {'keyword': searchKeyword, 'category_id': selectedCategory},
                    success: function (response) {
                        if(response.success) {
                            updateProductCards(response.data);
                        } else {
                            new Noty({
                                type: 'error',
                                text: 'Search products error.'
                            }).show();
                        }
                    },
                    error: function() {
                        new Noty({
                                type: 'error',
                                text: "Search products error."
                        }).show();
                    }
                });
            } else {
                getListProduct();
            }
        }

        // Document ready to get product
        jQuery(document).ready(function() {
            getListProduct();
            updateLogic();
            updateBtnCategoryFilter();
        })

        // Search product
        $( 'input#search-product' ).on('input', function() {
            filterProduct();
        })

        let categoryContainer = $( 'div#category-container' ).click(function (e) {
            let clickedTarget = $(e.target);
            if ($(clickedTarget).attr('id') === 'btn-cate-filter') {
                selectedCategory = $(clickedTarget).data('category-id');
                updateBtnCategoryFilter();
                filterProduct();
            } else if ($(clickedTarget).attr('id') === 'btn-show-all') {
                selectedCategory = null;
                updateBtnCategoryFilter();
                let searchKeyword = $( 'input#search-product' ).val();
                if (searchKeyword) {
                    filterProduct();
                } else {
                    getListProduct();
                }
            }
        })


        /***** Logic *****/

        // Update all logic
        function updateLogic() {
            let totalItem = 0;
            let totalDiscount = 0;
            let totalAmount = 0;

            // Logic in table
            if ($( 'tbody tr' ).first().length > 0) {
                $( 'tbody tr' ).each(function (index, element) {
                    let itemPrice = $(element).children("td#each-product-price").text();
                    let itemQuantity = $(element).children("td#each-product-quantity").children('input').val();
                    let itemDiscount = $(element).children("td#each-product-discount").children('input').val();
                    let amountEle = $(element).children("td#each-product-amount");
                    let totalAmountEle = $(element).children("td#each-product-total-amount");
                    let storeItemQuantity = $(element).children("td#each-product-action").children('input#store-item-quantity');
                    let storeItemDiscount = $(element).children("td#each-product-action").children('input#store-item-discount');
                    
                    let totalPrice = parseInt(itemPrice);
                    if (itemQuantity > 0) {
                        totalPrice = parseInt(itemPrice) * parseInt(itemQuantity);
                    }
                    $(amountEle).text(totalPrice);

                    if (itemDiscount) {
                        $(totalAmountEle).text(totalPrice - parseInt(itemDiscount));
                        totalDiscount += parseInt(itemDiscount);
                    } else {
                        $(totalAmountEle).text(totalPrice);
                    }

                    $(storeItemQuantity).val(itemQuantity);
                    if (itemDiscount > 0) {
                        $(storeItemDiscount).val(itemDiscount);
                    } else {
                        $(storeItemDiscount).val(0);
                    }

                    totalItem += 1;
                    totalAmount += parseInt(totalPrice);
                })
            }

            // logic in bottom conclusion
            let extraDiscount = $( 'input#extra-discount' ).val();
            if (extraDiscount > 0) {
                totalDiscount += parseInt(extraDiscount);
            }
            $( 'span#total-item' ).text(totalItem);

            $( 'span#discount' ).text(totalDiscount);
            $( 'input[name=discount]' ).val(totalDiscount);

            $( 'span#amount' ).text(totalAmount);
            $( 'input[name=amount]' ).val(totalAmount);

            if (totalAmount > 0) {
                $( 'span#amount_after_discount' ).text(totalAmount - totalDiscount);
                $( 'input[name=amount_after_discount]' ).val(totalAmount - totalDiscount);
            } else {
                $( 'span#amount_after_discount' ).text(totalAmount);
                $( 'input[name=amount_after_discount]' ).val(totalAmount);
            }
        }

        /***** Table items *****/

        // Add event to table tbody to update logic
        $('tbody').keydown( function() {
            updateLogic();
        })
        $('tbody').keyup( function() {
            updateLogic();
        })
        $('tbody').click( function() {
            updateLogic();
        })

        // Add item to DOM
        function addItem(item) {
            let isProductSelected = false;
            if ($( 'tbody tr' ).first().length > 0) {
                $( 'tbody tr' ).each(function (index, element) {
                    if ($(element).attr('data-product_id') == item['id']) {
                        isProductSelected = true;
                        
                        let selectedProQuantityEle = $(element).children('td#each-product-quantity').children('input');
                        if (parseInt($(selectedProQuantityEle).val()) < parseInt(item['stock'])) {
                            $(selectedProQuantityEle).val(parseInt($(selectedProQuantityEle).val()) + 1);
                        } else {
                            new Noty({
                                type: 'error',
                                text: 'Not enough stock.'
                            }).show();
                        }
                    }
                })
            }

            if (!isProductSelected) {
                let row = $( '<tr>' ).attr('data-product_id', item['id']).appendTo('table tbody');
                $( '<td>' ).text(item['name']).appendTo(row);
                $( '<td>' ).text(item['price']).attr('id', 'each-product-price').appendTo(row);
                let tdQuantity = $( '<td>' ).attr('id', 'each-product-quantity').attr('class', 'quan-field-container').appendTo(row);
                $( '<input>' ).attr('type', 'number').attr('min', '1').attr('minlength', '1').val(1).attr('id', 'each-quantity-field').appendTo(tdQuantity);
                $( '<td>' ).text(item['price']).attr('id', 'each-product-amount').appendTo(row);
                let tdDiscount = $( '<td>' ).attr('id', 'each-product-discount').appendTo(row);
                $( '<input>' ).attr('type', 'number').attr('min', '0').appendTo(tdDiscount);
                $( '<td>' ).text(item['price']).attr('id', 'each-product-total-amount').appendTo(row);
                let tdAction = $( '<td>' ).attr('id', 'each-product-action').appendTo(row);
                $( '<em>' ).attr('id', 'btn-delete-item').attr('class', 'la la-trash').attr('title', 'Remove').appendTo(tdAction);
    
                let maxIndex = 0;
                let indexToUse = maxIndex;
                if ($( 'input.store-index' ).first().length > 0) {
                    maxIndex = $( 'input.store-index' ).first().data('index')
                    $( 'input.store-index' ).each(function( index, element ) {
                        if ($(element).data('index') > maxIndex) {
                            maxIndex = $(element).data('index');
                        }
                    })
                    indexToUse = maxIndex+1;
                }
    
                $( '<input>' ).attr('type', 'hidden').attr('name', 'details['+indexToUse+'][id]').appendTo(tdAction);
                $( '<input>' ).attr('type', 'hidden').attr('name', 'details['+indexToUse+'][product_id]').val(item['id']).attr('class', 'store-index').attr('data-index', indexToUse).appendTo(tdAction);
                $( '<input>' ).attr('type', 'hidden').attr('name', 'details['+indexToUse+'][quantity]').val(1).attr('id', 'store-item-quantity').appendTo(tdAction);
                $( '<input>' ).attr('type', 'hidden').attr('name', 'details['+indexToUse+'][discount]').val(0).attr('id', 'store-item-discount').appendTo(tdAction);
            }

            updateLogic();
        }

        

        // Add item listener
        $( 'div.product-container' ).click(function(e) {
            let dataProductId = null;
            let clickedTarget = $(e.target);
            if (clickedTarget.attr('id') === 'each-product-display') {
                dataProductId = clickedTarget.attr('data-product-id');
            } else if (clickedTarget.parent().attr('id')  === 'each-product-display') {
                dataProductId = clickedTarget.parent().attr('data-product-id');
            }

            if (dataProductId !== null) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'get',
                    url: backpack_url + '/products/'+dataProductId,
                    success: function (response) {
                        if (response.data['stock'] > 0) {
                            addItem(response.data);
                        } else {
                            new Noty({
                                type: 'error',
                                text: 'Stock is not available.'
                            }).show();
                        }
                    },
                    error: function() {
                        new Noty({
                                type: 'error',
                                text: "Something went wrong."
                        }).show();
                    }
                });
            }
        });

        // Check stock of product
        function checkStock(clickedTarget) {
            let quantity = clickedTarget.val();
            let productId = clickedTarget.parent().parent().attr('data-product_id');
            if (quantity > 1 && productId) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    method: 'get',
                    url: backpack_url + '/products/'+productId,
                    success: function (response) {
                        if (response.data['stock'] < parseInt(quantity)) {
                            clickedTarget.val(response.data['stock']);
                            updateLogic();
                            new Noty({
                                type: 'error',
                                text: 'Not enough stock.'
                            }).show();
                        }
                    },
                    error: function() {
                        new Noty({
                                type: 'error',
                                text: "Something went wrong."
                        }).show();
                    }
                });
            }
        }

        // Item actions
        $( 'table tbody' ).click(function(e) {
            let clickedTarget = $(e.target);
            if (clickedTarget.attr('id') === 'btn-delete-item') {
                clickedTarget.parent().parent().remove();
                updateLogic();
            } else if (clickedTarget.attr('id') == 'each-quantity-field') {
                console.log(clickedTarget);
                checkStock(clickedTarget);
            }
        })

        // Bottom discount
        $( 'input#extra-discount' ).on('change', function(e) {
            updateLogic();
        })
        $( 'input#extra-discount' ).on('input', function(e) {
            updateLogic();
        })

        // Add event to document to check focus out
        $(document).focusout(function (e) {
            let focusedTarget =  $(e.target);
            if (focusedTarget.attr('id') == 'each-quantity-field') {
                checkStock(focusedTarget);
            }
            $("td#each-product-quantity").each(function (index, element) {
                if (!$(element).children('input').val() || $(element).children('input').val() == 0) {
                    $(element).children('input').val(1);
                }
            })
            updateLogic();
        })

    </script>
@endpush

@push('after_styles')
    <style>
        .scroll-box::-webkit-scrollbar-track {
            background-color: rgb(197, 197, 197);
            border-radius: 4px;
        }
        .scroll-box::-webkit-scrollbar {
            width: 4px;
        }
        .scroll-box::-webkit-scrollbar-thumb {
            background-color: #7c69ef;
            border-radius: 5px;
        }

        .scroll-box-hidden::-webkit-scrollbar {
            width: 0px;
        }

        /* Container */
        .main .container-fluid {
            padding: 0 5px;
        }

        /* Filter by category */
        .btn-cate {
            background: #eceff3;
            color: black;
        }
        .btn-cate:hover {
            background: #e3e6ee;
            color: black;
        }
        .btn-cate-selected {
            background: #7c69ef;
            color: white;
        }
        .btn-cate-selected:hover {
            color: white;
            background: #7462dc;

        }

        /* Product card */
        .product-container {
            justify-content: space-between;
            flex-wrap: wrap;
            box-sizing: border-box;
            overflow: hidden;
            display: block;
        }
        .product-card {
            height: max-content;
            background: #eceff3;
            cursor: pointer;
            border: 1px solid rgba(0,40,100,.12);
            border-radius: 0.25rem;
            margin-bottom: 0.35rem;
            width: 48.9%;
            box-sizing: border-box;
            overflow: hidden;
            display: block;
            font-size: 13.5px;
        }
        .product-card:hover {
            background: #e6e9ed;
        }
        .pr-card-header {
            padding: 0;
            margin: 0;
            text-align: center;
        }
        .pr-name,
        .pr-card-footer {
            font-weight: 600;
        }
        .pr-card-img {
            width: 100%;
            height: 60px;
            box-sizing: border-box;
        }
        .item-empty-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .item-empty {
            color: rgb(168, 168, 168);
            font-size: 14px;
        }

        /* Product name responsive */
        .pr-name{
            width: 300%;
            margin: 0;
            padding: 0;
            padding-bottom: 6px;
            padding-left: 5px;
            padding-right: 5px;
            white-space: nowrap;
            
            -webkit-transition: 3.3s;
            -moz-transition: 3.3s;
            transition: 4s;
            
            -webkit-transition-timing-function: linear;
            -moz-transition-timing-function: linear;
            transition-timing-function: linear;
        }
        
        .pr-name:hover{
            margin-left: -190%;
        }

        /* Table to list data */
        table {
            width: 100%;
            white-space: nowrap;
            table-layout: fixed;
            overflow: hidden;
        }
        table tbody {
            overflow-y: auto;
            box-sizing: border-box;
        }
        td {
            padding: 0.50rem 0 0.50rem 0.50rem;
        }
        td input {
            width: 90%;
            padding: 0 3px;
            height: 22px;
            font-size: 14px;
            outline: none;
        }
        td em {
            cursor: pointer;
            font-size: 18px;
        }
        td em#btn-delete-item {
            color: red;
        }
        td em#btn-delete-item:hover {
            color: rgb(213, 0, 0);
        }
        tr td:first-child {
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

