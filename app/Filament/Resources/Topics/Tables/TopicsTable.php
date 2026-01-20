<?php

namespace App\Filament\Resources\Topics\Tables;

use App\Enums\TopicStatus;
use App\Filament\Resources\Topics\TopicResource;
use App\Models\Topic;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query, Table $table) {
                return $query
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('title', 'asc');
            })
            ->recordUrl(
                fn (Topic $record): string => TopicResource::getUrl('view', ['record' => $record])
            )
            // ->groupingSettingsHidden()
            ->columns([
                IconColumn::make('status'),
                TextColumn::make('title')
                    ->label('Subject'),
                TextColumn::make('user.name')
                    ->label('Started by'),
                TextColumn::make('replies_count')
                    ->counts('replies')
                    ->label('Replies')
                    ->alignEnd(),
                TextColumn::make('view_count')
                    ->label('Views')
                    ->alignEnd(),
                TextColumn::make('latestPost.created_at')
                    ->description(function ($record) {
                        return 'By '.$record->latestPost->user->name;
                    })
                    ->dateTime('M d, Y H:i:s')
                    ->size('xs')
                    ->color('gray')
                    ->default(''),

            ])
            ->filters([
                TrashedFilter::make()
                    ->visible(fn () => auth()->user()?->canManage()),
            ])

            ->recordActions([
                // ViewAction::make(),
                EditAction::make()
                    ->modal()
                    ->slideOver()
                    ->modalWidth(Width::Large)
                    ->mutateDataUsing(function (array $data): array {

                        $data['is_pinned'] = $data['status'] == TopicStatus::Pinned ? true : false;
                        $data['is_locked'] = $data['status'] == TopicStatus::Locked ? true : false;

                        return $data;
                    })
                    ->visible(fn ($record): bool => auth()->check() && (auth()->id() == $record->user_id || auth()->user()?->canManage()) ?? false),
                DeleteAction::make()
                    ->visible(fn ($record): bool => auth()->check() && (auth()->id() == $record->user_id || auth()->user()?->canManage()) ?? false),
                RestoreAction::make(),
                // ->visible(fn ($record): bool => auth()->check() && (auth()->id() == $record->user_id || auth()->user()?->canManage()) ?? false),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn () => auth()->user()?->canManage()),
                    ForceDeleteBulkAction::make()->visible(fn () => auth()->user()?->canManage()),
                    RestoreBulkAction::make()->visible(fn () => auth()->user()?->canManage()),
                ]),
            ])
            ->paginated([50])
            ->defaultPaginationPageOption(50);
    }
}
