<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
                    <?= Html::beginForm('permissions', 'post',['autocomplete'=>'off']); ?>
                    <?= Html::input('hidden', 'vuid', $model->ID, ['id' => 'vuid']) ?>
                    <?= Html::input('hidden', 'mode', 'grant',['id' => 'mode']) ?>
                    <div class="input-group">
                        <?= Html::input('text', 'ws','', [
                          'id' => 'searchPermission',
                          'class'=> 'form-control',
                          'placeholder' => 'workstation name'
                        ]) ?>
                        <div class="input-group-btn">
                            <?= Html::button('<i class="glyphicon glyphicon-ok"></i>', [
                              'type' => 'submit',
                              'class' => 'btn btn-primary'
                            ]) ?>
                        </div>
                    </div>
                    <?= Html::endForm()?>
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
                    'label' =>  'PC name',
                ],
                [
                  'class' => 'yii\grid\ActionColumn',
                  'buttons' => [
                        'delete'=> function($url, $row_model, $key) use($model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                                ['vpn/permissions'],
                                [
                                  'data'=>[
                                    'method' => 'post',
                                    'params' => [
                                          'mode' => 'deny',
                                          'vuid' => $model->ID,
                                          'ws' => $row_model->name
                                    ]
                                  ]
                                ]
                                );
                        }
                  ],
                  'template' => '{delete}',
                ],
            ]
        ]);
    ?>        
   </div>
</div>
