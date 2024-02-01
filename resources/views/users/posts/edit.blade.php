@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        {{-- The @csrf is for the security of our application --}}
        {{-- CSRF stands for Cross-Site-Request-Forgeries --}}
        @method('PATCH')
        <div class="mb-3">
            <label for="category" class="form-label d-block fw-bold">
                Category <span class="text-muted fw-normal">(Up to 3)</span>
            </label>

            @foreach ($all_categories as $category)

                <div class="form-check form-check-inline">

                    @if (in_array($category->id, $selected_categories))
                        <input type="checkbox" name="category[]" id="{{ $category->name }}" value="{{ $category->id }}" class="form-check-input" checked>
                    @else
                        <input type="checkbox" name="category[]" id="{{ $category->name }}" value="{{ $category->id }}" class="form-check-input">
                    @endif

                    <label for="{{ $category->name }}" class="form-check-label">{{ $category->name }}</label>
                </div>
                @error('category')
                    <p class="text-danger small">{{ $message }}</p>
                @enderror
            @endforeach


        </div>
        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" placeholder="What's on your mind">{{ old('description', $post->description) }}</textarea>
            @error('description')
                <p class="text-danger small">{{ $message }}</p>
            @enderror
        </div>
        <div class="row mb-4">
            <div class="col-6">
                <label for="image" class="form-label fw-bold">Image</label>
                <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="img-thumbnail w-100">
                <input type="file" name="image" id="image" class="form-control mt-1" aria-describedby="image-info">
                <div class="form-text" id="image-info">
                    The acceptable formats are jpeg,jpg,png and gif only.<br>
                    Max file size: 1048Kb.
                </div>
                @error('image')
                    <p class="text-danger small">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-warning px-5">Save</button>
    </form>
@endsection