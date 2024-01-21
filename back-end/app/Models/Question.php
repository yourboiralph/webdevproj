<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'adminID',
        'question'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminID');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'questionID');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'questionID');
    }
}
