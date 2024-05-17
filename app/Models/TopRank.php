<?php
//廃止予定（Maﾓﾃﾞﾙに移行済）

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Exception;
use Illuminate\Support\Facades\Log;


class TopRank extends Model
{
    // ｶﾃｺﾞﾘ別に結合
    public function joinedByCategory($cate)
    {
        // maテーブルとmark or calc($cate)テーブルを左側結合
        $query = $this ->query()
            ->leftJoin($cate, 'ma.ncode', '=', $cate.'.ncode')
            ->select('ma.*', $cate.'.*');
        // 結合できなかったmaテーブルのデータを破棄
        $query->whereNull($cate.'.ncode');
        //最新日の日付データのカラムを命名
        $latestDateColumnName = $this->getLatestDate();
        return $query->selectRaw($latestDateColumnName." AS weekly");
    }

    // 総話数帯で抽出
    public function getFilteredByGeneralAllNo($from,$to)
    {
        // 総和数帯が〇〇以上××未満のデータを抽出
        $query = $this -> query()
            ->whereBetween('all_general_no', $from, $to);
        return $qyery;
    }

    // 期間の値を昇順で並べ替えて上位num分のﾃﾞｰﾀを抽出
    public function ASCAndFilteredByTopNum($timeSpan,$num)
    {
        // 期間別で上位num位分のﾃﾞｰﾀを抽出
        switch ($timeSpan) {
            case 'weekly':
                $data = $this -> query()
                    ->orderBy('weekly')
                    ->limit($num)->get();
                break;
            case 'monthly':
                $data = $this -> query()
                    ->orderBy('mean_monthly')
                    ->limit($num)->get();
                break;
            case 'half':
                $data = $this -> query()
                    ->orderBy('mean_half')
                    ->limit($num)->get();
                break;
            case 'yearly':
                $data = $this ->query()
                    ->orderBy('mean_yearly')
                    ->limit($num)->get();
                break;
            case 'all':
                $data = $this -> query()
                    ->orderBy('mean')
                    ->limit($num)->get();
                break;
            default:
                // デフォルトは直近の1週間
                $data = $this -> query()
                    ->orderBy('weekly')
                    ->limit($num)->get();
                break;        
        }
        return $data;
    }
    // 期間の値を降順で並べ替えて上位num分のﾃﾞｰﾀを抽出
    public function DESCAndFilteredByTopNum($query,$timeSpan,$num)
    {
        // 期間別で上位num位分のﾃﾞｰﾀを抽出
        switch ($timeSpan) {
            case 'weekly':
                $data = $query->orderBy('weekly', 'desc')
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
                $data = $query->orderBy('weekly', 'desc')
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
        Log::debug($columns);

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
        Log::debug($dateColumns);

        return max($dateColumns);
    }
}
