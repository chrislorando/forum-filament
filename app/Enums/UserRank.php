<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum UserRank: string implements HasColor, HasIcon, HasLabel
{
    case Newbie = 'newbie';
    case JuniorMember = 'junior_member';
    case FullMember = 'full_member';
    case SeniorMember = 'senior_member';
    case HeroMember = 'hero_member';
    case Legendary = 'legendary';

    public function getLabel(): string | Htmlable | null
    {
        return match ($this) {
            self::Newbie => 'Newbie',
            self::JuniorMember => 'Junior Member',
            self::FullMember => 'Full Member',
            self::SeniorMember => 'Senior Member',
            self::HeroMember => 'Hero Member',
            self::Legendary => 'Legendary',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Newbie => 'gray',
            self::JuniorMember => 'blue',
            self::FullMember => 'green',
            self::SeniorMember => 'purple',
            self::HeroMember => 'orange',
            self::Legendary => 'yellow',
        };
    }

    public function getIcon(): string | Htmlable | null
    {
        return match ($this) {
            self::Newbie => 'heroicon-m-user',
            self::JuniorMember => 'heroicon-m-user-plus',
            self::FullMember => 'heroicon-m-user-circle',
            self::SeniorMember => 'heroicon-m-star',
            self::HeroMember => 'heroicon-m-trophy',
            self::Legendary => 'heroicon-m-academic-cap',
        };
    }
}
