<?php

namespace App\Models;

use App\Models\Condition;
use App\Models\HierarchyEntity;
use App\Models\Organization;
use App\Models\Product;
use App\Models\User;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'entity_code',
        'entry_code',
        'user_id',
        'product_id',
        'quantity',
        'organization_id',
        'guide',
        'lote_number',
        'expiration_date',
        'condition_id',
        'authority_fullname',
        'authority_ci',
        'arrival_time',      
        'day',
        'month',
        'year',
        'description',
        'created_at',
        'updated_at',
        'status',
    ];


    public function entity()
    {
        return $this->belongsTo(HierarchyEntity::class, 'entity_code', 'code');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id', 'id');
    }
}
