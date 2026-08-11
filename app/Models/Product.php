<?php

namespace App\Models;

use App\Enums\ProductStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'barcode',
        'internal_code',
        'name',
        'description',
        'brand',
        'purchase_price',
        'sale_price',
        'stock',
        'minimum_stock',
        'unit',
        'image',
        'expiration_date',
        'status',
        'category_id',
        'location_id',
        'supplier_id',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'stock' => 'integer',
        'minimum_stock' => 'integer',
        'expiration_date' => 'date',
        'status' => ProductStatus::class,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return 'https://placehold.co/300x300/f1f5f9/64748b?text=' . urlencode($this->name);
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->purchase_price <= 0) {
            return 100.0;
        }

        return round((($this->sale_price - $this->purchase_price) / $this->purchase_price) * 100, 2);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->minimum_stock;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', ProductStatus::Active);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock', '>', 0)
            ->whereColumn('stock', '<=', 'minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '<=', 0);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%")
                ->orWhere('internal_code', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%");
        });
    }
}
