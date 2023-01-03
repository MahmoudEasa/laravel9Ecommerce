<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'abbr',
        'locale',
        'name',
        'direction',
        'active',
        'created_at',
        'updated_at',
    ];

    // protected $hidden = [
    //     'created_at',
    //     'updated_at',
    // ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    protected function abbr(): Attribute
    {
        return Attribute::make(
        get: fn($value) => $value ? strtolower($value) : "",
        );
    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'name', 'abbr', 'direction', 'active');
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : "غير مفعل";
    }
}
