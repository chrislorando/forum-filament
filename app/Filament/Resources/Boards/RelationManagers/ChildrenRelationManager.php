<?php

namespace App\Filament\Resources\Boards\RelationManagers;

use App\Filament\Resources\Boards\BoardResource;
use App\Models\Board;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ChildrenRelationManager extends RelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $relatedResource = BoardResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->recordUrl(
                fn (Board $record): string => BoardResource::getUrl('view', ['record' => $record])
            )
            ->headerActions([
                CreateAction::make(),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->children()->count() > 0;
    }
}
