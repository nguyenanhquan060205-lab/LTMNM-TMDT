@extends('layouts.app')

@section('content')
    <div class="row g-4">
        <aside class="col-lg-3">
            <nav class="list-group">
                <a class="list-group-item list-group-item-action" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="list-group-item list-group-item-action" href="{{ route('admin.users.index') }}">Users</a>
                <a class="list-group-item list-group-item-action" href="{{ route('admin.products.index') }}">Products</a>
                <a class="list-group-item list-group-item-action" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="list-group-item list-group-item-action" href="{{ route('admin.complaints.index') }}">Complaints</a>
                <a class="list-group-item list-group-item-action" href="{{ route('admin.categories.index') }}">Categories</a>
            </nav>
        </aside>
        <section class="col-lg-9">
            @yield('admin-content')
        </section>
    </div>
@endsection
