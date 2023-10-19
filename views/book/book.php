<?php
/** @var yii\web\View $this */

/** @var \app\models\Books\Book $book */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \app\models\Books\Book;

$this->title = $book->title ?? 'New book';
$this->params['breadcrumbs'][] = [
    'label' => 'Books',
    'url' => Yii::$app->urlManager->createUrl('books')
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edit_form">
    <?php
    Pjax::begin();
    $form = ActiveForm::begin(['action' => '/book/save']);
//    echo Html::hiddenInput('id', $book->id);
    echo $form->field($book, 'id')->hiddenInput();
    echo $form->field($book, 'title');
    echo $form->field($book, 'year');
    echo $form->field($book, 'description')->textarea(['rows' => '6']);
    echo $form->field($book, 'isbn');
    echo $form->field($book, 'cover')->fileInput();
    if ($book->cover) {
        echo Html::img(Yii::$app->params['coverDirectory'] . $book->cover, ['class' => 'cover']);
    } else {
        echo Html::img(Yii::$app->params['coverDirectory'] . Book::COVER_NO_COVER, ['class' => 'cover']);
    }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php
    ActiveForm::end();
    Pjax::end();
    ?>
</div>