<?php

namespace App\Http\Controllers\Ui;

use App\Bschmitt\Amqp\Amqp;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends Controller
{

    protected $VALID_COLUMN_NUMBER = 50;

    protected $DATA;

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
        return view('file.upload', [
            'headers' => $this->HEADER,
            'colNum' => $this->VALID_COLUMN_NUMBER-1
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'separator' => 'required'
        ]);

        $file = $request->file('files')[0];
        $separator = $request->input('separator', '|');

        $fileData = $this->csvToArray($file, $separator);

        if ($fileData) {
            $this->publishFile($fileData);
            return view('file.upload', [
                'headers' => $this->HEADER,
                'colNum' => $this->VALID_COLUMN_NUMBER-1,
                'title' => 'Success',
                'message' => 'Le fichier a été envoyé pour le traitement'
            ]);
        } else {
            return view('file.upload', [
                'headers' => $this->HEADER,
                'colNum' => $this->VALID_COLUMN_NUMBER-1,
                'title' => 'Erreur',
                'message' => "Le fichier chargé n'est pas au bon format"
            ]);
        }
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
    private function publishFile($data): void
    {
        foreach ($data as $row) {
            $message = $row[0];
            foreach (array_slice($row,1) as $attribute) {
                $message = $message . "|" . $attribute;
            }
            $this->publishRow($message);
        }
    }

    /**
     * @param $message
     */
    private function publishRow($message) {
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
