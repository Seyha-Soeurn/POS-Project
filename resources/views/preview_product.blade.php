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
    <div class="row">
        <div class="{{ $crud->getShowContentClass() }}">

        {{-- Default box --}}
        <div class="">
            @if ($crud->model->translationEnabled())
                <div class="row">
                    <div class="col-md-12 mb-2">
                        {{-- Change translation button group --}}
                        <div class="btn-group float-right">
                        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('_locale')?request()->input('_locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($crud->model->getAvailableLocales() as $key => $locale)
                                <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?_locale={{ $key }}">{{ $locale }}</a>
                            @endforeach
                        </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card no-padding no-border">
                @php
                    $product = $crud->entry;
                    $images = $product->getImages();
                    $relatedCategories = $product->getCategories();
                @endphp
                <table class="table table-striped mb-0">
                    <tbody>
                        <tr>
                            <td>
                                <strong>Product code</strong>
                            </td>
                            <td>
                                {{ $product->product_code }}
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <strong>Name</strong>
                            </td>
                            <td>
                                {{ $product->name }}
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <strong>Stock</strong>
                            </td>
                            <td>
                                {{ $product->stock }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Categories</strong>
                            </td>
                            <td>
                                <div class="d-flex" style="flex-wrap: wrap; gap: 3px;">
                                    @if (count($relatedCategories) > 0)
                                        @foreach ($relatedCategories as $item)
                                            <p class="bg-secondary rounded m-0 px-2" style="padding-top: 3px; padding-bottom: 3px;">{{ $item->getCategory()->name }}</p>
                                        @endforeach
                                    @else
                                        <span style="color: gray;">No added categories.</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <strong>Created date</strong>
                            </td>
                            <td>
                                {{ $product->created_at }}
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <strong>Updated at</strong>
                            </td>
                            <td>
                                {{ $product->updated_at }}
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                @if (count($images) > 0)
                                    <div class="d-flex flex-wrap" style="width: 100%; gap: 3px;">
                                        @foreach ($images as $item)
                                            <img src="{{ URL($item->url) }}" class="rounded" style="height: 70px;" alt="Image not found.">
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color: gray;">No uploaded images.</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
                            <td>
                                @include('crud::inc.button_stack', ['stack' => 'line'])
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        </div>
    </div>
@endsection