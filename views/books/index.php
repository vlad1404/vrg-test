<?php

use app\models\Authors;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\BooksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/js/books.js', ['position' => yii\web\View::POS_END]);
$authors = \yii\helpers\ArrayHelper::map(Authors::find()->all(), 'id', 'last_name');
?>
<div class="books-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= isset(Yii::$app->user->id) ? Html::button('Create', ['value' => Url::to('/books/create'), 'class' => 'btn btn-success modalButton']) : 'You may login with <strong>admin/admin</strong> or <strong>demo/demo</strong> to add new record.' ?>
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
            'attribute' => 'name',
            'format' => 'raw',
            'value' =>  function ($model) {
                return !empty($model->name) ? $model->name : '(not set)';
            },
        ],
        [
            'attribute' => 'desc',
            'format' => 'raw',
            'value' =>  function ($model) {
                return !empty($model->desc) ? $model->desc : '(not set)';
            },
        ],
        [
            'attribute' => 'photo',
            'format' => 'raw',
            'value' =>  function ($model) {
                return (!empty($model->photo) ? Html::img($model->photo, ['class' => 'img-circle', 'style' => 'height: 64px; width: 64px;']) : '(not set)');
            },
        ],
        [
            'attribute' => 'authors',
            'format' => 'raw',
            'value' => function ($modal) {
                $authors = [];
                foreach ($modal->booksAuthors as $booksAuthor) {
                    $authors[] = $booksAuthor->author->last_name;
                }
                $authors_list = implode(', ',$authors);
                return !empty($authors_list) ? $authors_list : '(not set)';
            },
            'filterType' => \kartik\grid\GridView::FILTER_SELECT2,
            'filter' => $authors,
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true, 'minimumResultsForSearch' => '-1'],
            ],
            'filterInputOptions' => ['placeholder' => 'Choose Author', 'id' => 'author']
        ],
        [
            'attribute' => 'publication',
            'format' => 'raw',
            'value' =>  function ($model) {
                return !empty($model->publication) ? $model->publication : '(not set)';
            },
        ],
        [
            'class' => ActionColumn::className(),
            'template'=>'{update} {delete}',
            'visible' => Yii::$app->user->isGuest != 1,
            'buttons' => [
                'update' => function ($url, $modal) {
                    return Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['value' => Url::to('/books/update?id='.$modal['id']), 'class' => 'btn btn-success modalButtonUpdate']);
                }
            ]
        ]
    ];

    Pjax::begin(['id' => 'books']);

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
                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-list"></i> Books </h3>',
                'after' => false,

            ],
        ],
        'options' => ['id'=>'dynagrid-notifications'] // a unique identifier is important
    ]);
    DynaGrid::end();

    \yii\widgets\Pjax::end();
    ?>
</div>
