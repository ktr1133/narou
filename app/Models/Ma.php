<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Point;
use App\Models\Unique;
use App\Models\Mark;
use App\Models\Update_frequency;
use App\Models\Calc;


class Ma extends Model
{
    use HasFactory;
    protected $table = 'ma';
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
    public $mean_monthly = 'mean_monthly';
    public $mean_half = 'mean_half';
    public $mean_yearly = 'mean_yearly';
    public $mean_all = 'mean';

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


    // ｶﾃｺﾞﾘ別に結合
    public function joinedByCategory(Builder $query, $cate)
    {
        // maテーブルとmark or calc($cate)テーブルを左側結合
        $query = $query ->query()
            ->leftJoin($cate, 'ma.ncode', '=', $cate.'.ncode')
            ->select('ma.*', $cate.'.*')
            ->whereNull($cate.'.ncode');
        return $query;

    }

    // 総話数帯で抽出
    public function getFilteredByGeneralAllNo($from,$to)
    {
        // 総和数帯が〇〇以上××未満のデータを抽出
        $query = $this -> query()
            ->whereBetween('general_all_no', $from, $to);
        return $query;
    }

    // 期間の値を昇順で並べ替えて上位num分のﾃﾞｰﾀを抽出
    public function ASCAndFilteredByRequest(Builder $query, $timeSpan)
    {
        $num = 20;
        // 期間別で上位num位分のﾃﾞｰﾀを抽出
        switch ($timeSpan) {
            case 'weekly':
                //最新日の日付データのカラムを命名
                $latestDateColumnName = $this->getLatestDate();
                $data = $query
                    ->orderBy($latestDateColumnName)
                    ->limit($num)->get();
                break;
            case 'monthly':
                $data = $query 
                    ->orderBy('mean_monthly')
                    ->limit($num)->get();
                break;
            case 'half':
                $data = $query
                    ->orderBy('mean_half')
                    ->limit($num)->get();
                break;
            case 'yearly':
                $data = $query
                    ->orderBy('mean_yearly')
                    ->limit($num)->get();
                break;
            case 'all':
                $data = $query
                    ->orderBy('mean')
                    ->limit($num)->get();
                break;
            default:
                // デフォルトは直近の1週間
                $latestDateColumnName = $this->getLatestDate();
                $data = $query
                    ->orderBy($latestDateColumnName)
                    ->limit($num)->get();
                break;        
        }
        return $data;
    }
    // 期間の値を降順で並べ替えて上位num分のﾃﾞｰﾀを抽出
    public function DESCAndFilteredByRequest(Builder $query,$timeSpan)
    {
        $num = 20;
        // 期間別で上位num位分のﾃﾞｰﾀを抽出
        switch ($timeSpan) {
            case 'weekly':
                $latestDateColumnName = $this->getLatestDate();
                $data = $query->orderBy($latestDateColumnName, 'desc')
                    ->limit($num)->get();
                break;
            case 'monthly':
                $data = $query->orderBy('mean_monthly', 'desc')
                    ->limit($num)->get();
                break;
            case 'half':
                $data = $query->orderBy('mean_half', 'desc')
                    ->limit($num)->get();
                break;
            case 'yearly':
                $data = $query->orderBy('mean_yearly', 'desc')
                    ->limit($num)->get();
                break;
            case 'all':
                $data = $query->orderBy('mean', 'desc')
                    ->limit($num)->get();
                break;
            default:
                // デフォルトは直近の1週間
                $latestDateColumnName = $this->getLatestDate();
                $data = $query->orderBy($latestDateColumnName, 'desc')
                    ->limit($num)->get();
                break;        
        }
        return $data;
    }


    //日付のカラム名をすべて取得
    public function getDateColumns()
    {
        // markテーブルのカラム名を取得
        $columns = DB::getSchemaBuilder()->getColumnListing('mark');

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

    //ランキングデータ作成
    public function generateRanking(Request $request)
    {
        $timeSpan = $request->input('r_time_span', 'weekly');
        $cate = $request->input('r_cate', 'lowPHighU');
        $gan = $request->input('r_gan', 'from500');
    
        $from = $this->getFromValue($gan);
        $to = $this->getToValue($gan);
    
        $num = 20;
    
        if ($cate === 'lowPHighU') {
            $result = $this->getRankingResult($timeSpan, $from, $to, 'mark');
        } elseif ($cate === 'lowPHighUHighF') {
            $result = $this->getRankingResult($timeSpan, $from, $to, 'calc');
        } else {
            $result = $this->getRankingResult($timeSpan, $from, $to, 'mark');
        }
    
        return $result;
    }
    
    private function getFromValue($gan)
    {
        switch ($gan) {
            case 'from100to300':
                return 100;
            case 'from300to500':
                return 300;
            case 'from500':
                return 500;
            default:
                return 500;
        }
    }
    
    private function getToValue($gan)
    {
        return ($gan === 'from500') ? PHP_INT_MAX : PHP_INT_MAX;
    }
    
    private function getRankingResult($timeSpan, $from, $to, $table)
    {
        $latestDateColumnName = $this->getLatestDate();
        $ave = $latestDateColumnName;
        switch ($timeSpan) {
            case 'weekly':
                $column = ($table === 'mark') ? $latestDateColumnName : $latestDateColumnName;
                $ave = $latestDateColumnName;
                break;
            case 'monthly':
                $column = ($table === 'mark') ? $this -> mean_monthly : $this -> mean_monthly;
                $ave = $this -> mean_monthly;
                break;
            case 'half':
                $column = ($table === 'mark') ? $this -> mean_half : $this -> mean_half;
                $ave = $this -> mean_half;
                break;
            case 'yearly':
                $column = ($table === 'mark') ? $this -> mean_yearly : $this -> mean_yearly;
                $ave = $this -> mean_yearly;
                break;
            case 'all':
                $column = ($table === 'mark') ? $this -> mean_all : $this -> mean_all;
                $ave = $this -> mean_all;
                break;
            default:
                $column = $latestDateColumnName;
                $ave = $latestDateColumnName;
                break;
        }
    
        return $this->query()
            ->leftJoin($table, 'ma.ncode', '=', "$table.ncode")
            ->select('ma.*', "$table.$ave as ave")
            ->whereBetween('general_all_no', [$from, $to])
            ->where($column, '>', 0)
            ->orderBy($column)
            ->limit(20)
            ->get();
    }
    
}
