<!DOCTYPE html>
<html lang="ja">
<head>
  @include('layouts.common')
  <!-- 固有CSS -->
  <link rel="stylesheet" href="{{asset('css/result.css')}}">  
</head>
<body>
    <!--↓サイトコンテンツ-->
    <button id="js-pagetop" class="pagetop"><span class="pagetop__arrow"></span></button>
    @include('layouts.nav')

    <ol>
      <div class="container clear">
        <li><a href="{{route('top-rank.show')}}">Home</a></li>
        <li><a href="{{route('create.show')}}">Create</a></li>
        <li>Create Ranking By Writer</li>
      </div>
    </ol>

    <section class="search-set">
        <div class="section-title">Create Ranking</div>
        <div class="section-subtitle">By Writer</div>
        <div class="container clear">
          @include('layouts.exception')
            <div class="word-list">
              <div class="search-set-title-wrapper">
                <div class="search-set-title">Setting</div>
              </div>
              <dl>
                <div class="list-row">
                    <dt>指標</dt>
                    <dd>{{ $select_data['cate'] }}</dd>
                </div>
                <div class="list-row">
                    <dt>期間</dt>
                    <dd>{{ $select_data['time_span'] }}</dd>
                </div>
                <div class="list-row">
                    <dt>ポイント合計</dt>
                    <dd>{{ $select_data['point_from'] }} ～ {{ $select_data['point_to'] }}</dd>
                </div>
                <div class="list-row">
                    <dt>ユニークユーザ合計</dt>
                    <dd>{{ $select_data['unique_from'] }} ～ {{ $select_data['unique_to'] }}</dd>
                </div>
                <div class="list-row">
                    <dt>平均更新頻度</dt>
                    <dd>{{ $select_data['frequency'] }}</dd>
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
                <th class="writer">
                    <span class="w-r-writer">作者</span>
                    <span class="w-r-writer"> / 作品数</span>
                    <span class="w-r-title"> / 主な作品</span>
                </th>
            </tr>
            @php $i = 1; @endphp
            @foreach($result as $record)
                @if($i % 2 === 1)
                    <tr style="background-color:#f9f9f9">
                @else
                    <tr>
                @endif
        
                @if($i === 1)
                    @if(request('cate') === 'HighR')
                        @php $before_ave = ''; @endphp
                    @else
                        @php $before_ave = ''; @endphp
                    @endif
                @endif
        
                @if(request('cate') === 'HighR')
                    @php
                        $rank = ($record['count'] === $before_ave) ? $i - 1 : $i;
                    @endphp
                @else
                    @php
                        $rank = ($record['ave'] === $before_ave) ? $i - 1 : $i;
                    @endphp
                @endif
        
                <th><span class="r-rank">{{ $rank }}</span>位</th>
                <td class="title-writer">
                    <span class="w-r-writer">{{ $record['writer'] }}</span>
                    <span class="w-r-writer"> / {{ $record['count'] }}</span>
                    <span class="w-r-title"> / {{ $record['title'] }}</span>
                </td>
        
                @if(request('cate') === 'HighR')
                    @php $before_ave = $record['count']; @endphp
                @else
                    @php $before_ave = $record['ave']; @endphp
                @endif
        
                @php $i++; @endphp
            @endforeach
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
    <script src="{{ asset('js/main.js') }}"></script>

</body>
</html>