<!-- resources/views/exams/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Buat Ujian</h1>
        <form action="{{ route('exams.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Judul</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div class="form-group">
                <label for="type">Jenis</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="ulangan">Ulangan</option>
                    <option value="TAS">TAS</option>
                    <option value="PAS">PAS</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
