<?php

namespace App\Models;

use App\Models\Condition;
use App\Models\HierarchyEntity;
use App\Models\Organization;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    use HasFactory;

    protected $fillable = 
    [
        'entity_code',
        'output_code',
        'user_id',
        'product_id',
        'condition_id',
        'quantity',
        'organization_id',
        'guide',
        'lote_number',
        'authority_fullname',
        'authority_ci',
        'departure_date',
        'departure_time',      
        'day',
        'month',
        'year',
        'description',
        'receiver_fullname',
        'receiver_ci',
        'expiration_date',
        'created_at',
        'updated_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function entity()
    {
        return $this->belongsTo(HierarchyEntity::class, 'entity_code', 'code');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
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
