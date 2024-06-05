<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Consts\NarouConst;
use App\Models\Point;
use App\Models\Unique;
use App\Models\Mark;
use App\Models\Update_frequency;
use App\Models\Calc;


class Ma extends Model
{
    use HasFactory;
    protected $table = NarouConst::TBL_MA;
    protected $fillable = [
        'ncode',
        'title',
        'writer',
        'general_all_no',
        'general_lastup',
        'records',
        'mean_mk',
        'mean_uf',
        'mean_c',
        'sum_po',
        'sum_un'
    ];
    private $mean_monthly = 'mean_monthly';
    private $mean_half = 'mean_half';
    private $mean_yearly = 'mean_yearly';
    private $mean_all = 'mean';
    private $sum_montyly = 'sum_monthly';
    private $sum_half = 'sum_half';
    private $sum_yearly = 'sum_yearly';
    private $sum_all = 'sum_all';
    private $tableNames = [
        NarouConst::MARK              => NarouConst::TBL_MARK,
        NarouConst::CALC              => NarouConst::TBL_CALC,
        NarouConst::POINT             => NarouConst::TBL_POINT,
        NarouConst::UNIQUE            => NarouConst::TBL_POINT,
        NarouConst::REGISTERED_TITLES => NarouConst::TBL_MA
    ];

    //リレーションの定義
    public function points(){
        return $this ->hasOne(Point::class);
    }
    public function uniques(){
        return $this ->hasOne(Unique::class);
    }
    public function marks(){
        return $this ->hasOne(Mark::class);
    }
    public function updateFrequencies(){
        return $this ->hasOne(Update_frequency::class);
    }
    public function calcs(){
        return $this ->hasOne(Calc::class);
    }


    // トップページ用。ｶﾃｺﾞﾘ別に結合
    public function joinedByCategory(Builder $query, $cate)
    {
        // maテーブルとmark or calc($cate)テーブルを左側結合
        $query = $query ->query()
            ->leftJoin($cate, 'ma.ncode', '=', $cate.'.ncode')
            ->select('ma.*', $cate.'.*')
            ->whereNull($cate.'.ncode');
        return $query;

    }

    //日付のカラム名をすべて取得
    public function getDateColumns()
    {
        // markテーブルのカラム名を取得
        $columns = DB::getSchemaBuilder()->getColumnListing(NarouConst::TBL_MARK);

        // 日付であるカラム名を抽出
        $dateColumns = [];
        foreach ($columns as $column) {
            if (strpos($column, '-') !== false) {
                $dateColumns[] = $column;
            }
        }
        return $dateColumns;
    }

    // 最新の日付のカラム名を取得
    public function getLatestDate()
    {
        $dateColumns = $this -> getDateColumns();

        return max($dateColumns);
    }

    //トップページのランキングデータ作成
    public function generateTopRanking(Request $request)
    {
        $timeSpan = $request->input(NarouConst::SELECT_TOP_TIMESPAN, NarouConst::TIME_SPAN_WEEKLY);
        $cate = $request->input(NarouConst::SELECT_TOP_CATEGORY, NarouConst::MARK);
        $gan = $request->input(NarouConst::SELECT_TOP_GENERAL_ALL_NO, NarouConst::GAN_OVER100_UNDER300);
    
        $from = $this->getFromValue($gan);
        $to = $this->getToValue($gan);
    
        if ($cate === NarouConst::MARK){
            $result = $this->getTopRankingResult($timeSpan, $from, $to, NarouConst::TBL_MARK);
        } elseif ($cate === NarouConst::CALC) {
            $result = $this->getTopRankingResult($timeSpan, $from, $to, NarouConst::TBL_CALC);
        } else {
            $result = $this->getTopRankingResult($timeSpan, $from, $to, NarouConst::TBL_MARK);
        }
    
        return $result;
    }
    
    public function getFromValue($gan)
    {
        switch ($gan) {
            case NarouConst::GAN_OVER100_UNDER300:
                return 100;
            case NarouConst::GAN_OVER100_UNDER300:
                return 300;
            case NarouConst::GAN_OVER100_UNDER300:
                return 500;
            default:
                return 500;
        }
    }
    
    public function getToValue($gan)
    {
        return ($gan === NarouConst::GAN_OVER100_UNDER300) ? PHP_INT_MAX : PHP_INT_MAX;
    }
    
    public function getTopRankingResult($timeSpan, $from, $to, $table)
    {
        $limit=50;
        $latestDateColumnName = $this->getLatestDate();
        $ave = $latestDateColumnName;
        switch ($timeSpan) {
            case NarouConst::TIME_SPAN_WEEKLY:
                $column = $latestDateColumnName;
                $ave = $latestDateColumnName;
                $sum = $latestDateColumnName;
                break;
            case NarouConst::TIME_SPAN_MONTHLY:
                $column = $this -> mean_monthly;
                $ave = $this -> mean_monthly;
                $sum = $this -> sum_monthly;
                break;
            case NarouConst::TIME_SPAN_HALF:
                $column = $this -> mean_half;
                $ave = $this -> mean_half;
                $sum = $this -> sum_half;
                break;
            case NarouConst::TIME_SPAN_YEARLY:
                $column = $this -> mean_yearly;
                $ave = $this -> mean_yearly;
                $sum = $this -> sum_yearly;
                break;
            case NarouConst::TIME_SPAN_ALL:
                $column = $this -> mean_all;
                $ave = $this -> mean_all;
                $sum = $this -> sum_all;
                break;
            default:
                $column = $latestDateColumnName;
                $ave = $latestDateColumnName;
                $sum = $latestDateColumnName;
                break;
        }
        if($table === NarouConst::TBL_MARK){
            $result = $this->query()
            ->leftJoin($table, 'ma.ncode', '=', "$table.ncode")
            ->select('ma.*', "$table.$ave as ave")
            ->whereBetween('general_all_no', [$from, $to])
            ->where($column, '>', 0)
            ->orderBy($column)
            ->limit($limit)
            ->get();
        }elseif($table === NarouConst::TBL_CALC){
            $result = $this->query()
            ->leftJoin($table, 'ma.ncode', '=', "$table.ncode")
            ->select('ma.*', "$table.$ave as ave")
            ->whereBetween('general_all_no', [$from, $to])
            ->where($column, '>', 0)
            ->orderBy($column, 'desc')
            ->limit($limit)
            ->get();
        }elseif($table === NarouConst::TBL_POINT || NarouConst::TBL_POINT){
            $result = $this->query()
            ->leftJoin($table, 'ma.ncode', '=', "$table.ncode")
            ->select('ma.*', "$table.$sum as ave")
            ->whereBetween('general_all_no', [$from, $to])
            ->where($column, '>', 0)
            ->orderBy($column, 'desc')
            ->limit($limit)
            ->get();
        }
        return $result;
    }
    //ｸﾘｴｲﾄﾍﾟｰｼﾞのランキング作成
    public function generateRanking(Request $request)
    {
        $timeSpan = $request->input(NarouConst::SELECT_CREATE_TIMESPAN, NarouConst::TIME_SPAN_WEEKLY);
        $cate = $request->input(NarouConst::SELECT_CREATE_CATEGORY, NarouConst::MARK);
        $gan = $request->input(NarouConst::INPUT_GENERAL_ALL_NO);
        $gan_from = $gan[NarouConst::INPUT_GENERAL_ALL_NO_FROM];
        $gan_to = $gan[NarouConst::INPUT_GENERAL_ALL_NO_TO];
        $point = $request->input(NarouConst::INPUT_POINT);
        $point_from = $point[NarouConst::INPUT_POINT_FROM];
        $point_to = $point[NarouConst::INPUT_POINT_TO];
        $unique = $request->input(NarouConst::INPUT_UNIQUE);
        $unique_from = $unique[NarouConst::INPUT_UNIQUE_FROM];
        $unique_to = $unique[NarouConst::INPUT_UNIQUE_TO];
        $frequency = $request->input(NarouConst::TBL_UF);
    
        $num = 50;
        
        $result = new EloquentCollection();
        if ($cate === NarouConst::MARK){
            //$result = $this->getRankingResult_ASC($timeSpan, $from, $to, NarouConst::TBL_MARK);
        } elseif ($cate === NarouConst::CALC) {
            //$result = $this->getRankingResult_DSC($timeSpan, $from, $to, NarouConst::TBL_CALC);
        } elseif ($cate === NarouConst::POINT){
            //$result = $this->getRankingResult_DSC($timeSpan, $from, $to, NarouConst::TBL_POINT);
        } elseif ($cate === NarouConst::UNIQUE){
            //$result = $this->getRankingResult_DSC($timeSpan, $from, $to, NarouConst::TBL_POINT);
        }
        return $result;
    }

    //ｸﾘｴｲﾄﾍﾟｰｼﾞのﾗﾝｷﾝｸﾞ作成
    public function getRankingResult(Request $request)
    {
        //ﾘｸｴｽﾄﾃﾞｰﾀの整理
        $cate = $request -> input(NarouConst::SELECT_CREATE_CATEGORY);
        $time_span = $request -> input(NarouConst::SELECT_CREATE_TIMESPAN);
        $gan_num = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO);
        $point_num = $request->input(NarouConst::INPUT_POINT);
        $unique_num = $request->input(NarouConst::INPUT_UNIQUE);
        $uf = $request -> input(NarouConst::SELECT_CREATE_FREQUENCY);

        $gan_flg = false;
        if ($gan_num) {
            $gan_flg = true;
            $gan_from = $gan_num[NarouConst::INPUT_GENERAL_ALL_NO_FROM];
            $gan_to = $gan_num[NarouConst::INPUT_GENERAL_ALL_NO_TO];
        };
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
        $frequency_flg = false;
        if ($uf) {
            $frequency_flg = true;
            array_push($tables, NarouConst::TBL_UF);
        }

        //期間に関するﾘｸｴｽﾄ情報から使用カラムの特定
        $latest_date_column_name = $this->getLatestDate();
        $time_span_column_point  = null;
        $time_span_column_unique  = null;
        $time_span_column_mark  = null;
        $time_span_column_calc  = null;
        $time_span_column_uf  = null;
        switch ($time_span){
            case NarouConst::TIME_SPAN_WEEKLY:
                $time_span_column_point  = 'point.'.$latest_date_column_name;
                $time_span_column_unique  = 'unque.'.$latest_date_column_name;
                $time_span_column_mark  = 'mark.'.$latest_date_column_name;
                $time_span_column_calc  = 'calc.'.$latest_date_column_name;
                $time_span_column_uf  = 'update_frequency.'.$latest_date_column_name;
                $column = ($cate === NarouConst::MARK) ? $latest_date_column_name : $latest_date_column_name;
                break;
            case NarouConst::TIME_SPAN_MONTHLY:
                $time_span_column_point  = 'point.sum_monthly';
                $time_span_column_unique  = 'unque.sum_monthly';
                $time_span_column_mark  = 'mark.mean_monthly';
                $time_span_column_calc  = 'calc.mean_monthly';
                $time_span_column_uf  = 'update_frequency.mean_monthly';
                $column = ($cate === NarouConst::MARK||NarouConst::CALC) ? $this -> mean_monthly : $this -> sum_monthly;
                break;
            case NarouConst::TIME_SPAN_HALF:
                $time_span_column_point  = 'point.sum_half';
                $time_span_column_unique  = 'unque.sum_half';
                $time_span_column_mark  = 'mark.mean_half';
                $time_span_column_calc  = 'calc.mean_half';
                $time_span_column_uf  = 'update_frequency.mean_half';
                $column = ($cate === NarouConst::MARK||NarouConst::CALC) ? $this -> mean_half : $this -> sum_half;
                break;
            case NarouConst::TIME_SPAN_YEARLY:
                $time_span_column_point  = 'point.sum_yearly';
                $time_span_column_unique  = 'unque.sum_yearly';
                $time_span_column_mark  = 'mark.mean_yearly';
                $time_span_column_calc  = 'calc.mean_yearly';
                $time_span_column_uf  = 'update_frequency.mean_yearly';
                $column = ($cate === NarouConst::MARK||NarouConst::CALC) ? $this -> mean_yearly : $this -> sum_yearly;
                break;
            case NarouConst::TIME_SPAN_ALL:
                $time_span_column_point  = 'point.sum_all';
                $time_span_column_unique  = 'unque.sum_all';
                $time_span_column_mark  = 'mark.mean';
                $time_span_column_calc  = 'calc.mean';
                $time_span_column_uf  = 'update_frequency.mean';
                $column = ($cate === NarouConst::MARK||NarouConst::CALC) ? $this -> mean_all : $this -> sum_all;
                break;
            default:
                $time_span_column_point  = 'point.sum_all';
                $time_span_column_unique  = 'unque.sum_all';
                $time_span_column_mark  = 'mark.mean';
                $time_span_column_calc  = 'calc.mean';
                $time_span_column_uf  = 'update_frequency.mean';
                $column = ($cate === NarouConst::MARK||NarouConst::CALC) ? $this -> mean_all : $this -> sum_all;
            break;
        }
    
        //sqlで使用するﾃｰﾌﾞﾙの指定
        $tables_set = array();
        if(!empty($point_num)) {
            $tables_set[] = [
                'table' => NarouConst::TBL_POINT,
                'column' => $time_span_column_point,
            ];
        } else if (!empty($unique_num)) {
            $tables_set[] = [
                'table' => NarouConst::TBL_UNIQUE,
                'column' => $time_span_column_unique,
            ];
        } else if (!empty($uf)) {
            $tables_set[] = [
                'table' => NarouConst::TBL_UF,
                'column' => $time_span_column_uf,
            ];
        } else if ($cate===NarouConst::MARK) {
            $tables_set[] = [
                'table' => NarouConst::TBL_MARK,
                'column' => $time_span_column_mark,
            ];
        } else if ($cate===NarouConst::CALC) {
            $tables_set[] = [
                'table' => NarouConst::TBL_CALC,
                'column' => $time_span_column_calc,
            ];
        }

        //ベースクエリ
        $query =  $this->query();

        //利用するﾃｰﾌﾞﾙの紐づけ
        if(empty($tables_set)){
            foreach($tables_set as $ele){
                $query->leftjoin($ele['table'], 'ma.ncode', '=', $ele['table'].'.ncode')
                        ->select();
            };
        }
        
        //ﾎﾟｲﾝﾄに関する条件設定
        if ($point_flg) {
            if ($point_from) {
                $query->where('point.'.$column, '>=', $point_from);
            };
            if ($point_to) {
                $query->where('point.'.$column, '<=', $point_to);
            }
        }

        //ﾕﾆｰｸﾕｰｻﾞ数に関する条件設定
        if ($unique_flg) {
            if ($unique_from) {
                $query->where('unique.'.$column, '>=', $unique_from);
            };
            if ($unique_to) {
                $query->where('unique.'.$column, '<=', $unique_to);
            }          
        }

        //総和数に関する条件設定
        if ($gan_flg) {
            if ($gan_from) {
                $query -> where('ma.general_all_no','>=',$gan_from);
            };
            if ($gan_to) {
                $query -> where('ma.general_all_no','<=',$gan_to);
            }
        }

        //更新頻度に関する条件設定
        if ($frequency_flg) {
            if ($uf===NarouConst::FREQUENCY_1TIMEPERDAY) {
                $query -> where('update_frequency.'.$column,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERDAY);
            } else if ($uf===NarouConst::FREQUENCY_1TIMEPERMONTH) {
                $query -> where('update_frequency.'.$column,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERMONTH);
            } else if ($uf===NarouConst::FREQUENCY_1TIMEPERHALF) {
                $query -> where('update_frequency.'.$column,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERHALF);
            } else if ($uf===NarouConst::FREQUENCY_1TIMEPERYEAR) {
                $query -> where('update_frequency.'.$column,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERYEAR);
            } else if ($uf===NarouConst::FREQUENCY_ELSE) {
                $query -> where('update_frequency.'.$column,'>=',NarouConst::FREQUENCY_VALUE_ELSE);
            }
        };

        //並べ替えの対象となるカラムの特定
        if ($cate===NarouConst::MARK) {
            $main_column = 'mark.'.$column;
        } else if ($cate===NarouConst::CALC) {
            $main_column = 'calc.'.$column;
        } else if ($cate===NarouConst::POINT) {
            $main_column = 'point.'.$column;
        } else if ($cate===NarouConst::UNIQUE) {
            $main_column = 'unique.'.$column;
        }

        //抽出する列のリストを作成
        $selected_columns = [
            'ma.*',
            "$main_column as ave",
        ];
        foreach($tables_set as $ele) {
            $selected_columns[] = $ele['column'];
        }

        return $query->select($selected_columns)
            ->orderBy($column)
            ->limit(50)
            ->get();
    }
}
