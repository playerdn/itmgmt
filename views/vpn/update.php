<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;


/* @var $this yii\web\View */
/* @var $model app\models\vpn\VpnUsersRecord */

$this->title = 'Update permissions for: ' . $model->user->ADLogin;
$this->params['breadcrumbs'][] = ['label' => 'Vpn Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user->ADLogin, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vpn-users-record-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="vpn-users-record-form">
    <p>
        <?= Html::button('Add permission', ['onclick' => 'addInput()', 'class' => 'btn btn-success']); ?>
    </p>

    <div id ="searchBlock" style="display: none;">
        <table>
            <tr>
                <td>
                    <input id = "searchPermission" class="input" type="text" placeholder="type here to search">
                    <button type="button" class="btn-grey">Select</button>
                </td>
            </tr>
        </table>
    </div>
    <div id="search_advice_wrapper"></div>

    <?php
        $dataProvider = new ActiveDataProvider(['query' => $model->getAllowedWorkstations()]);
        $dataProvider->pagination = false;
        
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'id'=> 'permissionsTable',
                 'class' => 'table table-striped table-bordered'
            ],
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' =>  'PC name'
                ]
            ]
        ]);
        
    ?>        
   </div>

</div>
