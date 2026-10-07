@extends('layout.admin')
@section('header', 'Edit tempat menarik | Flora')
@section('content')
<div class="places-admin px-4 py-6 mx-auto">
    <header class="mb-6">
        <h1 class="text-2xl font-semibold text-(--flora-moss) tracking-tight">Edit tempat menarik</h1>
        <p class="text-sm text-gray-600 mt-1">Perbarui informasi {{ $place->nama }}.</p>
    </header>
    <form method="POST" action="{{ route('admin.tempat-menarik.update', $place) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.tempat-menarik.form')
    </form>
</div>
@endsection
