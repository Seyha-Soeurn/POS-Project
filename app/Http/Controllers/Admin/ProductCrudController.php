<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\CategoryProduct;
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
        CRUD::column('stock');
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

    public function getProducts(){
        return Product::all();
    }
}
