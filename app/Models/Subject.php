<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table="subject";
    protected $fillable = ['id', 'id_subject', 'name', "number_lesson", 'id_teacher', 'qrcode'];

    public function teacher(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Teacher::class, "id_teacher");
    }
}
