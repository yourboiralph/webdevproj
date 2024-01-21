<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'email',
        'password'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'adminID');
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'adminID');
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class, 'adminID');
    }

    public function superUser()
    {
        return $this->hasOne(SuperUser::class, 'adminID');
    }
}
