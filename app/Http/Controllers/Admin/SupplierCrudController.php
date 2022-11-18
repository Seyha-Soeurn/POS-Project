<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SupplierCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SupplierCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation { destroy as traitDestroy; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Supplier::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/supplier');
        CRUD::setEntityNameStrings('supplier', 'suppliers');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'Profile',
            'type' => 'image',
            'height' => '30px',
            'width'  => '30px',
        ]);
        CRUD::column('name');
        CRUD::column('phone');
        CRUD::column('email');
        CRUD::column('address');
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
        CRUD::setValidation(SupplierRequest::class);
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
        ];
        $messages = [
            'name.required' => 'name is require to field!',
            'phone.required' => 'Phone field can be null!!',
            'email.required' => 'Email is require!!!',
            'address.required' => 'Address is require!!!',
        ];
        $this->crud->setValidation($rules, $messages);
        CRUD::field('name');

        CRUD::field('email');
        CRUD::field('address');
        CRUD::addField([
            'name' => 'image.url',
            'label' => 'Profile',
            'type' => 'image',
            'aspect_ratio' => 1,
            'crop' => true
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

    public function destroy()
    {
        $entry = $this->crud->getCurrentEntry();
        if ($entry->image->url) {
            \Storage::disk('upload')->delete($entry->image->url);
        }
        $entry->image()->delete();
        return $entry->delete();
    }
}
