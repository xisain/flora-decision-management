<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('user_id', 'full_name', 'initial_collector_name', 'is_manual', 'last_sequence')]
class CollectorInfo extends Model
{
    public function user(){
        return $this->hasOne(User::class);
    }
}
