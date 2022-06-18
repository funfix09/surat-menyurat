<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingMail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'letter_number',
        'date_letter_number',
        'regarding',
        'receiver',
        'division_id',
        'attachment_file',
        'status',
        'user_id'
    ];

    public function dataUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dataDivision()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
}
