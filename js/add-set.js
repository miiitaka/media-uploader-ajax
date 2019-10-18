$('#addSet').submit(function(event) {
    event.preventDefault();
    var $form = $(this);
    var $button = $form.find('.add-set-submit');
    $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: $form.serialize(),
        timeout: 10000,  // 単位はミリ秒
        // 送信前
        beforeSend: function(xhr, settings) {
            // ボタンを無効化し、二重送信を防止
            $button.attr('disabled', true);
        },
        // 応答後
        complete: function(xhr, textStatus) {
            // ボタンを有効化し、再送信を許可
            $button.attr('disabled', false);
        },
        // 通信成功時の処理
        success: function(result, textStatus, xhr) {
            // 入力値を初期化
            $form[0].reset();
            $(".item-set-wrap").append(result);
            var delCount = $('.item-set--del').length
            var addCount = parseInt($('.add-count').val())+1
            $('.add-count').val( addCount )
            $('.item-set--del').appendTo('.item-set-wrap')
            setTimeout( function(){
                $('.item-set').each( function(i,elm){
                    var $target = $(this)
                    var num = i+1
                    $target.attr('id','item-set--'+num)
                    $target.find('.hidden_class').attr('name','mt_submit_hidden'+num)
                    $target.find('ul').attr('id','sortableList'+num)
                    $target.find('.submit-arr').attr('name','mt_banners'+num)
                })
            },1000)
        },
        // 通信失敗時の処理
        error: function(xhr, textStatus, error) {
            alert('エラー。暫くたってからお試しください。');
        }
    });
});