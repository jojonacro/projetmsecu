<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Voyage extends Model
{
    use HasFactory;

    protected $fillable = [
        'passager_id',
        'document_id',
        'code_voyage',
        'qr_path',
    ];

    protected static function booted()
    {
        static::creating(function ($voyage) {
            do {
                $code = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 8);
            } while (self::where('code_voyage', $code)->exists());
            $voyage->code_voyage = $code;
        });
    }

    public function passager()
    {
        return $this->belongsTo(Passager::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
