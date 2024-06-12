/*--- loading画面設定 
// クラス変更
window.onload = function () {
    const spinner = document.getElementById('loading');
    spinner.classList.add('loaded');
}
---*/

//safari対策
window.onpageshow = function (event) {
    if (event.persisted) {
        window.location.reload();
    }
};

/*-- ﾓｰﾀﾞﾙで表示-- */
jQuery('.modal-link').on('click', function(e){
    e.preventDefault();
    var target = jQuery(this).data('target');
    jQuery(target).show();

    return false;
});

//ﾓｰﾀﾞﾙを閉じる
jQuery('.js-close-btn').on('click', function(e){
    e.preventDefault();
    var target = jQuery(this).data('target');
    jQuery(target).hide();

    return false;
});

/*-- スマホナビの表示・非表示 ---*/
$(function() {
    const close = $('#sp-menu, .close')
    const nav = $('.header-items-sp')
    close.on('click', function(){
        nav.toggleClass('toggle');
    });
});

/*-- 画面ﾄｯﾌﾟに戻るﾎﾞﾀﾝの設置-- */
$(function () {
    $("#js-pagetop").click(function () {
        $('html, body').animate({
        scrollTop: 0
        }, 500);
    });
    $(window).scroll(function () {
        if ($(window).scrollTop() > 80) {
        $('#js-pagetop').fadeIn(300).css('display', 'flex')
        } else {
        $('#js-pagetop').fadeOut(300)
        }
    });
});

/*-- 詳細ﾍﾟｰｼﾞにﾃﾞｰﾀを渡す-- */
/*$('.js-detail-btn').on('click' ,function(){
    var text = 'ncode='
    var ncode =  text.concat($(this).getAttribute('id'))
    window.location.href = "detail.php?"+ncode
})

/*-- 詳細検索ブロックを動かせるようにする-- */
$(document).ready(function() {
    var $dragging = null;

    $(document.body).on("mousemove", function(e) {
        if ($dragging) {
            $dragging.offset({
                top: e.pageY - $dragging.data("y"),
                left: e.pageX - $dragging.data("x")
            });
        }
    });

    $(".to-detail").on("mousedown", function(e) {
        var $this = $(this);
        $dragging = $this;
        $this.data("x", e.pageX - $this.offset().left);
        $this.data("y", e.pageY - $this.offset().top);
    });

    $(document.body).on("mouseup", function(e) {
        $dragging = null;
    });
});


/*-- もっと見る、閉じるボタン-- */
$(function(){
    //trの数を取得
    const trItems = 51;
    $('rank-content').each(function(){
        //最初に表示させるtrの数
        let num = 21,
            //閉じたときに表示させるtrの数
            closeNum = num -1;
        //【初期設定】もっと見るﾎﾞﾀﾝ⇒表示、閉じるﾎﾞﾀﾝ⇒非表示
        $(this).find('.js-btn-more').show();
        $(this).find('.js-btn-close').hide();
        //20行目まで表示
        $(this).find("tr:not(:tr(" + num + "))").hide();
        //もっと見るﾎﾞﾀﾝのｸﾘｯｸ時の挙動
        $("js-btn-more").click(function(){
            //numに30足す
            num += 30;
            $(this).parent().find("tr:lt(" + num + ")").slideDown();
            //trの数よりnumが多い滝
            if(trItems <= num){
                //もっと見るﾎﾞﾀﾝ非表示
                $(".js-btn-more").hide();
                //閉じるﾎﾞﾀﾝ表示
                $(".js-btn-close").show();
                //閉じるﾎﾞﾀﾝがｸﾘｯｸされたら6行目以降ｸﾘｯｸ
                $(".js-btn-close").click(function(){
                    $(this).parent().find("tr:gt(" + closeNum + ")");
                    $(this).hide();
                    $(".js-btn-more").show();
                })
            }
        })
    })
})


/*-- 検索ページのtab設定-- */
$('.tab_item').on('click', function(){
    $('.tab_item').removeClass('is-active');
    $(this).addClass('is-active');
    var paneltoshow = $(this).attr('rel');
    $('.panel.is-active').slideUp('100', function(){
        $(this).removeClass('is-active');
        $('#'+paneltoshow).delay('100').slideDown(function(){
            $(this).addClass('is-active');
        });
    });
})

/*-- ガイドﾍﾟｰｼﾞ(親)のtab設定-- */
$('.g_tab_item').on('click', function(){
    $('.g_tab_item').removeClass('is-active');
    $(".g-menu").removeClass('is-active');
    $(this).addClass('is-active');
    var paneltoshow = $(this).attr('rel');
    $('.g-panel-parent.is-active').slideUp('100', function(){
        if(paneltoshow === "how_to_use"){
            $("#g-menu-h").addClass('is-active')
        }else if(paneltoshow === "evaluation"){
            $("#g-menu-e").addClass('is-active')
        }
        $(this).removeClass('is-active');
        $('#'+paneltoshow).delay('100').slideDown(function(){
            $(this).addClass('is-active');
        });
    });
})

/*-- ガイドのメニュー表示-- */
$(function () {
    $("#js-pagetop").click(function () {
        $('html, body').animate({
        scrollTop: 0
        }, 500);
    });
    $(window).scroll(function () {
        if ($(window).scrollTop() > 80) {
        $('#g-menu-h').fadeIn(300).css('display', 'flex')
        } else {
        $('#g-menu-h').fadeOut(300)
        }
    });
});




/*----- アーカイブのセレクトボックス自動送信 
//年
$(function(){
    $("#p-year").change(function(){
        $("#p-form").submit();
        $('#p-month').addClass('is-active')
    })
})
//月
$(function(){
    $("#p-month").change(function(){
        $("#p-form").submit();
        $('#p-day').addClass('is-active')
    })
})
----- */