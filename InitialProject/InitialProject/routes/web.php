<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExpertiseController;

// Route for showcasing expertise
Route::get('/expertise', [ExpertiseController::class, 'index'])->name('expertise.index');