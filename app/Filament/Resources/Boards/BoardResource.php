<?php

namespace App\Filament\Resources\Boards;

use App\Filament\Resources\Boards\Pages\CreateBoard;
use App\Filament\Resources\Boards\Pages\EditBoard;
use App\Filament\Resources\Boards\Pages\ListBoards;
use App\Filament\Resources\Boards\Pages\ViewBoard;
use App\Filament\Resources\Boards\RelationManagers\ChildrenRelationManager;
use App\Filament\Resources\Boards\RelationManagers\TopicsRelationManager;
use App\Filament\Resources\Boards\Schemas\BoardForm;
use App\Filament\Resources\Boards\Schemas\BoardInfolist;
use App\Filament\Resources\Boards\Tables\BoardsTable;
use App\Models\Board;
use BackedEnum;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoardResource extends Resource
{
    protected static ?string $model = Board::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $recordRouteKeyName = 'slug';

    public static function form(Schema $schema): Schema
    {
        return BoardForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BoardInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BoardsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Forum', [
                ChildrenRelationManager::class,
                TopicsRelationManager::class
            ]),
            
        ];
    }

    public function getComponentRelationManagerLayout(): string
    {
        return 'list'; // Mengubah layout dari 'tabs' menjadi 'list'
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBoards::route('/'),
            'create' => CreateBoard::route('/create'),
            'view' => ViewBoard::route('/{record}'),
            'edit' => EditBoard::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
