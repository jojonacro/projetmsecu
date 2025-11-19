<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passager extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'sexe', 'date_naissance', 'lieu_naissance',
        'email', 'profession', 'fonction', 'nationalite', 'pays_residence'
    ];

    public function document()
    {
        return $this->hasOne(Document::class);
    }

    public function voyages()
    {
    return $this->hasMany(Voyage::class);
    }

}
