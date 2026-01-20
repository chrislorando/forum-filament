<?php

namespace App\Filament\Resources\Topics\Pages;

use App\Enums\TopicStatus;
use App\Filament\Resources\Boards\BoardResource;
use App\Filament\Resources\Topics\TopicResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Width;

class ViewTopic extends ViewRecord
{
    protected static string $resource = TopicResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        $sessionId = 'viewed_topic_' . $this->getRecord()->id;

        if (!session()->has($sessionId)) {
            $this->getRecord()->increment('view_count');
            session()->put($sessionId, true);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make()
            //     ->modal()
            //     ->slideOver()
            //     ->modalWidth(Width::Large)
            //     ->visible(fn ($record): bool => auth()->check() && (auth()->id() == $record->user_id || auth()->user()?->canManage()) ?? false),

            // DeleteAction::make()->visible(fn ($record): bool => auth()->check() && (auth()->id() == $record->user_id || auth()->user()?->canManage()) ?? false),
            
        ];
    }

    // public function getHeader(): ?\Illuminate\Contracts\View\View
    // {
    //     // Remove space for hidden infolist
    //     \Filament\Support\Facades\FilamentView::registerRenderHook(
    //         'panels::content.start',
    //         fn (): string => '<style>
    //             .fi-in.fi-resource-infolist-container { display: none !important; }
    //             .fi-sc-has-gap { gap: 0 !important; } 
    //             .fi-sc-has-gap > * + * { margin-top: 0 !important; }
    //         </style>',
    //     );

    //     return null;
    // }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();
        $boardResource = BoardResource::class;

        // 1. Mulai dari Index Forum
        $breadcrumbs = [
            $boardResource::getUrl('index') => $boardResource::getBreadcrumb(),
        ];

        // 2. Ambil Board tempat Topic ini bernaung
        $currentBoard = $this->record->board; 

        // 3. Panjat semua Parent Board ke atas
        $boardParents = collect();
        while ($currentBoard) {
            $boardParents->push($currentBoard);
            $currentBoard = $currentBoard->parent;
        }

        // 4. Masukkan Board parents ke breadcrumb (reverse agar urutan benar)
        foreach ($boardParents->reverse() as $board) {
            $breadcrumbs[$boardResource::getUrl('view', ['record' => $board])] = $board->name;
        }

        // 5. Terakhir, tambahkan Judul Topic sebagai ujung breadcrumb
        $breadcrumbs[] = $this->record->title;

        return $breadcrumbs;
    }
}
