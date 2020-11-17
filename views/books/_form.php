<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Books */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->registerJsFile('@web/js/books.js', ['position' => yii\web\View::POS_END, 'depends' => 'app\assets\AppAsset']);
?>
<div class="books-form">
    <?php Pjax::begin(['id' => 'new_book']) ?>

    <?php $form = ActiveForm::begin(['options' => ['id'=> 'formBook', 'enctype' => 'multipart/form-data', 'data-pjax' => true]]); ?>

    <input type="hidden" readonly value="<?=$model->id ?>" name="Books[id]">

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>

    <div class="form-group field-authors">
        <label class="control-label" for="authors">Authors</label>
        <?= Select2::widget([
            'id' => 'select_book_id',
            'name' => 'authors[]',
            'value' => array_column($model->booksAuthors, 'author_id'),
            'data' => \yii\helpers\ArrayHelper::map(\app\models\Authors::find()->all(), 'id', 'first_name'),
            'options' => ['multiple' => true, 'placeholder' => 'Choose author'],
            'pluginOptions' => ['minimumResultsForSearch' => -1],
            'hashVarLoadPosition' => View::POS_READY
        ]); ?>
        <div class="help-block"></div>
    </div>

    <?= !empty($model->photo) ? '<label class="control-label col-md-2">Current photo</label><img class="help-block img-rounded" src="/'.$model->photo.'" height="100px">': ''?>

    <?= $form->field($model, 'photo')->fileInput()->label(false); ?>

    <?= $form->field($model, 'publication')
        ->textInput(['type' => 'number',
            'min'=>0,
            'max'=>date('Y'),
            'value' => date('Y')]); ?>

    <div class="form-group">
        <?= Html::button('Save', ['class' => 'btn btn-success','id' => $model->isNewRecord ? 'create_book' : 'update_book']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
</div>
<?php
$js = <<<JS
    var form = $('form#formBook');
    $(form).find('button#create_book, button#update_book').off('click').on('click', function () {
        var id = $('form#formBook').find('input[name="Books[id]"').val();
        form.validate({
            rules: {
                'Books[name]': {
                    required: true
                },
                'authors[]': {
                    required: true
                }
            }
        });
        
        if (!form.valid()) {
              return;
            }
        var formData = new FormData($(form)[0]);
        $.ajax({
            url: '/books/save-data' + (id ? '?id='+id : ''),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function () {
                $('#modal').modal('hide');
                $.pjax.reload({container: "#books"});
                $(document).on('pjax:success', function() {
                    $('.modalButtonUpdate').off('click').on('click', function (){
                        $('#modal').modal('show').find('#modalContent').load($(this).attr('value'));
                    });
                });
            },error: function (response) {
                alert('Something goes wrong')
            }
        });
    })
JS;

$this->registerJs($js,\yii\web\View::POS_READY);

?>

