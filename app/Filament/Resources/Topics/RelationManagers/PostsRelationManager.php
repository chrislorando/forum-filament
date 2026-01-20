<?php

namespace App\Filament\Resources\Topics\RelationManagers;

use App\Enums\PostType;
use App\Filament\Resources\Topics\TopicResource;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    // protected static ?string $recordTitleAttribute = 'content';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('topic')
                    ->label('Topic')
                    ->content(function ($record) {
                        return 'Re: '.$this->getOwnerRecord()->title;
                    }),
                MarkdownEditor::make('content')
                    ->label('Message')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('content')
                    ->markdown()
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Post $record): bool => $record->trashed()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]))
            ->extraRecordLinkAttributes(fn (Model $record): array => [
                'id' => "post-{$record->id}",
            ])
            ->header(view('filament.tables.header', [
                'table' => $table,
            ]))
            ->recordTitleAttribute('title')
            ->heading('')
            ->paginated([20])
            ->defaultPaginationPageOption(20)
            ->extremePaginationLinks()
            ->columns([
                Grid::make([
                    'default' => 1,
                    'md' => 12,
                ])
                    ->schema([
                        Grid::make([
                            'default' => 3,
                            'md' => 1,
                        ])
                            ->schema([
                                ImageColumn::make('user.name')
                                    ->label('')
                                    ->square()
                                    ->size(60)
                                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->user?->username ?? 'User'))
                                    ->extraImgAttributes([
                                        'class' => 'rounded-lg',
                                    ]),
                                Stack::make([
                                    TextColumn::make('user.username')
                                        ->weight('bold')
                                        ->size('sm')
                                        ->color('primary'),
                                    TextColumn::make('user.rank')
                                        ->badge()
                                        ->size('xs'),
                                ])->space(1),

                                Stack::make([
                                    TextColumn::make('type')
                                        ->badge()
                                        ->size('xs')
                                        ->color(fn ($record): string => $record->type === PostType::FirstPost ? 'success' : 'gray')
                                        ->icon(fn ($record): ?string => $record->type === PostType::FirstPost ? 'heroicon-o-star' : null),
                                    TextColumn::make('user.posts_count')
                                        ->prefix('Posts: ')
                                        ->size('xs')
                                        ->color('gray'),
                                    TextColumn::make('user.topics_count')
                                        ->prefix('Topics: ')
                                        ->size('xs')
                                        ->color('gray'),
                                ])->space(1),
                            ])
                            ->columnSpan([
                                'default' => 12,
                                'md' => 2,
                            ]),

                        Stack::make([
                            TextColumn::make('topic.title')
                                ->prefix(fn ($record) => $record->type == PostType::Reply ? 'Re:' : '')
                                ->weight('bold')
                                ->size('sm')
                                ->color('primary'),
                            TextColumn::make('created_at')
                                ->dateTime('M d, Y, g:i A')
                                ->size('xs')
                                ->color('gray')
                                ->icon('heroicon-o-clock'),
                            TextColumn::make('updated_at')
                                ->prefix('Updated: ')
                                ->dateTime('M d, Y, g:i A')
                                ->size('xs')
                                ->color('gray')
                                ->visible(fn ($record): bool => $record?->updated_at && $record->updated_at->isAfter($record->created_at)),
                            TextColumn::make('content')
                                ->markdown()
                                ->wrap()
                                ->extraAttributes([
                                    'class' => 'flex-grow mb-10 fi-prose',
                                ]),
                            TextColumn::make('user.signature')
                                ->visible(fn ($record) => $record->user->signature ?? false)
                                ->size('xs')
                                ->color('gray')
                                ->markdown()
                                ->wrap()
                                ->extraAttributes([
                                    'class' => 'border-t border-gray-200 pt-2 mt-auto '.
                                            // Wrapper paragraf jadi flex biar sejajar
                                            '[&_p]:flex [&_p]:flex-wrap [&_p]:gap-2 '.
                                            // Paksa ukuran gambar di sini
                                            '[&_img]:h-10 [&_img]:w-auto [&_img]:object-contain [&_img]:rounded-sm',
                                ]),
                        ])
                            ->space(2)
                            ->columnSpan([
                                'default' => 12,
                                'md' => 10,
                            ]),

                    ]),
            ])

            ->filters([
                TrashedFilter::make()->visible(fn (): bool => auth()->check() && auth()->user()?->canManage() ?? false),
            ])
            ->headerActions([
                CreateAction::make()
                    ->modal()
                    ->slideOver()
                    ->modalWidth(Width::ExtraLarge)
                    ->createAnother(false)
                    ->mountUsing(function ($form, array $arguments) {
                        $form->fill([
                            'content' => $arguments['content'] ?? null,
                        ]);
                    })
                    ->mutateDataUsing(function (array $data): array {
                        $data['topic_id'] = $this->getOwnerRecord()->id;
                        $data['user_id'] = auth()->id();

                        return $data;
                    })
                    ->after(function (Model $record, array $data, $livewire): void {
                        $userIds = $record->topic->posts()
                            ->pluck('user_id')
                            ->push($record->topic->user_id)
                            ->unique()
                            ->filter(fn ($id) => $id !== auth()->id());

                        if ($userIds->isEmpty()) {
                            return;
                        }

                        $recipients = User::find($userIds);

                        Notification::make()
                            ->title('A new reply in Topic: '.$record->topic->title)
                            ->body(str('**'.auth()->user()->name."** says:\n".
                                '> '.str($record->content)->stripTags()->limit(50))->markdown()
                            )
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->iconColor('success')
                            ->actions([
                                Action::make('view')
                                    ->button()
                                    ->url(fn () => TopicResource::getUrl('view', ['record' => $record->topic->slug])),
                            ])
                            ->sendToDatabase($recipients);
                        $record->refresh();
                        // $livewire->dispatch('refreshPostsRelationManager');
                        // $livewire->dispatch('refreshRelationManager');
                        // $livewire->dispatch('$refresh');
                    }),
                // AssociateAction::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    // ViewAction::make(),
                    // DissociateAction::make(),
                    EditAction::make()
                        ->modal()
                        ->slideOver()
                        ->modalWidth(Width::ExtraLarge)
                        ->visible(fn ($record): bool => auth()->check() && (auth()->id() == $record->user_id || auth()->user()?->canManage()) ?? false),
                    DeleteAction::make()
                        ->visible(fn ($record): bool => auth()->check() && (auth()->id() == $record->user_id || auth()->user()?->canManage()) ?? false),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                    Action::make('Quote')
                        ->icon(Heroicon::ChatBubbleLeft)
                        ->action(function (Model $record, $livewire) {
                            $username = $record->user->username;

                            // 1. Bersihkan tag HTML
                            $cleanContent = strip_tags($record->content);

                            // 2. Tambahkan "> " di setiap awal baris baru agar tidak "bocor" keluar quote
                            $quotedBody = str_replace("\n", "\n> ", trim($cleanContent));

                            // 3. Gabungkan semuanya
                            $formattedQuote = "> **{$username} said:**\n> {$quotedBody}\n\n";

                            $livewire->mountTableAction('create', $record, [
                                'content' => $formattedQuote,
                            ]);
                        })
                        ->after(function (Model $record, array $data, $livewire): void {
                            $livewire->dispatch('$refresh');
                        }),

                ]),
            ], position: RecordActionsPosition::AfterContent)
            // ->toolbarActions([
            //     BulkActionGroup::make([
            //         DissociateBulkAction::make(),
            //         DeleteBulkAction::make(),
            //         ForceDeleteBulkAction::make(),
            //         RestoreBulkAction::make(),
            //     ]),
            // ])

            ->deferLoading()
            ->striped();
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
