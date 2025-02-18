<!-- resources/views/exams/show.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>{{ $exam->title }}</h1>
    <p>{{ $exam->description }}</p>
    <h2>Soal</h2>
    @if(auth()->user()->role != 'siswa')
        <a href="{{ route('questions.create', $exam->id) }}">Buat Soal Baru</a>
    @endif
    <ul>
        @foreach($exam->questions as $question)
            <li>
                {{ $question->question_text }}
                @if(auth()->user()->role != 'siswa')
                    <a href="{{ route('questions.edit', [$exam->id, $question->id]) }}">Edit</a>
                    <form action="{{ route('questions.destroy', [$exam->id, $question->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
