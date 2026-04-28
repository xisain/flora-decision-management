@extends('layout.admin')
@section('content')

<form action="{{ route('criteria.store') }}" method="post">
    <div>
        <label for="nama">Nama Kriteria</label> <br/>
        <input type="text" name="nama" id="nama" class="border border-gray-500 rounded-2xl">
        <label for="nama">Weight</label>
        <input type="decimal" name="nama" id="nama" class="border border-gray-500 rounded-2xl">
        <label for="type">Tipe</label>
        <select name="type" id="type">
            <option value="cost">Cost</option>
            <option value="benefit">benefit</option>
        </select>
        <label for="preference_type">Preferensi</label>
        <select name="preference_type" id="preference_type">
            <option value="u-shape">U-shape</option>
            <option value="gaussian">gaussian</option>
            <option value="usual">usual</option>
            <option value="v-shape">v-shape</option>
            <option value="linear">linear</option>
        </select>
        <label for="p">p</label>
        <input type="number" name="p" id="p">
        <label for="q">q</label>
        <input type="number" name="q" id="q">

    </div>
</form>
@endsection
