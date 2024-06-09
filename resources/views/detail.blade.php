<!doctype html>
<html lang="jp">
  @include('layouts.common')
<body>

  <!--↓サイトコンテンツ-->
  <button id="js-pagetop" class="pagetop"><span class="pagetop__arrow"></span></button>
  @include("layouts.nav")
  
  <ol>
    <div class="container clear">
      <li><a href="{{route('top-rank.show')}}">Home</a></li>
      <li><a href="{{route('create.show')}}">Create</a></li>
      <li><a href="{{route('result.show')}}">Create Ranking By Works</a></li>
      <li>Detail Works</li>
    </div>
  </ol>

  <section class="detail">
    <div class="container clear">
      <div class="detail-title">
        {{ $result['introduction']['title'] }}
      </div>
      <div class="update">
        Last Up Date: <time>{{ $result['introduction']['last_update'] }}</time>
      </div>
      <dl>
        <div class="d-row">
          <dt>作者名</dt>
          <dd>{{ $result['introduction']['writer'] }}</dd>
        </div>
        <div class="d-row">
          <dt>総話数</dt>
          <dd>{{ $result['introduction']['general_all_no'] }}</dd>
        </div>
        <div class="d-row">
          <dt>平均更新頻度</dt>
          <dd>{{ $result['introduction']['update_frequency'] }}</dd>
        </div>
      </dl>
      <div class="charts-wrapper">
        <div class="c-card">
          <div class="c-card-title flexible">指標1(ポイントの割に読者数は多い作品)</div>
          <canvas id="chart_mk" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd>{{ $result['rank_mark']['weekly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd>{{ $result['rank_mark']['monthly'] }}<span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>年間</dt><dd>{{ $result['rank_mark']['yearly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd>{{ $result['rank_mark']['all'] }}<span>位</span></dd></div>
            </div>
          </div>
        </div>
        <div class="c-card">
          <div class="c-card-title flexible">指標2(ポイントの割に読者数は多く、更新頻度が高い作品)</div>
          <canvas id="chart_calc" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd>{{ $result['rank_calc']['weekly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd>{{ $result['rank_calc']['monthly'] }}<span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>年間</dt><dd>{{ $result['rank_calc']['yearly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd>{{ $result['rank_calc']['all'] }}<span>位</span></dd></div>
            </div>
          </div>
        </div>
        <div class="c-card">
          <div class="c-card-title flexible">指標3(ポイントが高い作品)</div>
          <canvas id="chart_po" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd>{{ $result['rank_point']['weekly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd>{{ $result['rank_point']['monthly'] }}<span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>年間</dt><dd>{{ $result['rank_point']['yearly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd>{{ $result['rank_point']['all'] }}<span>位</span></dd></div>
            </div>
          </div>
        </div>
        <div class="c-card">
          <div class="c-card-title">指標4(ユニークユーザ数が多い作品)</div>
          <canvas id="chart_un" class="canvas"></canvas>
          <div class="c-card-rank-wrapper">
            <div class="c-c-r-row">
              <div class="row-item"><dt>週間</dt><dd>{{ $result['rank_unique']['weekly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>月間</dt><dd>{{ $result['rank_unique']['monthly'] }}<span>位</span></dd></div>
            </div>
            <div class="c-c-r-row">
              <div class="row-item"><dt>年間</dt><dd>{{ $result['rank_unique']['yearly'] }}<span>位</span></dd></div>
              <div class="row-item"><dt>累計期間</dt><dd>{{ $result['rank_unique']['all'] }}<span>位</span></dd></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  @include("layouts.footer")

  <!-- jQueryのライブラリー本体を読み込む -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- 必ずjQuery本体を読み込んだ後にjQueryで書いたファイルを読み込む-->
  <script src="{{asset('js/main.js')}}"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js"
    integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg=="
    crossorigin="anonymous"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@next/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
  <script>
    var ctx = document.getElementById('chart_mk');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [{{ $gragh_data['time_spans_for_g'] }} >],
        datasets: [{
          label: '指標1の値',
          data: [{{ $gragh_data['mark_for_g'] }}],
          borderColor: '#5eccb0ff',
        }]
      }
    })

    var ctx = document.getElementById('chart_calc');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [{{ $gragh_data['time_spans_for_g'] }}],
        datasets: [{
          label: '指標2の値',
          data: [{{ $gragh_data['calc_for_g'] }}],
          borderColor: '#bed630',
        }]
      }
    })

    var ctx = document.getElementById('chart_po');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [{{ $gragh_data['time_spans_for_g'] }}],
        datasets: [{
          label: '週間獲得ポイント数',
          data: [{{ $gragh_data['point_for_g'] }}],
          borderColor: '#f25814',
        }]
      }
    })

    var ctx = document.getElementById('chart_un');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [{{ $gragh_data['time_spans_for_g'] }}],
        datasets: [{
          label: '週間ユニークユーザ数',
          data: [{{ $gragh_data['unique_for_g'] }}],
          borderColor: '#6fa8dc',
        }]
      }
    })
  </script>
</body>
</html>