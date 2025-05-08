<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormRule extends Model
{
    protected $fillable = ['form_id', 'source_field_id', 'operator', 'value', 'target_field_id', 'action'];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function sourceField()
    {
        return $this->belongsTo(FormField::class, 'source_field_id');
    }

    public function targetField()
    {
        return $this->belongsTo(FormField::class, 'target_field_id');
    }
}
