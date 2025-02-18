<?php

// app/Http/Controllers/ScoreController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserExam;
use App\Models\Department;
use App\Models\SchoolClass;

class ScoreController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('scores.index', compact('departments'));
    }

    public function showClasses($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        $classes = $department->classes;
        return view('scores.classes', compact('department', 'classes'));
    }

    public function showStudents($departmentId, $classId)
    {
        $class = SchoolClass::findOrFail($classId);
        $students = $class->students;
        return view('scores.students', compact('class', 'students'));
    }

    public function showScores($studentId)
    {
        $student = User::findOrFail($studentId);
        $exams = $student->exams;
        return view('scores.scores', compact('student', 'exams'));
    }

    public function showStudentAnswers($userExamId)
    {
        $userExam = UserExam::findOrFail($userExamId);
        $answers = $userExam->userAnswers;
        return view('scores.answers', compact('userExam', 'answers'));
    }
}
