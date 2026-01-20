<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum UserRole: string implements HasColor, HasIcon, HasLabel
{
    case Admin = 'admin';
    case Moderator = 'moderator';
    case Member = 'member';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Moderator => 'Moderator',
            self::Member => 'User',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Moderator => 'warning',
            self::Member => 'info',
        };
    }

    public function getIcon(): string|Htmlable|null
    {
        return match ($this) {
            self::Admin => 'heroicon-m-shield-check',
            self::Moderator => 'heroicon-m-shield-exclamation',
            self::Member => 'heroicon-m-user',
        };
    }
}
