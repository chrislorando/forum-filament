<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BoardModerator extends Pivot
{
    protected $table = 'board_moderator';
}
