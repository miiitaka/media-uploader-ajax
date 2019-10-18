$('#delSet').submit(function(event) {
    event.preventDefault();
    var $form = $(this);
    var $button = $('.item-set__del');
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
        },
        // 通信失敗時の処理
        error: function(xhr, textStatus, error) {
            alert('エラー。暫くたってからお試しください。');
        }
    });
});
$( function(){
    $(document).on('click', '.item-set__del', function(event){
        event.preventDefault();
        var target = $(this).val()
        $('.set-del-button').val(target)
        $('#delSet').submit()
    })
})