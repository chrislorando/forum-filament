<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TopicStatus: string implements HasColor, HasIcon, HasLabel
{
    case Normal = 'normal';
    case Locked = 'locked';
    case Pinned = 'pinned';
    case Moved = 'moved';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::Normal => 'Normal',
            self::Locked => 'Locked',
            self::Pinned => 'Pinned',
            self::Moved => 'Moved',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Normal => 'gray',
            self::Locked => 'danger',
            self::Pinned => 'warning',
            self::Moved => 'info',
        };
    }

    public function getIcon(): string|Htmlable|null
    {
        return match ($this) {
            self::Normal => 'heroicon-m-document-text',
            self::Locked => 'heroicon-m-lock-closed',
            self::Pinned => 'heroicon-m-star',
            self::Moved => 'heroicon-m-arrow-path',
        };
    }
}
