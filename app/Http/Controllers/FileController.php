<?php

namespace App\Http\Controllers;

use App\Bschmitt\Amqp\Amqp;
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

    protected $VALID_COLUMN_NUMBER = 49;

    protected $QUEUE = 'file.upload';

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
    public  function index() {
        return view('file.upload');
    }

    /**
     * @param Request $request
     */
    public function parse(Request $request)
    {
        request()->validate([
            'file' => 'required',
            'separator' => 'required'
        ]);

        $file = $request->file('file');
        $separator = $request->input('separator', '|');

        //$this->validateCsv($file, $separator);

        $this->sendRows($file, $separator);
    }

    /**
     * @param $file
     * @param $separator
     */
    public function sendRows($file, $separator): void
    {
        $handle = fopen($file, "r");
        while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
            $message = "";
            for ($c=0; $c < $this->VALID_COLUMN_NUMBER; $c++) {
                $message = $message . "|" . $data[$c];
            }
            $this->publish($message);
        }
        fclose($handle);
    }

    /**
     * @param $message
     */
    private function publish($message) {
        try {
            (new Amqp)->publish($this->QUEUE, $message, ['queue' => $this->QUEUE]);
        } catch (Exception $exception) {
            report($exception);
        }
        view('welcome');
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

    private function validateCsv($file, $separator) {
        if (($handle = fopen($file, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
                if ( count($data) != $this->VALID_COLUMN_NUMBER) {
                    view('file.upload', [
                        'title' => 'Erreur',
                        'message' => "Le fichier n'est pas au bon format csv"
                    ]);
                }
            }
            fclose($handle);
        } else {
            view('file.upload', [
                'title' => 'Erreur',
                'message' => "Le fichier n'est pas au bon format csv"
            ]);
        }
    }
}
