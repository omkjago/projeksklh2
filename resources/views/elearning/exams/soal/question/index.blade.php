<!-- resources/views/questions/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Soal untuk Ujian: {{ $exam->title }}</h1>
        @if (Auth::user()->role !== 'siswa')
            <a href="{{ route('questions.create', $exam->id) }}" class="btn btn-primary mb-3">Tambah Soal</a>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Pertanyaan</th>
                    @if (Auth::user()->role !== 'siswa')
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($questions as $question)
                    <tr>
                        <td>{{ $question->question_text }}</td>
                        @if (Auth::user()->role !== 'siswa')
                            <td>
                                <a href="{{ route('questions.edit', [$exam->id, $question->id]) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('questions.destroy', [$exam->id, $question->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        @else
                            <td>
                                <a href="{{ route('questions.show', [$exam->id, $question->id]) }}" class="btn btn-primary">Jawab</a>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
