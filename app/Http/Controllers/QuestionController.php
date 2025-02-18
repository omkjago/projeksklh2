<?php

// app/Http/Controllers/QuestionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Answer;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $questions = $exam->questions;

        return view('elearning.exams.soal.questions.index', compact('exam', 'questions'));
    }

    public function show(Exam $exam, Question $question)
    {
        return view('elearning.exams.soal.questions.show', compact('exam', 'question'));
    }

    public function answer(Request $request, Exam $exam, Question $question)
    {
        $request->validate([
            'answer_id' => 'required|exists:answers,id'
        ]);

        $userAnswer = UserAnswer::updateOrCreate(
            [
                'user_exam_id' => $request->user_exam_id,
                'question_id' => $question->id,
            ],
            [
                'answer_id' => $request->answer_id,
            ]
        );

        return redirect()->route('elearning.exams.soal.questions.index', $exam->id)->with('success', 'Jawaban berhasil disimpan.');
    }
}
