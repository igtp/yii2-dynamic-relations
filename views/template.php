<?php
	use synatree\dynamicrelations\SynatreeAsset;

	SynatreeAsset::register($this);
?>
<?php \nterms\listjs\ListJs::begin([
		'id' => 'days-list',

		'search' => true,
		'sort' => [
				'id' => [
						'label' => Yii::t('app', 'По id '), // Сортировать по премии
				],
				'select2-selection' => [
						'label' => Yii::t('app', 'Заголовок'), // Сортировать по дню недели
				],
		],
		'clientOptions' => [
				'valueNames' => [
//						'id',
						'select2-selection',
				],
		],
]); ?>
<label class="form-control"><?= $title; ?></label>
<ul class="list list-group" data-related-view="<?= $ajaxAddRoute; ?>">
	<li class="list-group-item">
		<a href="#" class="btn btn-success btn-sm add-dynamic-relation">
			<i class="glyphicon glyphicon-plus"></i> Add
		</a>
	</li>

<?php 
	foreach($collection as $model)
	{
?>
	<li class="list-group-item">
		<button type="button" class="close remove-dynamic-relation" aria-label="Remove"><span aria-hidden="true">&times;</span></button>		
		<div class="dynamic-relation-container">
			<?= $this->renderFile( $viewPath, [ 'model' => $model ]); ?>
		</div>
	</li>	
<?php
	}
?>
</ul>
<ul class="pagination"></ul>
<?php \nterms\listjs\ListJs::end(); ?>
