<?php

/** @var yii\web\View $this */
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\Books\Author;
use app\models\User;

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
                return Html::button('Update', ['class' => 'btn btn-info', 'value' => $model->id, 'onclick' => 'window.location.href = \'/author/' . $model->id . '\';']);
            },
            'format' => 'raw',
        ];
    }
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_DELETE)) {
        $columns['columns'][] = [
            'label' => 'Delete',
            'value' => function (Author $model) {
                if (User::getCurrentUser()->hasSubscription($model->id)) {
                    return Html::button('Unsubscribe', ['class' => 'btn btn-outline-info', 'value' => $model->id, 'onclick' => 'unsubscribe(this);']);
                } else {
                    return Html::button('Subscribe', ['class' => 'btn btn-outline-info', 'value' => $model->id, 'onclick' => 'subscribe(this);']);
                }
            },
            'format' => 'raw',
        ];
    }
    if ($identity->checkAccess(\app\models\Auth\Access::ACCESS_DELETE)) {
        $columns['columns'][] = [
            'label' => 'Delete',
            'value' => function ($model) {
                return Html::button('Delete', ['class' => 'btn btn-danger', 'value' => $model->id, 'onclick' => 'window.location.href = \'/author/delete/' . $model->id . '\';']);
            },
            'format' => 'raw',
        ];
    }
    Pjax::begin();
    echo \yii\grid\GridView::widget($columns);
    Pjax::end();
    ?>
</div>
<script>
    function subscribe(button) {
        let authorId = $(button).val();
        $.ajax(
                {
                    url: '/subscribe/' + authorId,
                    success: function () {
                        let td = $(button).closest('td');
                        td.empty();
                        td.append('<?= Html::button('Unsubscribe', ['class' => 'btn btn-outline-info', 'value' => 0, 'onclick' => 'unsubscribe(this);']) ?>');
                        td.find('button').val(authorId);
                    },
                    datatype: 'json'
                }
        );
    }
    function unsubscribe(button) {
        let authorId = $(button).val();
        $.ajax(
                {
                    url: '/unsubscribe/' + authorId,
                    success: function () {
                        let td = $(button).closest('td');
                        td.empty();
                        td.append('<?= Html::button('Subscribe', ['class' => 'btn btn-outline-info', 'value' => 0, 'onclick' => 'subscribe(this);']) ?>');
                        td.find('button').val(authorId);
                    },
                    datatype: 'json'
                }
        );
    }
</script>