<?
$this->registerCssFile( '/css/item.css'    );
$this->registerCssFile( '/css/catalog.css' );
?>
<div class="information_basket">
    <div class="i_exit"><a onclick="$('.information_basket').hide();" href="javascript://">X</a></div>
	<div class="i_td">Товар: <span id="i_code"></span> в количестве: <span id="i_count"></span> шт. добавлен в <a href="/index.php/basket/">корзину</a>!</div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-body">
			<div style="background: #F0F0F0; padding: 15px 15px 20px;margin-bottom: 15px;">
			<form action="/index.php/search/index" method="post">
				<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
				<label>Поиск по каталогу:</label>
				<div class="input-group input-group-sm">
					<input type="text" name="s_query" class="form-control">
					<span class="input-group-btn"><button class="btn btn-info btn-flat" type="submit">Найти</button></span>
				</div>
			</form>
			</div>
			<p>Поисковый запрос: <b><?=htmlspecialchars($query);?></b></p>
			<?if (sizeof($product) > 0){?>
            <hr />
			<div class="all_products">
			<?
				foreach ($product as $p){
					$type = $p['quantity_name'];
					if ($type == 'mass'){
						$type_str = 'кг';
					} else if ($type == 'number'){
						$p['volume'] = 1;
						$type_str = 'шт.';
					} else {
						$type_str = 'л';
					}
					$p['volume'] += 0;
			?>
				<div class="product">
					<div class="p_img">
					<?if (!empty($p['photo'])){?>
						<img width="150" height="150" src="http://liquimoly.ru/catalogue_images/thumbs/<?=$p['photo']?>">
					<?}else{?>
						<img width="150" height="150" src="/images/nophoto_300x300.gif">
					<?}?>
					</div>
					<div class="p_text">
						<a class="p_link" href="/index.php/catalog/item/<?=$p['code_url']?>"><?=$p['name_rus']?></a>
						<p class="p_desc"><?=$p['short_description']?></p>
						<div style="width:100%;">
							<div class="p_info">
							<p>Артикул: <a href="/index.php/catalog/item/<?=$p['code_url']?>"><b><?=$p['code']?></b></a><?if (!empty($p['volume'])){?>, фасовка: <b><?=$p['volume'].' '.$type_str;?></b><?}?><?if (!empty($p['quantity_packing'])){?>, в упаковке: <b><?=$p['quantity_packing'];?> шт.</b><?}?><br />
							<?
                            switch( $p['av'] )
                                {
                                case 0: $av = '<span style="color: #F40000">нет на складе'.($p[ 'av_date' ] != '00.00.0000'?' до ' . $p['av_date']:'').'</span>'; break;
                                case 1: $av = '<span style="color: #F7AC11">мало</span>'; break;
                                case 2: $av = '<span style="color: #52A350">много</span>'; break;
                                }

                            if( $p['av'] === null )
                                {
                                $av = '<span style="color: #F7AC11">нет данных</span>';
                                }
							?>
							Наличие на складе: <b><?=$av?></b></p>
							</div>
							<div class="p_order">
	                        	<p>Цена: <b><?=($p['RetailPrice']?$p['RetailPrice'].' руб.':'по запросу')?></b>
	                        	<?if (Yii::$app->user->can('basket') && !($p['av'] === '0' && Yii::$app->params['disable_backorder'] == true )){?>
	                        	<input id="count_<?=$p['code']?>" class="p_count" name="count" type="number" value="<?=isset($basket[$p['code']])?$basket[$p['code']]:1?>">шт. <input onclick="AddBasket('<?=$p['code']?>', $( document.getElementById('count_<?= $p['code']?>') ).val());" type="submit" value="В корзину"></p>
                                <?}?>
	                        	<?if ($p['fasovok'] > 1){?>
        	                	<div><a onclick="show_fasovka('<?=$p['name_ger']?>',<?=$p['id']?>, $(this));" class="p_fasovka" href="javascript://">Показать в другой фасовке</a></div>
    	                    	<?}?>

							</div>
							<div id="fasovka_<?=$p['id'];?>"></div>
						</div>
					</div>
				</div>
			<?
				}
			?>
			</div>
            <?}else{?>
           <br />
            <?}?>
			</div>
		</div>
	</div>
</div>
<?
	$this->registerJsFile('@web/js/catalog.js', ['depends' => 'yii\web\JqueryAsset']);
	$this->registerJsFile('@web/js/basket.js', ['depends' => 'yii\web\JqueryAsset']);
?>