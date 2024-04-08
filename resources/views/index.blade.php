<?php
include_once('database.php');
try{
	// 接続
	$db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
} catch(PDOException $e){
    echo "データベース接続失敗" . PHP_EOL."<br>";
	echo $e->getMessage();
	exit;
};
// データを取得する
//更新時点用
$sql_date = "SELECT last_get_date FROM ma ORDER BY last_get_date DESC";
$result_date = $db -> query($sql_date);
$temp_date01 = $result_date -> fetch()[0];
$array = preg_split("/\s/", $temp_date01);
$temp_date02 = $array[0];
$last_date = preg_replace('/-/', '/', $temp_date02);

//時点取得用
$sql_get_time = "SHOW COLUMNS FROM `mark`";
$date_columns = $db -> query($sql_get_time);
$date_array = array();
while($rec =  $date_columns ->fetch()):
if (false !== strpos($rec['Field'], '-')){
    array_push($date_array, $rec['Field']);
};
endwhile;

$num01 = count($date_array) -1;
$num02 = count($date_array) -2;
$num03 = count($date_array) -3;
$num04 = count($date_array) -4;
$num05 = count($date_array) -5;
$temp_date01 = $date_array[$num01];
$temp_date02 = $date_array[$num02];
$temp_date03 = $date_array[$num03];
$temp_date04 = $date_array[$num04];
$temp_date05 = $date_array[$num05];

if(!empty($_GET)){
  if($_GET['r_time_span'] === 'weekly'){
    $title = '週間';
    $r_text_time = $title;
    if($_GET['r_cate'] === 'lowPHighU'){
      $text_cate = 'ポイントの割に読者数は多い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_weekly_mk_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
        $result = $db -> query($sql_weekly_mk_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_weekly_mk_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
        $result = $db -> query($sql_weekly_mk_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_weekly_mk_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
        $result = $db -> query($sql_weekly_mk_ov5);
      }
    }else if($_GET['r_cate'] === 'lowPHighUHighF'){
      $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_weekly_c_ov1un3 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 100<=`temp`.`general_all_no` AND 300>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
        $result = $db -> query($sql_weekly_c_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_weekly_c_ov3un5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 300<=`temp`.`general_all_no` AND 500>`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
        $result = $db -> query($sql_weekly_c_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_weekly_c_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
        $result = $db -> query($sql_weekly_c_ov5);
      }else{
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_weekly_c_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`$temp_date01` as ave FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp WHERE `temp`.`$temp_date01` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`$temp_date01` DESC LIMIT 20;";
        $result = $db -> query($sql_weekly_c_ov5);
      }
    }
  }else if($_GET['r_time_span'] === 'monthly'){
    $title = '月間';
    $r_text_time = $title;
    if($_GET['r_cate'] === 'lowPHighU'){
      $text_cate = 'ポイントの割に読者数は多い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_monthly_mk_ov1un3 = "SELECT * FROM 
          (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
            `mark`.`mean_monthly` AS `ave` 
          FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
          WHERE `ave` REGEXP '.+' 
            AND 100<=`temp`.`general_all_no` 
            AND 300>`temp`.`general_all_no` 
          ORDER BY `ave` ASC LIMIT 20;";
        echo $sql_monthly_mk_ov1un3;
        $result = $db -> query($sql_monthly_mk_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_monthly_mk_ov3un5 = "SELECT * FROM 
          (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
            `mark`.`mean_monthly` AS `ave` 
          FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
          WHERE `ave` REGEXP '.+' 
            AND 300<=`temp`.`general_all_no` 
            AND 500>`temp`.`general_all_no` 
          ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_monthly_mk_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_monthly_mk_ov5 = "SELECT * FROM 
          (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
            `mark`.`mean_monthly` AS `ave` 
          FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
          WHERE `ave` REGEXP '.+' 
            AND 500<=`temp`.`general_all_no` 
          ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_monthly_mk_ov5);
      }
    }else if($_GET['r_cate'] === 'lowPHighUHighF'){
      $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_monthly_c_ov1un3 = "SELECT * FROM 
          (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
            `mark`.`mean_monthly` AS `ave` 
          FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
          WHERE `ave` REGEXP '.+' 
            AND 100<=`temp`.`general_all_no` 
            AND 300>`temp`.`general_all_no` 
          ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_monthly_c_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_monthly_c_ov3un5 = "SELECT * FROM 
          (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
            `mark`.`mean_monthly` AS `ave` 
          FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
          WHERE `ave` REGEXP '.+' 
            AND 300<=`temp`.`general_all_no` 
            AND 500>`temp`.`general_all_no` 
          ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_monthly_c_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_monthly_c_ov5 = "SELECT * FROM 
          (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,
            `mark`.`mean_monthly` AS `ave` 
          FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
          WHERE `ave` REGEXP '.+' 
            AND 500<=`temp`.`general_all_no` 
          ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_monthly_c_ov5);
      }
    }
  }else if($_GET['r_time_span'] === 'half'){
    $title = '半期';
    $r_text_time = $title;
    if($_GET['r_cate'] === 'lowPHighU'){
      $text_cate = 'ポイントの割に読者数は多い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_half_mk_ov1un3 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave` 
         FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 100<=`temp`.`general_all_no` 
          AND 300>`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_half_mk_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_half_mk_ov3un5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave`  
         FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 300<=`temp`.`general_all_no` 
          AND 500>`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_half_mk_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_half_mk_ov5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_half` AS `ave`  
         FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 500<=`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_half_mk_ov5);
      }
    }else if($_GET['r_cate'] === 'lowPHighUHighF'){
      $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_half_c_ov1un3 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
         FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
        WHERE `ave` REGEXP '.+' 
          AND 100<=`temp`.`general_all_no` 
          AND 300>`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_half_c_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_half_c_ov3un5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
         FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 300<=`temp`.`general_all_no` 
          AND 500>`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_half_c_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_half_c_ov5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `calc`.`mean_half` AS `ave`  
         FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 500<=`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_half_c_ov5);
      }
    }
  }else if($_GET['r_time_span'] === 'yearly'){
    $title = '年間';
    $r_text_time = $title;
    if($_GET['r_cate'] === 'lowPHighU'){
      $text_cate = 'ポイントの割に読者数は多い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_yearly_mk_ov1un3 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_yearly` AS `ave` 
         FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 100<=`temp`.`general_all_no` 
          AND 300>`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_yearly_mk_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_yearly_mk_ov3un5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`mean_yearly` AS `ave` 
         FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 300<=`temp`.`general_all_no` 
          AND 500>`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_yearly_mk_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_yearly_mk_ov5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`mark`.`mean_yearly` AS `ave` 
         FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 500<=`temp`.`general_all_no` 
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_yearly_mk_ov5);
      }
    }else if($_GET['r_cate'] === 'lowPHighUHighF'){
      $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_yearly_c_ov1un3 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
         FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 100<=`temp`.`general_all_no`
          AND 300>`temp`.`general_all_no`
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_yearly_c_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_yearly_c_ov3un5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
         FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 300<=`temp`.`general_all_no`
          AND 500>`temp`.`general_all_no`
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_yearly_c_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_yearly_c_ov5 = "SELECT * FROM 
        (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`,`calc`.`mean_yearly` AS `ave` 
         FROM `ma`, `calc` WHERE `ma`.`ncode`=`calc`.`ncode`) AS temp 
         WHERE `ave` REGEXP '.+' 
          AND 500<=`temp`.`general_all_no`
        ORDER BY `ave` ASC LIMIT 20;";
        $result = $db -> query($sql_yearly_c_ov5);
      }
    }
  }else if($_GET['r_time_span'] === 'all'){
    $title = '累計';
    $r_text_time = $title;
    if($_GET['r_cate'] === 'lowPHighU'){
      $text_cate = 'ポイントの割に読者数は多い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_all_mk_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave ASC LIMIT 20";
        $result = $db -> query($sql_all_mk_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_all_mk_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave ASC LIMIT 20";
        $result = $db -> query($sql_all_mk_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_all_mk_ov5 = "SELECT ncode, title, writer, general_all_no, mean_mk as ave from ma WHERE 500<=general_all_no ORDER BY ave ASC LIMIT 20";
        $result = $db -> query($sql_all_mk_ov5);
      }
    }else if($_GET['r_cate'] === 'lowPHighUHighF'){
      $text_cate = 'ポイントの割に読者数は多く、更新頻度が高い作品';
      $r_text_cate = $text_cate;
      if($_GET['r_gan'] === 'from100to300'){
        $text_gan = '100話以上300話未満';
        $r_text_gan = $text_gan;
        $sql_all_c_ov1un3 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 100<=general_all_no AND 300>general_all_no ORDER BY ave DESC LIMIT 20";
        $result = $db -> query($sql_all_c_ov1un3);
      }else if($_GET['r_gan'] === 'from300to500'){
        $text_gan = '300話以上500話未満';
        $r_text_gan = $text_gan;
        $sql_all_c_ov3un5 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 300<=general_all_no AND 500>general_all_no ORDER BY ave DESC LIMIT 20";
        $result = $db -> query($sql_all_c_ov3un5);
      }else if($_GET['r_gan'] === 'from500'){
        $text_gan = '500話以上';
        $r_text_gan = $text_gan;
        $sql_all_c_ov5 = "SELECT ncode, title, writer, general_all_no, mean_c as ave from ma WHERE 500<=general_all_no ORDER BY ave DESC LIMIT 20";
        $result = $db -> query($sql_all_c_ov5);
      }
    }
  };
}else{
  $title = '週間';
  $r_text_time = $title;
  $r_text_cate = 'ポイントの割に読者数は多い作品';
  $r_text_gan = '500話以上';
  $sql_weekly_mk_ov5 = "SELECT * FROM (SELECT `ma`.`ncode`, `ma`.`title`, `ma`.`writer`, `ma`.`general_all_no`, `mark`.`$temp_date01` as ave FROM `ma`, `mark` WHERE `ma`.`ncode`=`mark`.`ncode`) AS temp WHERE `temp`.`ave` REGEXP '.+' AND 500<=`temp`.`general_all_no` ORDER BY `temp`.`ave` ASC LIMIT 20;";
  $result = $db -> query($sql_weekly_mk_ov5);
}
?>

<!doctype html>
<html lang="jp">
  <head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L279PCKJGD"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-L279PCKJGD');
    </script>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@dehonjo" />
    <meta property="og:url" content="https://freenovelanalysis.com/" />
    <meta property="og:title" content="Search Free Novels" />
    <meta property="og:description" content="なろう作品, 良作, ランキング作成" />
    <meta property="og:image" content="https://freenovelanalysis.com/img/twitter_card.png" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" href="novel_icon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">


    <title>Search Narou Works</title>
  </head>
  <body>

    <!--↓サイトコンテンツ-->
    <button id="js-pagetop" class="pagetop"><span class="pagetop__arrow"></span></button>
    <?php require_once('./PHPparts/nav.php')?>

    <?php require_once('./PHPparts/top.php')?>

    <?php require_once('./PHPparts/about.php')?>

    <section id="ranking" class="ranking">
      <div class="rank-bg">
        <div class="container clear">
          <h3 class="section-title">ランキング</h3>
          <div class="rank-text">
            Last Up Date：<time><?php echo $last_date;?></time>
          </div>
          <form action="index.php" method="get" class="rank_content">
            <dl>
              <div class="r-form-row">
                <dt class="r-form-item"><label for="r-time-span">期間</label></dt>
                <dd class="r-form-explain">
                  <div class="r-select-wrapper">
                    <select name="r_time_span" id="r-time-span" class="time-span">
                      <option value="weekly" selected>週間</option>
                      <option value="monthly">月間</option>
                      <option value="half">半期</option>
                      <option value="yearly">年間</option>
                      <option value="all">累計</option>
                    </select>
                  </div>
                </dd>
                <dt class="r-form-item"><label for="r-cate">種類</label></dt>
                <dd class="r-form-explain r_cate">
                  <div class="r-select-wrapper">
                    <select name="r_cate" id="r-cate">
                      <option value="lowPHighU" selected>ポイントの割に読者数は多い作品</option>
                      <option value="lowPHighUHighF">ポイントの割に読者数は多く、更新頻度が高い作品</option>
                    </select>
                  </div>
                </dd>
                <dt class="r-form-item"><label for="r-gan">総話数帯</label></dt>
                <dd class="r-form-explain">
                  <div class="r-select-wrapper">
                    <select name="r_gan" id="r-gan" class="r_gan">
                      <option value="from100to300">100話～300話</option>
                      <option value="from300to500">300話～500話</option>
                      <option value="from500" selected>500話～</option>
                    </select>
                  </div>
                </dd>
              </div>
            </dl>
            <div class="rank-btn-space">
              <input type="submit" name="submit" value="選択" class="r-form-submit">
            </div>
          </form>

          <div class="rank-frame">
            <div class="subtitle-frame">
              <div class="section-subtitle"><?php echo $title.'ランキング'; ?></div>
              <div class="r-frame-items">
                <div class="r-f-item"><div class="r-f-item-name">期間</div><div class="r-f-item-text"><?php echo $r_text_time?></div></div>
                <div class="r-f-item"><div class="r-f-item-name">種類</div><div class="r-f-item-text"><?php echo $r_text_cate?></div></div>
                <div class="r-f-item"><div class="r-f-item-name">総話数帯</div><div class="r-f-item-text"><?php echo $r_text_gan?></div></div>
              </div>
            </div>
            <div class="rank-content">
              <table>
                <tr>
                  <th>順位</th>
                  <th class="title-writer"><span class="r-title">作品名</span><span class="r-writer"> / 作者 / </span><span class="r-ncode">ncode</span></th>
                </tr>
                <?php $i=1?>
                <?php $before_ave = null;?>
                <?php while($record = $result -> fetch()):?>
                  <?php if($i % 2 ===1):?>
                    <tr <?php echo "style='background-color:#f9f9f9'"?>>
                  <?php else:?>
                    <tr>
                  <?php endif;?>
                    <?php
                      if($record['ave']===$before_ave){
                        $rank = $i-1;
                      }else{
                        $rank = $i;
                      };
                    ?>
                      <th><span class='r-rank'><?php echo $rank;?></span>位</th>
                      <td class="title-writer">
                        <span class="r-title"><?php echo $record['title']; ?></span>
                        <span class="r-writer"> / <?php echo $record['writer']; ?> / </span>
                        <span class="r-ncode"><?php echo $record['ncode']; ?></span>
                      </td>
                      <?php $before_ave = $record['ave']?>
                      <?php $i++?>
                <?php endwhile;?>
              </table>
              <div class="to-detail">
                <div class="to-detail-title">作品の詳細を知りたい場合</div>
                <form action="./detail/detail.php" method="GET" class="to-detail-form">
                  <div class="to-detail-input-wrapper">
                    <input type="text" name="ncode" placeholder="ncodeを入力してください" class="to-detail-input">
                  </div>
                  <div class="to-detail-btn-space">
                    <input type="submit" value='詳細表示' class="to-detail-submit-btn">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php require_once('./PHPparts/contact.php')?>

    <?php require_once('./PHPparts/footer.php')?>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- jQueryのライブラリー本体を読み込む -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
    <script src="./js/main.js"></script>
  </body>
</html>