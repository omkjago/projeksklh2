<!-- resources/views/modules/index.blade.php -->
@extends('layouts.app')

@section('content')
    <h1>Daftar Modul</h1>
    <a href="{{ route('elearning.materials.create') }}">Buat Modul Baru</a> <!-- Change materials to modules -->
    <ul>
        @foreach($modules as $module)
            <li>
                <a href="{{ route('elearning.modules.show', $module->id) }}">{{ $module->title }}</a>
                <a href="{{ route('elearning.modules.edit', $module->id) }}">Edit</a>
                <form action="{{ route('elearning.modules.destroy', $module->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Hapus</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
