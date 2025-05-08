<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormAnswer extends Model
{
    protected $fillable = ['form_response_id', 'field_id', 'answer'];

    public function response()
    {
        return $this->belongsTo(FormResponse::class, 'form_response_id');
    }

    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
