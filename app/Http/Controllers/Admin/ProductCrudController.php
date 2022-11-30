<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use App\Models\CategoryProduct;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\ProductRequest;
use App\Repository\Eloquent\ProductRepository;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {destroy as traitDestroy;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
        $this->crud->setShowView('preview_product');
        CRUD::enableExportButtons();
        $this->crud->addButton('top', 'import_button', 'view', 'Import', 'end');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('price');
        CRUD::column('stock')->default('0');
        CRUD::column('created_at');
        CRUD::column('updated_at');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::field('name')->tab('Product info');
        CRUD::field('price')->tab('Product info');
        CRUD::field('stock')->tab('Product info');
        CRUD::addField([
            'label'     => "Categories",
            'type'      => 'select2_multiple',
            'name'      => 'categories',
            'tab'      => 'Product info',
        ]);
        CRUD::addField([
            'name' => 'images',
            'label' => "Upload images related to your product",
            'tab' => 'Images',
            'type' => 'multi_images_upload',
            'sub-field' => 'url',
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    // Delete Customer and relationship
    public function destroy()
    {
        $entry = $this->crud->getCurrentEntry();
        foreach ($entry->images as $item) {
            if ($item->url) {
                \Storage::disk('upload')->delete($item->url);
            }
        }
        $entry->images()->delete();
        return $this->traitDestroy($entry);
    }

    // Get all products
    public function listProduct()
    {
        $listProduct = Product::with(['images'])->get();
        return response()->json(['success' => true, 'data' => $listProduct]);
    }

    // Search products
    public function filterProduct(Request $request)
    {
        if ($request->category_id && $request->keyword) {
            $categoryProducts = CategoryProduct::select('product_id')->where('category_id', $request->category_id)->pluck('product_id')->toArray();
            $listProduct = Product::with(['images'])
            ->whereIn('id', $categoryProducts)
            ->where(strtolower('name'), 'LIKE', '%'.strtolower($request->keyword).'%')
            ->orWhere(strtolower('price'), 'LIKE', '%'.strtolower($request->keyword).'%')
            ->orWhere(strtolower('product_code'), 'LIKE', '%'.strtolower($request->keyword).'%')
            ->get();
            return response()->json(['success' => true, 'data' => $listProduct]);
        } else {
            if ($request->keyword) {
                $listProduct = Product::with(['images'])
                ->where(strtolower('name'), 'LIKE', '%'.strtolower($request->keyword).'%')
                ->orWhere(strtolower('price'), 'LIKE', '%'.strtolower($request->keyword).'%')
                ->orWhere(strtolower('product_code'), 'LIKE', '%'.strtolower($request->keyword).'%')
                ->get();
                return response()->json(['success' => true, 'data' => $listProduct]);
            } elseif ($request->category_id) {
                $categoryProducts = CategoryProduct::select('product_id')->where('category_id', $request->category_id)->pluck('product_id')->toArray();
                $listProduct = Product::with(['images'])
                ->whereIn('id', $categoryProducts)->get();
                return response()->json(['success' => true, 'data' => $listProduct]);
            }
        }
    }

    // Get one product
    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json(['success' => true, 'data' => $product]);
    }
}
