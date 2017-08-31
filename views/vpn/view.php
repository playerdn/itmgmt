<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\vpn\VpnUsersRecord */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Vpn Users Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-users-record-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'ID',
            'UID',
            'OVPN_CONF_KIT',
            'CERT_PASS',
            'REQUEST_DOC_ID',
            'START_DATE',
            'EXPIRATION',
            'LAST_ACCESS',
        ],
    ]) ?>

</div>
