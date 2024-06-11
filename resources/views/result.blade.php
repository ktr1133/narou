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
          <li>Create Ranking By Works</li>
        </div>
      </ol>

      <section class="search-set">
          <div class="section-title">Create Ranking</div>
          <div class="section-subtitle">By Works</div>
          <div class="container clear">
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
                      <dt>総話数</dt>
                      <dd>{{ $select_data['gan_from'] }} ～ {{ $select_data['gan_to'] }}</dd>
                  </div>
                  <div class="list-row">
                      <dt>ポイント数</dt>
                      <dd>{{ $select_data['point_from'] }} ～ {{ $select_data['point_to'] }}</dd>
                  </div>
                  <div class="list-row">
                      <dt>ユニークユーザ数</dt>
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
                    <th class="title-writer">
                        <span class="r-title">作品名</span>
                        <span class="r-writer"> / 作者 / </span>
                        <span class="r-ncode">ncode</span>
                    </th>
                </tr>
                @php $i = 1 @endphp
                @foreach($result as $record)
                    @if($i % 2 === 1)
                        <tr style="background-color:#f9f9f9">
                    @else
                        <tr>
                    @endif
                        @php
                            if($i === 1){
                                $before_ave = '';
                            };
                            if($record['ave'] === $before_ave){
                                $rank = $i - 1;
                            } else {
                                $rank = $i;
                            };
                        @endphp
                        <th><span class="r-rank">{{ $rank }}</span>位</th>
                        <td class="title-writer">
                            <span class="r-title">{{ $record['title'] }}</span>
                            <span class="r-writer"> / {{ $record['writer'] }} / </span>
                            <span class="r-ncode">{{ $record['ncode'] }}</span>
                        </td>
                        @php $before_ave = $record['ave'] @endphp
                        @php $i++ @endphp
                    </tr>
                @endforeach
            </table>
            <div class="to-detail">
                <div class="to-detail-title">作品の詳細を知りたい場合</div>
                <form action="{{ route('detail.show') }}" method="GET" class="to-detail-form">
                  <div class="to-detail-input-wrapper">
                    <input type="text" name="ncode" placeholder="ncode" class="to-detail-input">
                  </div>
                  <div class="to-detail-btn-space">
                    <input type="submit" value='詳細表示' class="s-submit-btn">
                  </div>
                </form>
              </div>
          </div>
        </div>
      </section>
      <div class="modal target-modal">
          <div class="modal__overlay"></div>
          <div class="pre-detail-wrapper">
              <p class="title"></p>
              <div class="modal-content">
                  <div class="modal-btn">
                      <a href="" class="js-close-btn" data-target=".target-modal">閉じる</a>
                  </div>
                  <div class="close-btn">
                      <a href="" class="js-close-btn" data-target=".target-modal"></a>
                  </div>
              </div>
          </div>
      </div>


      @include("layouts.footer")

      <!-- Option 1: Bootstrap Bundle with Popper -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
      <!-- jQueryのライブラリー本体を読み込む -->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
      <script src="../js/main.js"></script>

  </body>
</html>