<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use app\Models\Question;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    //
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function questions(): HasMany{
        return $this->hasMany(Question::class);
    }
}
