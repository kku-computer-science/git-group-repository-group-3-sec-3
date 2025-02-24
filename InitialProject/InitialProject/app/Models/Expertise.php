<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expertise extends Model
{
    use HasFactory;

    protected $table = 'expertises'; // Assuming the table name is 'expertises'

    protected $fillable = [
        'title_en', // English title
        'title_th', // Thai title
        'title_cn', // Chinese title
        'description_en', // English description
        'description_th', // Thai description
        'description_cn', // Chinese description
        'created_at',
        'updated_at',
    ];

    // Method to retrieve expertise data
    public static function getExpertiseData()
    {
        return self::all();
    }
}