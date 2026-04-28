<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
#[Table('criteria')]
#[Fillable('name','weight','type','preference_type','p','q')]

class Criteria extends Model
{
    //
}
