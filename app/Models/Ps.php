<?php
/**
 * Ps
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Ps
 */
class Ps extends Model {

    protected $primaryKey = 'nationalId';

    protected $fillable = ['email', 'phone'];
    /**
     * Get the professions for this Ps.
     */
    public function professions(): HasMany
    {
        return $this->hasMany(PsProfessionalInfo::class);
    }

}
