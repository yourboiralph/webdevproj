<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'adminID',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminID');
    }
}
