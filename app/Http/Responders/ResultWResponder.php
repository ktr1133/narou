<?php

namespace App\Http\Responders;

use Illuminate\Http\Response;


class ResultWGetResponder
{
    /**
     * コンストラクタ
     */
    public function __construct(
        protected Response $response,
    )
    {
    }

    /**
     * レンダー
     */
    public function render(array $results_set):Response
    {
        return response([
            'result'   => $results_set['result'],
            'message'  => $results_set['message']
        ]);
    }
}