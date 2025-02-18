<!-- resources/views/modules/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Edit Modul</h1>
    <form action="{{ route('elearning.materials.update', $module->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" value="{{ $module->title }}">
        <label for="content">Konten:</label>
        <textarea id="content" name="content">{{ $module->content }}</textarea>
        <button type="submit">Simpan</button>
    </form>
@endsection
