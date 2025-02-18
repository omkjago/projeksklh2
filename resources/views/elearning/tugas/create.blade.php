<!-- resources/views/assignments/create.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Buat Tugas Baru</h1>
    <form action="{{ route('assignments.store') }}" method="POST">
        @csrf
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title">
        <label for="content">Konten:</label>
        <textarea id="content" name="content"></textarea>
        <button type="submit">Simpan</button>
    </form>
@endsection
