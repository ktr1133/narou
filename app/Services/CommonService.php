<?php

namespace App\Services;

use App\Consts\NarouConst;
use App\Models\Ma;
use App\Http\Requests\CreatePostRequest as Request;
use Illuminate\Support\Facades\Log;

/**
 * 共通パーツサービスクラス
 */
class CommonService
{
    /**
     * コンストラクタ
     */
    public function __construct(
        protected Request $request
    )
    {
    }

    /**
     * 検索条件用語変換
     * 
     * @param Request $request
     * @return array
     */
    public function getSelection(Request $request)
    {
        Log::info('Start getSelectData');
        $cate = $request -> input(NarouConst::SELECT_CREATE_CATEGORY);
        $time_span = $request -> input(NarouConst::SELECT_CREATE_TIMESPAN);
        $gan_num = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO);
        $gan_from = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO_FROM);
        $gan_to = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO_TO);
        $point_num = $request -> input(NarouConst::INPUT_POINT);
        $point_from = $request -> input(NarouConst::INPUT_POINT)[NarouConst::INPUT_POINT_FROM];
        $point_to = $request -> input(NarouConst::INPUT_POINT)[NarouConst::INPUT_POINT_TO];
        $unique_num = $request -> input(NarouConst::INPUT_UNIQUE);
        $unique_from = $request -> input(NarouConst::INPUT_UNIQUE)[NarouConst::INPUT_UNIQUE_FROM];
        $unique_to = $request -> input(NarouConst::INPUT_UNIQUE)[NarouConst::INPUT_UNIQUE_TO];
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
            if ($time_span ===NarouConst::TIME_SPAN_WEEKLY) {
                $time_span_tex = NarouConst::WEEKLY_TEXT;
            } else if ($time_span ===NarouConst::TIME_SPAN_MONTHLY) {
                $time_span_tex = NarouConst::MONTHLY_TEXT;
            } else if ($time_span ===NarouConst::TIME_SPAN_HALF) {
                $time_span_tex = NarouConst::HALF_TEXT;
            } else if ($time_span ===NarouConst::TIME_SPAN_YEARLY) {
                $time_span_tex = NarouConst::YEARLY_TEXT;
            } else if ($time_span ===NarouConst::TIME_SPAN_ALL) {
                $time_span_tex = NarouConst::ALL_TEXT;
            } else {
                $time_span_tex = null;
            }
        }
        //総話数表示文
        $gan_from_tex = '';
        $gan_to_tex = '';
        if(!empty($gan_num)){
            if ($gan_from) {
                $gan_from_tex =$gan_from.'話';
            } else {
                $gan_from_tex = '';
            }
            if ($gan_to) {
                $gan_to_tex = $gan_to.'話';
            } else {
                $gan_to_tex = '';
            }
        }
        //ポイント数表示文
        $point_from_tex = '';
        $point_to_tex = '';
        Log::info('point_num: ', ['point_num' => $point_num]);
        Log::info('point_from: '.$point_from);
        if (!empty($point_num)) {
            if ($point_from) {
                $point_from_tex = $point_from;
            } else {
                $point_from_tex = NarouConst::NO_INPUT_DATA;
            }
            if ($point_to) {
                $point_to_tex = $point_to;
            } else {
                $point_to_tex = NarouConst::NO_INPUT_DATA;
            }
        }
        //ユニークユーザ数表示文
        $unique_from_tex = '';
        $unique_to_tex = '';
        Log::info('unique_num: ', ['unique_num' => $unique_num]);
        if (!empty($unique_num)) {
            if ($unique_from) {
                $unique_from_tex =$unique_from;
            } else {
                $unique_from_tex = NarouConst::NO_INPUT_DATA;
            }
            if ($unique_to) {
                $unique_to_tex = $unique_to;
            } else {
                $unique_to_tex = NarouConst::NO_INPUT_DATA;
            }
        } else {
            $unique_from_tex = NarouConst::NO_INPUT_DATA;
        }
        //平均更新頻度表示文
        $frequency = '';
        if ($uf) {
            if ($uf ===NarouConst::FREQUENCY_1TIMEPERMONTH) {
                $frequency = NarouConst::FREQUENCY_TEXT_1TIMEPERMONTH;
            } else if ($uf ===NarouConst::FREQUENCY_1TIMEPERWEEK) {
                $frequency = NarouConst::FREQUENCY_TEXT_1TIMEPERWEEK;
            } else if ($uf ===NarouConst::FREQUENCY_1TIMEPERDAY) {
                $frequency = NarouConst::FREQUENCY_TEXT_1TIMEPERDAY;
            } else {
                $frequency = NarouConst::NO_SELECT_DATA;
            }
        } else {
            $frequency = NarouConst::NO_SELECT_DATA;
        }
        return [
            NarouConst::SELECT_CREATE_CATEGORY      => $cate_tex,
            NarouConst::SELECT_CREATE_TIMESPAN      => $time_span_tex,
            NarouConst::INPUT_GENERAL_ALL_NO_FROM   => $gan_from_tex,
            NarouConst::INPUT_GENERAL_ALL_NO_TO     => $gan_to_tex,
            NarouConst::INPUT_POINT_FROM            => $point_from_tex,
            NarouConst::INPUT_POINT_TO              => $point_to_tex,
            NarouConst::INPUT_UNIQUE_FROM           => $unique_from_tex,
            NarouConst::INPUT_UNIQUE_TO             => $unique_to_tex,
            NarouConst::SELECT_CREATE_FREQUENCY     => $frequency,
            ];
    }

    /**
     * 期間カラム選択
     * 
     * @param Request $request 検索条件
     * @return array
     */
    public function getTimeSpanColumn(Request $request): array
    {
        $ma = new Ma();
        // 期間
        $latest_date_column_name = $ma->getLatestDate();
        switch ($request['time_span']) {
            // 週間
            case NarouConst::TIME_SPAN_WEEKLY:
                $time_span_column_point  = 'point.'.$latest_date_column_name;
                $time_span_column_unique  = 'unique.'.$latest_date_column_name;
                $time_span_column_mark  = 'mark.'.$latest_date_column_name;
                $time_span_column_calc  = 'calc.'.$latest_date_column_name;
                break;
            // 月間
            case NarouConst::TIME_SPAN_MONTHLY:
                $time_span_column_point  = 'point.sum_monthly' ?? null;
                $time_span_column_unique  = 'unique.sum_monthly' ?? null;
                $time_span_column_mark  = 'mark.mean_monthly' ?? null;
                $time_span_column_calc  = 'calc.mean_monthly' ?? null;
                break;
            // 半年
            case NarouConst::TIME_SPAN_HALF:
                $time_span_column_point  = 'point.sum_half' ?? null;
                $time_span_column_unique  = 'unique.sum_half' ?? null;
                $time_span_column_mark  = 'mark.mean_half' ?? null;
                $time_span_column_calc  = 'calc.mean_half' ?? null;
                break;
            // 年間
            case NarouConst::TIME_SPAN_YEARLY:
                $time_span_column_point  = 'point.sum_yearly' ?? null;
                $time_span_column_unique  = 'unique.sum_yearly' ?? null;
                $time_span_column_mark  = 'mark.mean_yearly' ?? null;
                $time_span_column_calc  = 'calc.mean_yearly' ?? null;
                break;
            // 累計
            case NarouConst::TIME_SPAN_ALL:
                $time_span_column_point  = 'point.sum_all' ?? null;
                $time_span_column_unique  = 'unique.sum_all' ?? null;
                $time_span_column_mark  = 'mark.mean' ?? null;
                $time_span_column_calc  = 'calc.mean' ?? null;
                break;
        }

        return[
            'time_span_column_point'  => $time_span_column_point,
            'time_span_column_unique' => $time_span_column_unique,
            'time_span_column_mark'   => $time_span_column_mark,
            'time_span_column_calc'   => $time_span_column_calc,
        ];
    }
    
}