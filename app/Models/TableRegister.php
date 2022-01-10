<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableRegister extends Model
{
    use HasFactory;

    protected $table="table_register";
    protected $fillable = ['id', 'id_student', 'id_subject', "date", "start_date", "end_date"];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class, "id_student");
    }

    public function subject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subject::class, "id_subject");
    }

}
