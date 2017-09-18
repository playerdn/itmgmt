<?php

namespace app\controllers;

use Yii;
use app\models\vpn\VpnUsersRecord;
use app\models\vpn\VpnUsersSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use app\models\LogRecord;
use yii\web\ForbiddenHttpException;
use app\models\WorkstationsRecord;
use app\models\vpn\VpnRdpAccessRecord;

/**
 * VpnController implements the CRUD actions for VpnUsersRecord model.
 */
class VpnController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all VpnUsersRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(! \Yii::$app->user->can('observeVpnPermissions')) {
            throw new ForbiddenHttpException('Access denied');
        }
        $searchModel = new VpnUsersSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VpnUsersRecord model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = null)
    {
        if($id == null) {
            $id = \Yii::$app->request->post('id', null);
        }
        
        if(!is_numeric($id)) {
            return $this->goHome();
        }
        
        if(!\Yii::$app->user->isGuest && 
            (\Yii::$app->user->can('viewOwnVPNCredentials', 
                    ['vpnId' => $id]) || 
                (
                    \Yii::$app->user->can('viewUserCredentials') &&
                    \Yii::$app->user->can('viewUserPermissions')
                )
            )
        )
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }
    }

    /**
     * Creates a new VpnUsersRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VpnUsersRecord();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing VpnUsersRecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if(! \Yii::$app->user->can('updatePermissions')) {
            throw new ForbiddenHttpException('You have no permission for view this.');
        }
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ID]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VpnUsersRecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    

    /**
     * Finds the VpnUsersRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VpnUsersRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VpnUsersRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionCredentials($mode, $VUID){
        // Check access. If Guest or if has no permission to view anything - 403
        if(\Yii::$app->user->isGuest || 
            ! (\Yii::$app->user->can('viewOwnVPNCredentials', 
                    ['vpnId' => $VUID]) || \Yii::$app->user->can('viewUserCredentials') 
            )
        ) {
            throw new ForbiddenHttpException('You have no permission for view this.');
        }

        if($mode != "pass" and $mode != "cert") {
            return 'unsuported';
        }
        if(($model = VpnUsersRecord::findOne($VUID)) === null) {
            throw new NotFoundHttpException('The requested info does not exists.');
        }
        
        if($mode == 'cert') {
            if(strlen($model->OVPN_CONF_KIT) == 0) {
                throw new NotFoundHttpException('User does not have connection kit');
            }
            return $this->returnConnectionKit($model);
        } 
        else if($mode == 'pass') {
            return $this->returnPassword($model);
        }
    }
    /**
     * 
     * @param VpnUsersRecord $model
     * @throws ServerErrorHttpException
     */
    private function returnConnectionKit($model) {
        $logRec = new LogRecord([
            'SOURCE' => 'vpn_sense_data',
            'MSG' => "VPN credentials request (cert) for $model->userName (VUID: $model->ID) BY USER " . 
                \Yii::$app->user->identity->username
        ]);
        
        if(!$logRec->save()) {
            throw new ServerErrorHttpException('Log subsystem failed.');
        }
        return \Yii::$app->response->sendContentAsFile($model->OVPN_CONF_KIT, $model->userName . '.zip');
    }
    /**
     * 
     * @param VpnUsersRecord $model
     */
    private function returnPassword($model){
        $logRec = new LogRecord([
            'SOURCE' => 'vpn_sense_data',
            'MSG' => "VPN credentials request (pass) for $model->userName (VUID: $model->ID) BY USER " . 
                    \Yii::$app->user->identity->username
        ]);
        if(!$logRec->save()) {
            throw new ServerErrorHttpException('Log subsystem failed.');
        }
        if(isset($model->CERT_PASS)) { return $model->CERT_PASS;}
        else { return ''; }
    }
    /**
     * Returns Request for vpn access
     * @param string $VUID VPN user ID
     * @return Response object with access request in pdf
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionRequest($VUID) {
        if(! \Yii::$app->user->can('observeVpnPermissions')) {
            throw new ForbiddenHttpException('You have no permission for view this.');
        }
        if(($model = VpnUsersRecord::findOne($VUID)) === null) {
            throw new NotFoundHttpException('The requested info does not exists.');
        }
        if(!isset($model->requestDoc->PDF_OBJECT)) { return; }
        if(isset($model->requestDoc->DESCRIPTION)) {
            $fn = $model->requestDoc->DESCRIPTION;
        } else {$fn = 'Request';}
        
        return \Yii::$app->response->sendContentAsFile($model->requestDoc->PDF_OBJECT, 
                $fn . '.pdf', ['inline' => true]);
    }
    /**
     * AJAX Search for workstation's names
     * @param string $wsname
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionWsname($wsname) {
        if(! \Yii::$app->user->can('updatePermissions')) {
            throw new ForbiddenHttpException('You have no permission for view this.');
        }
        
        if (\Yii::$app->request->isAjax) {
            $wsname = Yii::$app->request->get('wsname', 'none');
            if($wsname === 'none') {
                return 'No params';
            }
                    
            if(!isset($wsname)) {return ''; }
            $wsz = WorkstationsRecord::find()->
                    filterWhere(['like', 'name', $wsname])->
                    orderBy('name')->limit(10)->all();
            
            $ret = array();
            foreach($wsz as $ws) {
                $ret[] = $ws->name;
            }
          
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $ret;
        }
    }
    /**
     * Grants or denies access for given user to given workstation
     * 
     * @return type
     * @throws ForbiddenHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionPermissions() {
        // If we need to use post action we need bind params manually and d
        // don't use action with params
        
        if(! \Yii::$app->user->can('updatePermissions')) {
            throw new ForbiddenHttpException('You have no permission for view this.');
        }

        $vuid = \Yii::$app->request->post('vuid');
        $mode = \Yii::$app->request->post('mode');
        $ws = \Yii::$app->request->post('ws');

        if(! isset($vuid) or ! isset($mode) or ! isset($ws)) {
            throw new \yii\web\BadRequestHttpException('Missing required parameter');
        }
        
        if(($user = VpnUsersRecord::findOne(['ID' => $vuid]))!=null) {
            if($mode == 'grant') {
                $this->grantRdpAccess($user, $ws);
            } else if ($mode == 'deny') {
                $this->denyRdpAccess($user, $ws);
            }
        }

        return $this->redirect(\yii\helpers\Url::to(['update', 'id'=> $vuid]));
    }
    /**
     * Grants RDP access to workstation
     * @param VpnUsersRecord $vpn_user Vpn user model
     * @param string $ws Name of workstation
     * @return type
     */
    private function grantRdpAccess($vpn_user, $ws){
        if(($oWs = WorkstationsRecord::findOne(['name' => $ws])) != null) {
            foreach ($vpn_user->allowedWorkstations as $ws) {
                if($ws->id == $oWs->id) {return;}
            }
            $vpnAccessRec = new \app\models\vpn\VpnRdpAccessRecord(['WSID'=>$oWs->id, 'VPN_UID' => $vpn_user->ID]);
            $vpnAccessRec->save();
        }
    }
    /**
     * Denies RDP access for user
     * @param VpnUsersRecord $vpn_user
     * @param string $ws
     */
    private function denyRdpAccess($vpn_user, $ws) {
        if(($oWs = WorkstationsRecord::findOne(['name' => $ws])) != null) {
            if(($oVRA = VpnRdpAccessRecord::findOne(['WSID'=>$oWs->id, 
                        'VPN_UID' => $vpn_user->ID])) != null) {
                $oVRA->delete();
            }
        }
    }
}
