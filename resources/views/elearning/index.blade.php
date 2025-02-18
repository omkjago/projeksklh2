<!-- resources/views/elearning/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>E-Learning</h1>
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('exams.index') }}" class="btn btn-primary btn-block">Ujian</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('scores.index') }}" class="btn btn-primary btn-block">Nilai</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('materials.index') }}" class="btn btn-primary btn-block">Modul</a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('assignments.index') }}" class="btn btn-primary btn-block">Tugas</a>
            </div>
        </div>
    </div>
@endsection
