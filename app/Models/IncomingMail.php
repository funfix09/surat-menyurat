<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingMail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'referance_number',
        'date_letter_number',
        'origin_number',
        'date_of_origin',
        'sender_mail',
        'regarding',
        'attachment_file',
        'is_urgent',
        'user_id'
    ];

    public function dataDivision()
    {
        return $this->hasMany(IncomingMailDivision::class, 'mail_id', 'id');
    }

    public function dataUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
