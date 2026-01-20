<?php

use App\Filament\Resources\Boards\BoardResource;
use App\Filament\Resources\Categories\CategoryResource;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(BoardResource::getUrl('index'));
});