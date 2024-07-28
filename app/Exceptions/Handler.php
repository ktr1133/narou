<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

class Handler extends ExceptionHandler
{

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof QueryException) {
            // フラッシュメッセージを設定
            Session::flash('error', $exception->getMessage());

            // 前のページにリダイレクト
            return redirect()->back();
        }

        return parent::render($request, $exception);
    }
}
