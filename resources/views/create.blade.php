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
    <meta name="description" content="なろう作品のハイファンタジー・VRゲームの良作に出会う機会の創出を目的としたサイトです。独自の指標を設定し、ランキングを作成しています。また、あなたがその指標をカスタマイズすることもできます。">
    <meta property="og:url" content="https://freenovelanalysis.com/" />
    <meta property="og:title" content="Search Free Novels" />
    <meta property="og:description" content="なろう作品, 良作, ランキング作成" />
    <meta property="og:image" content="https://freenovelanalysis.com/img/twitter_card.png" />
    <meta name="robots" content="all">

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
    @include("layouts.nav")

    <ol>
        <li><a href="https://freenovelanalysis.com/">Home</a></li>
        <li>Create</li>
    </ol>

    <section id="search" class="search">
        <div class="container clear">
            <h3 class="section-title">ランキング作成</h3>
            <ul class="search-tabs  tab-border">
                <li rel=by_title class="tab_item is-active">作品</li>
                <li rel=by_writer class="tab_item">作者</li>
            </ul>
            <div id="by_title" class="panel is-active">
                <form action="result.php" method="get" class="search_content">
                    <dl>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="cate">指標</label><span>必須</span></dt>
                            <dd class="s-form-explain">
                                <div class="select-wrapper">
                                    <select name="cate" id="cate" class="cate">
                                        <option value="lowPHighU" selected>ポイントの割に読者数は多い作品</option>
                                        <option value="lowPHighUHighF">ポイントの割に読者数は多く、更新頻度が高い作品</option>
                                        <option value="HighP">ポイントが高い作品</option>
                                        <option value="HighU">読者数が多い作品</option>
                                    </select>
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="time_span">期間</label></dt>
                            <dd class="s-form-explain">
                                <div class="select-wrapper">
                                    <select name="time_span" id="time-span" class="time-span">
                                    <option value="weekly">週間</option>
                                    <option value="monthly">月間</option>
                                    <option value="half">半期</option>
                                    <option value="yearly">年間</option>
                                    <option value="all" selected>累計</option>
                                    </select>
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="general_all_no">総話数</label></dt>
                            <dd class="s-form-explain">
                                <div class="range">
                                    <input type="text" id="general_all_no_from" name="gan_num[gan_from]" class="gan-text" placeholder="0">
                                    <span> ～ </span>
                                    <input type="text" id="general_all_no_to" name="gan_num[gan_to]" class="gan-text" placeholder="入力してください">
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row ver_radio">
                            <dt class="s-form-item ver_radio">
                                <label for="frequency">平均更新頻度</label>
                            </dt>
                            <dd class="s-form-explain select">
                                <div class="select-wrapper">
                                    <select name="frequency" id="frequency" class="frequency">
                                    <option value="" selected class="default">選択してください</option>
                                    <option value="morePerM" class="morePerM">月1回以上</option>
                                    <option value="morePerW" class="morePerW">週1回以上</option>
                                    <option value="morePerD" class="morePerD">日1回以上</option>
                                    </select>
                                </div>
                            </dd>
                        </div>
                    </dl>
                    <div class="submit-btn-space">
                    <input type="submit" value="作成" class="submit-btn">
                    </div>
                </form>
            </div>
            <div id="by_writer" class="panel">
                <form action="./search/result_w.php" method="get" class="search_content">
                    <dl>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="cate">指標</label><span>必須</span></dt>
                            <dd class="s-form-explain">
                                <div class="select-wrapper">
                                    <select name="cate" id="cate" class="cate">
                                        <option value="lowPHighU" selected>ポイントの割に読者数は多い作品を持つ作者</option>
                                        <option value="lowPHighUHighF">ポイントの割に読者数は多く、更新頻度が高い作品を持つ作者</option>
                                        <option value="HighP">ポイントが高い作品を持つ作者</option>
                                        <option value="HighU">読者数が多い作品を持つ作者</option>
                                        <option value="HighR">登録作品数が多い作者</option>
                                    </select>
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="time_span">期間</label></dt>
                            <dd class="s-form-explain">
                                <div class="select-wrapper">
                                    <select name="time_span" id="time-span" class="time-span">
                                    <option value="weekly">週間</option>
                                    <option value="monthly">月間</option>
                                    <option value="half">半期</option>
                                    <option value="yearly">年間</option>
                                    <option value="all" selected>累計</option>
                                    </select>
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="general_all_no">総話数</label></dt>
                            <dd class="s-form-explain">
                                <div class="range">
                                    <input type="text" id="general_all_no_from" name="gan_num[gan_from]" class="gan-text" placeholder="0">
                                    <span> ～ </span>
                                    <input type="text" id="general_all_no_to" name="gan_num[gan_to]" class="gan-text" placeholder="入力してください">
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="point">ポイント合計</label></dt>
                            <dd class="s-form-explain">
                                <div class="range">
                                    <input type="text" id="point_from" name="point_num[point_from]" class="point-text" placeholder="0">
                                    <span> ～ </span>
                                    <input type="text" id="point_to" name="point_num[point_to]" class="point-text" placeholder="入力してください">
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row">
                            <dt class="s-form-item"><label for="unique">ユニークユーザ合計</label></dt>
                            <dd class="s-form-explain">
                                <div class="range">
                                    <input type="text" id="unique_from" name="unique_num[unique_from]" class="unique-text" placeholder="0">
                                    <span> ～ </span>
                                    <input type="text" id="unique_to" name="unique_num[unique_to]" class="unique-text" placeholder="入力してください">
                                </div>
                            </dd>
                        </div>
                        <div class="s-form-row ver_radio">
                            <dt class="s-form-item ver_radio">
                                <label for="frequency">平均更新頻度</label>
                            </dt>
                            <dd class="s-form-explain select">
                                <div class="select-wrapper">
                                    <select name="frequency" id="frequency" class="frequency">
                                    <option value="" selected class="default">選択してください</option>
                                    <option value="morePerM" class="morePerM">月1回以上</option>
                                    <option value="morePerW" class="morePerW">週1回以上</option>
                                    <option value="morePerD" class="morePerD">日1回以上</option>
                                    </select>
                                </div>
                            </dd>
                        </div>
                    </dl>
                    <div class="submit-btn-space">
                        <input type="submit" value="作成" class="submit-btn">
                    </div>
                </form>
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