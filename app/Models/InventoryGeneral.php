<?php

namespace App\Models;

use App\Models\HierarchyEntity;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryGeneral extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_code',
        'product_id',
        'stock_expired',
        'stock_bad',
        'stock',
        'entries',
        'outputs',
        'search',
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function entity()
    {
        return $this->belongsTo(HierarchyEntity::class, 'entity_code', 'code');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'product_id', 'product_id');
    }
}
