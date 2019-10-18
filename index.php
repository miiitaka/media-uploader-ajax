<?php
/*
Plugin Name: Add Banner
Plugin URI: https://github.com/1000git/add-banner
Description: リスト形式のバナーのセットを管理画面から複数作成することができる。
Version: 2.0.0
Author: Chiba Takeshi
License: GPLv2 or later
*/

add_action( 'admin_menu', 'register_my_custom_menu_page__banner' );
function register_my_custom_menu_page__banner(){
    add_menu_page(
		'バナー設定',
		'バナー設定',
		'manage_options',
		plugin_basename( __DIR__ ),
		'page_render',
		'',
		7
	);
	wp_enqueue_media();

}

function page_render () {
	// ユーザーが必要な権限を持つか確認する必要がある
	if (!current_user_can('manage_options'))
	{
	  wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	$set_name = 'mt_set_banners';
	$set_hidden_field_name = 'mt_set_submit_hidden';
	$set_field_name = 'mt_set_banners';
	$set_val = get_option( $set_name );
    // ユーザーが何か情報を POST したかどうかを確認
    // POST していれば、隠しフィールドに 'Y' が設定されている
	if( isset($_POST[ $set_hidden_field_name ]) && $_POST[ $set_hidden_field_name ] == 'Y' ) {
	    // POST されたデータを取得
	    $set_val = $_POST[ $set_field_name ];

	    // POST された値をデータベースに保存
	    update_option( $set_name, $set_val );
		// 画面に「設定は保存されました」メッセージを表示

		?>
		<div class="updated"><p><strong><?php _e('保存しました。', 'add-banner' ); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">この通知を非表示にする</span></button></div>
		<?php
	}
	?>
	<div class="wrap">
		<h2>バナー設定</h2>
		<p>表示させるバナーのセットを作成してください。</p>
		<?php

		$setArr = explode(',', $set_val);
		?>
		<form action="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/add-set.php" method="post" id="addSet">
			<input type="text" name="setName" value="" placeholder="新しいバナーのセット作成" class="add-set-name">
			<input type="hidden" name="setNum" value="<?php echo count($setArr) +1; ?>" class="add-count">
			<input type="submit" value="追加" class="add-set-submit">
		</form>
		<form action="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/del-set.php" method="post" id="delSet">
			<input type="hidden" name="delNum" value="" class="set-del-button">
		</form>
		<hr />
		<p class="note"><span>::</span>をドラッグで並び替えが可能です。</p>

		<script src="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/js/sortable.min.js"></script>
		<script src="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/js/media-uploader-main.js"></script>
		<script src="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/js/add-set.js"></script>
		<script src="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/js/del-set.js"></script>
		<form name="form1" id="form" method="post" action="">
				<input type="hidden" name="<?php echo $set_hidden_field_name; ?>" value="Y">
			<div class="item-set-wrap" id="adapt">
			<?php
			require dirname(__FILE__).'/banner-set.php';
			for($i=0; $i<count($setArr); $i++):
				$setNum = $i + 1;
				$setName = $setArr[$i];
				new Banner($setNum, $setName);
			endfor;
			?>
			</div>
			<p class="submit">
				<input type="hidden" name="<?php echo $set_field_name; ?>" value="<?php echo $set_val; ?>" class="mt_set_banners">
				<input type="submit" name="Submit" class="button-primary js-submit" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>
		</form>

		<div class="add_banner_publish">
			<h3>出力結果について</h3>
			<p>以下のようにHTMLが出力されます。<br>
				<span class="note">※ &lt;ul&gt;、&lt;/ul&gt;は出力しません。</span></p>
			<pre>
&lt;li class="banner-list__list"&gt;
	&lt;a class="banner-list__link" href="{リンク先URL}" target="_blank"&gt;
		&lt;img class="banner-list__img" src="{バナー画像URL}" alt="{代替テキスト}"&gt;
	&lt;/a&gt;
&lt;/li&gt;
&lt;li class="banner-list__list"&gt;
	&lt;a class="banner-list__link" href="{リンク先URL}"&gt;
		&lt;img class="banner-list__img" src="{バナー画像URL}" alt="{代替テキスト}"&gt;
	&lt;/a&gt;
&lt;/li&gt;
・・・</pre>
		</div>
	</div>
	<style>
		.data-item {}
		.data-item__image { border:solid 1px #ccc; border-radius: 3px; line-height: 2em; height: 30px; font-size: 14px; display: block; width: 100%; height: auto; box-sizing: border-box;}
		.data-item__item-list { border: dashed 1px transparent; border-radius: 4px; background: #FFF; padding: 10px 5px 2px 2px;  position: relative; border-radius: 5px; display: block; box-shadow: 0 3px 3px rgba(0,0,0,0.2);}
		.data-item__image-wrap { position: relative; width:calc(100% - 38px); display: inline-block;}
		.media-upload { position: absolute; background: rgba(0,0,0,0.3); border-radius:4px; width: 100%; height: 100%; top: 0; left: 0;box-sizing: border-box;padding: 0; cursor: pointer; opacity: 0;}
		.media-upload:hover { opacity: 1; }
		.media-upload:focus: { outline: none; }
		.media-upload span { font-size: 50px; color: #fff; width: 50px; height: 50px;}
		.add-data-item { width: 100%; display: block;}
		.add-data-item__button { width: 247px; height: 38px; border: dashed 2px #aaa; border-radius: 3px; color: #aaa; display: block; width: 100%; line-height: 34px; background: beige; cursor: pointer;}
		.add-data-item__button:hover { background: antiquewhite; }
		.add-data-item__button:focus { outline: none; }
		.data-item__item-list p { font-size: 12px; margin-top: 5px; padding: 0 20px;}
		.data-item__link,
		.data-item__text { border-radius: 4px; height: 30px; line-height: 30px; width: calc(100% - 90px); font-size: 12px;}
		.blue-background-class { opacity: 0.5; border: dashed 1px #ccc;}
		.blue-background-class .data-item__image { background: #ccc; }
		.handle { cursor: grab; vertical-align: top; height: 30px; display: inline-block; padding-bottom: 30px;}
		/* .handle:active {cursor: grabbing;} */
		.sortable-fallback:active { cursor: grabbing;}
		.handle:before { content: '::'; line-height: 30px; font-weight: bold; display: block; width: 15px; text-align: center; vertical-align: top; position: relative; top:-10px; }
		.handle:hover:before { color: #ccc;}
		.note { font-size: 12px; color: darkgoldenrod;}
		.note span { display: inline-block; background: #FFF; color: #000; border-radius:3px 0 0 3px; width: 14px; text-align: center; height:26px;line-height: 26px; vertical-align: middle; position: relative; top: -3px; margin-right: 5px;}
		.del-data-item { display: block; position: absolute; top: 9px; right: 4px;}
		.del-data-item__button { transform: rotate(-45deg); display: block; position: relative; margin-left: 7px; background: #ccc; width: 14px; height: 14px; border-radius: 50%; cursor: pointer;}
		.del-data-item__button:before,
		.del-data-item__button:after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: block; background: #fff;  }
		.del-data-item__button:before {  width: 8px; height: 2px;}
		.del-data-item__button:after { width: 2px; height: 8px;}
		.del-data-item__button:hover { background: #000; }

		.item-set-wrap { display: flex; flex-wrap: wrap; width: 100%; }
		.item-set { width: 32%; box-sizing: border-box; padding: 15px 12px; border: #E1E1E1 solid 1px; border-top: #E1E1E1 solid 5px; margin-right: 2%; margin-bottom: 2%; background: #F9F9F9; position: relative;}
		.item-set:nth-child(3n) { margin-right: 0; }

		.item-set__del { position: absolute; top: 5px; right: 5px; width: 20px; height: 20px; background: #ccc; border-radius: 50%; cursor: pointer; transform: rotate(45deg);}
		.item-set__del:before,
		.item-set__del:after { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display: block; background: #fff;  }
		.item-set__del:before {  width: 8px; height: 2px;}
		.item-set__del:after { width: 2px; height: 8px;}
		.item-set__del:hover { background: #000; }
		.add-set-name { width:200px; height: 40px; line-height: 40px; display: inline-block; vertical-align: top;}
		.add-set-submit { width:100px; height: 40px; line-height: 40px; display: inline-block; vertical-align: top; border-color: rgba(222,222,222,1); padding: 0;}
		.item-set__title { font-weight: bold; font-size: 20px; background: none; border:none; width: 100%; margin-bottom: 10px; }
		.item-set__php-label { display: table; clear: both; width: 100%;}
		.item-set__php-label span { font-size: 12px; line-height: 20px; box-sizing: border-box; border-radius: 4px 0 0 4px; display: table-cell; vertical-align: middle; color: #fff; background: #9db5c0; width: 75px; text-align: center;}
		.item-set__php { font-size: 12px; line-height: 20px; padding: 0; box-sizing: border-box; outline:none; margin: 0; border-radius: 0 4px 4px 0; border-left: none; display: table-cell; width: 100%; padding: 0 10px; vertical-align: middle; color: #000; font-weight: bold;}
		.updated { position: relative; }

		.add_banner_publish { padding: 10px 20px; background: #fff; border-radius: 5px; }
		.add_banner_publish h3 { color:#999; }
		.add_banner_publish h3 span { color:#444; }
		.add_banner_publish pre {  font-size: 12px; border-radius: 3px; box-shadow: inset 0 0 3px rgba(0,0,0,0.1); padding: 10px; background: #333; color: #ccc;}
	</style>
	<script>
		$('.notice-dismiss').on('click', function(){
			$('.updated').fadeOut();
		})
		// Enter無効（セットが削除されてしまうため）
		document.onkeypress = enter;
		function enter(){
		  if( window.event.keyCode == 13 ){
		    return false;
		  }
		}
	</script>
	<?php
}

function add_banner($bannerNum){
	$opt_val = get_option( 'mt_banners'.$bannerNum );
	$arr = explode(',', $opt_val);
	if(count($arr)>0):
		for($i=0; $i<count($arr); $i++):
			$item = explode('_|_', $arr[$i]);
			$itemImage = $item[0];
			$itemLink = $item[1];
			$itemBlank =  $item[2] === 'true' ? ' target="_blank"' : '';
			$itemText = $item[3];
			echo '<li class="banner-list__list"><a class="banner-list__link" href="'.$itemLink.'"'.$itemBlank.'><img class="banner-list__img" src="'.$itemImage.'" alt="'.$itemText.'"></a></li>'."\n";
		endfor;
	endif;
}
