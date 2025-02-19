<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = "posts";
        protected $fillable = [
        'post_title',
        'brand_id',
        'platform_id',
        'due_date',
        'payment',
        'status'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function scopeFilters($query, $filters)
    {
        if (isset($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }
        if (isset($filters['platform_id'])) {
            $query->where('platform_id', $filters['platform_id']);
        }
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query;
    }
}
