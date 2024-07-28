<?php

namespace App\Http\Controllers;

use App\Consts\NarouConst;
use App\Exceptions\QueryException;
use App\Models\Mark;
use DateTime;
use App\Http\Requests\CreatePostRequest;
use App\Services\CommonService;
use App\Models\Ma;
use Illuminate\Support\Facades\Log;


class ResultWController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param CreatePostRequest $request
     */
    public function show(CreatePostRequest $request){
        // デバッグログを追加
        Log::info('ResultWController@showが呼び出されました');
        $select_data = $this->getSelectData($request);
        Log::info('$this->getSelectData($request)の処理が終了', ['select_data' => $select_data]);
        Log::info('$this->generateRanking($request)の処理を開始');
        $result = $this->generateWRanking($request);
        Log::info('$this->generateRanking($request)の処理が終了');
        // 結果が0件の場合は例外を投げる
        if ($result->isEmpty()) {
            throw new QueryException('No results found for your selection.');
        }
        return view('result_w', ['result' => $result, 'select_data' => $select_data]);
    }

    /**
     * DBのmarkテーブルからカラム名が日付型のものをすべて取得
     */
    public function getDateList()
    {
        //日付のカラムを持つﾃｰﾌﾞﾙの１つから日付の配列を昇順に並べ替えて取得
        $date_columns = [];
        $mark = new Mark;
        $mark_column_names = $mark->getColumnNames();
        foreach ($mark_column_names as $column) {
            if (strpos($column, '-') !== false) {
                $date_columns[] = $column;
            }
        }
        return $date_columns;
    }

    /**
     * get date lists. weekly or monthly or half year or yearly or all of terms
     */
    public function getPeriod(CreatePostRequest $request)
    {
        $validated_request = $request->validated();
        if(!empty($validated_request)){
            $range = $validated_request -> input('r_time_span');
            // 日付配列を取得
            $date_list = $this -> getDateList();
            // 現在の日付を取得
            $current_date = new DateTime();
            // 抽出する期間の開始日を設定
            $start_date = new DateTime();
            switch ($range) {
                case NarouConst::TIME_SPAN_WEEKLY:
                    $start_date->modify('-1 week');
                    break;
                case NarouConst::TIME_SPAN_MONTHLY:
                    $start_date->modify('-1 month');
                    break;
                case NarouConst::TIME_SPAN_HALF:
                    $start_date->modify('-6 months');
                    break;
                case NarouConst::TIME_SPAN_YEARLY:
                    $start_date->modify('-1 year');
                    break;
                case NarouConst::TIME_SPAN_ALL:
                    break;
                default:
                    // デフォルトは直近の1週間
                    $start_date->modify('-1 week');
                    break;        
            }
            // 開始日以降の日付を持つ配列を生成
            $filtered_array = array_filter($date_list, function($date) use ($start_date, $current_date) {
                $date_obj = new DateTime($date);
                $date_obj >= $start_date && $date_obj <= $current_date;
            });
        }else{
            $date_list = $this -> getDateList();
            $current_date = new DateTime();
            $start_date = new DateTime();
            $start_date->modify('-1 week');
            $filtered_array = array_filter($date_list, function($date) use ($start_date, $current_date) {
                $date_obj = new DateTime($date);
                $date_obj >= $start_date && $date_obj <= $current_date;
            });
        }
        return $filtered_array;
    }
}
