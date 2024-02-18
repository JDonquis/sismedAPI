<?php

namespace App\Models;

use App\Models\Condition;
use App\Models\HierarchyEntity;
use App\Models\InventoryGeneral;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Carbon\Carbon;


class Inventory extends Model
{
    use HasFactory;


    protected $table = 'inventories';

    protected $fillable = [
        'entity_code',
        'product_id',
        'lote_number',
        'expiration_date',
        'stock',
        'condition_id',
        'search',
        'entries',
        'outputs'
    ];

  

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function entity()
    {
        return $this->belongsTo(HierarchyEntity::class, 'entity_code', 'code');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id', 'id');
    }

    public function inventoryGeneral()
    {
        return $this->belongsTo(InventoryGeneral::class, 'product_id', 'product_id');
    }


}
