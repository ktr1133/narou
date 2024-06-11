<?php

namespace App\Models;

use App\Consts\NarouConst;
use App\Http\Requests\DetailPostRequest;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Unique extends Model
{
    protected $table = 'unique';

    protected $fillable = [
        'ncode',
        'sum_all',
        'sum_monthly',
        'sum_half',
        'sum_yearly',
        '2023-01-26 00:00:00',
        '2023-01-30 00:00:00',
        '2023-02-01 00:00:00',
        '2023-02-06 00:00:00',
        '2023-02-13 00:00:00',
        '2023-02-20 00:00:00',
        '2023-02-27 00:00:00',
        '2023-03-12 00:00:00',
        '2023-03-13 00:00:00',
        '2023-03-21 00:00:00',
        '2023-03-27 00:00:00',
        '2023-04-03 00:00:00',
        '2023-04-10 00:00:00',
        '2023-04-17 00:00:00',
        '2023-04-24 00:00:00',
        '2023-05-01 00:00:00',
        '2023-05-08 00:00:00',
        '2023-05-15 00:00:00',
        '2023-05-22 00:00:00',
        '2023-05-29 00:00:00',
        '2023-06-06 00:00:00',
        '2023-06-12 12:34:28',
        '2023-06-19 13:21:28',
        '2023-06-26 09:10:36',
        '2023-07-03 09:53:49',
        '2023-07-10 09:23:24',
        '2023-07-18 13:59:17',
        '2023-07-24 09:31:33',
        '2023-07-31 12:35:17',
        '2023-08-07 09:28:40',
        '2023-08-14 11:54:49',
        '2023-08-21 19:21:43',
        '2023-08-28 09:05:03',
        '2023-09-04 09:26:56',
        '2023-09-11 08:29:46',
        '2023-09-18 11:26:20',
        '2023-09-25 10:42:18',
        '2023-10-02 21:39:07',
        '2023-10-09 18:21:37',
        '2023-10-16 20:51:16',
        '2023-10-23 20:18:31',
        '2023-11-05 10:15:15',
        '2023-11-06 22:54:58',
        '2023-11-13 23:51:13',
        '2023-11-20 22:44:10',
        '2023-11-27 22:16:21',
        '2023-12-04 16:08:47',
        '2023-12-11 22:33:32',
        '2023-12-18 21:47:17',
        '2023-12-25 16:03:29',
        '2024-01-01 23:38:17',
        '2024-01-08 17:58:25',
        '2024-01-15 21:54:48',
        '2024-01-23 22:14:16',
        '2024-01-29 22:48:12',
        '2024-02-05 22:59:33',
        '2024-02-12 22:14:40',
        '2024-02-19 22:41:30',
        '2024-02-26 22:15:33',
        '2024-03-04 23:04:50',
        '2024-03-11 09:25:35',
        '2024-03-18 22:40:24',
        '2024-03-25 22:34:20',
        '2024-04-02 22:13:12',
        '2024-04-08 14:36:24',
        '2024-04-15 22:58:13',
        '2024-04-22 22:15:51',
    ];

    use HasFactory;


     /**
     * テーブルのカラム名を取得
     * 
     * @return array
     */

     public function getColumnNames(): array
     {
         return Schema::getColumnListing('unique');
     }
 
      /**
      * uniqueテーブルから対象となるカラムが0より大きな値のﾃﾞｰﾀを降順で並べ替えて全件取得
      * 
      * @return EloquentCollection
      */
     public function getRankData(): EloquentCollection
     {
         return $this->query()
             ->select('unique.*')
             ->get();
     }
 
      /**
      * ランキングデータ作成用
      * 
      * @param  DetailPostRequest $request
      * @return ?array
      */
     public function calcRank(DetailPostRequest $request): ?array
     {
         //weekly
         //日付のカラムを持つﾃｰﾌﾞﾙの１つから日付の配列を昇順に並べ替えて取得
         $date_columns = [];
         $column_names = $this->getColumnNames();
         foreach ($column_names as $column) {
             if (strpos($column, '-') !== false) {
                 $date_columns[] = $column;
             }
         }
         $weekly = $date_columns[count($date_columns) - 1];
         $origin = $this->getRankData();
         $result = $origin
             ->filter( function ($rec) use ($weekly) {
                 return $rec[$weekly] > 0;
             })->sortByDesc($weekly);
         $rank_w = null;
         $i=1;
         $before = 9999999999999;
         foreach($result as $rec){
             if($rec[$weekly] ===$before){
               $i = $i -1;
             }
             if($rec['ncode']===$request['ncode']){
                 $rank_w = $i;
                 break;
             }else{
               $i++;
               $before = $rec[$weekly];
             }
         }
         if ($rank_w === null){
             $rank_w = NarouConst::UNRANKED;
         }
 
         //monthly
         $monthly = 'mean_monthly';
         $origin = $this->getRankData();
         $result = $origin
             ->filter( function ($rec) use ($monthly) {
                 return $rec[$monthly] > 0;
             })->sortByDesc($monthly);
         $rank_m = null;
         $i=1;
         $before = 9999999999999;
         foreach($result as $rec){
             if($rec[$monthly] ===$before){
               $i = $i -1;
             }
             if($rec['ncode']===$request['ncode']){
                 $rank_m = $i;
                 break;
             }else{
               $i++;
               $before = $rec[$monthly];
             }
         }
         if ($rank_m === null){
             $rank_m = NarouConst::UNRANKED;
         }
 
         //half
         $half = 'sum_half';
         $origin = $this->getRankData();
         $result = $origin
             ->filter( function ($rec) use ($half) {
                 return $rec[$half] > 0;
             })->sortByDesc($half);
         $rank_h = null;
         $i=1;
         $before = 9999999999999;
         foreach($result as $rec){
             if($rec[$half] ===$before){
               $i = $i -1;
             }
             if($rec['ncode']===$request['ncode']){
                 $rank_h = $i;
                 break;
             }else{
               $i++;
               $before = $rec[$half];
             }
         }
         if ($rank_h === null){
             $rank_h = NarouConst::UNRANKED;
         }
 
         //yearly
         $yearly = 'sum_yearly';
         $origin = $this->getRankData();
         $result = $origin
             ->filter( function ($rec) use ($yearly) {
                 return $rec[$yearly] > 0;
             })->sortByDesc($yearly);
         $rank_y = null;
         $i=1;
         $before = 9999999999999;
         foreach($result as $rec){
             if($rec[$yearly] ===$before){
               $i = $i -1;
             }
             if($rec['ncode']===$request['ncode']){
                 $rank_y = $i;
                 break;
             }else{
               $i++;
               $before = $rec[$yearly];
             }
         }
         if ($rank_y === null){
             $rank_y = NarouConst::UNRANKED;
         }
 
         //all
         $all = 'sum_all';
         $origin = $this->getRankData();
         $result = $origin
             ->filter( function ($rec) use ($all) {
                 return $rec[$all] > 0;
             })->sortByDesc($all);
         $rank_a = null;
         $i=1;
         $before = 9999999999999;
         foreach($result as $rec){
             if($rec[$all] ===$before){
               $i = $i -1;
             }
             if($rec['ncode']===$request['ncode']){
                 $rank_a = $i;
                 break;
             }else{
               $i++;
               $before = $rec[$all];
             }
         }
         if ($rank_a === null){
             $rank_a = NarouConst::UNRANKED;
         }
 
         return [
             'weekly'  => $rank_w,
             'monthly' => $rank_m,
             'half'    => $rank_h,
             'yearly'  => $rank_y,
             'all'     => $rank_a,
         ];
     }
}
