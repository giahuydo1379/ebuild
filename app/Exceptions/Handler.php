<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Ixudra\Curl\Facades\Curl;

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
		if ($exception instanceof Exception) { //&& config('app.env')!='local'
            // whenever any error occurs, a mail will be sent to desired email.
            // content variable will contain message that you can send to emails.error_report(or whatever) view.
            // you can use this variable simply echoing $content.
            $ip = app('request')->ip();
            if (!$ip && app()->runningInConsole())
            {
                $ip = 'Console Jobs';
            }
            $content = urlencode(config("app.name")." There was an error: \n".
                " - Message : ".$exception->getMessage()." \n".
                " - Line : ".$exception->getLine()." \n".
                " - File : ".$exception->getFile()." \n".
                " - Ip : ".$ip.
                " - Url : ".url()->full()
            );
//            $url = 'https://api.telegram.org/bot545339852:AAH9-wfIRfeWX19P7uPvrHPrGho57CuIunc/getUpdates'; dùng get chat id
            $url = 'https://api.telegram.org/bot545339852:AAH9-wfIRfeWX19P7uPvrHPrGho57CuIunc/sendMessage';
            $url .= '?chat_id=-303834693';
            $url .= '&text='.urlencode(config("app.name")).': '.$content;

            $res = Curl::to($url)->get();
        }
		
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
