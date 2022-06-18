<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingMailDivision extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mail_id',
        'division_id',
    ];

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id', 'id');
    }
}
