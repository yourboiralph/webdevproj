<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'diagnosisID',
        'questionID', 
        'optionID',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'questionID');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'optionID');
    }   

    public function diagnosis()
    {
        return $this->belongsTo(Diagnosis::class, 'diagnosisID');
    }
}
