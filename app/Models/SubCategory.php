<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'subcategories';

    protected $fillable = [
        'parent_id',
        'category_id',
        'translation_lang',
        'translation_of',
        'name',
        'slug',
        'photo',
        'active',
        'created_at',
        'updated_at',
    ];

    // protected $hidden = [
    //     'created_at',
    //     'updated_at',
    // ];



    // protected static function boot()
    // {
    //     parent::boot();
    //     MainCategory::observe(MainCategoryObserver::class);
    // }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'parent_id', 'category_id', 'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active');
    }

    protected function defaultCategory()
    {
        return $this::where('translation_lang', get_default_lang());
    }

    protected function translationLang(): Attribute
    {
        return Attribute::make(
        get: fn($value) => $value ? strtolower($value) : "",
        );
    }

    protected function photo(): Attribute
    {
        return Attribute::make(
            get: fn($val) => $val !== null ? asset('assets/'.$val) : "",
        );
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : "غير مفعل";
    }

    // public function vendors()
    // {
    //     return $this->hasMany(Vendor::class, 'category_id');
    // }

    public function otherLangs()
    {
        return $this->hasMany($this::class, 'translation_of');
    }

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'category_id');
    }
}