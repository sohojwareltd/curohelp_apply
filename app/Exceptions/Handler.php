<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use App\Http\SafeJsonResponse;
use Illuminate\Support\Facades\Mail;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception): void
    {
        if ($this->shouldReport($exception)) {
            try {
                Mail::send('emails.errors', ['exception' => $exception], function ($message) {
                    foreach (['ahmedtamim19050@gmail.com'] as $email) {
                        $message->to($email)->subject('Error in Curohelp');
                    }
                });
            } catch (Throwable $mailError) {
                // Avoid interrupting the reporting flow if mail fails
            }
        }

        parent::report($exception);
    }

    /**
     * Prepare exception for rendering.
     */
    public function render($request, Throwable $exception)
    {
        // Catch the UTF-8 encoding error and return a SafeJsonResponse
        if ($exception instanceof InvalidArgumentException && 
            str_contains($exception->getMessage(), 'Malformed UTF-8 characters')) {
            
            // The error happened during JsonResponse creation
            // Try to recover by returning an error response
            return response()->json([
                'error' => 'Data encoding error. The response could not be serialized.',
                'message' => 'UTF-8 encoding issue in the response data.'
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
