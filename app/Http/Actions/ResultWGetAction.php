<?php

namespace App\Http\Actions;

use App\Consts\NarouConst;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest as Request;
// use App\Http\Responder\ResultWGetResponder
use App\Services\CommonService;
use App\Services\ResultWService;
use Illuminate\Http\Response;
use DateTime;

/**
 * 作者ランキング作成
 * 
 * 
 */
class ResultWGetAction  extends Controller
{

    /**
     * コンストラクタ
     */
    public function __construct(
        protected Request $request,
        protected CommonService $common_service,
        protected ResultWService $result_w_service,
    )
    {
    }

    /**
     * 作者ランキング作成
     * 
     * @param  Request  $request 検索条件
     * @return Response
     */
    public function __invoke(Request $request):Response
    {
        $show_messages = $this->common_service->getSelection($request);
        // 選択肢による場合分け
        //// mark
        $result = null;
        if ( $request['cate'] === NarouConst::MARK ) {
            $result = $this->result_w_service->createMarkRank($request);
        //// calc
        } elseif ($request['cate'] === NarouConst::CALC) {
            $result = $this->result_w_service->createCalcRank($request);
        //// point
        } elseif ($request['cate'] === NarouConst::POINT) {
            $result = $this->result_w_service->createPointRank($request);
        //// unique
        } elseif ($request['cate'] === NarouConst::UNIQUE) {
            $result = $this->result_w_service->creatUniqueRank($request);
        //// number of resisterd works
        } else {
            $result = $this->result_w_service->creatRegisteredWorksRank($request);
        }
        
        return response([
            'messages' => $show_messages,
            'result'   => $result,
        ]);
    }
}