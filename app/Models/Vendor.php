<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class Vendor extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
        'address',
        'email',
        'password',
        'category_id',
        'logo',
        'active',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        // 'password',
        // 'created_at',
        // 'updated_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'name', 'mobile', 'address', 'email', 'password', 'category_id', 'logo', 'active');
    }

    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn($val) => $val !== null ? asset('assets/'.$val) : "",
        );
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => bcrypt($value),
        );
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : "غير مفعل";
    }

    public function category(){
        return $this->belongsTo(MainCategory::class, 'category_id');
    }
}
