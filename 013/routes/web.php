<?php

use App\Http\Controllers\FormResponseController;
use App\Livewire\FormCreate;
use App\Livewire\FormFieldsEditor;
use App\Livewire\FormPublic;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/form/create', FormCreate::class)->name('form.create');
    Route::get('/form/{form}/edit', FormFieldsEditor::class)->name('form.edit');
    Route::get('/form/{form}/respostas', [FormResponseController::class, 'index'])
    ->name('form.responses');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

Route::get('/f/{slug}', FormPublic::class)->name('form.public');


require __DIR__.'/auth.php';
