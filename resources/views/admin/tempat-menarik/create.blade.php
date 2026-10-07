@extends('layout.admin')
@section('header', 'Tambah tempat menarik | Flora')
@section('content')
<div class="places-admin px-4 py-6 mx-auto">
    <header class="mb-6">
        <h1 class="text-2xl font-semibold text-(--flora-moss) tracking-tight">Tambah tempat menarik</h1>
        <p class="text-sm text-gray-600 mt-1">Tambahkan foto dan informasi tempat di kawasan kebun raya.</p>
    </header>
    <form method="POST" action="{{ route('admin.tempat-menarik.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.tempat-menarik.form')
    </form>
</div>
@endsection
