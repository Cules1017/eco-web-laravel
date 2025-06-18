<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeSection extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'description',
        'type',
        'list_categories',
        'num',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'num' => 'integer',
        'order' => 'integer',
        'type' => 'integer'
    ];

    public function getProducts()
    {
        $query = Product::where('is_active', true);

        switch ($this->type) {
            case 1: // featured_products
                $query->where('is_featured', true);
                break;
            case 2: // new_products
                $query->orderBy('created_at', 'desc');
                break;
            case 3: // category_products
                if ($this->list_categories) {
                    $categoryIds = array_filter(explode(',', $this->list_categories));
                    if (count($categoryIds)) {
                        $query->whereIn('category_id', $categoryIds);
                    } else {
                        return collect();
                    }
                } else {
                    return collect();
                }
                break;
        }

        return $query->take($this->num)->get();
    }

    public function getDisplayTitleAttribute()
    {
        return $this->title ?: $this->name;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function getCategoryIdsAttribute()
    {
        return $this->list_categories ? explode(',', $this->list_categories) : [];
    }

    public function categories()
    {
        return Category::whereIn('id', $this->category_ids);
    }
} 