<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum PostType: string implements HasColor, HasIcon, HasLabel
{
    case FirstPost = 'first_post';
    case Reply = 'reply';

    public function getLabel(): string|Htmlable|null
    {
        return match ($this) {
            self::FirstPost => 'First Post',
            self::Reply => 'Reply',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::FirstPost => 'primary',
            self::Reply => 'gray',
        };
    }

    public function getIcon(): string|Htmlable|null
    {
        return match ($this) {
            self::FirstPost => 'heroicon-m-star',
            self::Reply => 'heroicon-m-chat-bubble-bottom-center-text',
        };
    }
}
