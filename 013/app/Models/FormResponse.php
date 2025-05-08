<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormResponse extends Model
{
    protected $fillable = ['form_id', 'submitted_at', 'ip', 'user_agent'];

    protected $casts = [
        'submitted_at' => 'datetime'
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(FormAnswer::class);
    }
}
