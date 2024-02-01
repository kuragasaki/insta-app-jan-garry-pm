@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
{{-- Note: We will display all the list of categories here. Because category is required when creating a post --}}
<form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    {{-- The @csrf is for the security of our application --}}
    {{-- CSRF stands for Cross-Site-Request-Forgeries --}}
    <div class="mb-3">
        <label for="category" class="form-label d-block fw-bold">
            Category <span class="text-muted fw-normal">(Up to 3)</span>
        </label>

        @foreach ($all_categories as $category)
            <div class="form-check form-check-inline">
                <input type="checkbox" name="category[]" id="{{ $category->name }}" value="{{ $category->id }}" class="form-check-input">

                <label for="{{ $category->name }}" class="form-check-label">{{ $category->name }}</label>
            </div>
            @error('category')
                <p class="text-danger small">{{ $message }}</p>
            @enderror
        @endforeach
    </div>
    <div class="mb-3">
        <label for="description" class="form-label fw-bold">Description</label>
        <textarea name="description" id="description" rows="3" class="form-control" placeholder="What's on your mind">{{ old('description') }}</textarea>
        @error('description')
            <p class="text-danger small">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="image" class="form-label fw-bold">Image</label>
        <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
        <div class="form-text" id="image-info">
            The acceptable formats are jpeg,jpg,png and gif only.<br>
            Max file size: 1048Kb.
        </div>
        @error('image')
            <p class="text-danger small">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary px-5">Post</button>
</form>
@endsection