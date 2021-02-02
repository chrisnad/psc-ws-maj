<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Ps;
use App\Models\Structure;
use App\Psc\Transformers\ExpertiseTransformer;
use App\Psc\Transformers\ProfessionTransformer;
use App\Psc\Transformers\PsTransformer;
use App\Psc\Transformers\StructureTransformer;
use App\Psc\Transformers\WorkSituationTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ApiController
 * @package App\Http\Controllers\Api
 */
class ApiController extends Controller
{
    use ApiResponder;

    /**
     * @var PsTransformer
     */
    protected $psTransformer;
    /**
     * @var ProfessionTransformer
     */
    protected $professionTransformer;
    /**
     * @var ExpertiseTransformer
     */
    protected $expertiseTransformer;
    /**
     * @var WorkSituationTransformer
     */
    protected $situationTransformer;

    /**
     * @var StructureTransformer
     */
    protected $structureTransformer;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->psTransformer = new PsTransformer();
        $this->professionTransformer = new ProfessionTransformer();
        $this->expertiseTransformer = new ExpertiseTransformer();
        $this->situationTransformer = new WorkSituationTransformer();
        $this->structureTransformer = new StructureTransformer();
    }

    /**
     * @param $psId
     * @return Ps
     */
    public function getPs($psId) : Ps
    {
        try {
            $ps = Ps::findOrFail($psId);
        } catch(ModelNotFoundException $e) {
            $this->notFoundResponse("Ce professionel n'exist pas.")->send();
            die();
        }
        return $ps;
    }

    /**
     * @param $structureId
     * @return Structure
     */
    public function getStructure($structureId) : Structure
    {
        try {
            $structure = Structure::findOrFail($structureId);
        } catch(ModelNotFoundException $e) {
            $this->notFoundResponse("Cette structure n'existe pas.")->send();
            die();
        }
        return $structure;
    }

    /**
     * @param $psId
     * @param $exProId
     * @return mixed
     */
    public function getExPro($psId, $exProId)
    {
        $ps = $this->getPs($psId);
        $profession = $ps->professions()->firstWhere('exProId', $exProId);
        if (! $profession) {
            $this->notFoundResponse("Cet exercice professionnel n'exist pas.")->send();
            die();
        }
        return $profession;
    }

    /**
     * @param $psId
     * @param $exProId
     * @param $situId
     * @return mixed
     */
    public function getSituation($psId, $exProId, $situId) {
        $profession = $this->getExPro($psId, $exProId);
        $situation = $profession->workSituations()->firstWhere('situId', $situId);
        if (! $situation) {
            $this->notFoundResponse("Cette situation d'exercise n'exist pas.")->send();
            die();
        }
        return $situation;
    }

    /**
     * @param $psId
     * @param $exProId
     * @param $expertiseId
     * @return mixed
     */
    public function getExpertise($psId, $exProId, $expertiseId) {
        $profession = $this->getExPro($psId, $exProId);
        $situation = $profession->expertises()->firstWhere('expertiseId', $expertiseId);
        if (! $situation) {
            $this->notFoundResponse("Ce savoir fair n'exist pas.")->send();
            die();
        }
        return $situation;
    }
}
