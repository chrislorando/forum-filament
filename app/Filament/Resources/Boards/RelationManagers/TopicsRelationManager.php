<?php

namespace App\Filament\Resources\Boards\RelationManagers;

use App\Enums\PostType;
use App\Enums\TopicStatus;
use App\Filament\Resources\Topics\TopicResource;
use App\Models\Topic;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TopicsRelationManager extends RelationManager
{
    protected static string $relationship = 'topics';

    protected static ?string $relatedResource = TopicResource::class;

    // public function form(Schema $schema): Schema
    // {
    //     return $schema
    //         ->model(Topic::class)
    //         ->schema([
    //             TextInput::make('title')
    //                 ->label('Notes')
    //                 ->columnSpanFull()
    //                 ->nullable(),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['slug'] = \Str::slug($data['title']);
                        if (isset($data['status']) && $data['status'] == TopicStatus::Pinned) {
                            $data['is_pinned'] = true;
                        }

                        return $data;
                    })
                    ->after(function (Model $record, array $data): void {
                        $record->posts()->create([
                            'type' => PostType::FirstPost,
                            'content' => $data['description'],
                            'user_id' => auth()->id(),
                        ]);
                    })
                    ->modal()
                    ->slideOver()
                    ->modalWidth(Width::Large)
                    ->createAnother(false)
                    ->visible(fn ($record): bool => auth()->check() ?? false),
            ])
            ->extraAttributes([
                'class' => 'mt-6',
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
