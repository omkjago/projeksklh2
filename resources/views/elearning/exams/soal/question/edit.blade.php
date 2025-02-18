<!-- resources/views/questions/edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Soal</h1>
        <form action="{{ route('questions.update', [$exam->id, $question->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="question_text">Pertanyaan</label>
                <textarea class="form-control" id="question_text" name="question_text" rows="3" required>{{ $question->question_text }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Update Soal</button>
        </form>
    </div>
@endsection
