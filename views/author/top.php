<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;

$this->title = 'Top 10 authors';
$this->params['breadcrumbs'][] = [
    'label' => 'Authors',
    'url' => Yii::$app->urlManager->createUrl('authors')
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="edit_form">
    <?php
    $items = [];
    for ($i = intval(date('Y')); $i > 1970; $i--) {
        $items[$i] = $i;
    }
    echo Html::dropDownList('years', null, $items, ['id' => 'years', 'onchange' => 'loadTop();']);
    ?>
    <table id="top10" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Books number</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<script>
    function loadTop() {
        let year = $('#years').val();
        $.post(
                '/authors/top10',
                {
                    year: year
                },
                function (list) {
                    $('#top10 tbody').empty();
                    for (let i = 0; i < list.length; i++) {
                        let row = '<tr>' +
                                '<td>' + list[i].name + '</td>' +
                                '<td>' + list[i].number + '</td>' +
                                '</tr>';
                        $('#top10 tbody').append(row);
                    }
                },
                'json'
                );
    }
</script>