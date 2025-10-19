@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Category Management</h1>
    <!-- Add new Category -->
    @if (Auth::user() && Auth::user()->isAdmin())
        <button id="toggle-add-category">Add Category</button>
        <form id="add-form" action="{{route('categories.makeNew')}}" method="POST" style="display: none">
            @csrf
            <input type="text" name="name" class="form-control" placeholder="Category Name" required>
            <button type="submit">Add</button>
        </form>
    @endif

    <h2>Existing Categories</h2>
    @foreach ($categories as $category)
        <p>
            <a href="{{ route('categories.show', $category->category_id) }}">
                {{ $category->name }}
            </a>
        </p>
        @if (Auth::user() && Auth::user()->isAdmin())
            <!-- Edit Category -->
            <button class="toggle-edit-category">Edit Category</button>
            <form class="edit-form" action="{{ route('categories.edit', $category->category_id) }}" method="POST" style="display: none">
                @csrf
                @method('PUT')
                <input type="text" name="name" class="form-control" placeholder="New Name" required>
                <button type="submit">Confirm changes</button>
            </form>

            <!-- Delete Category -->
            <form action="{{ route('categories.erase', $category->category_id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Remove</button>
            </form>
        @endif
    @endforeach
</div>

<script type="text/javascript" src="{{ url('public/js/app.js') }}" defer></script>

@endsection