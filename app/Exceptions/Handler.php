<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        /*$this->renderable(function(Exception $e, $request) {
            return $this->handleException($request, $e);
        });*/

        $this->reportable(function (Throwable $e) {
            return redirect(route('welcome', [
                'title' => 'Erreur',
                'message' => $e->getMessage()
            ]));
        });
    }

    public function handleException($request, Exception $exception)
    {
        /*if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('The specified method for the request is invalid', 405);
        }

        if ($exception instanceof AuthenticationException) {
            return redirect($exception->redirectTo());
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->notFoundResponse('The specified URL cannot be found');
        }

        if ($exception instanceof HttpException) {
            if ($exception->getPrevious() instanceof TokenMismatchException) {
                // token mismatch is a security concern, ensure logout.
                Auth::logout();

                // Go to welcome page and tell the user.
                return redirect(route('welcome', [
                    'title' => 'Erreur',
                    'message' => 'Votre session a expirée, veuillez vous reconnecter'
                ]));
            }
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }*/

//        if (config('app.debug')) {
//            return parent::render($request, $exception);
//        }

        if (config('app.env') == 'production') {
            return $this->internalErrorResponse('Unexpected Exception. Try later');
        }
    }

}
