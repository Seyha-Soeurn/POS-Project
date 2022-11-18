{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<!-- cutomer menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
        <i class="nav-icon la la-users"></i> 
        Cutomers
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('customer') }}"><i class="nav-icon la la-list"></i> Customer list</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('customer/create') }}"><i class="nav-icon la la-plus"></i> Add Customer</a></li>
    </ul>
</li>

<!-- user menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
        <i class="nav-icon la la-user-circle"></i> 
        User
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-list"></i> User list</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user/create') }}"><i class="nav-icon la la-plus"></i> Add user</a></li> 
    </ul>
</li>

<!-- supplier menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
    <i class="nav-icon la la-user"></i>
        Supplier
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('supplier') }}"><i class="nav-icon la la-list"></i> Supplier List</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('supplier/create') }}"><i class="nav-icon la la-plus"></i> Add Supplier</a></li>
    </ul>
</li>

<!-- category menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
    <i class="nav-icon la la-cog"></i>
        Category
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-list"></i> Category list</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('category/create') }}"><i class="nav-icon la la-plus"></i> Add Category</a></li>
    </ul>
</li>

<!-- order menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
    <i class="nav-icon la la-shopping-cart"></i>
        Order
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('order') }}"><i class="nav-icon la la-list"></i> Order List</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('order/create') }}"><i class="nav-icon la la-plus"></i> Add Order</a></li>
    </ul>
</li>

<!-- order product menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
    <i class="nav-icon la la-book"></i>
    Order Details
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('order-product') }}"><i class="nav-icon la la-list"></i>List order Product </a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('order-product/create') }}"><i class="nav-icon la la-plus"></i> Add new</a></li>
    </ul>
</li>

<!-- product menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
    <i class="nav-icon la la-store"></i>
        Product
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('product') }}"><i class="nav-icon la la-list"></i> Product list</a></li> 
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('product/create') }}"><i class="nav-icon la la-plus"></i> Add Product</a></li> 
    </ul>
</li>

<!-- purchase menu -->
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#">
    <i class="nav-icon la la-truck"></i>
        Purchase
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('purchase') }}"><i class="nav-icon la la-list"></i> Purchase List</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('purchase/create') }}"><i class="nav-icon la la-plus"></i> Add purchase</a></li>
    </ul>
</li>


