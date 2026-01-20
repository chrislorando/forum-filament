<?php

namespace App\Filament\Resources\Boards\Tables;

use App\Filament\Resources\Boards\BoardResource;
use App\Models\Board;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BoardsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query, Table $table) {
                $livewire = $table->getLivewire();

                return $query
                    ->join('categories', 'boards.category_id', '=', 'categories.id')
                    ->select('boards.*')
                    ->withCount(['topics', 'posts', 'children'])
                    ->orderBy('categories.sort_order', 'asc')
                    ->when(
                        ! ($livewire instanceof \Filament\Resources\RelationManagers\RelationManager),
                        fn ($q) => $q->whereNull('parent_id')
                    )
                    ->withoutGlobalScopes();
            })
            ->recordUrl(
                fn (Board $record): string => BoardResource::getUrl('view', ['record' => $record])
            )
            ->defaultGroup('category.name')
            ->groupingSettingsHidden()
            ->groups([
                Group::make('category.name')
                    ->titlePrefixedWithLabel(false)
                    ->getDescriptionFromRecordUsing(fn (Board $record): string => $record->category?->description ?? '')
                    ->getTitleFromRecordUsing(fn (Board $record): string => strtoupper($record->category->name)),
            ])
            ->defaultSort('sort_order')
            ->paginated(['all'])
            ->defaultPaginationPageOption(100)
            ->columns([
                Grid::make([
                    'default' => 1,
                    'lg' => 12,
                ])
                    ->schema([
                        // 1. Board Info (Ambil 6 dari 12 bagian)
                        Stack::make([
                            TextColumn::make('name')
                                ->weight('bold'),
                            TextColumn::make('description')
                                ->size('sm')
                                ->color('gray')
                                ->wrap(),
                            TextColumn::make('children.name')
                                ->label('Sub-Boards')
                                ->size('xs')
                                ->color('info')
                                ->badge(),
                        ])->columnSpan(6),

                        // 2. Statistik (Ambil 2 dari 12 bagian)
                        Stack::make([
                            TextColumn::make('posts_count')
                                ->state(fn ($record) => 'Posts: '.($record->posts_count ?? 0))
                                ->size('xs')
                                ->color('gray'),
                            TextColumn::make('topics_count')
                                ->state(fn ($record) => 'Topics: '.($record->topics_count ?? 0))
                                ->size('xs')
                                ->color('gray'),
                        ])
                            ->columnSpan(2)
                            ->alignment(Alignment::Start),

                        // 3. Last Post (Ambil 4 dari 12 bagian)
                        Stack::make([
                            TextColumn::make('latestPost.user.name')
                                ->prefix('Last post by ')
                                ->weight('bold')
                                ->size('xs')
                                ->color('primary')
                                ->default('No posts yet'),

                            TextColumn::make('latestPost.topic.title')
                                ->prefix('in ')
                                ->size('xs')
                                ->limit(35)
                                ->default(''),

                            TextColumn::make('latestPost.created_at')
                                ->dateTime('M d, Y')
                                ->prefix('on ')
                                ->size('xs')
                                ->color('gray')
                                ->default(''),
                        ])
                            ->columnSpan(4)
                            ->visible(fn ($record) => $record->id !== null),
                    ]),
            ])
            ->filters([
                TrashedFilter::make()->visible(fn () => auth()->user()?->canManage()),
            ])
            ->recordActions([
                // ViewAction::make()->visible(fn() => auth()->user()?->canManage()),
                EditAction::make()
                    ->modal()
                    ->slideOver()
                    ->modalWidth(Width::Large)
                    ->visible(fn () => auth()->user()?->canManage()),
                DeleteAction::make()->visible(fn () => auth()->user()?->canManage()),
            ])
            // ->toolbarActions([
            //     BulkActionGroup::make([
            //         DeleteBulkAction::make()->visible(fn() => auth()->user()?->canManage()),
            //         ForceDeleteBulkAction::make()->visible(fn() => auth()->user()?->canManage()),
            //         RestoreBulkAction::make()->visible(fn() => auth()->user()?->canManage()),
            //     ]),
            // ])
            ->deferLoading()
            ->striped();
    }
}
