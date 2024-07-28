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
use App\Exceptions\QueryException;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DetailPostRequest;
use App\Models\Point;
use App\Models\Unique;
use App\Models\Mark;
use App\Models\UpdateFrequency;
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
    private $mean_half    = 'mean_half';
    private $mean_yearly  = 'mean_yearly';
    private $mean_all     = 'mean';
    private $sum_montyly  = 'sum_monthly';
    private $sum_half     = 'sum_half';
    private $sum_yearly   = 'sum_yearly';
    private $sum_all      = 'sum_all';
    private $table_names = [
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
        return $this ->hasOne(UpdateFrequency::class);
    }
    public function calcs(){
        return $this ->hasOne(Calc::class);
    }


    // トップページ用
    /**
     * ｶﾃｺﾞﾘ別に結合
     */
    public function joinedByCategory(Builder $query, $cate)
    {
        // maテーブルとmark or calc($cate)テーブルを左側結合
        $query = $query ->query()
            ->leftJoin($cate, 'ma.ncode', '=', $cate.'.ncode')
            ->select('ma.*', $cate.'.*')
            ->whereNull($cate.'.ncode');
        return $query;

    }

    /**
     * 日付のカラム名をすべて取得
     */
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

    /**
     * 最新の日付取得
     */
    public function getLatestDate()
    {
        $dateColumns = $this -> getDateColumns();

        return max($dateColumns);
    }

    /**
     * ランキングデータ作成
     */
    public function generateTopRanking(Request $request)
    {
        $time_span = $request->input(NarouConst::SELECT_TOP_TIMESPAN, NarouConst::TIME_SPAN_WEEKLY);
        $cate = $request->input(NarouConst::SELECT_TOP_CATEGORY, NarouConst::MARK);
        $gan = $request->input(NarouConst::SELECT_TOP_GENERAL_ALL_NO, NarouConst::GAN_OVER100_UNDER300);
    
        $from = $this->getFromValue($gan);
        $to = $this->getToValue($gan);
    
        if ($cate === NarouConst::MARK){
            $result = $this->getTopRankingResult($time_span, $from, $to, NarouConst::TBL_MARK);
        } elseif ($cate === NarouConst::CALC) {
            $result = $this->getTopRankingResult($time_span, $from, $to, NarouConst::TBL_CALC);
        } else {
            $result = $this->getTopRankingResult($time_span, $from, $to, NarouConst::TBL_MARK);
        }
    
        return $result;
    }
    
    /**
     * 総話数帯のfrom値取得
     */
    public function getFromValue($gan)
    {
        switch ($gan) {
            case NarouConst::GAN_OVER100_UNDER300:
                return 100;
            case NarouConst::GAN_OVER300_UNDER500:
                return 300;
            case NarouConst::GAN_OVER500:
                return 500;
            default:
                return 500;
        }
    }
    
    /**
     * 総話数帯to値取得
     */
    public function getToValue($gan)
    {
        return ($gan === NarouConst::GAN_OVER500) ? PHP_INT_MAX : PHP_INT_MAX;
    }
    
    /**
     * ランキングデータ作成
     */
    public function getTopRankingResult($time_span, $from, $to, $table)
    {
        $limit=50;
        $latestDateColumnName = $this->getLatestDate();
        $ave = $latestDateColumnName;
        switch ($time_span) {
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
            ->where($table.'.'.$column, '>', 0)
            ->orderBy($table.'.'.$column, 'asc')
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

    //ｸﾘｴｲﾄﾍﾟｰｼﾞ
    /**
     * ランキングデータ作成(作品単位)
     */
    public function getRankingResult(CreatePostRequest $request)
    {
        //ﾘｸｴｽﾄﾃﾞｰﾀの整理
        $cate = $request -> input(NarouConst::SELECT_CREATE_CATEGORY);
        $time_span = $request -> input(NarouConst::SELECT_CREATE_TIMESPAN);
        $gan_num = $request -> input(NarouConst::INPUT_GENERAL_ALL_NO);
        $point_num = $request->input(NarouConst::INPUT_POINT);
        $unique_num = $request->input(NarouConst::INPUT_UNIQUE);
        $uf = $request -> input(NarouConst::SELECT_CREATE_FREQUENCY);

        $tables = array();
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
                $time_span_column_unique  = 'unique.'.$latest_date_column_name;
                $time_span_column_mark  = 'mark.'.$latest_date_column_name;
                $time_span_column_calc  = 'calc.'.$latest_date_column_name;
                $time_span_column_uf  = 'update_frequency.'.$latest_date_column_name;
                break;
            case NarouConst::TIME_SPAN_MONTHLY:
                $time_span_column_point  = 'point.sum_monthly';
                $time_span_column_unique  = 'unique.sum_monthly';
                $time_span_column_mark  = 'mark.mean_monthly';
                $time_span_column_calc  = 'calc.mean_monthly';
                $time_span_column_uf  = 'update_frequency.mean_monthly';
                break;
            case NarouConst::TIME_SPAN_HALF:
                $time_span_column_point  = 'point.sum_half';
                $time_span_column_unique  = 'unique.sum_half';
                $time_span_column_mark  = 'mark.mean_half';
                $time_span_column_calc  = 'calc.mean_half';
                $time_span_column_uf  = 'update_frequency.mean_half';
                break;
            case NarouConst::TIME_SPAN_YEARLY:
                $time_span_column_point  = 'point.sum_yearly';
                $time_span_column_unique  = 'unique.sum_yearly';
                $time_span_column_mark  = 'mark.mean_yearly';
                $time_span_column_calc  = 'calc.mean_yearly';
                $time_span_column_uf  = 'update_frequency.mean_yearly';
                break;
            case NarouConst::TIME_SPAN_ALL:
                $time_span_column_point  = 'point.sum_all';
                $time_span_column_unique  = 'unique.sum_all';
                $time_span_column_mark  = 'mark.mean';
                $time_span_column_calc  = 'calc.mean';
                $time_span_column_uf  = 'update_frequency.mean';
                break;
            default:
                $time_span_column_point  = 'point.sum_all';
                $time_span_column_unique  = 'unique.sum_all';
                $time_span_column_mark  = 'mark.mean';
                $time_span_column_calc  = 'calc.mean';
                $time_span_column_uf  = 'update_frequency.mean';
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
        if ($frequency_flg) {
            $tables_set[] = [
                'table' => NarouConst::TBL_UF,
                'column' => $time_span_column_uf,
            ];
        }
        
        Log::info('結合する必要のあるﾃｰﾌﾞﾙﾘｽﾄ');
        Log::debug($tables_set);

        //ベースクエリ
        $query =  $this->query();

        //利用するﾃｰﾌﾞﾙの紐づけ
        if(!empty($tables_set)){
            foreach($tables_set as $ele){
                $query->leftjoin($ele['table'], 'ma.ncode', '=', $ele['table'].'.ncode');
            };
        }

        
        Log::info('point_flg: '.$point_flg.' unique_flg: '.$unique_flg.' gan_flg: '.$gan_flg.' uf_flg: '.$frequency_flg);
        //ﾎﾟｲﾝﾄに関する条件設定
        if ($point_flg) {
            if ($point_from) {
                $query->where($time_span_column_point, '>=', $point_from);
            };
            if ($point_to) {
                $query->where($time_span_column_point, '<=', $point_to);
            }
        }

        //ﾕﾆｰｸﾕｰｻﾞ数に関する条件設定
        if ($unique_flg) {
            if ($unique_from) {
                $query->where($time_span_column_unique, '>=', $unique_from);
            };
            if ($unique_to) {
                $query->where($time_span_column_unique, '<=', $unique_to);
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
                $query -> where($time_span_column_uf,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERDAY);
            } else if ($uf===NarouConst::FREQUENCY_1TIMEPERMONTH) {
                $query -> where($time_span_column_uf,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERMONTH);
            } else if ($uf===NarouConst::FREQUENCY_1TIMEPERHALF) {
                $query -> where($time_span_column_uf,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERHALF);
            } else if ($uf===NarouConst::FREQUENCY_1TIMEPERYEAR) {
                $query -> where($time_span_column_uf,'>=',NarouConst::FREQUENCY_VALUE_1TIMEPERYEAR);
            } else if ($uf===NarouConst::FREQUENCY_ELSE) {
                $query -> where($time_span_column_uf,'>=',NarouConst::FREQUENCY_VALUE_ELSE);
            }
        };

        //並べ替えの対象となるカラムの特定
        $ascending_flg = false;
        if ($cate===NarouConst::MARK) {
            $main_column = $time_span_column_mark;
            $ascending_flg = true;
        } else if ($cate===NarouConst::CALC) {
            $main_column = $time_span_column_calc;
            $ascending_flg = false;
        } else if ($cate===NarouConst::POINT) {
            $main_column = $time_span_column_point;
            $ascending_flg = false;
        } else if ($cate===NarouConst::UNIQUE) {
            $main_column = $time_span_column_unique;
            $ascending_flg = false;
        }

        //抽出する列のリストを作成
        $selected_columns = [
            'ma.*',
            "$main_column as ave",
        ];
        foreach($tables_set as $ele) {
            $selected_columns[] = $ele['column'];
        }

        if ($ascending_flg) {
            $result = $query->select($selected_columns)
                ->where($main_column, '>', 0)
                ->orderBy($main_column, 'asc')
                ->limit(50)
                ->get();
        } else {
            $result = $query->select($selected_columns)
                ->where($main_column, '>', 0)
                ->orderBy($main_column, 'desc')
                ->limit(50)
                ->get();
        }

        return $result;
    }

    /**
     * ランキングデータ作成(作者単位)
     */
    public function getWRankingResult(CreatePostRequest $request)
    {
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
        $latest_date_column_name = $this->getLatestDate();
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
        $query =  $this->query()
                    ->select(
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

    //ここからDetailﾍﾟｰｼﾞ用
    /**
     * 対象作品のﾃﾞｰﾀを全テーブルから取得
     * 
     * @param  DetailPostRequest $request
     * @return Model
     */
    public function getDetailWork(DetailPostRequest $request): Model
    {
        Log::info('getDetailWorkの処理開始');
        $query = $this->query()
                      ->where('ma.ncode', '=', $request['ncode'])
                      ->select('ma.*'); // テーブル名の指定が必要
        
        Log::debug('クエリ: ' . $query->toSql(), $query->getBindings());
        $detail = $query->first();
        if ($detail) {
            Log::info('取得した詳細:', $detail->toArray());
        } else {
            Log::warning('指定されたncodeに一致するデータが見つかりませんでした。');
        }
    
        return $detail;
    }

     /**
     * 対象作品のﾃﾞｰﾀをpointテーブルから取得
     * 
     * @param  DetailPostRequest $request
     * @return Model
     */
    public function getPoint(DetailPostRequest $request): Model
    {
        Log::info('getPoint 処理開始');
        return $this->query()
            ->leftJoin(
                NarouConst::TBL_POINT,
                NarouConst::TBL_MA.'.ncode',
                '=',
                NarouConst::TBL_POINT.'.ncode'
                )
            ->select(
                'point.sum_all as sum_all_po',
                'point.sum_yearly as sum_yearly_po',
                'point.sum_half as sum_half_po',
                'point.sum_monthly as sum_monthly_po',
                )
            ->where('ma.ncode','=',$request['ncode'])
            ->first();
    }

     /**
     * 対象作品のﾃﾞｰﾀをuniqueテーブルから取得
     * 
     * @param  DetailPostRequest $request
     * @return Model
     */
    public function getUnique(DetailPostRequest $request): Model
    {
        Log::info('getUnique 処理開始');
        return $this->query()
            ->leftJoin(
                NarouConst::TBL_UNIQUE,
                NarouConst::TBL_MA.'.ncode',
                '=',
                NarouConst::TBL_UNIQUE.'.ncode'
                )
            ->select(
                'unique.sum_all as sum_all_un',
                'unique.sum_yearly as sum_yearly_un',
                'unique.sum_half as sum_half_un',
                'unique.sum_monthly as sum_monthly_un',
                )
            ->where('ma.ncode','=',$request['ncode'])
            ->first();
    }

     /**
     * 対象作品のﾃﾞｰﾀをmarkテーブルから取得
     * 
     * @param  DetailPostRequest $request
     * @return Model
     */
    public function getMark(DetailPostRequest $request): Model
    {
        Log::info('getMark 処理開始');
        return $this->query()
            ->leftJoin(
                NarouConst::TBL_MARK,
                NarouConst::TBL_MA.'.ncode',
                '=',
                NarouConst::TBL_MARK.'.ncode'
                )
            ->select(
                'mark.mean as mean_mk',
                'mark.mean_yearly as mean_yearly_mk',
                'mark.mean_half as mean_half_mk',
                'mark.mean_monthly as mean_monthly_mk',
                )
            ->where('ma.ncode','=',$request['ncode'])
            ->first();
    }

     /**
     * 対象作品のﾃﾞｰﾀをupdate_frequencyテーブルから取得
     * 
     * @param  DetailPostRequest $request
     * @return Model
     */
    public function getUF(DetailPostRequest $request): Model
    {
        Log::info('getUF 処理開始');
        return $this->query()
            ->leftJoin(
                NarouConst::TBL_UF,
                NarouConst::TBL_MA.'.ncode',
                '=',
                NarouConst::TBL_UF.'.ncode'
                )
            ->select(
                'update_frequency.mean as mean_uf',
                'update_frequency.mean_yearly as mean_yearly_uf',
                'update_frequency.mean_half as mean_half_uf',
                'update_frequency.mean_monthly as mean_monthly_uf',
                )
            ->where('ma.ncode','=',$request['ncode'])
            ->first();
    }

     /**
     * 対象作品のﾃﾞｰﾀをcalcテーブルから取得
     * 
     * @param  DetailPostRequest $request
     * @return Model
     */
    public function getCalc(DetailPostRequest $request): Model
    {
        Log::info('getCalc 処理開始');
        return $this->query()
            ->leftJoin(
                NarouConst::TBL_CALC,
                NarouConst::TBL_MA.'.ncode',
                '=',
                NarouConst::TBL_CALC.'.ncode'
                )
            ->select(
                'calc.mean as mean_c',
                'calc.mean_yearly as mean_yearly_c',
                'calc.mean_half as mean_half_c',
                'calc.mean_monthly as mean_monthly_c',
                )
            ->where('ma.ncode','=',$request['ncode'])
            ->first();
    }
}
