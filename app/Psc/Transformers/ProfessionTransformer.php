<?php


namespace App\Psc\Transformers;


/**
 * Class ProfessionTransformer
 * @package App\Psc\Transformers
 */
class ProfessionTransformer extends Transformer {

    /**
     * transform Profession into a protected Profession.
     *
     * @param $profession
     * @return array
     */
    public function transform($profession): array
    {
        return [
            'code' => $profession['code'],
            'categoryCode' => $profession['categoryCode'],
            'salutationCode' => $profession['salutationCode'],
            'lastName' => $profession['lastName'],
            'firstName' => $profession['firstName']
        ];
    }
}
