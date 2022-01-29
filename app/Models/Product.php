<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'product_variants')->withPivot('variant');
    }

    public function variantPrices()
    {
        return $this->hasMany(ProductVariantPrice::class);
    }

    public function scopeFilter($query, $request)
    {
        $title      = $request->input('title');
        $price_from = $request->input('price_from');
        $price_to   = $request->input('price_to');
        $date       = $request->input('date');
        $variant   = $request->input('variant');
        
        $query->when($title, function($q) use ($title) {
            $q->where('title', 'LIKE', "%{$title}%");
        });

        $query->when($price_from, function($q) use ($price_from) {
            $q->whereHas('variantPrices', function($q) use ($price_from) {
                $q->where('price', '>=', $price_from);
            });
        });

        $query->when($price_to, function($q) use ($price_to) {
            $q->whereHas('variantPrices', function($q) use ($price_to) {
                $q->where('price', '<=', $price_to);
            });
        });

        $query->when($variant, function($q) use ($variant) {
            $q->whereHas('variants', function($q) use ($variant) {
                $q->where('variant', $variant);
            });
        });
        
        $query->when($date, function($q) use ($date) {
            $q->whereDate('created_at', $date);
        });

        return $query;
    }

    public function generateVariant( $variantPrice )
    {
        $variant_one = $variantPrice->variant_one ? $variantPrice->variant_one->variant : '';
        $variant_two = $variantPrice->variant_two ? $variantPrice->variant_two->variant : '';
        $variant_three = $variantPrice->variant_three ? $variantPrice->variant_three->variant : '';

        return "$variant_one/$variant_two/$variant_three";
    }

}
