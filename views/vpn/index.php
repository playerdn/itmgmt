<?php

use yii\helpers\Html;
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
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute'=> 'username',
                'label'=>'User name',
            ],
            'CERT_PASS',
            'REQUEST_DOC_ID',
            // 'START_DATE',
            // 'EXPIRATION',
            // 'LAST_ACCESS',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
