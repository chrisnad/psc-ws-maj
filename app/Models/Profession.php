<?php
/**
 * Profession
 */
namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\EmbedsMany;
use Jenssegers\Mongodb\Relations\HasMany;

/**
 * Profession
 */
class Profession extends Model {

    protected $connection = 'mongodb';

    // protected $primaryKey = 'code';

    protected $fillable = [
        'code',
        'categoryCode',
        'salutationCode',
        'lastName',
        'firstName'
    ];

    /**
     * Get the Expertise list for this Profession.
     */
    public function expertises(): EmbedsMany
    {
        return $this->embedsMany(Expertise::class);
    }

    /**
     * Get the WorkSituation list for this Profession.
     */
    public function workSituations(): EmbedsMany
    {
        return $this->embedsMany(WorkSituation::class);
    }

}
