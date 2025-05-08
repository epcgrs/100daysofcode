<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $fillable = ['user_id', 'title', 'slug', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(FormRule::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }
}
