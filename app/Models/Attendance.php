<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'time',
        'timestamp',
        'photo',
        'latitude',
        'longitude',
        'address',
        'status',
        'teacher_id',
        'teacher_notes',
        'device',
        'browser',
        'ip_address',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
