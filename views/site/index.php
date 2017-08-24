<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <!--<p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>-->
        <?php
            if(!\Yii::$app->user->isGuest && 
                \Yii::$app->user->can('viewOwnVPNCredentials', ['username' => \Yii::$app->user->identity->username])) {
                echo '<p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">View my VPN &raquo;</a>'."\n";
            }
            if(!\Yii::$app->user->isGuest && 
                \Yii::$app->user->can('viewOwnEmailCredentials', ['username' => \Yii::$app->user->identity->username])) {
                echo '<a class="btn btn-default" href="http://www.yiiframework.com/extensions/">View my Email &raquo;</a>'."\n";
            }
            echo '</p>';

        ?>
    </div>
</div>
