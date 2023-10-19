<?php
/** @var yii\web\View $this */

/** @var \app\models\Books\Author $author */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;

$this->title = $author ? $author->getFullName() : 'New author';
$this->params['breadcrumbs'][] = [
    'label' => 'Authors',
    'url' => Yii::$app->urlManager->createUrl('author')
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edit_form">
    <?php
    Pjax::begin();
    $form = ActiveForm::begin(['action' => '/author/save', 'class' => 'form-horizontal']);
    echo $form->field($author, 'id')->hiddenInput();
    echo $form->field($author, 'first_name');
    echo $form->field($author, 'middle_name');
    echo $form->field($author, 'last_name');
    ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end();
    ?>
</div>