<?php

namespace App\Services;

use App\Consts\NarouConst;
use App\Repositories\MaRepository;
use App\Repositories\MarkRepository;
use App\Http\Requests\CreatePostRequest as Request;
use Illuminate\Support\Collection;

/**
 * 作者別ランキング表示サービスクラス
 * 
 */
class ResultWService
{
    /**
     * コンストラクタ
     * 
     * @param Request       $request        リクエスト
     * @param Marepository  $ma_repository  作者別ランキング作成用レポジトリ
     */
    public function __construct(
        protected MaRepository $ma_repository,
        protected MarkRepository $mark_repository,
        protected Request $request,
    )
    {
        $this->ma_repository = $ma_repository;
        $this->mark_repository = $mark_repository;
        $this->request = $request;
    }

    /**
     * メッセージ表示
     * 
     * @param  Request $request
     * @return array
     */
    function getResult(Request $request):array
    {
        //ﾘｸｴｽﾄﾃﾞｰﾀの整理
        $cate = $request-> input(NarouConst::SELECT_CREATE_CATEGORY);
        $time_span = $request-> input(NarouConst::SELECT_CREATE_TIMESPAN);
        $point_num = $request-> input(NarouConst::INPUT_POINT);
        $unique_num = $request-> input(NarouConst::INPUT_UNIQUE);

        $tables = array();
        $point_flg = false;
        if ($point_num) {
            $point_flg = true;
            $point_from = $point_num[NarouConst::INPUT_POINT_FROM];
            $point_to = $point_num[NarouConst::INPUT_POINT_TO];
            array_push($tables, NarouConst::TBL_POINT);
        };
        $unique_flg = false;
        if ($unique_num) {
            $unique_flg = true;
            $unique_from = $unique_num[NarouConst::INPUT_UNIQUE_FROM];
            $unique_to = $unique_num[NarouConst::INPUT_UNIQUE_TO];
            array_push($tables, NarouConst::TBL_UNIQUE);
        }



        return [

        ];
    }

    /**
     * create mark rank
     * 
     * @param  Request $request 検索条件
     * @return Collection
     */
    public function createMarkWRank(Request $request): Collection
    {
        return $this->mark_repository->createMarkWRank($request);
    }
}