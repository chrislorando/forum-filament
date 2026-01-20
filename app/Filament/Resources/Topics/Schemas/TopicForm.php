<?php

namespace App\Filament\Resources\Topics\Schemas;

use App\Enums\TopicStatus;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;

class TopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                MarkdownEditor::make('description')
                    ->label('First post')
                    ->columnSpanFull()
                    ->hiddenOn('edit'),

                ToggleButtons::make('status')
                    ->inline()
                    ->options(TopicStatus::class)
                    ->required()
                    ->columnSpanFull()
                    ->visible(fn ($record): bool => auth()->check() && auth()->user()?->canManage() ?? false),

            ]);
    }
}
