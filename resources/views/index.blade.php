<!doctype html>
<html lang="jp">
<head>
    @include('layouts.common')
    <!-- 固有CSS -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>        
<body>

    <!--↓サイトコンテンツ-->
    <button id="js-pagetop" class="pagetop"><span class="pagetop__arrow"></span></button>
    @include('layouts.nav')

    @include('layouts.top')

    @include('layouts.about')

    <section id="ranking" class="ranking">
        <div class="rank-bg">
            <div class="container clear">
                <h3 class="section-title">ランキング</h3>
                <div class="rank-text">Last Up Date：<time>{{$last_date}}</time></div>
                <form action="{{route('top-rank.show')}}" method="get" class="rank_content">
                    @csrf
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
                        <div class="section-subtitle">{{$select_date['title']}}</div>
                        <div class="r-frame-items">
                            <div class="r-f-item"><div class="r-f-item-name">期間</div><div class="r-f-item-text">{{$select_date['r_text_time']}}</div></div>
                            <div class="r-f-item"><div class="r-f-item-name">種類</div><div class="r-f-item-text">{{$select_date['r_text_cate']}}</div></div>
                            <div class="r-f-item"><div class="r-f-item-name">総話数帯</div><div class="r-f-item-text">{{$select_date['r_text_gan']}}</div></div>
                        </div>
                    </div>
                    <div class="rank-content">
                        <table>
                            <tr>
                                <th>順位</th>
                                <th class="title-writer">
                                    <span class="r-title">作品名</span>
                                    <span class="r-writer"> / 作者 / </span>
                                    <span class="r-ncode">ncode</span>
                                </th>
                            </tr>
                            @php
                                $i = 1;
                                $j = 1;
                                $beforeAve = null;
                            @endphp
                            @foreach ($result as $record)
                                <tr @class(['even-row' => $j %2 === 0, 'odd-row' => $j %2 !== 0,])>
                                    @php
                                        $rank = $record['ave'] === $beforeAve ? ($i === 1 ? $i : $i - 1) : $i;
                                        $beforeAve = $record['ave'];
                                        $i++;
                                        $j++;
                                    @endphp
                                    <th><span class="r-rank">{{ $rank }}</span>位</th>
                                    <td class="title-writer">
                                        <span class="r-title">{{ $record['title'] }}</span>
                                        <span class="r-writer"> / {{ $record['writer'] }} / </span>
                                        <span class="r-ncode">{{ $record['ncode'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div class="to-detail">
                            <div class="to-detail-title">詳細検索</div>
                            <form action="{{route('detail.show')}}" method="POST" class="to-detail-form">
                                @csrf
                                <div class="to-detail-input-wrapper">
                                    <input type="text" name="ncode" placeholder="ncode" class="to-detail-input">
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

    @include('layouts.contact')

    @include('layouts.footer')

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- jQueryのライブラリー本体を読み込む -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
    <script src="{{asset('js/main.js')}}"></script>
</body>
</html>