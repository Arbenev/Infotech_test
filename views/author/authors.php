<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use \yii\widgets\Pjax;

$this->title = 'Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <?php
    $identity = Yii::$app->user->getIdentity();
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_ADD)) {
        echo Html::button('New', ['class' => 'btn btn-primary right', 'onclick' => 'window.location.href = \'/author/create\';']);
    }
    ?>
    <h1><?= Html::encode($this->title) ?></h1>


    <?php
    $columns = [
        'dataProvider' => $authors,
        'columns' => [
            'id',
            'last_name',
            [
                'label' => 'First name',
                'value' => function ($model) {
                    return $model->first_name . ($model->middle_name ? ' ' . $model->middle_name : '');
                },
                'format' => 'html',
            ],
        ],
    ];
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_EDIT)) {
        $columns['columns'][] = [
            'label' => 'Update',
            'value' => function ($model) {
                return '<button class="btn btn-info" value="' . $model->id . '" onclick="window.location.href = \'/author/' . $model->id . '\';">Update</button>';
            },
            'format' => 'raw',
        ];
    }
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_DELETE)) {
        $columns['columns'][] = [
            'label' => 'Delete',
            'value' => function ($model) {
                return '<button class="btn btn-danger" value="' . $model->id . '" onclick="window.location.href = \'/author/delete/' . $model->id . '\';">Delete</button>';
            },
            'format' => 'raw',
        ];
    }
    Pjax::begin();
    echo \yii\grid\GridView::widget($columns);
    Pjax::end();
    ?>
</div>
