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
        <h1>IT self service</h1>

        <?php
            // Only for logined users...
            if(!\Yii::$app->user->isGuest) {
                echo '<p class="lead">You can view or manage next items: </p>';
                $vuid = VpnUsersRecord::GetVpnUserID(\Yii::$app->user->identity->username);
                if($vuid) { // if VPN access granted for given user
                    echo Html::a("My VPN access &raquo;", 
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
                
                if(\Yii::$app->user->can('viewOwnEmailCredentials', 
                        ['username' => \Yii::$app->user->identity->username])) {
                    echo Html::a("My Email &raquo;",
                            Url::to(['email/view']),
                            [
                                'class' => 'btn btn-default',
                                'data' =>  [
                                    'method' => 'post',
                                    'params' => [],
                                ],
                            ]
                        );
                }
            }

            echo '</p>';
        ?>
    </div>
</div>
