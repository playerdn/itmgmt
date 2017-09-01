<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\UsersRecord;
use app\models\vpn\VpnUsersRecord;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <!--<p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>-->
        <?php
            if(!\Yii::$app->user->isGuest &&
            VpnUsersRecord::GetVpnUserID(\Yii::$app->user->identity->username)
                ) {
                $vuid = VpnUsersRecord::GetVpnUserID(\Yii::$app->user->identity->username);
                if($vuid) {
                    echo Html::a("View my VPN &raquo;", 
                            Url::to(['vpn/view']),
                            [
                                'class' => 'btn btn-default',
                                'data' => [
                                    'method'=>'post',
                                    'params' => [
                                        'id'=>$vuid,
                                        '_csrf' => Yii::$app->request->getCsrfToken(),
                                    ],
                                ],
                            ]);
                    echo "\n";
                }
            }

            if(!\Yii::$app->user->isGuest && 
                \Yii::$app->user->can('viewOwnEmailCredentials', ['username' => \Yii::$app->user->identity->username])) {
                echo '<a class="btn btn-default" href="http://www.yiiframework.com/extensions/">View my Email &raquo;</a>'."\n";
            }
            echo '</p>';

        ?>
    </div>
</div>
