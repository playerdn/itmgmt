<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\vpn\VpnUsersRecord */

$this->title = $model->user->ADLogin;
$this->params['breadcrumbs'][] = ['label' => 'Vpn Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->user->ADLogin;
?>
<div class="vpn-users-record-view">

    <h1><?= Html::encode($model->user->ADLogin) ?></h1>

    <p>
        <?php
            if(\Yii::$app->user->can('updatePermissions')) {
                echo Html::a('Update', ['update', 'id' => $model->ID], 
                        ['class' => 'btn btn-primary']);
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'Username',
                'value' => $model->user->ADLogin,
            ],
            [
                'attribute' => 'Password',
                'value' => 
                    function ($model) {
                        $ret = "<div class=\"replace$model->ID\">";
                        $ret .= Html::button('Show', [
                                    'class' => 'btn btn-primary',
                                    'style' => 'margin-left: 1px;',
                                    'onclick' => 'showPass(' . $model->ID . ')',
                        ]);
                        $ret .= "</div>";
                        return $ret;
                    },
                'format' => 'raw',
            ],
            [
                'attribute' => 'Permissions',
                'value' => $model->AllowedWorkstationsAsString,
                'format' => 'paragraphs',       
            ],
            [
                'attribute' => 'Connection kit',
                'format' => 'raw',
                'value' => function ($model) {
                    if($model->OVPN_CONF_KIT) {
                        return Html::button('Download', [
                                'class' => 'btn btn-primary',
                                'style' => 'margin-left: 1px;',
                                'onclick' => "location.href='" .
                                    Url::to(['vpn/credentials',
                                        'mode' => 'cert',
                                        'VUID' => $model->ID]
                                    ) .
                                    "'",
                        ]);
                    } else { return ''; }
                }
            ],
        ],
    ]) ?>

</div>
