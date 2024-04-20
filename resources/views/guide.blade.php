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
    <meta name="robots" content="all">
    <meta property="og:url" content="https://freenovelanalysis.com/" />
    <meta property="og:title" content="Search Free Novels" />
    <meta property="og:description" content="なろう作品, 良作, ランキング作成" />
    <meta property="og:image" content="https://freenovelanalysis.com/img/twitter_card.png" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" href="./css/reset.css">
    <link rel="stylesheet" href="./css/style.css">
    <!-- icon -->
    <link rel="icon" href="novel_icon.ico">
    <!-- font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&display=swap" rel="stylesheet">
    <!-- 数式表示用 -->
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <title>Search Narou Works</title>
  </head>
  <body>

    <!--↓サイトコンテンツ-->
    <button id="js-pagetop" class="pagetop"><span class="pagetop__arrow"></span></button>
    <?php require_once('./PHPparts/nav.php')?>

    <ol>
        <div class="container clear">
            <li><a href="https://freenovelanalysis.com/">Home</a></li>
            <li>Guide</li>
        </div>
    </ol>

    <section id="guide" class="guide">
        <div class="container clear">
            <h3 class="section-title">Guide</h3>
            <ul class="guide-tabs  tab-border">
                <li rel=how_to_use class="g_tab_item is-active">使い方</li>
                <li rel=evaluation class="g_tab_item">評価方法</li>
            </ul>
            <article id="how_to_use" class="g-panel-parent is-active">
                <h4 class="panel-title">使い方</h4>
                <section id="g-rank" class="g-panel-sec">
                    <h5 class="panel-subtitle">ランキング閲覧</h5>
                    <p class="g-explain">
                        &emsp;トップページの中段以降に掲載されている、トップ20ランキング。<br>&emsp;プルダウンから選択肢し、「選択」ボタンをクリックすると、ランキングがページ下部に生成される。<br>&emsp;プルダウンの説明は以下のとおり。
                    </p>
                    <div class="article-wrapper">
                        <h6 class="article-header">指標の選択</h6>
                        <div id="g_lowPHighU" class="g-panel-child">
                            <dl>
                                <div class="g-panel-row">
                                    <dt><strong>ポイントの<br>割に読者数<br>は多い作品</strong></dt>
                                    <dd>「指標1」により算出した値を昇順で並べる</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>ポイントの<br>割に読者数<br>は多く、<br>更新頻度が<br>高い作品</strong></dt>
                                    <dd>「指標2」により算出した値を降順で並べる</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>ポイントが<br>高い作品</strong></dt>
                                    <dd>「指標3」により算出した値を降順で並べる</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>読者数が<br>多い作品</strong></dt>
                                    <dd>「指標4」により算出した値を降順で並べる</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>登録作品数</strong></dt>
                                    <dd>ランキング作成の「作者」で作成する場合にのみ選択可能。<br>
                                        作者が「小説家になろう」に登録している作品数を降順に並べる</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <div class="article-wrapper">
                        <h6 class="article-header">期間の選択</h6>
                        <div id="g_lowPHighU" class="g-panel-child">
                            <dl>
                                <div class="g-panel-row">
                                    <dt><strong>週間</strong></dt>
                                    <dd>更新日を基準日として７日間</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>月間</strong></dt>
                                    <dd>更新日を基準日として5週間</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>半期</strong></dt>
                                    <dd>更新日を基準日として26週間</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>年間</strong></dt>
                                    <dd>更新日を基準日として52週間</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt><strong>累計期間</strong></dt>
                                    <dd>更新日を基準日として当サイトがデータを取得し始めた日(2023/1/26)までの間</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <div class="article-wrapper">
                        <h6 class="article-header">総話数帯の選択</h6>
                        <div id="g_lowPHighU" class="g-panel-child">
                            <dl>
                                <div class="g-panel-row">
                                    <dt class="gan_dt"><strong>100話～300話</strong></dt>
                                    <dd class="gan_dt">総話数が100回以上300回未満の作品</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt class="gan_dt"><strong>300話～500話</strong></dt>
                                    <dd class="gan_dt">総話数が300回以上500回未満の作品</dd>
                                </div>
                                <div class="g-panel-row">
                                    <dt class="gan_dt"><strong>500話～</strong></dt>
                                    <dd class="gan_dt">総話数が500回以上の作品</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <div class="article-wrapper">
                        <h6 class="article-header">「選択」ボタンをクリック</h6>
                        <div id="g_lowPHighU" class="g-panel-child">
                            <img src="./img/選択クリック.png" alt="" class="g-img">
                        </div>
                    </div>
                </section>
                <section id="g-create" class="g-panel-sec">
                    <h5 class="panel-subtitle">ランキング作成</h5>
                    <p class="g-explain">
                        &emsp;指標や条件を設定することで、独自のランキングを作成することができる。
                    </p>
                    <div class="article-wrapper">
                        <h6 class="article-header">メニューバーから「Create」を選択</h6>
                        <img src="./img/ランキング作成step1.png" alt="" class="g-img">
                        <h6 class="article-header">タグから「作品」か「作者」を選択（デフォルトは「作品」）</h6>
                        <img src="./img/ランキング作成step2.png" alt="" class="g-img">
                        <h6 class="article-header">【作品編】①から④のプルダウンを各種選択し、作成ボタンをクリック</h6>
                        <img src="./img/ランキング作成step3.png" alt="" class="g-img">
                        <dl>
                            <div class="g-panel-row">
                                <dt>総話数</dt>
                                <dd>作品の全掲載部分数。上限値と下限値を任意で入力</dd>
                            </div>
                        </dl>
                        <h6 class="article-header">【作者編】①から⑥のプルダウンを各種選択し、作成ボタンをクリック</h6>
                        <img src="./img/ランキング作成step4.png" alt="" class="g-img">
                        <dl>
                            <div class="g-panel-row">
                                <dt>ポイント合計</dt>
                                <dd>作者が掲載した作品の獲得ポイント合計値の上限値と下限値を任意で入力</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>ユニークユーザ合計</dt>
                                <dd>作者が掲載した作品のユニークユーザ数（読者数）の上限値と下限値を任意で入力</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>平均更新頻度</dt>
                                <dd>作者が掲載している全作品の平均更新頻度</dd>
                            </div>
                        </dl>
                    </div>
                </section>

            </article>
            <article id="evaluation" class="g-panel-parent">
                <h4 class="panel-title">評価方法</h4>
                <section class="g-panel-sec">
                    <h5 class="panel-subtitle">ランキング基本情報</h5>
                    <div class="article-wrapper">
                        <h6 class="article-header">ランキングの指標</h6>
                        <div id="g_lowPHighU" class="g-panel-child">
                            <div class="article-subheader">ポイントの割に読者数は多い作品</div>
                            <div class="formula">
                                \[ 指標1 = \dfrac{ \displaystyle \sum_{i=1}^{期間n} \dfrac { 週間ﾎﾟｲﾝﾄ_i } { 週間ﾕﾆｰｸﾕｰｻﾞ_i }} {期間n} \]
                            </div>
                            <div class="g-panel-child-wrapper">
                                <dl>
                                    <div class="g-panel-row">
                                        <dt>\[ 週間ﾎﾟｲﾝﾄ \]</dt>
                                        <dd>
                                            なろうapiから取得したデータ<br>
                                            ランキング集計時点から過去7日以内で新たに登録されたブックマークや評価が対象
                                        </dd>
                                    </div>
                                    <div class="g-panel-row">
                                        <dt>\[ 週間ﾕﾆｰｸﾕｰｻﾞ \]</dt>
                                        <dd>
                                            なろうapiから取得したデータ<br>
                                            前週の日曜日から土曜日分のユニークユーザ
                                        </dd>
                                    </div>
                                    <div class="g-panel-row">
                                        <dt>\[ 期間n \]</dt>
                                        <dd>
                                            最新更新日からプルダウンの選択肢にある「期間」分遡った週数
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div id="g_lowPHighUHighF" class="g-panel-child">
                            <div class="article-subheader">ポイントの割に読者数は多く、更新頻度が高い作品</div>
                            <div class="formula">
                                \[ 指標2 =\dfrac {\displaystyle \sum_{i=1}^{期間n} \dfrac { 週間ﾕﾆｰｸﾕｰｻﾞ_i } { 週間ﾎﾟｲﾝﾄ_i } \times 更新頻度_i} { 期間n } \]
                            </div>
                            <div class="g-panel-child-wrapper">
                                <dl>
                                    <div class="g-panel-row">
                                        <dt>\[ 更新頻度 \]</dt>
                                        <dd>
                                            なろうapiから取得したデータを基に当サイトが算出した値で、
                                            １日当たりに作者が掲載した掲載部分数。
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <div id="g_HighP" class="g-panel-child">
                            <div class="article-subheader">ポイントが高い作品</div>
                            <p class="formula">
                                \[ 指標3 =\displaystyle \sum_{i=1}^{期間n} 週間ﾎﾟｲﾝﾄ_i \]
                            </p>
                            <div class="g-panel-child-wrapper">
                                
                            </div>
                        </div>

                        <div id="g_HighU" class="g-panel-child">
                            <div class="article-subheader">読者数が多い作品を持つ作者</div>
                            <p class="formula">
                                \[ 指標4 = \displaystyle \sum_{i=1}^{期間n} 週間ﾕﾆｰｸﾕｰｻﾞ_i\]
                            </p>
                            <div class="g-panel-child-wrapper">
                                
                            </div>
                        </div>

                        <div id="g_HighR" class="g-panel-child">
                            <div class="article-subheader">登録作品数</div>
                            <p class="g-explain">
                                &emsp;作者が「小説家になろう」に登録されしている作品のうち、
                                当サイトが取得したデータに含まれる作品数
                            </p>
                        </div>
                    </div>
                </section>
                <section class="g-panel-sec">
                    <h5 class="panel-subtitle">データ基本情報</h5>
                    <div class="article-wrapper">
                        <img src="" alt="">
                        <p class="text">
                            当サイトでランキング作成に使用しているデータに関する事項。
                        </p>
                        <h6 class="article-header">データ取得に関する事項</h6>
                        <dl>
                            <div class="g-panel-row">
                                <dt>取得日</dt>
                                <dd>毎週月曜日</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>取得開始日</dt>
                                <dd>2023年1月23日</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>取得方法</dt>
                                <dd><a href="https://dev.syosetu.com/man/api/">なろう小説API</a>を使用</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>取得条件</dt>
                                <dd>
                                    <span class="bullet">全掲載部分数が100以上の作品</span><br>
                                    <span class="bullet">ｼﾞｬﾝﾙがﾊｲﾌｧﾝﾀｼﾞ-又はVRｹﾞｰﾑの作品</span><br>
                                    <span class="bullet">週間ﾕﾆｰｸﾕｰｻﾞが100以上の作品</span>
                                </dd>
                            </div>
                        </dl>
                        <h6 class="article-header">なろう小説APIにより取得したデータに関する事項</h6>
                        <p>なろう小説APIで取得可能な項目のうち、当サイトが使用しているデータは以下のとおり</p>
                        <dl>
                            <div class="g-panel-row">
                                <dt>title</dt>
                                <dd>小説名</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>ncode</dt>
                                <dd>Nコード</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>writer</dt>
                                <dd>作者名</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>genre</dt>
                                <dd>ジャンル<br>
                                <span>当サイトでは、ﾊｲﾌｧﾝﾀｼﾞ-・VRｹﾞｰﾑを対象</span></dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>general_lastup</dt>
                                <dd>最終掲載日</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>general_all_no</dt>
                                <dd>全掲載部分数(当サイトでは、総話数と呼称)</dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>weekly_point</dt>
                                <dd>週間ﾎﾟｲﾝﾄ<br>
                                    ランキング集計時点から過去7日以内で新たに登録されたブックマークや評価が対象
                                </dd>
                            </div>
                            <div class="g-panel-row">
                                <dt>weekly_unique</dt>
                                <dd>週間ﾕﾆｰｸﾕｰｻﾞ<br>
                                    前週の日曜日から土曜日分のユニークの合計<br>
                                    毎週火曜日早朝に更新
                                </dd>
                            </div>
                        </dl>
                    </div>
                </section>
                <section id="notion" class="notion">
                    <h5 class="panel-subtitle">注意事項</h5>
                    <div class="article-wrapper">
                        <img src="" alt="">
                        <p class="notion-text">
                            「小説家になろう」はヒナプロジェクトの登録商標です。<br><br>
                            当サイトは小説家になろう、及びヒナプロジェクト社とは関係のない非公式サイトです。
                        </p>
                    </div>
                </section>
            </article>

        </div>
    </section>

    <?php require_once('./PHPparts/footer.php')?>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- jQueryのライブラリー本体を読み込む -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
    <script src="./js/main.js"></script>
  </body>
</html>