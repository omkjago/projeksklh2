<!-- resources/views/questions/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Jawab Soal</h1>
        <h2>{{ $question->question_text }}</h2>
        <form action="{{ route('questions.answer', [$exam->id, $question->id]) }}" method="POST">
            @csrf
            <input type="hidden" name="user_exam_id" value="{{ $user_exam_id }}">
            @foreach ($question->answers as $answer)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answer_id" id="answer{{ $answer->id }}" value="{{ $answer->id }}">
                    <label class="form-check-label" for="answer{{ $answer->id }}">
                        {{ $answer->answer_text }}
                    </label>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary mt-3">Kirim Jawaban</button>
        </form>
    </div>
@endsection
