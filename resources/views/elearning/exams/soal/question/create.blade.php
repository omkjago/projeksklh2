<!-- resources/views/questions/create.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tambah Soal untuk Ujian: {{ $exam->title }}</h1>
        <form action="{{ route('questions.store', $exam->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="question_text">Pertanyaan</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Tambah Soal</button>
        </form>
    </div>
@endsection
