<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'adminID',
        'questionID',
        'option',
        'score'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminID');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'questionID');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'optionID');
    }

}
