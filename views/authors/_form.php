<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Authors */
/* @var $form yii\widgets\ActiveForm */
?>
<?php
$this->registerJsFile('@web/js/books.js', ['position' => yii\web\View::POS_END, 'depends' => 'app\assets\AppAsset']);
?>
<div class="authors-form">
    <?php Pjax::begin(['id' => 'new_author']) ?>
    <?php $form = ActiveForm::begin(['options' => ['id'=> 'formAuthor', 'enctype' => 'multipart/form-data', 'data-pjax' => true]]); ?>

    <input type="hidden" readonly value="<?=$model->id ?>" name="Authors[id]">

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::button('Save', ['class' => 'btn btn-success','id' => $model->isNewRecord ? 'create_author' : 'update_author']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>

</div>
<?php
$js = <<<JS
    var form = $('form#formAuthor');
    $(form).find('button#create_author, button#update_author').off('click').on('click', function () {
        var id = $('form#formAuthor').find('input[name="Authors[id]"').val();
        form.validate({
            rules: {
                'Author[last_name]': {
                    required: true,
                    minlength: 3
                    
                },
                'Author[first_name]': {
                    required: true
                }
            }
        });
        
        if (!form.valid()) {
              return;
            }
        var formData = new FormData($(form)[0]);
        $.ajax({
            url: '/authors/save-data' + (id ? '?id='+id : ''),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function () {
                $('#modal').modal('hide');
                $.pjax.reload({container: "#authors"});
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
