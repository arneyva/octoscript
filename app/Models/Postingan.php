<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Postingan extends Model
{
    protected $table = "postingan";
    public function scopeFilters($query, $filters)
{
    if (isset($filters['brand'])) {
        $query->where('brand', $filters['brand']);
    }

    if (isset($filters['platform'])) {
        $query->where('platform', $filters['platform']);
    }

    if (isset($filters['status'])) {
        $query->where('status', $filters['status']);
    }
    if (isset($filters['post_title'])) {
        $query->where('post_title', 'LIKE', '%' . $filters['post_title'] . '%');
    }

    return $query;
}

}
