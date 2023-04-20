<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    protected $fillable = ['survey_id', 'start_date', 'end_date'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
