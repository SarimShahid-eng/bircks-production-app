<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionBatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'batch_no',
        'production_date',
        'raw_bricks_used',
        'bricks_produced',
        'labor_cost',
        'fuel_cost',
        'total_material_cost',
        'total_expense_cost',
        'total_cost',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'production_date' => 'date',
            'labor_cost' => 'decimal:2',
            'fuel_cost' => 'decimal:2',
            'total_material_cost' => 'decimal:2',
            'total_expense_cost' => 'decimal:2',
            'total_cost' => 'decimal:2',
        ];
    }

    public function productionMaterials(): HasMany
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
