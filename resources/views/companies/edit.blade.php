@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Company</h1>
    <form method="POST" action="{{ route('companies.update', $company->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $company->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $company->email }}" required>
        </div>

        @if ($company->logo)
        <div class="mb-3">
            <label for="logo" class="form-label">Current Logo</label>
            <img src="{{ asset('storage/logos/' . basename($company->logo)) }}" alt="Company Logo" style="max-width: 200px;">
        </div>
        @endif

        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
        </div>

        <div class="mb-3">
            <label for="website" class="form-label">Website</label>
            <input type="text" class="form-control" id="website" name="website" value="{{ $company->website }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection