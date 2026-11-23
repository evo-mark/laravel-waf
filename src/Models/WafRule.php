<?php

declare(strict_types=1);

namespace EvoMark\LaravelWaf\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class WafRule extends Model
{
    use Notifiable;

    protected $dispatchesEvents = [
        'updated' => ''
    ];
}
