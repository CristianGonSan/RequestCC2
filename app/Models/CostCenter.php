<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $company_id
 * @property string $name
 * @property string|null $description
 * @property bool $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Company|null $company
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestModel> $requests
 * @property-read int|null $requests_count
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter query()
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CostCenter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CostCenter extends Model
{
    use HasFactory;

    protected $table = 'cost_centers';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function requests()
    {
        return $this->hasMany(RequestModel::class, 'cost_center', 'name');
    }
}
