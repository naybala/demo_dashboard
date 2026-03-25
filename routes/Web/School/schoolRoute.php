<?php

use BasicDashboard\Web\AcademicSessions\Controllers\AcademicSessionController;
use BasicDashboard\Web\Classes\Controllers\ClassController;
use BasicDashboard\Web\Exams\Controllers\ExamController;
use BasicDashboard\Web\Grades\Controllers\GradeController;
use BasicDashboard\Web\Marks\Controllers\MarkController;
use BasicDashboard\Web\Students\Controllers\StudentController;
use BasicDashboard\Web\Announcements\Controllers\AnnouncementController;
use BasicDashboard\Web\Events\Controllers\EventController;
use BasicDashboard\Web\Subjects\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::resource('grades', GradeController::class);
Route::resource('students', StudentController::class);
Route::resource('classes', ClassController::class);
Route::resource('academic-sessions', AcademicSessionController::class);
Route::resource('exams', ExamController::class);
Route::resource('subjects', SubjectController::class);
Route::resource('marks', MarkController::class);
Route::resource('announcements', AnnouncementController::class);
Route::resource('events', EventController::class);
