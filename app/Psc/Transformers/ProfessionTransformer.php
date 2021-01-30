<?php


namespace App\Psc\Transformers;


/**
 * Class ProfessionTransformer
 * @package App\Psc\Transformers
 */
class ProfessionTransformer extends Transformer {

    protected $expertiseTransformer;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->expertiseTransformer = new ExpertiseTransformer();
    }

    /**
     * transform Profession into a protected Profession.
     *
     * @param $profession
     * @return mixed
     */
    public function transform($profession)
    {
        $protectedProfession = $profession;
        if (isset($profession['expertises'])) {
            $protectedProfession['expertises'] = $this->expertiseTransformer->transformCollection($profession['expertises']);
        }

        return $protectedProfession;
    }
}
