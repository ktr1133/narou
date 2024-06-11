<?php

namespace App\Http\Controllers;

use App\Consts\NarouConst;
use App\Models\Mark;
use DateTime;
use App\Http\Requests\CreatePostRequest;
use App\Models\Ma;
use Illuminate\Support\Facades\Log;


class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param CreatePostRequest $request
     */
    public function show(CreatePostRequest $request){
        // デバッグログを追加
        Log::info('ResultController@showが呼び出されました');
        $select_data = $this->getSelectData($request);
        Log::info('$this->getSelectData($request)の処理が終了');
        Log::info('$this->generateRanking($request)の処理を開始');
        $result = $this->generateRanking($request);
        Log::info('$this->generateRanking($request)の処理が終了');
        return view('result', ['result' => $result, 'select_data' => $select_data]);
    }

    /**
     * Request Data change to Words.
     * 
     * @param CreatePostRequest $request
     * @return array
     */
    public function getSelectData(CreatePostRequest $request): array
    {
        Log::info('Start getSelectData');
        $cate = $request -> input(NarouConst::SELECT_CREATE_CATEGORY);
        $time_span = $request -> input(NarouConst::SELECT_CREATE_TIMESPAN);
        $gan_num = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO);
        $gan_from = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO_FROM);
        $gan_to = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO_TO);
        $point_from = $request -> input(NarouConst::INPUT_POINT_FROM);
        $point_to = $request -> input(NarouConst::INPUT_POINT_TO);
        $unique_from = $request -> input(NarouConst::INPUT_UNIQUE_FROM);
        $unique_to = $request -> input(NarouConst::INPUT_UNIQUE_TO);
        $uf = $request -> input(NarouConst::SELECT_CREATE_FREQUENCY);
        //種別表示文
        if(!empty($cate)){
            if($cate===NarouConst::MARK){
                $cate_tex = NarouConst::CATEGORY_MARK;
            }else if($cate ===NarouConst::CALC){
                $cate_tex = NarouConst::CATEGORY_CALC;
            }else if($cate ===NarouConst::POINT){
                $cate_tex = NarouConst::CATEGORY_POINT;
            }else if($cate ===NarouConst::UNIQUE){
                $cate_tex = NarouConst::CATEGORY_UNIQUE;
            }else{
                $cate_tex = NarouConst::CATEGORY_ERROR;
            }
        }
        //期間表示文
        $time_span_tex = '';
        if(!empty($time_span)){
            if($time_span ===NarouConst::TIME_SPAN_WEEKLY){
                $time_span_tex = NarouConst::WEEKLY_TEXT;
            }else if($time_span ===NarouConst::TIME_SPAN_MONTHLY){
                $time_span_tex = NarouConst::MONTHLY_TEXT;
            }else if($time_span ===NarouConst::TIME_SPAN_HALF){
                $time_span_tex = NarouConst::HALF_TEXT;
            }else if($time_span ===NarouConst::TIME_SPAN_YEARLY){
                $time_span_tex = NarouConst::YEARLY_TEXT;
            }else if($time_span ===NarouConst::TIME_SPAN_ALL){
                $time_span_tex = NarouConst::ALL_TEXT;
            }else{
                $time_span_tex = null;
            }
        }
        //総話数表示文
        $gan_from_tex = '';
        $gan_to_tex = '';
        if(!empty($gan_num)){
            if(!empty($gan_num)){
                $gan_from_tex =$gan_from.'話';
            }else{
                $gan_from_tex = '';
            }
            if(!empty($gan_num)){
                $gan_to_tex = $gan_to.'話';
            }else{
                $gan_to_tex = '';
            }
        }
        //ポイント数表示文
        $point_from_tex = '';
        $point_to_tex = '';
        if(!empty($point_num)){
            if(!empty($point_num)){
                $point_from_tex =$point_from.'話';
            }else{
                $point_from_tex = '';
            }
            if(!empty($point_num)){
                $point_to_tex = $point_to.'話';
            }else{
                $point_to_tex = '';
            }
        }
        //ユニークユーザ数表示文
        $unique_from_tex = '';
        $unique_to_tex = '';
        if(!empty($unique_num)){
            if(!empty($unique_num)){
                $unique_from_tex =$unique_from.'話';
            }else{
                $unique_from_tex = '';
            }
            if(!empty($unique_num)){
                $unique_to_tex = $unique_to.'話';
            }else{
                $unique_to_tex = '';
            }
        }
        //平均更新頻度表示文
        $frequency = '';
        if(!empty($uf)){
            if($uf ===NarouConst::FREQUENCY_1TIMEPERMONTH){
                $frequency = NarouConst::FREQUENCY_TEXT_1TIMEPERMONTH;
            }else if($uf ===NarouConst::FREQUENCY_1TIMEPERWEEK){
                $frequency = NarouConst::FREQUENCY_TEXT_1TIMEPERWEEK;
            }else if($uf ===NarouConst::FREQUENCY_1TIMEPERDAY){
                $frequency = NarouConst::FREQUENCY_TEXT_1TIMEPERDAY;
            }else{
                $frequency = '';
            }
        }
        return [
            NarouConst::SELECT_CREATE_CATEGORY => $cate_tex,
            NarouConst::SELECT_CREATE_TIMESPAN => $time_span_tex,
            NarouConst::INPUT_GENERAL_ALL_NO_FROM => $gan_from_tex,
            NarouConst::INPUT_GENERAL_ALL_NO_TO => $gan_to_tex,
            NarouConst::INPUT_POINT_FROM => $point_from_tex,
            NarouConst::INPUT_POINT_TO => $point_to_tex,
            NarouConst::INPUT_UNIQUE_FROM => $unique_from_tex,
            NarouConst::INPUT_UNIQUE_TO => $unique_to_tex,
            NarouConst::SELECT_CREATE_FREQUENCY => $frequency,
            ];
    }

    /**
     * DBのmarkテーブルからカラム名が日付型のものをすべて取得
     */
    public function getDateList()
    {
        //日付のカラムを持つﾃｰﾌﾞﾙの１つから日付の配列を昇順に並べ替えて取得
        $dateColumns = [];
        $mark = new Mark;
        $markColumnNames = $mark->getColumnNames();
        foreach ($markColumnNames as $column) {
            if (strpos($column, '-') !== false) {
                $dateColumns[] = $column;
            }
        }
        return $dateColumns;
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
            $dateList = $this -> getDateList();
            // 現在の日付を取得
            $currentDate = new DateTime();
            // 抽出する期間の開始日を設定
            $startDate = new DateTime();
            switch ($range) {
                case NarouConst::TIME_SPAN_WEEKLY:
                    $startDate->modify('-1 week');
                    break;
                case NarouConst::TIME_SPAN_MONTHLY:
                    $startDate->modify('-1 month');
                    break;
                case NarouConst::TIME_SPAN_HALF:
                    $startDate->modify('-6 months');
                    break;
                case NarouConst::TIME_SPAN_YEARLY:
                    $startDate->modify('-1 year');
                    break;
                case NarouConst::TIME_SPAN_ALL:
                    break;
                default:
                    // デフォルトは直近の1週間
                    $startDate->modify('-1 week');
                    break;        
            }
            // 開始日以降の日付を持つ配列を生成
            $filteredArray = array_filter($dateList, function($date) use ($startDate, $currentDate) {
                $dateObj = new DateTime($date);
                $dateObj >= $startDate && $dateObj <= $currentDate;
            });
        }else{
            $dateList = $this -> getDateList();
            $currentDate = new DateTime();
            $startDate = new DateTime();
            $startDate->modify('-1 week');
            $filteredArray = array_filter($dateList, function($date) use ($startDate, $currentDate) {
                $dateObj = new DateTime($date);
                $dateObj >= $startDate && $dateObj <= $currentDate;
            });
        }
        return $filteredArray;
    }

    /**
     * ランキングデータ作成
     */
    function generateRanking(CreatePostRequest $request)
    {
        $ma = new Ma();
        return $ma->getRankingResult($request);
    }
}
