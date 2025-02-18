<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\CheckRoleForRegister;
use App\Actions\Fortify\CreateNewUser;
use App\Imports\ImportsUser;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ElearningController;



Route::get('/', function () {
    return view('splash');
});


Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

//auth role user
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard-admin', function () {
        return view('dashboard-admin');
    })->name('dashboard-admin');
});
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard-guru', function () {
        return view('dashboard-guru');
    })->name('dashboard-guru');
});
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard-siswa', function () {
        return view('dashboard-siswa');
    })->name('dashboard-siswa');
});
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard-orangtua', function () {
        return view('dashboard-orangtua');
    })->name('dashboard-orangtua');
});

//fungsi register guard admin or guru
Route::middleware(['auth', CheckRoleForRegister::class])->group(function () {
    Route::get('/register', function () {
        session()->put('previous_url', url()->previous());
        return view('auth.register');
    })->name('register');

    Route::post('/register', function (Request $request) {
        $method = $request->input('registration_method');

        if ($method === 'excel') {
            // Jika metode adalah excel, panggil metode createFromExcel
            $rowCount = (new CreateNewUser())->createFromExcel($request->file('excel_file'));

            // Redirect dengan pesan sukses
            return redirect(session()->pull('previous_url'))->with('success', $rowCount . ' users registered successfully! Please login.');
        } else {
            // Jika metode bukan excel, gunakan metode create yang lama
            $user = (new CreateNewUser())->create($request->only(['name', 'email', 'password', 'password_confirmation', 'role']));

            // Redirect dengan pesan sukses
            return redirect(session()->pull('previous_url'))->with('success', 'User registered successfully! Please login.');
        }
    });
});

//update foto profile
Route::post('/profile/photo', [ProfilePhotoController::class, 'store'])->name('profile.photo.store');

//presensi
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/presensi/create', [PresensiController::class, 'create'])->name('presensi.create');
    Route::post('/presensi/store', [PresensiController::class, 'store'])->name('presensi.store');
    Route::get('/riwayat-absensi', [PresensiController::class, 'index'])->name('riwayat.absensi');
    Route::get('/presensi/guru-admin', [PresensiController::class, 'guruadmin'])->name('presensi.presensi_adminguru');

});

use App\Http\Controllers\ExamController;
use App\Http\Controllers\ScoreController;

Route::middleware(['auth'])->group(function () {
    Route::resource('exams', ExamController::class);
    Route::get('exams/{exam}/questions/create', [ExamController::class, 'createQuestion'])->name('questions.create');
    Route::post('exams/{exam}/questions', [ExamController::class, 'storeQuestion'])->name('questions.store');
    Route::get('exams/{exam}/questions/{question}/edit', [ExamController::class, 'editQuestion'])->name('questions.edit');
    Route::put('exams/{exam}/questions/{question}', [ExamController::class, 'updateQuestion'])->name('questions.update');
    Route::delete('exams/{exam}/questions/{question}', [ExamController::class, 'destroyQuestion'])->name('questions.destroy');
    Route::post('exams/{exam}/toggle-status', [ExamController::class, 'toggleStatus'])->name('exams.toggleStatus');
    Route::get('exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('exams/{exam}', [ExamController::class, 'show'])->name('exams.show');
    Route::get('scores', [ScoreController::class, 'index'])->name('scores.index');
    Route::get('scores/{department}/classes', [ScoreController::class, 'showClasses'])->name('scores.classes');
    Route::get('scores/{department}/classes/{class}/students', [ScoreController::class, 'showStudents'])->name('scores.students');
    Route::get('scores/{student}', [ScoreController::class, 'showScores'])->name('scores.scores');
    Route::get('my-scores', [ScoreController::class, 'showScores'])->name('my.scores');
    Route::get('my-scores/{userExam}/answers', [ScoreController::class, 'showStudentAnswers'])->name('my.scores.answers');
});
// routes/web.php

use App\Http\Controllers\MaterialController;


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('elearning/materials', MaterialController::class)->except(['edit', 'update', 'show']);
    Route::resource('elearning/assignments', AssignmentController::class);
    Route::resource('elearning/exams', ExamController::class)->except(['edit', 'update', 'show']);
});
// routes/web.php


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('elearning/modules', ModuleController::class)->except(['edit', 'update', 'show']);
});




Route::middleware(['auth'])->group(function () {
    Route::get('/elearning', [ElearningController::class, 'index'])->name('elearning.index');
});

// modul ajar
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\AssignmentController;

Route::middleware(['auth'])->group(function () {
    Route::resource('materials', ModuleController::class);
    Route::resource('assignments', AssignmentController::class);
});
