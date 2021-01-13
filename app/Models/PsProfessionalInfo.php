<?php
/**
 * PsProfessionalInfo
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PsProfessionalInfo
 */
class PsProfessionalInfo extends Model {

    /** @var string $professionCode */
    private $professionCode;

    /** @var string $professionalCategoryCode */
    private $professionalCategoryCode;

    /** @var string $expertiseTypeCode */
    private $expertiseTypeCode;

    /** @var string $expertiseCode */
    private $expertiseCode;

    /** @var string $professionalName */
    private $professionalName;

    /** @var string $professionalLastName */
    private $professionalLastName;

    /** @var string $professionalTitleCode */
    private $professionalTitleCode;

    /**
     * Get the Ps that owns the comment.
     */
    public function ps(): BelongsTo
    {
        return $this->belongsTo(Ps::class);
    }

}
