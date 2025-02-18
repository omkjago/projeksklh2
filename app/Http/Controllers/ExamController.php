<?php

// app/Http/Controllers/ExamController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\UserExam;

class ExamController extends Controller
{
    // app/Http/Controllers/ExamController.php

public function index()
{
    $exams = Exam::all();
    $canCreate = auth()->user()->hasRole('admin') || auth()->user()->hasRole('guru');
    $canUpdate = auth()->user()->hasRole('admin') || auth()->user()->hasRole('guru');
    
    return view('elearning.exams.index', compact('exams', 'canCreate', 'canUpdate'));
}


    public function create()
    {
        return view('elearning.exams.soal.create');
    }

    public function store(Request $request)
{
    $data = $request->all();
    $data['created_by'] = auth()->id(); // Assuming you use Laravel's built-in authentication
    $exam = Exam::create($data);
    return redirect()->route('exams.index');
}

    public function show($id)
    {
        $exam = Exam::findOrFail($id);
        return view('elearning.exams.soal.show', compact('exam'));
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        return view('elearning.exams.soal.edit', compact('exam'));
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        $exam->update($request->all());
        return redirect()->route('exams.index');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();
        return redirect()->route('exams.index');
    }

    public function createQuestion($examId)
    {
        $exam = Exam::findOrFail($examId);
        return view('questions.create', compact('exam'));
    }

    public function storeQuestion(Request $request, $examId)
    {
        $exam = Exam::findOrFail($examId);
        $question = $exam->questions()->create($request->all());

        foreach ($request->answers as $answer) {
            $question->answers()->create($answer);
        }

        return redirect()->route('exams.show', $exam->id);
    }

    public function editQuestion($examId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        return view('questions.edit', compact('question'));
    }

    public function updateQuestion(Request $request, $examId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->update($request->all());

        foreach ($request->answers as $answer) {
            $question->answers()->updateOrCreate(['id' => $answer['id']], $answer);
        }

        return redirect()->route('exams.show', $examId);
    }

    public function destroyQuestion($examId, $questionId)
    {
        $question = Question::findOrFail($questionId);
        $question->delete();
        return redirect()->route('exams.show', $examId);
    }

    public function toggleStatus($examId)
    {
        $exam = Exam::findOrFail($examId);
        $exam->status = $exam->status == 'active' ? 'inactive' : 'active';
        $exam->save();
        return redirect()->route('exams.index');
    }
}
