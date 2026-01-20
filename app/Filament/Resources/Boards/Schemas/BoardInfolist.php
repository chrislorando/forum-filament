<?php

namespace App\Filament\Resources\Boards\Schemas;

use App\Models\Board;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class BoardInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([])
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'hidden p-0']),
                // TextEntry::make('category.name')
                //     ->label('Category'),
                // TextEntry::make('parent.name')
                //     ->label('Parent')
                //     ->placeholder('-'),
                // TextEntry::make('name'),
                // TextEntry::make('description')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
                // TextEntry::make('sort_order')
                //     ->numeric(),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('deleted_at')
                //     ->dateTime()
                //     ->visible(fn (Board $record): bool => $record->trashed()),
            ]);
    }
}
