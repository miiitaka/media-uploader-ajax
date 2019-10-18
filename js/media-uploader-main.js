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
			var images = custom_uploader.state().get('selection');
			images.each(function(file) {
				$('.current-item').find(".image-url").val(file.toJSON().url);
				$('.current-item').find(".image-view").attr("src", file.toJSON().url);
			});
			$('.current-item').removeClass('current-item');
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

		// 項目追加処理
		$(document).on( 'click','.add-data-item__button', function(){
			var num = $(this).parents('.item-set').attr('id').replace( 'item-set--', '' )
			addItem(num)
		})

		//バナーセット削除処理
		$(document).on( 'click', '.item-set__del', function(){
			$(this).next('ul').find('li').each( function(){
				$(this).find('.del-data-item__button').click()
			})
			$target = $(this).parents('.item-set')
			$target.attr('id','item-set--999')
			$target.find('.hidden_class').attr('name','mt_submit_hidden999')
			$target.find('ul').attr('id','sortableList999')
			$target.find('.submit-arr').attr('name','mt_banners999')
			$target.find('.item-set__title').val('')
			$target.find('.item-set__php').val('')
			$target.appendTo('.item-set-wrap')
			var $this = $(this);
			setTimeout( function(){
				adapt()
				setTimeout( function(){
					$this.parents('.item-set').addClass('item-set--del').css({position:'absolute', left:-9999, opacity:0})
					resetId()
				},100)
			})
		})

		// id等振り直し
		function resetId() {
			$('.item-set').each( function(i,elm){
				var $target = $(this)
				var num = i+1
				$target.attr('id','item-set--'+num)
				$target.find('.hidden_class').attr('name','mt_submit_hidden'+num)
				$target.find('ul').attr('id','sortableList'+num)
				$target.find('.submit-arr').attr('name','mt_banners'+num)
				$target.find('.item-set__php').val('<?php add_banner('+ num +');>;')
			})
		}

		//項目削除処理
		$(document).on( 'click', '.del-data-item__button', function(){
			$(this).parents('li').remove()
		})

		// 監視
		$(document).on( 'submit', '#form', function(){
			adapt()
		})
		var target = document.getElementById('adapt');
		function MonitorDOMaddition() {
			adapt()
		}
		var mo = new MutationObserver(MonitorDOMaddition);
		mo.observe(target, {childList: true});


		function addItem(num){
			$('#item-set--'+num).find('.data-item ul').append(`
				<li class="data-item__item-list">
					<span class="handle"></span>
					<div class="data-item__image-wrap">
						<img class="data-item__image image-view" src="../wp-content/plugins/add-banner/images/no-image.gif">
						<button type="button" class="media-upload"><span class="dashicons dashicons-format-image"></span></button>
					</div>
					<input type="hidden" value="" size="20" class="data-item__imageurl image-url">
					<p>リンク先URL：<input type="text" value="" size="20" class="data-item__link" placeholder="URLを入力してください"></p>
					<p>別タブ表示：<input type="checkbox" value="" class="data-item__blank"></p>
					<p>代替テキスト：<input type="text" value="" class="data-item__text" placeholder="画像の説明を入力してください"></p>
					<span class="del-data-item">
						<span class="del-data-item__button"></span>
					</span>
				</li>`)
		}
	});
	function adapt(){
		var titleArr = '';
		$('.item-set').each( function(index,elm){
			var title = $(this).find('.item-set__title').val()
			if(titleArr==''){
				if(title!=''){
					titleArr = title
				}
			} else {
				if(title!=''){
					titleArr = titleArr +','+ title
				}
			}

			var dataArr = '';
			var num = index+1;
			$('#item-set--'+num).find('.data-item__item-list').each( function(){
				var itemImage = $(this).find('.data-item__imageurl').val()
				var itemLink = $(this).find('.data-item__link').val()
				var itemBlank = $(this).find('.data-item__blank').prop("checked") ? true : false
				var itemText = $(this).find('.data-item__text').val()
				if(dataArr==''){
					if(itemImage!=''){
						dataArr = itemImage + '_|_' + itemLink + '_|_' + itemBlank + '_|_' + itemText
					}
				} else {
					if(itemImage!=''){
						dataArr = dataArr +','+ itemImage + '_|_' + itemLink + '_|_' + itemBlank + '_|_' + itemText
					}
				}
			})
			//bannerSet()
			$('#item-set--'+num).find('.submit-arr').val(dataArr)
		})
		$('.mt_set_banners').val(titleArr)
	}
})(jQuery);