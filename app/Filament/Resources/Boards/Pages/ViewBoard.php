<?php

namespace App\Filament\Resources\Boards\Pages;

use App\Filament\Resources\Boards\BoardResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBoard extends ViewRecord
{
    protected static string $resource = BoardResource::class;

    public function getTitle(): string
    {
        return $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            // EditAction::make()->visible(fn() => auth()->user()?->canManage()),
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

    // public function getSubheading(): ?string
    // {
    //     return $this->record->description;
    // }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();
        $breadcrumbs = [
            $resource::getUrl('index') => $resource::getBreadcrumb(),
        ];

        // Ambil semua parent dari board yang sedang dibuka
        $parents = collect();
        $current = $this->record->parent;

        while ($current) {
            $parents->push($current);
            $current = $current->parent;
        }

        // Balik urutannya (dari paling atas ke bawah) dan masukkan ke breadcrumbs
        foreach ($parents->reverse() as $parent) {
            $breadcrumbs[$resource::getUrl('view', ['record' => $parent])] = $parent->name;
        }

        // Terakhir, tambahkan record yang sekarang sedang dibuka
        $breadcrumbs[] = $this->record->name;

        return $breadcrumbs;
    }
}
