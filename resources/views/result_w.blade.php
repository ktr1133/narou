<?php
require_once('../database.php');
require_once('../classes/prepare_sql.php');
require_once('../functions/writerRankSql.php');
require_once('../functions/make_time_span.php');


try{
  // 接続
  $db = new PDO('mysql:host='.$host.';dbname='.$dbname.'', $username, $password);
} catch(PDOException $e){
  echo "データベース接続失敗" . PHP_EOL."<br>";
  echo $e->getMessage();
  exit;
};

//ランキング作成の設定
////種類の特定
if(!empty($_GET['cate'])){
  if($_GET['cate']==='lowPHighU'){
    $cate = 'ポイントは低いけど読者数は多い作品を持つ作者';
  }else if($_GET['cate']==='lowPHighUHighF'){
    $cate = 'ポイントは低いけど読者数は多く、更新頻度が高い作品を持つ作者';
  }else if($_GET['cate']==='HighP'){
    $cate = 'ポイントが高い作品を持つ作者';
  }else if($_GET['cate']==='HighU'){
    $cate = '読者数が多い作品を持つ作者';
  }else if($_GET['cate']==='HighR'){
    $cate = '登録作品数が多い作者';
  }else{
    $cate = null;
  }
}
////期間の特定
if(!empty($_GET['time_span'])){
  if($_GET['time_span']==='weekly'){
    $time_span = '週間';
  }else if($_GET['time_span']==='monthly'){
    $time_span = '月間';
  }else if($_GET['time_span']==='half'){
    $time_span = '半期';
  }else if($_GET['time_span']==='yearly'){
    $time_span = '年間';
  }else if($_GET['time_span']==='all'){
    $time_span = '累計期間';
  }else{
    $time_span = null;
  }
}
////ポイントの特定
if(!empty($_GET['point_num'])){
  $po_num = $_GET['point_num'];
  if(!empty($po_num['point_from'])){
    $po_from =$po_num['point_from'];
  }else{
    $po_from = '';
  }
  if(!empty($po_num['gan_to'])){
    $po_to = $po_num['gan_to'].'話';
  }else{
    $po_to = '';
  }
}
////ユニークユーザの特定
if(!empty($_GET['unique_num'])){
  $un_num = $_GET['unique_num'];
  if(!empty($un_num['unique_from'])){
    $un_from =$un_num['unique_from'];
  }else{
    $un_from = '';
  }
  if(!empty($un_num['un_to'])){
    $un_to = $un_num['un_to'];
  }else{
    $un_to = '';
  }
}
////総話数の特定
if(!empty($_GET['gan_num'])){
  $gan_num = $_GET['gan_num'];
  if(!empty($gan_num['gan_from'])){
    $gan_from =$gan_num['gan_from'].'話';
  }else{
    $gan_from = '';
  }
  if(!empty($gan_num['gan_to'])){
    $gan_to = $gan_num['gan_to'].'話';
  }else{
    $gan_to = '';
  }
}
////平均更新頻度の特定
if(!empty($_GET['frequency'])){
  if($_GET['frequency']==='morePerM'){
    $frequency = '月１回以上';
  }else if($_GET['frequency']==='morePerW'){
    $frequency = '週１回以上';
  }else if($_GET['frequency']==='morePerD'){
    $frequency = '日１回以上';
  }else{
    $frequency = '';
  }
}

//ランキング作成用
$c = $_GET['cate'];
$t = $_GET['time_span'];
$un = $_GET['unique_num'];
$po = $_GET['point_num'];
$gan = $_GET['gan_num'];
$uf = $_GET['frequency'];

$paramsClass = new PrepareSQL($c, $t, $gan, $uf);

$sql = writerRankSql($c, $t, $gan, $uf);

$result = $db -> query($sql);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L279PCKJGD"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-L279PCKJGD');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@dehonjo" />
    <meta name="description" content="なろう作品のハイファンタジー・VRゲームの良作に出会う機会の創出を目的としたサイトです。独自の指標を設定し、ランキングを作成しています。また、あなたがその指標をカスタマイズすることもできます。">
    <meta property="og:url" content="https://politicalactivityanalysis.com/" />
    <meta property="og:title" content="Making Politics More Accessible" />
    <meta property="og:description" content="なろう, 良作, ランキング, 探す" />
    <meta property="og:image" content="https://politicalactivityanalysis.com/img/twitter_card.png" />
    <meta name="robots" content="all">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="./css/s_style.css">
    <link rel="icon" href="../novel_icon.ico">
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
    @include("layoutes.nav_2nd")

    <ol>
      <div class="container clear">
        <li><a href="https://freenovelanalysis.com/">Home</a></li>
        <li><a href="https://freenovelanalysis.com/search.php">Create</a></li>
        <li>Create Ranking By Writer</li>
      </div>
    </ol>

    <section class="search-set">
        <div class="section-title">Create Ranking</div>
        <div class="section-subtitle">By Writer</div>
        <div class="container clear">
            <div class="word-list">
              <div class="search-set-title-wrapper">
                <div class="search-set-title">Setting</div>
              </div>
              <dl>
                <div class="list-row">
                    <dt>指標</dt>
                    <dd><?php echo $cate;?></dd>
                </div>
                <div class="list-row">
                    <dt>期間</dt>
                    <dd><?php echo $time_span;?></dd>
                </div>
                <div class="list-row">
                    <dt>総話数</dt>
                    <dd><?php echo $gan_from;?> ～ <?php echo $gan_to;?></dd>
                </div>
                <div class="list-row">
                    <dt>ポイント合計</dt>
                    <dd><?php echo $po_from;?> ～ <?php echo $po_to;?></dd>
                </div>
                <div class="list-row">
                    <dt>ユニークユーザ合計</dt>
                    <dd><?php echo $un_from;?> ～ <?php echo $un_to;?></dd>
                </div>
                <div class="list-row">
                    <dt>平均更新頻度</dt>
                    <dd><?php echo $frequency;?></dd>
                </div>
              </dl>
            </div>
        </div>
    </section>

    <section class="result">
      <div class="container clear">
        <div class="result-wrapper">
          <div class="result-tag">Ranking</div>
            <table>
              <tr>
                <th>順位</th>
                <th class="writer"><span class="w-r-writer">作者</span><span class="w-r-writer"> / 作品数</span><span class="w-r-title"> / 主な作品</span></th>
              </tr>
              <?php $i=1?>
              <?php while($record = $result -> fetch()):?>
                <?php if($i % 2 === 1):?>
                  <tr <?php echo "style='background-color:#f9f9f9'"?>>
                <?php else:?>
                  <tr>
                <?php endif;?>
                <?php
                  if($i===1){
                    if($_GET['cate']==='HighR'){
                      $before_ave === '';
                    }else{
                      $before_ave === '';
                    }
                  };
                  if($_GET['cate']==='HighR'){
                    if($record['count']===$before_ave){
                      $rank = $i-1;
                    }else{
                      $rank = $i;
                    };
                  }else{
                    if($record['ave']===$before_ave){
                      $rank = $i-1;
                    }else{
                      $rank = $i;
                    };
                  }
                ?>
                  <th><span class="r-rank"><?php echo $rank;?></span>位</th>
                  <td class="title-writer">
                    <span class="w-r-writer"><?php echo $record['writer']; ?></span>
                    <span class="w-r-writer"> / <?php echo $record['count']; ?></span>
                    <span class="w-r-title"> / <?php echo $record['title']; ?></span>
                  </td>
                  <?php
                  if($_GET['cate']==='HighR'){
                    $before_ave = $record['count'];
                  }else{
                    $before_ave = $record['ave'];
                  };
                  ?>
                  <?php $i++?>
              <?php endwhile;?>
            </table>
            <div class="to-detail">
              <div class="to-detail-title">作者の詳細を知りたい場合</div>
              <form action="../detail/detail_w.php" method="GET" class="to-detail-form">
                <div class="to-detail-input-wrapper">
                  <input type="text" name="writer" placeholder="作者名を入力してください" class="to-detail-input">
                </div>
                <div class="to-detail-btn-space">
                  <input type="submit" value='詳細表示' class="s-submit-btn">
                </div>
              </form>
            </div>
        </div>
      </div>
    </section>

    @include("layouts.footer")

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- jQueryのライブラリー本体を読み込む -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
    <script src="./js/main.js"></script>

</body>
</html>