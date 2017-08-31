<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\vpn\VpnUsersRecord */

$this->title = 'Create Vpn Users Record';
$this->params['breadcrumbs'][] = ['label' => 'Vpn Users Records', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vpn-users-record-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
