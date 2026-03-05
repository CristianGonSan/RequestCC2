<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @property int $enabled
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereEnabled($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 * @property array|null $allowed_types
 * @property-read Collection<int, BugReport> $bugReports
 * @property-read int|null $bug_reports_count
 * @property-read Collection<int, FileManagement> $files
 * @property-read int|null $files_count
 * @property-read Collection<int, RequestModel> $requests
 * @property-read int|null $requests_count
 * @method static Builder|User whereAllowedTypes($value)
 * @property-read Collection<int, \App\Models\Company> $companies
 * @property-read int|null $companies_count
 * @property-read Collection<int, \App\Models\Type> $types
 * @property-read int|null $types_count
 * @mixin Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'allowed_types'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'allowed_types' => 'array',
        'enabled' => 'boolean'
    ];

    /**
     * Determine if the user is enabled for login.
     *
     * @return bool|int
     */
    public function isEnabled(): bool|int
    {
        return $this->enabled;
    }

    public function requests(): HasMany
    {
        return $this->hasMany(RequestModel::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(FileManagement::class);
    }

    public function bugReports(): HasMany
    {
        return $this->hasMany(BugReport::class);
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'type_user');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user');
    }

    public function getTypesEnabled()
    {
        return $this->types()->where('enabled', true)->get();
    }

    public function getEnabledCompanies()
    {
        return $this->companies()->where('enabled', true)->get();
    }

    /**
     * Obtiene las empresas habilitadas junto con sus centros de costo habilitados.
     *
     * Este método consulta todas las empresas relacionadas con el modelo actual que
     * tengan el campo 'enabled' establecido en true. Además, realiza una carga anticipada
     * (eager loading) de los centros de costo asociados, filtrando solo aquellos que
     * también estén habilitados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *         Colección de empresas habilitadas con sus respectivos centros de costo habilitados.
     */
    public function getEnabledCompaniesWithEnabledCostCenters()
    {
        return $this->companies()->where('enabled', true)->with(['costCenters' => function ($query) {
            $query->where('enabled', true);
        }])->get();
    }
}
