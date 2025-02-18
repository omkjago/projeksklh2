<!-- resources/views/exams/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Daftar Ujian</h1>
        @if($canCreate)
            <a href="{{ route('exams.create') }}" class="btn btn-primary mb-3">Buat Ujian</a>
        @endif
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Jenis</th>
                    <th>Status</th>
                    @if($canUpdate)
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($exams as $exam)
                    <tr>
                        <td>{{ $exam->title }}</td>
                        <td>{{ $exam->description }}</td>
                        <td>{{ $exam->type }}</td>
                        <td>{{ $exam->status }}</td>
                        @if($canUpdate)
                            <td>
                                <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                                <form action="{{ route('exams.toggleStatus', $exam->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">
                                        {{ $exam->status == 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
