<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $value = preg_replace('/[^А-яËёA-z\s\-]/u', '', $value);
                $value = preg_replace('/\s+/', ' ', $value);

                return trim($value);
            }
        );
    }
}
