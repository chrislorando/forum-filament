<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class EditProfile extends BaseEditProfile
{
    // protected string $view = 'filament.pages.auth.edit-profile';

    // protected static bool $isScopedToTenant = false;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
              
                Section::make('Profile Information')
                    ->columns(1)
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        TextInput::make('username')->required()->unique(),
                        MarkdownEditor::make('signature')
                        // $this->getPasswordFormComponent(),
                        // $this->getPasswordConfirmationFormComponent(),
                    ]),
                Section::make('Status')
                    ->columns(1)
                    ->schema([
                        Placeholder::make('rank')->disabled(),
                        Placeholder::make('role')->disabled(),
                        Placeholder::make('posts_count')->disabled(),
                        Placeholder::make('topics_count')->disabled(),
                    ]),

                Section::make('Security')
                    ->columns(1)
                    ->schema([
                        Checkbox::make('change_password')
                            ->label('Change Password')
                            ->dehydrated(false)
                            ->live()
                            ->default(false)
                            ->columnSpanFull(),
                        TextInput::make('password')
                            ->password()
                            ->confirmed()
                            ->required(fn($get) => $get('change_password'))
                            ->disabled(fn($get) => !$get('change_password')),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->required(fn($get) => $get('change_password'))
                            ->disabled(fn($get) => !$get('change_password'))
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
