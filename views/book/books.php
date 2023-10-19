<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use \yii\widgets\Pjax;

$this->title = 'Books';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="">
    <?php
    $identity = Yii::$app->user->getIdentity();
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_ADD)) {
        echo Html::button('New', ['class' => 'btn btn-primary right', 'onclick' => 'window.location.href = \'/book/create\';']);
    }
    ?>
    <h1><?= Html::encode($this->title) ?></h1>


    <?php
    $columns = [
        'dataProvider' => $books,
        'columns' => [
            'id',
            'title',
            'year',
            [
                'label' => 'description',
                'value' => function ($model) {
                    return $model->description;
                },
                'format' => 'html',
            ],
            'isbn',
            [
                'label' => 'cover',
                'value' => function ($model) {
                    return '<img src="' . Yii::$app->params['coverDirectory'] . $model->cover . '" class="cover" />'; //$model->description;
                },
                'format' => 'html',
            ],
            'authors',
        ],
    ];
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_EDIT)) {
        $columns['columns'][] = [
            'label' => 'Update',
            'value' => function ($model) {
                return '<button class="btn btn-info" value="' . $model->id . '" onclick="window.location.href = \'/book/' . $model->id . '\'">Update</button>';
            },
            'format' => 'raw',
        ];
    }
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_DELETE)) {
        $columns['columns'][] = [
            'label' => 'Delete',
            'value' => function ($model) {
                return '<button class="btn btn-danger" value="' . $model->id . '" onclick="window.location.href = \'/book/delete/' . $model->id . '\'">Delete</button>';
            },
            'format' => 'raw',
        ];
    }
    Pjax::begin();
    echo \yii\grid\GridView::widget($columns);
    Pjax::end();
    ?>
</div>
