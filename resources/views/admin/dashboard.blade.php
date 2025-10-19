@extends('layouts.app')

@section('content')
    <div class="products-container">
        <h1>Shop Management</h1>
        <div id="cards">
            <article class="card">
                <div class="product-tittle">
                    <h2>Products</h2>
                    <p>Add, edit and remove products.</p>
                    <form action="{{ route('admin.products') }}" method="GET">
                        <button class="admin-products" type="submit" style="padding: 10px; margin: 1px; display: flex; justify-content: center; align-items: center;">
                            Go to products
                        </button>
                    </form>
                </div>
            </article>
            <article class="card">
                <div class="user-tittle">
                    <h2>Customers</h2>
                    <p>Add and remove users. Edit personal information.</p>
                    <form action="{{ route('admin.users') }}" method="GET">
                        <button class="admin-users" type="submit" style="padding: 10px; margin: 1px; display: flex; justify-content: center; align-items: center;">
                            Go to users
                        </button>
                    </form>
                </div>
            </article>
            <article class="card">
                <div class="order-tittle">
                    <h2>Orders</h2>
                    <p>View all purchases.</p>
                    <form action="{{ route('admin.orders') }}" method="GET">
                        <button class="admin-users" type="submit" style="padding: 10px; margin: 1px; display: flex; justify-content: center; align-items: center;">
                            Go to orders
                        </button>
                    </form>
                </div>
            </article>
        </div>
        </div>
@endsection

