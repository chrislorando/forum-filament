<?php

namespace App\Filament\Resources\Boards\Pages;

use App\Filament\Resources\Boards\BoardResource;
use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Width;

class ListBoards extends ListRecords
{
    protected static string $resource = BoardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Categories')
                ->outlined()
                ->url(fn()=>CategoryResource::getUrl())
                ->visible(fn() => auth()->user()?->canManage()),
            CreateAction::make()
                ->modal()
                ->slideOver()
                ->modalWidth(Width::Large)
                ->createAnother(false)
                ->mutateDataUsing(function (array $data): array {
                    $data['slug'] = \Str::slug($data['name']);
            
                    return $data;
                })
                ->visible(fn() => auth()->user()?->canManage()),
        ];
    }
}
