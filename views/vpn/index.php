<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\vpn\VpnUsersSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'OpenVPN';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-users-record-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Vpn Users Record', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'username',
                'label' => 'User name',
            ],
            [
                'attribute' => 'ipAddresses',
                'label' => 'Assigned IPs',
                'format' => 'paragraphs',
                'value' => function($model) {
                    $ret = '';
                    foreach ($model->vpnIPs as $link) {
                        if ($ret == '') {
                            $ret .= $link->ip;
                        } else {
                            $ret .= ", " . $link->ip;
                        }
                    }
                    return $ret;
                }
            ],
            [
                'label' => 'Connection kit',
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
            [
                'label' => 'Password',
                'format' => 'raw',
                'value' => function($model) {
                    if($model->CERT_PASS) {
                        $ret = "<div class=\"replace$model->ID\" align=\"center\">";
                        $ret .= Html::button('Show', [
                                    'class' => 'btn btn-primary',
                                    'style' => 'margin-left: 1px;',
                                    'onclick' => 'showPass(' . $model->ID . ')',
                        ]);
                        $ret .= "</div>";
                        return $ret;
                    } else { return ''; }
                },
            ],
            [
                'label' => 'Request',
                'format' => 'raw',
                'value' => function($model) {
                    $rd = $model->requestDoc;
                    if($rd) {
                        return Html::a($model->requestDoc->DESCRIPTION, 
                                Url::to(['vpn/request', 
                                    'VUID'=> $model->ID]));
                    } else {
                        return '';
                    }
                },
            ],
            [
                'attribute' => 'workstations',
                'label' => 'Workstations',
                'format' => 'paragraphs',
                'value' => function($model){return $model->AllowedWorkstationsAsString;}
            ],
            // 'START_DATE',
            // 'EXPIRATION',
            [
                'attribute' => 'LAST_ACCESS',
                'label' => 'Last access',
                'format'=> 'paragraphs'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}'
            ],
        ],
    ]);
    ?>
</div>
