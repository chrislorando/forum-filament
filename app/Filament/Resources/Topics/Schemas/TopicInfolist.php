<?php

namespace App\Filament\Resources\Topics\Schemas;

use App\Models\Topic;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class TopicInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([])
                ->columnSpanFull()
                ->extraAttributes(['class' => 'hidden p-0'])
                // TextEntry::make('board.name')
                //     ->label('Board'),
                // TextEntry::make('user.name')
                //     ->label('User'),
                // TextEntry::make('title'),
                // TextEntry::make('slug'),
                // TextEntry::make('status')
                //     ->badge(),
                // IconEntry::make('is_pinned')
                //     ->boolean(),
                // IconEntry::make('is_locked')
                //     ->boolean(),
                // TextEntry::make('view_count')
                //     ->numeric(),
                // TextEntry::make('created_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('updated_at')
                //     ->dateTime()
                //     ->placeholder('-'),
                // TextEntry::make('deleted_at')
                //     ->dateTime()
                //     ->visible(fn (Topic $record): bool => $record->trashed()),
                // TextEntry::make('description')
                //     ->placeholder('-')
                //     ->columnSpanFull(),
            ]);
    }
}
