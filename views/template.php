<?php
/** @var array $fields - fields for search and sort */
use synatree\dynamicrelations\SynatreeAsset;

SynatreeAsset::register($this);
?>
<?php if ($hideBlock): ?>
<div class="js_hide" data-title="<?= $title ?>">
<?php endif ?>
    <?php \nterms\listjs\ListJs::begin([
        'id' => 'days-list' . uniqid(),

        'search' => $search,
        'sort' => $fields,
        'options' => [
            'class' => 'relations-frame',
        ],
        'clientOptions' => [

            'valueNames' => $fields ? array_keys($fields) : ['id']
        ],
    ]); ?>
<!--    <label class="form-control">--><?//= $title; ?><!--</label>-->
    <ul class="list list-group" data-related-view="<?= $ajaxAddRoute; ?>" style="max-height:500px;overflow-y: scroll" >
        <?php if($addButton): ?>
        <li class="list-group-item">
            <a href="#" class="btn btn-success btn-sm add-dynamic-relation">
                <i class="glyphicon glyphicon-plus"></i> Add
            </a>
        </li>
        <?php endif ?>

        <?php
        foreach ($collection as $model) {
            ?>
            <li class="list-group-item">
                <?php if($deleteButton): ?>
                <button type="button" class="close remove-dynamic-relation" aria-label="Remove"><span
                        aria-hidden="true">&times;</span></button>
                <?endif?>
                <div class="dynamic-relation-container">
                    <?= $this->renderFile($viewPath, [
                        'model' => $model,
                        'rootModel' => $rootModel,
                    ]); ?>
                </div>
            </li>
            <?php
        }
        ?>
    </ul>
    <?php \nterms\listjs\ListJs::end(); ?>
<? if($hideBlock): ?>
</div>
<? endif ?>
