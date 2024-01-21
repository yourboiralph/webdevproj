<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'takerID',
    ];

    public function responses()
    {
        return $this->hasMany(Response::class, 'diagnosisID');
    }

    public function taker()
    {
        return $this->belongsTo(Taker::class, 'takerID');
    }

    public function depressionType()
    {
        return $this->belongsTo(DepressionType::class, 'depression_type_id');
    }
}
