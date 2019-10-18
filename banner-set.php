<?php
class Banner {
	//プロパティ
	public $opt_name;
	public $hidden_field_name;
	public $data_field_name;
	public $opt_val;

	//コンストラクタ
	public function __construct($num, $setName) {
		$this->opt_name = 'mt_banners'. $num;
		$this->hidden_field_name = 'mt_submit_hidden'. $num;
		$this->data_field_name = 'mt_banners'. $num;
		//delete_option( $this->opt_name );

    // データベースから既存のオプション値を取得
    $opt_val = get_option( $this->data_field_name );


    // ユーザーが何か情報を POST したかどうかを確認
    // POST していれば、隠しフィールドに 'Y' が設定されている
    if( isset($_POST[ $this->hidden_field_name ]) && $_POST[ $this->hidden_field_name ] == 'Y' ) {
        // POST されたデータを取得
        $opt_val = $_POST[ $this->data_field_name ];

        // POST された値をデータベースに保存
        update_option( $this->opt_name, $opt_val );

	}
	$str = $opt_val;
	$arr = explode(',', $str);

	// view部分セット
	?>
	<div class="item-set" id="item-set--<?php echo $num ?>">
			<input type="hidden" class="hidden_class" name="<?php echo $this->hidden_field_name; ?>" value="Y">
			<input type="text" class="item-set__title" value="<?php echo $setName; ?>" placeholder="バナーセットのタイトル">
			<label class="item-set__php-label"><span>出力タグ：</span><input type="text" class="item-set__php" value="&lt;?php add_banner(<?php echo $num; ?>); ?&gt;" disabled></label>

			<div class="data-item">
				<button class="item-set__del" value="<?php echo $this->data_field_name; ?>"></button>
				<ul id="sortableList<?php echo $num ?>">
				<?php
				$noImage = plugins_url( 'images/no-image.gif', __FILE__ );
				for($i=0; $i<count($arr); $i++):
					$item = explode('_|_', $arr[$i]);
					$imageUrl = $item[0] ? $item[0] :  plugins_url() . '/' .plugin_basename( __DIR__ ).'/images/no-image.gif';
					$checked = $item[2]==='true' ? ' checked' : '';
					$altText = $item[3] ? $item[3] : '';
					echo <<<EOD
					<li class="data-item__item-list">
						<span class="handle"></span>
						<div class="data-item__image-wrap">
							<img class="data-item__image image-view" src="$imageUrl">
							<button type="button" class="media-upload"><span class="dashicons dashicons-format-image"></span></button>
						</div>
						<input type="hidden" value="$item[0]" size="20" class="data-item__imageurl image-url">
						<p>リンク先URL：<input type="text" value="$item[1]" size="20" class="data-item__link" placeholder="URLを入力してください"></p>
						<p>別タブ表示：<input type="checkbox" class="data-item__blank"$checked></p>
						<p>代替テキスト：<input type="text" value="$altText" class="data-item__text" placeholder="画像の説明を入力してください"></p>
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
			<input type="hidden" name="<?php echo $this->data_field_name; ?>" value="<?php echo $opt_val; ?>" class="submit-arr">
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

	//メソッド
	public function banner_set() {
	}

}
