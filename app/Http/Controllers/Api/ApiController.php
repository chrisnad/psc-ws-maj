<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;

/**
 * Class ApiController
 * @package App\Http\Controllers\Api
 */
class ApiController extends Controller
{

    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @return mixed
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return ApiController
     */
    public function setStatusCode($statusCode): ApiController
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function responseNotFound($message = 'Not found!'){
        return $this->setStatusCode(404)->respondwithError($message);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function responseInternalError($message = 'Internal Error!'){
        return $this->setStatusCode(500)->respondwithError($message);
    }

    /**
     * @param $message
     * @return mixed
     */
    public function respondWithError($message){
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respond($data, $headers = []){
        return response()->json($data, $this->getStatusCode(), $headers);
    }

}
