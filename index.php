<?php
/*
Plugin Name: Add Banner
Plugin URI:
Description: バナーを管理画面から追加してテーマでリスト表示。
Version: 1.0.0
Author: Chiba Takeshi
License: GPLv2 or later
*/

new Add_Banner();

class Add_Banner {
	public function __construct () {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function admin_menu () {
		$page = add_menu_page(
			'バナー設定',
			'バナー設定',
			'manage_options',
			plugin_basename( __FILE__ ),
			array( $this, 'page_render' ),
			'',
			7
		);

		add_action( 'admin_print_scripts-' . $page, array( $this, 'admin_scripts') );
	}

	public function admin_scripts () {
		wp_enqueue_media();
	}

	public function page_render () {
		// ユーザーが必要な権限を持つか確認する必要がある
		if (!current_user_can('manage_options'))
		{
		  wp_die( __('You do not have sufficient permissions to access this page.') );
		}
		
		function banner_set($num, $setName){
		    // フィールドとオプション名の変数
		    $opt_name = 'mt_banners'. $num;
		    $hidden_field_name = 'mt_submit_hidden'. $num;
		    $data_field_name = 'mt_banners'. $num;

			// var_dump($opt_name);

		    // データベースから既存のオプション値を取得
		    $opt_val = get_option( $opt_name );

		    // ユーザーが何か情報を POST したかどうかを確認
		    // POST していれば、隠しフィールドに 'Y' が設定されている
		    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		        // POST されたデータを取得
		        $opt_val = $_POST[ $data_field_name ];

		        // POST された値をデータベースに保存
		        update_option( $opt_name, $opt_val );

			}
			$str = $opt_val;
			$arr = explode(',', $str);

			// view部分
			?>
			<div class="item-set" id="item-set--<?php echo $num ?>">
					<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
					<h2 class="item-set__title"><?php echo $setName; ?></h2>
					<div class="data-item">
						<ul id="sortableList<?php echo $num ?>">
			<?php
			$noImage = plugins_url( 'images/no-image.gif', __FILE__ );
			for($i=0; $i<count($arr); $i++):
				$item = explode('__|', $arr[$i]);
				//var _dump($item[1]);
				$imageUrl = $item[0] ? $item[0] :  plugins_url() . '/' .plugin_basename( __DIR__ ).'/images/no-image.gif';
				$checked = $item[2]==='true' ? ' checked' : '';
				$altText = $item[3] ? $item[3] : '';
				//var _dump($item[1]);
				echo <<<EOD
				<li class="data-item__item-list">
					<span class="handle"></span>
					<div class="data-item__image-wrap">
						<img class="data-item__image image-view" src="$imageUrl">
						<button type="button" class="media-upload"><span class="dashicons dashicons-format-image"></span></button>
					</div>
					<input type="hidden" value="$item[0]" size="20" class="data-item__imageurl image-url">
					<p>リンク先URL：<input type="text" value="$item[1]" size="20" class="data-item__link"></p>
					<p>別タブ表示：<input type="checkbox" class="data-item__blank"$checked></p>
					<p>代替テキスト：<input type="text" value="$altText" class="data-item__text"></p>
					<span class="del-data-item">
						<span class="del-data-item__button"></span>
					</span>
				</li>
EOD;
			endfor;
			?>
						</ul>
					</div>


					<div class="add-data-item"><button type="button" class="add-data-item__button">＋ 項目追加</button></div>
					<input type="hidden" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" class="submit-arr">
<script>
		new Sortable(sortableList<?php echo $num ?>, {
			animation: 150,
			ghostClass: 'blue-background-class',
			handle: ".handle"
		});
</script>
			</div>
			<?php
		}

		if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
			// 画面に「設定は保存されました」メッセージを表示
			?>
			<div class="updated"><p><strong><?php _e('保存しました。', 'add-banner' ); ?></strong></p></div>
			<?php
		}
	?>
	<div class="wrap">
		<h2>バナー設定</h2>
		<p>表示させるバナーのセットを作成してください。</p>
		<p class="note"><span>::</span>をドラッグで並び替えが可能です。</p>
		<hr />

		<script src="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/js/sortable.min.js"></script>
		<script src="<?php echo plugins_url() . '/' . plugin_basename( __DIR__ );?>/js/media-uploader-main.js"></script>
		<form name="form1" id="form" method="post" action="">
			<div class="item-set-wrap">
<?php banner_set(1, 'サイドバー'); ?>
<?php banner_set(2, 'SPメニュー'); ?>
			</div>
			<p class="submit">
				<input type="submit" name="Submit" class="button-primary js-submit" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>
		</form>
		<script>
			$(function(){
			})
		</script>
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
		.data-item__link { border-radius: 4px; height: 30px; line-height: 30px; width: calc(100% - 90px); font-size: 12px;}
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

		.item-set-wrap { display: flex; }
		.item-set { width: 400px; padding: 15px 12px; border: #E1E1E1 solid 1px; margin-right: 10px; background: #F9F9F9;}
		.item-set:last-child { margin-right: 0; }
	</style>
		<?php
	}
}
	/* [出力方法]
	 * <?php add_banner(1); ?> // サイドバー
	 * <?php add_banner(2); ?> // SPメニュー
	 * ※ 137行目に追加すれば別のバナーリストも作成可能
	 *
	 * [出力結果]
	 * <li class="banner-list__list"><a class="banner-list__link" href="XXX.XXX" target="_blank"><img class="banner-list__img" src="XXX.XXX" alt="XXXXXX" target="_blank"></a></li>
	 * <li class="banner-list__list"><a class="banner-list__link" href="XXX.XXX" target="_blank"><img class="banner-list__img" src="XXX.XXX" alt="XXXXXX"></a></li>
	 * 	…
	 */
	function add_banner($bannerNum){
		$opt_val = get_option( 'mt_banners'.$bannerNum );
		$arr = explode(',', $opt_val);
		if(count($arr)>0):
			for($i=0; $i<count($arr); $i++):
				$item = explode('__|', $arr[$i]);
				$itemImage = $item[0];
				$itemLink = $item[1];
				$itemBlank =  $item[2] === 'true' ? ' target="_blank"' : '';
				$itemText = $item[3];
				echo '<li class="banner-list__list"><a class="banner-list__link" href="'.$itemLink.'"'.$itemBlank.'><img class="banner-list__img" src="'.$itemImage.'" alt="'.$itemText.'"></a></li>'."\n";
			endfor;
		endif;
	}
