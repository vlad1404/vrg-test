<?php

use kartik\dynagrid\DynaGrid;
use yii\bootstrap\Modal;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\AuthorsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="authors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= isset(Yii::$app->user->id) ? Html::button('Create', ['value' => Url::to('/authors/create'), 'class' => 'btn btn-success modalButton']) : 'You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong> to add new record.' ?>
    </p>

    <?php  Modal::begin([
        'id' => 'modal',
        'size' => 'modal-lg',
    ]);
    echo '<div id="modalContent"></div>';
    Modal::end();?>

    <?php
    $columns = [
        [
            'attribute' => 'first_name',
            'format' => 'raw',
            'value' =>  function ($model) {
                return !empty($model->first_name) ? $model->first_name : '(not set)';
            },
        ],
        [
            'attribute' => 'last_name',
            'format' => 'raw',
            'value' =>  function ($model) {
                return !empty($model->last_name) ? $model->last_name : '(not set)';
            },
        ],
        [
            'attribute' => 'middle_name',
            'format' => 'raw',
            'value' =>  function ($model) {
                return !empty($model->middle_name) ? $model->middle_name : '(not set)';
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template'=>'{update} {delete}',
            'visible' => Yii::$app->user->isGuest != 1,
            'buttons' => [
                'update' => function ($url, $modal) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => Url::to('/authors/update?id='.$modal['id']), 'class' => 'btn btn-success modalButtonUpdate']);
                }
            ]
        ]
    ];

    Pjax::begin(['id' => 'authors']);

    $dynagrid = DynaGrid::begin([
        'columns' => $columns,
        'theme'=>'panel-default',
        'showPersonalize' => true,
        'storage' => 'session',
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => false,
            'floatHeader' => false,
            'pjax' => true,
            'responsiveWrap' => false,
            'responsive' => false,
            'containerOptions' => ['style' => 'overflow: auto'],
            'panel'=> [
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> Authors </h3>',
                'after' => false,

            ],
        ],
        'options' => ['id'=>'dynagrid-notifications'] // a unique identifier is important
    ]);
    DynaGrid::end();

    \yii\widgets\Pjax::end();
    ?>
</div>
