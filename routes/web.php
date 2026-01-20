<?php

use App\Filament\Resources\Boards\BoardResource;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(BoardResource::getUrl('index'));
});
