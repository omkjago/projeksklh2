<!-- resources/views/exams/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Ujian</h1>
        <form action="{{ route('exams.update', $exam->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $exam->title }}" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" required>{{ $exam->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="type">Jenis</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="ulangan" {{ $exam->type == 'ulangan' ? 'selected' : '' }}>Ulangan</option>
                    <option value="TAS" {{ $exam->type == 'TAS' ? 'selected' : '' }}>TAS</option>
                    <option value="PAS" {{ $exam->type == 'PAS' ? 'selected' : '' }}>PAS</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
