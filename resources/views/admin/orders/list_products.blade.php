{{-- @extends(backpack_view('blank')) --}}

{{-- @section('content')

    <div> --}}
    
    {{-- @include('crud::inc.grouped_errors') --}}
        {{-- <form method="post"
        action="{{ URL('admin/order/create') }}"
        enctype="multipart/form-data"
        >
        {!! csrf_field() !!}

            <div class="d-flex mb-2" style="height: 66vh;">
                <div class="mr-2 py-2 rounded bg-white justify-content-start"
                style="width: 70%; border: 1px solid rgba(0,40,100,.12); box-sizing: border-box;">
                    <div class="d-flex">
                        
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
    
                <div class="scroll-box rounded p-2 h-100 bg-white"
                 style="width: 30%; border: 1px solid rgba(0,40,100,.12); overflow-y: auto;">
                    <div class="product-container d-flex">
                        @for ($i=0; $i<7; $i++)
                            <div class="rounded product-card mb-2" style="width: 48.8%; box-sizing: border-box; font-size: 14px;">
                                <p class="pr-card-header px-1">$ 10</p>
                                <img class="pr-card-img" src="{{ URL('uploads/folder_1/folder_2/2e648e2f99890f36e31a99f09b3d6aff.jpg') }}" style="width: 100%; height: 70px; box-sizing: border-box;">
                                <span class="pr-card-footer">
                                    <p class="px-1 p-0 m-0 mt-1 text-center text-primary">(PR-00{{ $i }})</p>
                                    <div class="pr-name-wrapper px-1">
                                        <p class="pr-name">
                                        This is an extreamly longtext, kindof like a name could be
                                        </p>
                                    </div>
                                </span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            @include('crud::inc.form_save_buttons')
        </form>
    </div>

@endsection --}}
{{-- 
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
@endpush --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
</body>
</html>