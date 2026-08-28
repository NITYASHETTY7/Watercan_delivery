<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */

namespace App\Exceptions;
use App\Events\ErrorOccurred;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        $this->reportable(
            function (Throwable $e) {
                //
            }
        );
    }

     /**
     * Report the exception handling callbacks for the application.
     *
     * @return void
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            event(new ErrorOccurred($exception));
        }

        parent::report($exception);
    }

        public function render($request, Throwable $exception)
        {
            if ($exception instanceof PostTooLargeException) {
                return redirect()->back()
                    ->withErrors(['file_upload' => 'The uploaded file exceeds the maximum allowed size.'])
                    ->withInput();
            }

            return parent::render($request, $exception);
        }
}
