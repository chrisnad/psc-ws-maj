<?php
/**
 * WorkSituation
 */
namespace App\Models;


use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;

/**
 * WorkSituation
 */
class WorkSituation extends Model {

    protected $connection = 'mongodb';

    protected $fillable = [
        'modeCode',
        'activitySectorCode',
        'pharmacistTableSectionCode',
        'roleCode'
    ];

    /**
     * Get the Structure that this WorkSituation belongs to.
     */
    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

}
