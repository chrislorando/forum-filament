<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ReportReason: string implements HasColor, HasIcon, HasLabel
{
    case Spam = 'spam';
    case Scam = 'scam';
    case InappropriateContent = 'inappropriate_content';
    case WrongBoard = 'wrong_board';
    case DuplicateTopic = 'duplicate_topic';

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::Spam => 'Spam',
            self::Scam => 'Scam',
            self::InappropriateContent => 'Inappropriate Content',
            self::WrongBoard => 'Wrong Board',
            self::DuplicateTopic => 'Duplicate Topic',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Spam => 'danger',
            self::Scam => 'danger',
            self::InappropriateContent => 'warning',
            self::WrongBoard => 'info',
            self::DuplicateTopic => 'gray',
        };
    }

    public function getIcon(): string | Htmlable | null
    {
        return match ($this) {
            self::Spam => 'heroicon-m-inbox',
            self::Scam => 'heroicon-m-exclamation-triangle',
            self::InappropriateContent => 'heroicon-m-no-symbol',
            self::WrongBoard => 'heroicon-m-arrow-right',
            self::DuplicateTopic => 'heroicon-m-document-duplicate',
        };
    }
}
