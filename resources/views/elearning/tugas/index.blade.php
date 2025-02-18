<!-- resources/views/assignments/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Daftar Tugas</h1>
    <a href="{{ route('assignments.create') }}">Buat Tugas Baru</a>
    <ul>
        @foreach($assignments as $assignment)
            <li>
                <a href="{{ route('assignments.show', $assignment->id) }}">{{ $assignment->title }}</a>
                <a href="{{ route('assignments.edit', $assignment->id) }}">Edit</a>
                <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
