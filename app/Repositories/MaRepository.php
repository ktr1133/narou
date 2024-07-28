<?php

namespace App\Repositories;

use App\Consts\NarouConst;
use App\Exceptions\QueryException;
use App\Http\Requests\CreatePostRequest;
use App\Models\Ma;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Log;

/**
 * Maレポジトリ
 * 
 */
class MaRepository extends Repository
{
    /**
     * 作者別ランキング作成
     * 
     * @param  CreatePostRequest $request
     * @return Collection
     */
    public function getWRankingResult(CreatePostRequest $request): Collection
    {
        $ma = new Ma();
        $query = $ma->query();

        //ﾘｸｴｽﾄﾃﾞｰﾀの整理
        $cate = $request -> input(NarouConst::SELECT_CREATE_CATEGORY);
        $time_span = $request -> input(NarouConst::SELECT_CREATE_TIMESPAN);
        $point_num = $request->input(NarouConst::INPUT_POINT);
        $unique_num = $request->input(NarouConst::INPUT_UNIQUE);

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

        //期間に関するﾘｸｴｽﾄ情報から使用カラムの特定
        $latest_date_column_name = $ma->getLatestDate();
        $time_span_column_point  = null;
        $time_span_column_unique  = null;
        $time_span_column_mark  = null;
        $time_span_column_calc  = null;
        switch ($time_span){
            case NarouConst::TIME_SPAN_WEEKLY:
                $time_span_column_point  = 'point.'.$latest_date_column_name;
                $time_span_column_unique  = 'unique.'.$latest_date_column_name;
                $time_span_column_mark  = 'mark.'.$latest_date_column_name;
                $time_span_column_calc  = 'calc.'.$latest_date_column_name;
                break;
            case NarouConst::TIME_SPAN_MONTHLY:
                $time_span_column_point  = 'point.sum_monthly';
                $time_span_column_unique  = 'unique.sum_monthly';
                $time_span_column_mark  = 'mark.mean_monthly';
                $time_span_column_calc  = 'calc.mean_monthly';
                break;
            case NarouConst::TIME_SPAN_HALF:
                $time_span_column_point  = 'point.sum_half';
                $time_span_column_unique  = 'unique.sum_half';
                $time_span_column_mark  = 'mark.mean_half';
                $time_span_column_calc  = 'calc.mean_half';
                break;
            case NarouConst::TIME_SPAN_YEARLY:
                $time_span_column_point  = 'point.sum_yearly';
                $time_span_column_unique  = 'unique.sum_yearly';
                $time_span_column_mark  = 'mark.mean_yearly';
                $time_span_column_calc  = 'calc.mean_yearly';
                break;
            case NarouConst::TIME_SPAN_ALL:
                $time_span_column_point  = 'point.sum_all';
                $time_span_column_unique  = 'unique.sum_all';
                $time_span_column_mark  = 'mark.mean';
                $time_span_column_calc  = 'calc.mean';
                break;
            default:
                $time_span_column_point  = 'point.sum_all';
                $time_span_column_unique  = 'unique.sum_all';
                $time_span_column_mark  = 'mark.mean';
                $time_span_column_calc  = 'calc.mean';
            break;
        }
    
        //sqlで使用するﾃｰﾌﾞﾙの指定
        $tables_set = array();
        Log::info('cate: '.$cate);
        if ($cate===NarouConst::MARK) {
            $tables_set[] = [
                'table' => NarouConst::TBL_MARK,
                'column' => $time_span_column_mark,
            ];
        } else if ($cate===NarouConst::CALC) {
            $tables_set[] = [
                'table' => NarouConst::TBL_CALC,
                'column' => $time_span_column_calc,
            ];
        } else if ($cate===NarouConst::POINT) {
            $tables_set[] = [
                'table' => NarouConst::TBL_POINT,
                'column' => $time_span_column_point,
            ];            
        } else if ($cate===NarouConst::TBL_UNIQUE) {
            $tables_set[] = [
                'table' => NarouConst::TBL_UNIQUE,
                'column' => $time_span_column_unique,
            ];            
        }
        if($point_flg) {
            $tables_set[] = [
                'table' => NarouConst::TBL_POINT,
                'column' => $time_span_column_point,
            ];
        }
        if ($unique_flg) {
            $tables_set[] = [
                'table' => NarouConst::TBL_UNIQUE,
                'column' => $time_span_column_unique,
            ];
        }

        //並べ替えの対象となるカラムの特定
        if ($cate===NarouConst::MARK) {
            $main_column = $time_span_column_mark;
        } else if ($cate===NarouConst::CALC) {
            $main_column = $time_span_column_calc;
        } else if ($cate===NarouConst::POINT) {
            $main_column = $time_span_column_point;
        } else if ($cate===NarouConst::UNIQUE) {
            $main_column = $time_span_column_unique;
        }

        //ベースクエリ
        $query =  $query->select(
                        'ma.ncode',
                        'ma.writer',
                        'ma.title',
                        'ma.general_all_no',
                    )
                    ->selectRaw(
                        'Count(ma.ncode) as count'
                    );

        //利用するﾃｰﾌﾞﾙの紐づけ
        if(!empty($tables_set)){
            foreach($tables_set as $ele){
                $query->leftjoin(
                    $ele['table'],
                    'ma.ncode',
                    '=',
                    $ele['table'].'.ncode');
            };
        }

        //ﾎﾟｲﾝﾄに関する条件設定
        if ($point_flg) {
            if ($point_from) {
                $query->addSelect("$time_span_column_point as point_sum")
                    ->where($time_span_column_point, '>=', $point_from)
                    ->groupBy($time_span_column_point);
            };
            if ($point_to) {
                $query->addSelect("$time_span_column_point as point_sum")
                    ->where($time_span_column_point, '<=', $point_to)
                    ->groupBy($time_span_column_point);
            }
        }

        //ﾕﾆｰｸﾕｰｻﾞ数に関する条件設定
        if ($unique_flg) {
            if ($unique_from) {
                $query->addSelect("$time_span_column_unique as unique_sum")
                ->where($time_span_column_unique, '>=', $unique_from)
                ->groupBy($time_span_column_unique);
            };
            if ($unique_to) {
                $query->addSelect("$time_span_column_unique as unique_sum")
                ->where($time_span_column_unique, '<=', $unique_to)
                ->groupBy($time_span_column_unique);
            }
        }

        //作者単位で集計
        if ($cate===NarouConst::MARK) {
            $result = $query
                ->where($main_column, '>', 0)
                ->groupBy(
                    'ma.ncode',
                    'ma.writer',
                    'ma.title',
                    'ma.general_all_no',
                    $main_column,
                    )
                ->selectRaw("ROUND(SUM($main_column)/Count(ma.ncode),3) as ave")
                ->orderBy("ave")
                ->limit(50)
                ->get();
        } else if ($cate===NarouConst::CALC) {
            $result = $query
                ->where($main_column, '>', 0)
                ->groupBy(
                    'ma.ncode',
                    'ma.writer',
                    'ma.title',
                    'ma.general_all_no',
                    $main_column,
                    )
                ->selectRaw("ROUND(SUM($main_column)/Count(ma.ncode),3) as ave")
                ->orderBy("ave", 'desc')
                ->limit(50)
                ->get();
        } else if ($cate===NarouConst::POINT) {
            $result = $query
                ->groupBy(
                    'ma.ncode',
                    'ma.writer',
                    'ma.title',
                    'ma.general_all_no',
                    $main_column,
                    )
                ->selectRaw("SUM($main_column) as ave")
                ->orderBy("ave", 'desc')
                ->limit(50)
                ->get();
        } else if ($cate===NarouConst::UNIQUE) {
            $result = $query
                ->groupBy(
                    'ma.ncode',
                    'ma.writer',
                    'ma.title',
                    'ma.general_all_no',
                    $main_column,
                    )
                ->selectRaw("SUM($main_column) as ave")
                ->orderBy("ave", 'desc')
                ->limit(50)
                ->get();
        } else if ($cate===NarouConst::REGISTERED_TITLES) {
            $result = $query
                ->groupBy(
                    'ma.ncode',
                    'ma.writer',
                    'ma.title',
                    'ma.general_all_no',
                    $main_column,
                    )
                ->orderBy('count', 'desc')
                ->limit(50)
                ->get();
        } else {
            throw new QueryException('No results found for your selection.');
        }

        return $result;
    }
}





