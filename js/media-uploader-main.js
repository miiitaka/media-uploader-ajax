(function($) {
	$(function() {
		var custom_uploader = wp.media({
			title: 'Choose Image',
			library: {
				type: 'image'
			},
			button: {
				text: 'Choose Image'
			},
			multiple: false
		});

		custom_uploader.on("select", function () {
			//var index = $("div").index(this.parent());
			var images = custom_uploader.state().get('selection');

			images.each(function(file) {
				$('.current-item').find(".image-url").val(file.toJSON().url);
				$('.current-item').find(".image-view").attr("src", file.toJSON().url);
			});
			$('.current-item').removeClass('current-item');
			// var num = $(this).parents('.item-set').attr('id').replace( 'item-set--', '' )
			// adapt(num)
		});

		$(document).on("click", '.media-upload', function(e) {
			e.preventDefault();
			$(this).parents('li').addClass('current-item');
			custom_uploader.open();
		});
		$(document).on("click", '.media-modal-icon, .media-modal-backdrop', function(e) {
			e.preventDefault();
			$('.current-item').removeClass('current-item');
		});


		// // 初期表示
		// $('.item-set').each( function(){
		// 	if( $(this).find('.data-item__item-list').length===0) {
		// 		//$('.data-item ul').append('<li class="data-item__no-item">人気のキーワードがありません。</li>');
		// 	}
		// })

		// 項目追加処理
		$('.add-data-item__button').on( 'click', function(){
			var num = $(this).parents('.item-set').attr('id').replace( 'item-set--', '' )
			addItem(num)
			//$('.data-item__no-item').remove()
		})
			// 入力時のデータ変更処理
			$(document).on( 'change','.data-item__imageurl', function(){
				var num = $(this).parents('.item-set').attr('id').replace( 'item-set--', '' )
				adapt(num)
			})
			$(document).on( 'change','.data-item__link', function(){
				var num = $(this).parents('.item-set').attr('id').replace( 'item-set--', '' )
				adapt(num)
			})
			$(document).on( 'click','.data-item__blank', function(){
				var num = $(this).parents('.item-set').attr('id').replace( 'item-set--', '' )
				adapt(num)
			})
		$('#form').on( 'submit', function(){
			adapt(1)
			adapt(2)
			//alert('submit')
		})
		//項目削除処理
		$(document).on( 'click', '.del-data-item__button', function(){
			$(this).parents('li').remove()
			// var num = $(this).parents('.item-set').attr('id').replace( 'item-set--', '' )
			// adapt(num)
		})

		function addItem(num){
			$('#item-set--'+num).find('.data-item ul').append(`
				<li class="data-item__item-list">
					<span class="handle"></span>
					<div class="data-item__image-wrap">
						<img class="data-item__image image-view" src="../wp-content/plugins/add-banner/images/no-image.gif">
						<button type="button" class="media-upload"><span class="dashicons dashicons-format-image"></span></button>
					</div>
					<input type="hidden" value="" size="20" class="data-item__imageurl image-url">
					<p>リンク先URL：<input type="text" value="" size="20" class="data-item__link"></p>
					<p>別タブ表示：<input type="checkbox" value="" class="data-item__blank"></p>
					<p>代替テキスト：<input type="text" value="" class="data-item__text"></p>
					<span class="del-data-item">
						<span class="del-data-item__button"></span>
					</span>
				</li>`)
		}
	});
	function adapt(num){
		var dataArr = '';
		$('#item-set--'+num).find('.data-item__item-list').each( function(){
			var itemImage = $(this).find('.data-item__imageurl').val()
			var itemLink = $(this).find('.data-item__link').val()
			var itemBlank = $(this).find('.data-item__blank').prop("checked") ? true : false
			var itemText = $(this).find('.data-item__text').val()
			if(dataArr==''){
				if(itemImage!=''){
					dataArr = itemImage + '__|' + itemLink + '__|' + itemBlank + '__|' + itemText
				}
			} else {
				if(itemImage!=''){
					dataArr = dataArr +','+ itemImage + '__|' + itemLink + '__|' + itemBlank + '__|' + itemText
				}
			}
		})
		$('#item-set--'+num).find('.submit-arr').val(dataArr)
	}
})(jQuery);