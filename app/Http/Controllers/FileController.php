<?php

namespace App\Http\Controllers;

use App\Bschmitt\Amqp\Amqp;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends Controller
{

    protected $VALID_COLUMN_NUMBER = 50;

    protected $QUEUE = 'file.upload';

    protected $HEADER = [
        "Type d'identifiant PP",
        "Identifiant PP",
        "Identification nationale PP",
        "Nom de famille",
        "Prénoms",
        "Date de naissance",
        "Code commune de naissance",
        "Code pays de naissance",
        "Lieu de naissance",
        "Code sexe",
        "Téléphone (coord. correspondance)",
        "Adresse e-mail (coord. correspondance)",
        "Code civilité",
        "Code profession",
        "Code catégorie professionnelle",
        "Code civilité d'exercice",
        "Nom d'exercice",
        "Prénom d'exercice",
        "Code type savoir-faire",
        "Code savoir-faire",
        "Code mode exercice",
        "Code secteur d'activité",
        "Code section tableau pharmaciens",
        "Code rôle",
        "Numéro SIRET site",
        "Numéro SIREN site",
        "Numéro FINESS site",
        "Numéro FINESS établissement juridique",
        "Identifiant technique de la structure",
        "Raison sociale site",
        "Enseigne commerciale site",
        "Complément destinataire (coord. structure)",
        "Complément point géographique (coord. structure)",
        "Numéro Voie (coord. structure)",
        "Indice répétition voie (coord. structure)",
        "Code type de voie (coord. structure)",
        "Libellé Voie (coord. structure)",
        "Mention distribution (coord. structure)",
        "Bureau cedex (coord. structure)",
        "Code postal (coord. structure)",
        "Code commune (coord. structure)",
        "Code pays (coord. structure)",
        "Téléphone (coord. structure)",
        "Téléphone 2 (coord. structure)",
        "Télécopie (coord. structure)",
        "Adresse e-mail (coord. structure)",
        "Code Département (structure)",
        "Ancien identifiant de la structure",
        "Autorité d'enregistrement",
        ""
    ];

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return Application|Factory|View
     */
    public function index() {
        return view('file.upload');
    }

    public function publish() {
        $data = Auth::user()->fileData->data;
        $this->sendRows($data);
        return view('file.upload', [
            'title' => 'Success',
            'message' => 'Le fichier a été envoyé pour le traitement'
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'separator' => 'required'
        ]);

        $file = $request->file('file');
        $separator = $request->input('separator', '|');

        $data = $this->csvToArray($file, $separator);

        if ($data) {
            Auth::user()->fileData()->updateOrCreate([], ['data' => $data]);
            return redirect()->route('files.validation')->withInput(['page' => 0]);
        } else {
            return view('file.upload', [
                'title' => 'Erreur',
                'message' => "Le fichier n'est pas au bon format csv"
            ]);
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function validation(Request $request)
    {
        $data = Auth::user()->fileData->data;

        $page = $request->input('page') ? $request->input('page') : 0;

        return view('file.validation', [
            'page' => $page,
            'data' => $data,
            'headers' => $this->HEADER,
            'colNum' => $this->VALID_COLUMN_NUMBER
        ]);
    }

    /**
     * @param $page
     * @return mixed
     */
    public function getPage($page)
    {
        $data = Auth::user()->fileData->data;

        return view('file.page', [
            'data' => $data[$page],
            'headers' => $this->HEADER,
            'colNum' => $this->VALID_COLUMN_NUMBER
        ]);
    }

    private function csvToArray($filename='', $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 2000, $delimiter)) !== FALSE)
            {
                if(!$header) {
                    $header = $this->HEADER;
                } else if (count($header) == count($row) && count($row) == $this->VALID_COLUMN_NUMBER) {
                    // Valid
                    array_push($data, $row);
                } else {
                    // Not valid
                    fclose($handle);
                    return false;
                }
            }
            fclose($handle);
        } else {
            return false;
        }
        return $data;
    }

    /**
     * @param $data
     */
    private function sendRows($data): void
    {
        foreach ($data as $row) {
            $message = $row[0];
            foreach (array_slice($row,1) as $attribute) {
                $message = $message . "|" . $attribute;
            }
            $this->send($message);
        }
    }

    /**
     * @param $message
     */
    private function send($message) {
        try {
            (new Amqp)->publish($this->QUEUE, $message, ['queue' => $this->QUEUE]);
        } catch (Exception $exception) {
            report($exception);
        }
    }

    /**
     *
     */
    private function consume() {
        try {
            (new Amqp)->consume($this->QUEUE, function ($message, $resolver) {
                var_dump($message->body);
                $resolver->acknowledge($message);
                $resolver->stopWhenProcessed();
            });
        } catch (Exception $exception) {
            dd($exception);
        }
    }
}
