@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.add') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
	<section class="container-fluid">
	  <h2>
        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
        <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>

        @if ($crud->hasAccess('list'))
          <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
	  </h2>
	</section>
@endsection

@section('content')
    <div class="col-lg-12">
        @include('crud::inc.grouped_errors')
        <form 
            method="POST"
            action="{{ $crud->entry ? url($crud->route .'/' . $crud->entry->id) : url($crud->route) }}"
            @if ($crud->hasUploadFields('create'))
                enctype="multipart/form-data"
            @endif
            >
            {!! csrf_field() !!}
            @if ($crud->entry)
                @method('PUT')
            @endif
            <input type="hidden" name="_http_referrer" value="{{ url($crud->route) }}">
            {{-- load the view from the application if it exists, otherwise load the one in the package --}}
            @if(view()->exists('vendor.backpack.crud.form_content'))
                @include('vendor.backpack.crud.form_content', [ 'fields' => $crud->fields(), 'action' => 'create' ])
            @else
            <div class="card">
                <!-- THE FIELD INCLUDE FROM CONTROLLER -->
                <div class="d-flex mt-3">
                    @php
                        $fields = $crud->fields();
                    @endphp
                    @foreach ($fields as $field)
                        @if (array_key_exists('id',$fields))
                            @if ($field['name'] == 'id')
                                <input type="hidden" name="{{ $field['name'] }}" value="{{ $crud->entry->id }}">
                            @else
                                @if ($field['name'] != 'amount')
                                    @if ($field['name']=='products') 
                                        <div class="w-50">
                                            <label>Products</label>
                                            <select 
                                            style="width:95%"
                                            name="products"
                                            data-init-function="bpFieldInitRelationshipSelectElement" 
                                            data-field-is-inline="false" 
                                            ata-column-nullable="true" 
                                            data-placeholder="Select an entry" 
                                            data-field-multiple="false" 
                                            data-language="en" 
                                            data-is-pivot-select="false" 
                                            bp-field-main-input="" 
                                            class="form-control"
                                            >
                                                <option selected="-">select entry</option>
                                                @foreach($products as $item)
                                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="w-50">
                                            @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @else
                            @if($field['name'] !="amount" && $field['name']!="id")
                                <div class="w-50">
                                    @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="purchase_items">
                {{-- TABLE ONE USE TO SHOW THE PRODUCT AFTER SELECTED --}}
                <table 
                    class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline"
                    id="crudTable"
                    data-responsive-table="1"
                    data-has-details-row="0"
                    data-has-bulk-actions="0"
                    aria-describedby="crudTable_info"
                >
                    <thead>
                        <tr>
                            <th data-orderable="true" data-priority="1" data-column-name="name" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">
                                Product
                            </th>
                            <th data-orderable="true" data-priority="3" data-column-name="phone" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="Phone: activate to sort column ascending">
                                Quantity
                            </th>
                            <th data-orderable="true" data-priority="4" data-column-name="address" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">
                                Amount($)
                            </th>
                            <th data-orderable="false" id="action" data-priority="1" data-visible-in-export="false" class="sorting_disabled" rowspan="1" colspan="1" aria-label="Actions">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody_table1">

                        {{-- EDIT FORM --}}
                        @if ($crud->entry)
                            @foreach($crud->entry->products as $key=>$product)
                                <tr class="odd">
                                    <td class="name">
                                        <input name="{{"products[".$key."][product_id]"}}" type="hidden" value={{$product['pivot']['product_id']}}>
                                        <span>{{$product['name']}}</span>
                                    </td>
                                    <td>
                                        <input name="{{"products[".$key."][quantity]"}}" id="quantity" type="number" value={{$product['pivot']['quantity']}} required="required">
                                    </td>
                                    <td class="amount">
                                        <input name="{{"products[".$key."][amount]"}}" id="amount_1" type="hidden" value={{$product['pivot']['amount']}}>
                                        <span class="amount">{{$product['pivot']['amount']}}</span>
                                    </td>
                                    <td>
                                        <i class="nav-icon la la-trash trash_icon" id="delete_icon"></i>
                                    </td>
                                </tr>
                            @endforeach                   
                        @endif 
                    </tbody>
                </table>
                <input type="text" name="amount" hidden id="total_amount">

                {{-- TABLE TWO USE TO SHOW THE TOTAL AMOUNT --}}
                <table 
                    class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs mt-2 dataTable dtr-inline"
                    id="crudTable"
                    data-responsive-table="1"
                    data-has-details-row="0"
                    data-has-bulk-actions="0"
                    aria-describedby="crudTable_info"
                >
                    <thead>
                        <tr>
                            <th data-orderable="false" data-priority="0" data-column-name="Profile" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting_disabled" rowspan="1" colspan="1" aria-label="Profile">
                                G. Total
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbody_table2">
                        <tr class="odd" id="g_amount_column">
                            <td>
                                @if ($crud->entry)
                                    <span> {{$crud->entry->amount}} $</span>
                                    <input type="hidden" name="amount" value={{$crud->entry->amount}}>
                                @else
                                    <span> $</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            @endif
            {{-- This makes sure that all field assets are loaded. --}}
            <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
            @include('crud::inc.form_save_buttons')
        </form>
	</div>
</div>

@push('after_scripts')
    <script>
        $( document ).ready(function() {
            // GLOBAL VARIABLE
            var supplier_selected_global = "";
            var product_selected_global = "";  // PRODUCT WHICH SELECTED
            var supplierid_select_global = "" // SUPPLIER_ID WHICH SELECTED
            var tr_id = 0; 
            var purchase_product_list = []; // LIST OF THE PRODUCT SELECTED BY USER
            var amount_list = []; // LIST OF AMOUNT FROM EACH PRODUCT
            var g_Amount = 0; // TOTAL AMOUNT AFTER SUM AN AMOUNT FROM EACH PRODUCT

            // AUTO FUNCTION USE TO GET OLD DATA FROM DATABASE
            function getoldData(){
                var url = $(location).attr("href");
                var segment = url.split('/');
                var purcahse_id = segment[5];
                if (purcahse_id != "undefined"){
                    $.get("http://127.0.0.1:8000/admin/getpurchases/"+purcahse_id).then((response)=>{
                        setUp_global_var(response)
                    })
                }
            }
            getoldData();

            // USE TO ASSIGN LIST, AND TR ID BY DATA GET FROM DB
            function setUp_global_var(data){
                var products = data['products'];
                var supplier = data['supplier'];
                supplier_selected_global = supplier['name'];
                supplierid_select_global = supplier['id'];
                tr_id = products.length;
                for (i=0;i<products.length;i++){
                    var object = {};
                    amount_list.push(products[i]['pivot']['amount'])
                    object['supplier'] = supplier['name'];
                    object['product'] = products[i]['name'];
                    purchase_product_list.push(object)
                }
            }
            // GLOBAL DOM VARIABLE
            var tbody_table1 = $('#tbody_table1');
            var trash_icon = $('#delete_icon');
            // GET SUPPLIER SELECTED
            var select_supplier_dom = $($('select[name="supplier_id"]'));
            select_supplier_dom.change(function()
            {
                if ($(this).children("option:selected").text() != "-"){
                    supplier_selected_global = $(this).children("option:selected").text();
                    supplierid_select_global = $(this).children("option:selected").val();
                }
                console.log('log from function1:: select supplier')
            });
            
            // GET PRODUCT SELECTED
            var select_product_dom =  $('select[name="products"]');
            select_product_dom.on('change',function()
            {
                var product_selected = $(this).children("option:selected").text();
                if (product_selected != '-' && supplier_selected_global != ""){
                    var object = {};
                    object['supplier'] = supplier_selected_global;
                    object['product'] = product_selected;
                    if (purchase_product_list.length==0){
                        purchase_product_list.push(object);
                        product_selected_global = $(this).children("option:selected").val();
                        createTablerecord(object);
                    }else{
                        product_selected_global = $(this).children("option:selected").val();
                        check_if_obj_in_arr(purchase_product_list,object);
                    }
                }else {
                    if (supplier_selected_global == ""){
                        new Noty({
                            type: "error",
                            text: 'supplier is require to select',
                        }).show();
                    }
                }
                console.log('log from function2:: select product');

            });
            
            // DELETE PRODUCT SELECTED FROM TABLE
            $('body').on('click','.trash_icon',function(e)
            {
                var record_to_delete = $(this).parent().parent();
                var product_name_to_delete = record_to_delete.children(".name").text();
                var product_supplier_to_delete = record_to_delete.children(".supplier").text();
                var product_amount_column = record_to_delete.children('.amount');
                var product_amount_to_delete = product_amount_column.children('span').text();
                $.confirm({
                    title: 'Delete!',
                    content: 'Do you want to delete this product?',
                    buttons: {
                        confirm: {
                            action: function () {
                                record_to_delete.remove();
                                g_Amount -= product_amount_to_delete;
                                setAmount_to_dom(g_Amount);
                                // DELETE THE AMOUNT FROM AMOUNT LIST WHEN PRODUCT SELECTED DELETE
                                for (i=0;i<amount_list.length;i++){
                                    if (amount_list[i]==product_amount_to_delete){
                                        amount_list.splice(i,1);
                                    }
                                }
                                // DELETE PURCHASE_PRODUCT FROM THE LIST WHEN THE PRODUCT SELECTED DELETE
                                for (index=0;index < purchase_product_list.length; index++){

                                    if (purchase_product_list[index]['product']==product_name_to_delete && purchase_product_list[index]['supplier']==product_supplier_to_delete){
                                        purchase_product_list.splice(index,1);

                                    }
                                }
                                getListamount();
                                alert_delete();
                                console.log('log from function3::delete product');
                            },
                            btnClass:"btn-red",
                        }
                        ,
                        cancel: {
                            action: function () {
                                $.alert('Product did not delete');
                            },
                            btnClass:"btn-green",
                        }
                    }
                });
            })

            // CALCULATE PURCHASE AMOUNT
            //======>>> when user input quantity field
            $('body').on('click','#quantity',function(e)
            {
                $(this).on("input",function(){
                    var product_quantity = $(this).val();
                    var parent_of_this_element = $(this).parent().parent();
                    var product_column = parent_of_this_element.children(".name");
                    var product = product_column.children('span');
                    var product_name = product.text();
                    var amount_column = parent_of_this_element.children(".amount");
                    var span_of_amount_column = amount_column.children('span')
                    var product_input_amount_column = amount_column.children("input");
                    // span_of_amount_column.html(product_name)
                    $.get('http://127.0.0.1:8000/admin/getproducts',function(data){
                        var one_time = true
                        for (i=0;i<data.length;i++){
                            amount_list = []
                            if (data[i]['name']==product_name && one_time){
                                span_of_amount_column.html((data[i]['price'])*product_quantity);
                                product_input_amount_column.val((data[i]['price'])*product_quantity);
                                getListamount();
                                one_time = false
                            }
                        }
                    })
                    console.log('log from function4:: calculate total amount')
                })
            })

            // USE TO ALERT WHEN PRODUCT ADDED TO LIST
            function alert_success()
            {
                new Noty({
                    type: "success",
                    text: 'Product is added!',
                }).show();
                console.log("log from function6:: alert success message when product added")
            }

            // USE TO ALERT WHEN PRODUCT DELETE FROM LIST
            function alert_delete()
            {
                new Noty({
                    type: "success",
                    text: 'Product is deleted!',
                }).show();   
                console.log("log function7:: alert delete message")
            }

            // USE TO COLLECT ALL THE AMOUNT FROM EACH PRODUCT (GET FROM DOM)
            function getListamount()
            {
                g_Amount = 0;
                amount_list = [];
                // find total amount
                var table_container = $('#tbody_table1').children();
                var previous_child = $("#tbody_table1").children();
                var tr = previous_child.first()
                for (i=0;i<table_container.length;i++){
                    if (i==0){
                        tr = previous_child.first();
                    }else {
                        tr = tr.next();
                    }
                    var td_amount = tr.children("td.amount");
                    amount_list.push(td_amount.children("span.amount").text())
                }
                getAmount(amount_list);
                console.log("log from function8::get text from element")                
            }

            // USE TO SUM ALL THE AMOUNT AFTER IT COLLECT INTO THE LIST
            function getAmount(amounts)
            {
                for (i=0;i<amounts.length;i++){
                    g_Amount += parseInt(amounts[i])
                }
                setAmount_to_dom(g_Amount);
                console.log('log from function9:: sum all amount to get total amount');
            }

            // USE TO ASSIGN THE TOTAL AMOUNT TO THE DOM (HTML)
            function setAmount_to_dom(amount)
            {
                var tbody_table2 = $('#tbody_table2');
                var tr = tbody_table2.children('#g_amount_column');
                var td = tr.children('td');
                var span = td.children('span');
                $('#total_amount').val(amount);
                span.html(amount + "$");
                console.log('log from function10:: set add total amount to element text');
            }
            
            // USE TO CHECK IF THE PRODUCT IS ALREADY SELECT (TO PREVENT DUPLICATE PRODUCT SELECTION)
            function check_if_obj_in_arr(array,object)
            {
                for (i=0;i<array.length;i++){
                    if (array[i]['product']==object['product']){
                        if (array[i]['supplier']==object['supplier']){
                            alert_obj_is_in_list(object['supplier']);
                            return;
                        }else {
                            if (i==array.length){
                                purchase_product_list.push(object);
                                createTablerecord(object);
                                return;
                            }
                        }
                    }else if(array[i]['product']!=object['product'] && i==array.length-1){
                        purchase_product_list.push(object);
                        createTablerecord(object);
                        return;
                    }
                }
                console.log("log from function11:: check duplicate object in array");
            }

            // USE TO ALERT WHEN THE PRODUCT ALREADY SELECTED
            function alert_obj_is_in_list(supplier)
            {
                $.confirm({
                    title: 'Duplicate product!',
                    content: 'This product is already bought from supplier '+supplier,
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        close: function () {
                        }
                    }
                })
                console.log("log from function12:: alert when object duplicate")
            }

            // USE TO CREATE THE TR AND APPEND IT TO TABLE BODY WHEN USER SELECT PRODUCT
            function createTablerecord(data)
            {
                var tr = $('<tr></tr>').attr({class:"odd",id:tr_id})
                tr_id++;
                // CREATE PRODUCT COLUMN AND APPEND TO THE CONTIANER (TR)
                var td_column_product = $('<td></td>').attr({class:"name"});
                var span_column_product = $('<span></span>');
                var input_column_product = $('<input>').attr({type:"hidden",value:product_selected_global});
                var input_product_id = $('<input></input>').attr({name:"products["+tr_id+"][product_id]",type:'hidden',value:product_selected_global});
                td_column_product.append(input_product_id);
                span_column_product.html(data['product']);
                td_column_product.append(span_column_product);
                td_column_product.append(input_column_product);
                tr.append(td_column_product);



                // CREATE QUANTITY COLUMN AND APPEND TO THE CONTAINER (TR)
                var td_column_quantity = $('<td></td>');
                var input_column_quantity = $('<input>').attr({name: "products["+tr_id+"][quantity]",id: "quantity",type: "number",required:true});
                td_column_quantity.append(input_column_quantity)
                tr.append(td_column_quantity);

                // CREATE AMOUNT COLLUMN AND APPEND TO THE CONTAINER (TR)
                var td_column_amount = $('<td></td>').attr({class:"amount"});
                var input_column_amount = $('<input>').attr({name:"products["+tr_id+"][amount]",id:"amount_"+tr_id,type:"hidden"})
                var span_column_amount = $('<span></span>').attr({class:"amount"});
                span_column_amount.html(0);
                td_column_amount.append(input_column_amount);
                td_column_amount.append(span_column_amount)
                tr.append(td_column_amount);

                // CREATE ACTION COLUMN AND APPEND TO THE CONTAINER (TR)
                var td_column_action = $('<td></td>');
                var delete_icon = $('<i></i>').attr({class:"nav-icon la la-trash trash_icon",id:"delete_icon"})
                td_column_action.append(delete_icon);
                tr.append(td_column_action);
                tbody_table1.append(tr);
                alert_success();
                console.log("log from function13:: create dom element");
            }
        });
    </script>
@endpush
@push('after_style')
    <style>
        tbody tr td {
            width:10%;
        }
        #quantity, #amount {
            width: 70%;
        }
        #delete_icon {
            margin-left: 15%;
            cursor: pointer;
            color: red;
        }
        #select_supplier, #select_product {
            width: 45%;
        }
    </style>
@endpush
@endsection