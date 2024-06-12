<?php

namespace App\Http\Controllers;

use App\Consts\NarouConst;
use App\Http\Requests\DetailPostRequest;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Calc;
use App\Models\Ma;
use App\Models\Mark;
use App\Models\Point;
use App\Models\Unique;
use App\Models\UpdateFrequency;

class DetailController extends Controller
{
     /**
     * Display a listing of the resource.
     * 
     * @param DetailPostRequest $request
     */
    public function show(DetailPostRequest $request){
        // デバッグログを追加
        Log::info($request['ncode']);
        Log::info('DetailController@showが呼び出されました');
        $result = $this->generateDetailWork($request);
        Log::debug($result);
        
        $gragh_data = $this->getGraghData($request);

        return view('detail', ['result' => $result, 'gragh_data' => $gragh_data]);
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
     * 期間の配列を取得
     * 
     * @return array
     */
    function timeSpan():array
    {
        $date_array = $this->getDateList();

        $time_span_w = [];
        for($i=1; $i<2; $i++){
            array_push($time_span_w, $date_array[count($date_array) - $i]);
        }
        $time_span_m = [];
        for($i=1; $i<6; $i++){
            array_push($time_span_m, $date_array[count($date_array) - $i]);
        }
        $time_span_h = [];
        for($i=1; $i<27; $i++){
            array_push($time_span_h, $date_array[count($date_array) - $i]);
        }
        $time_span_y = [];
        for($i=1; $i<53; $i++){
            array_push($time_span_y, $date_array[count($date_array) - $i]);
        }
        $time_span_all = [];
        for($i=1; $i<count($date_array); $i++){
            array_push($time_span_all, $date_array[count($date_array) - $i]);
        }

        return [
            'weekly'  => $time_span_w,
            'monthly' => $time_span_m,
            'half'    => $time_span_h,
            'yearly'  => $time_span_y,
            'all'     => $time_span_all,
        ];
    }

    /**
     * markﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
     * 
     * @param  DetailPostRequest $request
     * @return ?array
     */
    public function calcRankMark(DetailPostRequest $request): ?array
    {
        Log::info('calcRankMarkが呼び出されました');
        $table = new Mark;
        return $table->calcRank($request);
    }

    /**
     * pointﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
     * 
     * @param  DetailPostRequest $request
     * @return ?array
     */
    public function calcRankPoint(DetailPostRequest $request): ?array
    {
        Log::info('calcRankPointが呼び出されました');
        $table = new Point;
        return $table->calcRank($request);
    }

    /**
     * uniqueﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
     * 
     * @param  DetailPostRequest $request
     * @return ?array
     */
    public function calcRankUnique(DetailPostRequest $request): ?array
    {
        Log::info('calcRankUniqueが呼び出されました');
        $table = new Unique;
        return $table->calcRank($request);
    }

    /**
     * update_frequencyﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
     * 
     * @param  DetailPostRequest $request
     * @return ?array
     */
    public function calcRankUF(DetailPostRequest $request): ?array
    {
        Log::info('calcRankUFが呼び出されました');
        $table = new UpdateFrequency;
        return $table->calcRank($request);
    }

    /**
     * calcﾃｰﾌﾞﾙの、引数で指定した期間のランキング位置確認用関数
     * 
     * @param  DetailPostRequest $request
     * @return ?array
     */
    public function calcRankCalc(DetailPostRequest $request): ?array
    {
        Log::info('calcRankCalcが呼び出されました');
        $table = new Calc;
        return $table->calcRank($request);
    }

    /**
     * 作品詳細データ作成
     * 
     * @param  DetailPostRequest $request
     * @return array
     */
    function generateDetailWork(DetailPostRequest $request): array
    {
        Log::info('generateDetailWorkの処理開始');
        $ma = new Ma;
        Log::debug('$request[ncode]: '.$request['ncode']);
        $detail = $ma->getDetailWork($request);
        Log::info('getDetailWorkの処理終了');
        Log::info('getDetailWork取得結果: '.$detail);
        
        //Detailﾍﾟｰｼﾞﾍｯﾀﾞｰ部分表示項目
        $title = $detail['title'];
        $writer = $detail['writer'];
        $general_all_no = $detail['general_all_no'];
        $update_fre = $detail['mean_uf'];
                
        Log::info('Detailﾍﾟｰｼﾞﾍｯﾀﾞｰ部分表示項目 取得完了');        
        
        $time_spans = $this -> timeSpan();
        Log::info('timeSpan 処理完了');
        $time_temp = $time_spans['weekly'];
        
        $calc_rank_un = $this -> calcRankUnique($request);
        $rank_un_w = $calc_rank_un['weekly'];
        $rank_un_m = $calc_rank_un['monthly'];
        $rank_un_y = $calc_rank_un['yearly'];
        $rank_un_a = $calc_rank_un['all'];
        Log::info('calcRankUnique 処理完了');
        
        $calc_rank_po = $this -> calcRankPoint($request);
        $rank_po_w = $calc_rank_po['weekly'];
        $rank_po_m = $calc_rank_po['monthly'];
        $rank_po_y = $calc_rank_po['yearly'];
        $rank_po_a = $calc_rank_po['all'];
        Log::info('calcRankPoint 処理完了');
        
        $calc_rank_mk = $this -> calcRankMark($request);
        $rank_mk_w = $calc_rank_mk['weekly'];
        $rank_mk_m = $calc_rank_mk['monthly'];
        $rank_mk_y = $calc_rank_mk['yearly'];
        $rank_mk_a = $calc_rank_mk['all'];
        Log::info('calcRankMark 処理完了');
        
        $calc_rank_c = $this -> calcRankCalc($request);
        $rank_c_w =  $calc_rank_c['weekly'];
        $rank_c_m =  $calc_rank_c['monthly'];
        $rank_c_y =  $calc_rank_c['yearly'];
        $rank_c_a =  $calc_rank_c['all'];
        Log::info('calcRankCalc 処理完了');
        
        $update_temp01 = $time_temp[0];
        $update_temp02 = explode(' ', $update_temp01);
        $update_temp03 = explode('-', $update_temp02[0]);
        $last_update = $update_temp03[0].'年'.$update_temp03[1].'月'.$update_temp03[2].'日';
        Log::info('last-update 処理完了');
        
        if($detail['mean_uf'] < NarouConst::FREQUENCY_VALUE_1TIMEPERYEAR){
            $update_fre = '１年に１回未満';
        }else if($detail['mean_uf'] < NarouConst::FREQUENCY_VALUE_1TIMEPERHALF){
            $update_fre = '１年に１回以上半期に１回未満';
        }else if($detail['mean_uf'] < NarouConst::FREQUENCY_VALUE_1TIMEPERMONTH){
            $update_fre = '半期に１回以上月に１回未満';
        }else if($detail['mean_uf'] < NarouConst::FREQUENCY_VALUE_1TIMEPERWEEK){
            $update_fre = '月に１回以上週に１回未満';
        }else if($detail['mean_uf'] < NarouConst::FREQUENCY_VALUE_1TIMEPERDAY){
            $update_fre = '週に１回以上日に１回未満';
        }else{
            $update_fre = '日に１回以上';
        }
        Log::info('generateDetailWork 処理完了');

        return [
            'introduction' => [
                'title'            => $title,
                'writer'           => $writer,
                'general_all_no'   => $general_all_no,
                'update_frequency' => $update_fre,
                'last_update'      => $last_update,
            ],
            'rank_mark' => [
                'weekly'  => $rank_mk_w,
                'monthly' => $rank_mk_m,
                'yearly'  => $rank_mk_y,
                'all'     => $rank_mk_a,
            ],
            'rank_calc' => [
                'weekly'  => $rank_c_w,
                'monthly' => $rank_c_m,
                'yearly'  => $rank_c_y,
                'all'     => $rank_c_a,
            ],
            'rank_point' => [
                'weekly'  => $rank_po_w,
                'monthly' => $rank_po_m,
                'yearly'  => $rank_po_y,
                'all'     => $rank_po_a,
            ],
            'rank_unique' => [
                'weekly'  => $rank_un_w,
                'monthly' => $rank_un_m,
                'yearly'  => $rank_un_y,
                'all'     => $rank_un_a,
            ],
        ];
    }

    /**
     * グラフ用データ作成
     * 
     * @param  DetailPostRequest $request
     * @return JsonResponse
     */
    public function getGraghData(DetailPostRequest $request): JsonResponse
    {
        $time_spans_for_g = [];
        $point_for_g = [];
        $unique_for_g = [];
        $mark_for_g = [];
        $calc_for_g = [];
    
        //期間別の実日付のリストを作成
        $time_spans = $this->timeSpan();
        $time_spans_all = $time_spans['all'];
        sort($time_spans_all);
    
        //グラフ描画用ﾃﾞｰﾀ処理開始
        $point = new Point;
        $unique = new Unique;
        $mark = new Mark;
        $calc = new Calc;

        $point_a = $point->getRankData()->where('ncode', $request['ncode'])->first();
        Log::info('point_a');
        Log::debug($point_a);
        $unique_a = $unique->getRankData()->where('ncode', $request['ncode'])->first();
        $mark_a = $mark->getRankData()->where('ncode', $request['ncode'])->first();
        $calc_a = $calc->getRankData()->where('ncode', $request['ncode'])->first();
    
        //グラフ描画用ﾃﾞｰﾀｾｯﾄ取得完了
        foreach ($time_spans_all as $time) {
            $t_temp = explode(' ', $time);
            $t = $t_temp[0];
            $time_spans_for_g[] = $t;
            $point_for_g[] = $point_a[$time];
            $unique_for_g[] = $unique_a[$time];
            $mark_for_g[] = $mark_a[$time];
            $calc_for_g[] = $calc_a[$time];
        }
        Log::info('mark_for_g');
        Log::debug($mark_for_g); 
    
        return response()->json([
            'time_spans_for_g' => $time_spans_for_g,
            'mark_for_g'       => $mark_for_g,
            'calc_for_g'       => $calc_for_g,
            'point_for_g'      => $point_for_g,
            'unique_for_g'     => $unique_for_g,
        ]);
    }

}
