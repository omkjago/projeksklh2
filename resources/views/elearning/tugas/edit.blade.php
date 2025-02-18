<!-- resources/views/assignments/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Edit Tugas</h1>
    <form action="{{ route('assignments.update', $assignment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" value="{{ $assignment->title }}">
        <label for="content">Konten:</label>
        <textarea id="content" name="content">{{ $assignment->content }}</textarea>
        <button type="submit">Simpan</button>
    </form>
@endsection
