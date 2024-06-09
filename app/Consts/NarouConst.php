<?php

//定数の管理用ﾌｧｲﾙ
namespace App\Consts;

class NarouConst
{
    //Form pulldown list
    const MARK = 'lowPHighU';
    const CALC = 'lowPHighUHighF';
    const POINT = 'HighP';
    const UNIQUE = 'HighU';
    const REGISTERED_TITLES = 'HighR';
    const GAN_OVER100_UNDER300='from100to300';
    const GAN_OVER300_UNDER500='from300to500';
    const GAN_OVER500='from500';
    const FREQUENCY_1TIMEPERDAY = 'morePerD';
    const FREQUENCY_1TIMEPERWEEK = 'morePerw';
    const FREQUENCY_1TIMEPERMONTH = 'morePerM';
    const FREQUENCY_1TIMEPERHALF = 'morePerH';
    const FREQUENCY_1TIMEPERYEAR = 'morePerY';
    const FREQUENCY_ELSE = 'else';
    const TIME_SPAN_WEEKLY = 'weekly';
    const TIME_SPAN_MONTHLY = 'monthly';
    const TIME_SPAN_HALF = 'half';
    const TIME_SPAN_YEARLY = 'yearly';
    const TIME_SPAN_ALL = 'all';
    //TopPage Form select tag
    const SELECT_TOP_GENERAL_ALL_NO = 'r_gan';
    const SELECT_TOP_CATEGORY = 'r_cate';
    const SELECT_TOP_TIMESPAN = 'r_time_span';
    const SELECT_TOP_FREQUENCY = 'frequency';
    //CreatePage Form select tag
    const SELECT_CREATE_CATEGORY = 'cate';
    const SELECT_CREATE_TIMESPAN = 'time_span';
    const SELECT_CREATE_FREQUENCY = 'frequency';
    //CreatePage Form input tag
    const INPUT_GENERAL_ALL_NO = 'gan_num';
    const INPUT_GENERAL_ALL_NO_FROM = 'gan_from';
    const INPUT_GENERAL_ALL_NO_TO = 'gan_to';
    const INPUT_POINT = 'point_num';
    const INPUT_POINT_FROM = 'point_from';
    const INPUT_POINT_TO = 'point_to';
    const INPUT_UNIQUE = 'unique_num';
    const INPUT_UNIQUE_FROM = 'unique_from';
    const INPUT_UNIQUE_TO = 'unique_to';
    //Table名
    const TBL_MA = 'ma';
    const TBL_POINT = 'point';
    const TBL_UNIQUE = 'unique';
    const TBL_MARK = 'mark';
    const TBL_UF = 'update_frequency';
    const TBL_CALC = 'calc';
    //Pageに表示する項目
    const CATEGORY_MARK = 'ポイントの割に読者数は多い作品';
    const CATEGORY_CALC = 'ポイントの割に読者数は多く、更新頻度が高い作品';
    const CATEGORY_POINT = 'ポイントが高い作品';
    const CATEGORY_UNIQUE = 'ユニークユーザが高い作品';
    const CATEGORY_ERROR = '種類の選択が無効です';
    const GAN_OVER_100_UNDER_300_TEXT = '100話以上300話未満';
    const GAN_OVER_300_UNDER_500_TEXT = '300話以上500話未満';
    const GAN_OVER_500_TEXT = '500話以上';
    const TOPPAGE_TITLE_WEEKLY = '週間ランキング';
    const TOPPAGE_TITLE_MONTHLY = '月間ランキング';
    const TOPPAGE_TITLE_HALF = '半期ランキング';
    const TOPPAGE_TITLE_YEARLY = '年間ランキング';
    const TOPPAGE_TITLE_ALL_TERMS = '累計期間ランキング';
    const WEEKLY_TEXT = '週間';
    const MONTHLY_TEXT = '月間';
    const HALF_TEXT = '半期';
    const YEARLY_TEXT = '年間';
    const ALL_TEXT = '累計期間';
    const FREQUENCY_TEXT_1TIMEPERDAY = '日に１回以上';
    const FREQUENCY_TEXT_1TIMEPERWEEK = '週に１回以上';
    const FREQUENCY_TEXT_1TIMEPERMONTH = '月に１回以上';
    const FREQUENCY_TEXT_1TIMEPERHALF = '半期に１回以上';
    const FREQUENCY_TEXT_1TIMEPERYEAR = '年に１回以上';
    //固定値
    const FREQUENCY_VALUE_1TIMEPERDAY = 1;
    const FREQUENCY_VALUE_1TIMEPERWEEK = 1/7;
    const FREQUENCY_VALUE_1TIMEPERMONTH = 1/30;
    const FREQUENCY_VALUE_1TIMEPERHALF = 1/182;
    const FREQUENCY_VALUE_1TIMEPERYEAR = 1/365;
    const FREQUENCY_VALUE_ELSE = 0;
    //詳細ページで使用
    const UNRANKED = 'ランク外';



}
