<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', function () {
    $school = \App\Models\School::first();
    $posts  = \App\Models\BlogPost::where('school_id', $school->id)
        ->where('status', 'published')
        ->get(['slug', 'updated_at']);

    $content = view('sitemap', compact('posts'))->render();

    return response($content, 200)->header('Content-Type', 'application/xml');
});

// Public website
Route::get('/', [\App\Http\Controllers\PublicController::class, 'home'])->name('public.home');
Route::get('/blog', [\App\Http\Controllers\PublicController::class, 'blog'])->name('public.blog');
Route::get('/blog/{slug}', [\App\Http\Controllers\PublicController::class, 'blogPost'])->name('public.blog.post');
Route::get('/events', [\App\Http\Controllers\PublicController::class, 'events'])->name('public.events');
Route::get('/gallery', [\App\Http\Controllers\PublicController::class, 'gallery'])->name('public.gallery');

// Login available regardless of session state
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Authenticated
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.update');

    // Admin
    Route::middleware(['must.change.password', 'role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
            Route::resource('academic-years', \App\Http\Controllers\Admin\AcademicYearController::class);
            Route::resource('classrooms', \App\Http\Controllers\Admin\ClassroomController::class);
            Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);
            Route::resource('teachers', \App\Http\Controllers\Admin\TeacherController::class);
            Route::patch('teachers/{teacher}/toggle-active', [\App\Http\Controllers\Admin\TeacherController::class, 'toggleActive'])->name('teachers.toggle-active');
            Route::resource('students', \App\Http\Controllers\Admin\StudentController::class);
            Route::get('teacher-assignments', [\App\Http\Controllers\Admin\TeacherAssignmentController::class, 'index'])->name('teacher-assignments.index');
            Route::get('teacher-assignments/create', [\App\Http\Controllers\Admin\TeacherAssignmentController::class, 'create'])->name('teacher-assignments.create');
            Route::post('teacher-assignments', [\App\Http\Controllers\Admin\TeacherAssignmentController::class, 'store'])->name('teacher-assignments.store');
            Route::delete('teacher-assignments/{teacherAssignment}', [\App\Http\Controllers\Admin\TeacherAssignmentController::class, 'destroy'])->name('teacher-assignments.destroy');
            Route::get('sequences', [\App\Http\Controllers\Admin\SequenceController::class, 'index'])->name('sequences.index');
            Route::get('sequences/create', [\App\Http\Controllers\Admin\SequenceController::class, 'create'])->name('sequences.create');
            Route::post('sequences', [\App\Http\Controllers\Admin\SequenceController::class, 'store'])->name('sequences.store');
            Route::get('sequences/{term}/edit', [\App\Http\Controllers\Admin\SequenceController::class, 'edit'])->name('sequences.edit');
            Route::put('sequences/{term}', [\App\Http\Controllers\Admin\SequenceController::class, 'update'])->name('sequences.update');
            Route::patch('sequences/{sequence}/toggle-lock', [\App\Http\Controllers\Admin\SequenceController::class, 'toggleLock'])->name('sequences.toggle-lock');
            Route::get('report-cards', [\App\Http\Controllers\Admin\ReportCardController::class, 'index'])->name('report-cards.index');
            Route::post('report-cards/generate', [\App\Http\Controllers\Admin\ReportCardController::class, 'generate'])->name('report-cards.generate');
            Route::get('report-cards/show', [\App\Http\Controllers\Admin\ReportCardController::class, 'show'])->name('report-cards.show');
            Route::get('report-cards/pdf', [\App\Http\Controllers\Admin\ReportCardController::class, 'pdf'])->name('report-cards.pdf');
            Route::get('promotions', [\App\Http\Controllers\Admin\PromotionController::class, 'index'])->name('promotions.index');
            Route::post('promotions/compute', [\App\Http\Controllers\Admin\PromotionController::class, 'compute'])->name('promotions.compute');
            Route::patch('promotions/{promotion}/decision', [\App\Http\Controllers\Admin\PromotionController::class, 'updateDecision'])->name('promotions.update-decision');
            Route::post('promotions/confirm', [\App\Http\Controllers\Admin\PromotionController::class, 'confirm'])->name('promotions.confirm');



            // CMS
           Route::prefix('cms')->name('cms.')->group(function () {
           Route::resource('pages', \App\Http\Controllers\Admin\CMS\PageController::class);
           Route::resource('blog', \App\Http\Controllers\Admin\CMS\BlogController::class);
           Route::resource('events', \App\Http\Controllers\Admin\CMS\EventController::class);
           Route::get('gallery', [\App\Http\Controllers\Admin\CMS\GalleryController::class, 'index'])->name('gallery.index');
           Route::post('gallery/photos', [\App\Http\Controllers\Admin\CMS\GalleryController::class, 'storePhoto'])->name('gallery.photos.store');
           Route::delete('gallery/photos/{photo}', [\App\Http\Controllers\Admin\CMS\GalleryController::class, 'destroyPhoto'])->name('gallery.photos.destroy');
           Route::post('gallery/videos', [\App\Http\Controllers\Admin\CMS\GalleryController::class, 'storeVideo'])->name('gallery.videos.store');
           Route::delete('gallery/videos/{video}', [\App\Http\Controllers\Admin\CMS\GalleryController::class, 'destroyVideo'])->name('gallery.videos.destroy');
          });
        });

    // Teacher
    Route::middleware(['must.change.password', 'role:teacher'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
            Route::get('/marks', [\App\Http\Controllers\Teacher\MarkController::class, 'index'])->name('marks.index');
            Route::get('/marks/enter', [\App\Http\Controllers\Teacher\MarkController::class, 'enter'])->name('marks.enter');
            Route::post('/marks/save', [\App\Http\Controllers\Teacher\MarkController::class, 'save'])->name('marks.save');
            Route::get('/classes', [\App\Http\Controllers\Teacher\ClassController::class, 'index'])->name('classes.index');
            Route::get('/classes/{classroom}', [\App\Http\Controllers\Teacher\ClassController::class, 'show'])->name('classes.show');
        });
});