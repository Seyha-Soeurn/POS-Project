<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PurchaseRequest;
use App\Models\Purchase;
use App\Models\Supplier;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PurchaseCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PurchaseCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as traitCreate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {destroy as traitDestroy;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Purchase::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/purchase');
        CRUD::setEntityNameStrings('purchase', 'purchases');
        CRUD::setCreateView('create_purchase_form');
        CRUD::setUpdateView('create_purchase_form');
        $this->data['supplier'] = Supplier::get();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('supplier_id');
        CRUD::column('amount');
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
        CRUD::setValidation(PurchaseRequest::class);

        CRUD::field('supplier_id');
        CRUD::addField([
            'name' => 'products',
            'type' => 'select2'
        ]);
        // CRUD::field('product_id');
        CRUD::field('amount');
        // CRUD::field('quantity');

        /**
         * Fields can be defined using the fluparameterizeent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    public function setupUpdateOperation()
    {
        CRUD::field('supplier_id');
        CRUD::addField([
            'name' => 'products',
            'type' => 'select2'
        ]);
        CRUD::field('amount');
    }

    public function getPurchases()
    {
        return Purchase::with('supplier','product')->get();
    }

    public function destroy()
    {
        $entry = $this->crud->getCurrentEntry();
        $entry->products()->delete();
        return $this->traitDestroy($entry);
    }

}
