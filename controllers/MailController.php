<?php

namespace app\controllers;

use Yii;
use app\models\mail\MailRecord;
use app\models\mail\MailSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\EmplPersonsRecord;
use app\helpers\EmailHelpers;
use app\validators\EmailListValidator;
use yii\base\ErrorException;

/**
 * MailController implements the CRUD actions for MailRecord model.
 */
class MailController extends Controller
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
     * Lists all MailRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MailSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MailRecord model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new MailRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($selectedEmpl = null)
    {
        $selectedEmpl = Yii::$app->request->post('selectedEmpl', null);
        
        if($selectedEmpl != null) {
            if( ($empl = EmplPersonsRecord::findOne(['ID' => $selectedEmpl])) != null) {
                $newMailRecord = new MailRecord();
                $newMailRecord->name_f = $empl->Фамилия;
                $newMailRecord->name_o = $empl->Отчество;
                $newMailRecord->name_i = $empl->Имя;
                $newMailRecord->guid = $selectedEmpl;
                $newMailRecord->spam_f="1";
                $newMailRecord->greylist="1";
                $newMailRecord->visible_mail="1";
                $newMailRecord->passwd = EmailHelpers::generatePassword(8);
               

                // Prepare other fields as well
                $newMailRecord->login = 
                    EmailHelpers::SuggestLogin($newMailRecord->name_f, 
                        $newMailRecord->name_i, $newMailRecord->name_o);
                
                return $this->render('create4empl', ['model' => $newMailRecord]);
            }
        } 

        $model = new MailRecord(['scenario' => 'userEmail']);
        
        if($model->load(Yii::$app->request->post())){
            if(!$model->GenerateE_mailField()) {return 'Email generation error!'; }

            if($model->validate()){
                if($model->save()) { return $this->redirect(['view', 'id' => $model->id]); }
            } else {
                if(array_key_exists('E_mail', $model->errors)) {
                    $model->addError('login', $model->errors['E_mail'][0]);
                }
                return $this->render('create4empl', ['model' => $model]);
            }
        } else {
            return $this->render('create', ['model' => $model,]);
        }
    }

    public function actionCreateServiceEmail(){
        $model = new MailRecord(['scenario' => 'serviceEmail']);
        
        if($model->load(Yii::$app->request->post())){
            if(!$model->GenerateE_mailField()) {return 'Email generation error!'; }

            if($model->validate()){
                if($model->save()) { return $this->redirect(['view', 'id' => $model->id]); }
//                return $this->render('create4service', ['model' => $model]);
//                echo '<pre>' . print_r($model, true) . '</pre>';
            } else {
                if(array_key_exists('E_mail', $model->errors)) {
                    $model->addError('login', $model->errors['E_mail'][0]);
                }
                return $this->render('create4service', ['model' => $model]);
            }
        } else {
            $model->spam_f="1";
            $model->greylist="1";
            $model->visible_mail="0";
            $model->passwd = EmailHelpers::generatePassword(8);

            return $this->render('create4service', ['model' => $model]);
        }
    }
    
    /**
     * Updates an existing MailRecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->IsAlias) {
            $model->scenario = 'alias';
        } else {$model->scenario = 'userEmail'; }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MailRecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // We dont want just delete emails especially when deleting address is 
        // using as destination for another. 
        
        // Before removing user need to propose specify another address for forwarding. 
        // In that case we remove user and set up alias for deleting e-mail.
        
        // If this address is destination for another address - before remove check
        // if that another address is alias (login empty) and does it address has 
        // another destinations if yes - delete requested e-mail, if no - ask user for 
        // delete as well that source e-mail because after deletion first it has no sense
        
        $model = $this->findModel($id);
        
        foreach($model->GetDependings() as $depend){
            $depend->delete();
        }
        $model->delete();
        
        return $this->redirect(['index']);
    }

    /** Suggest user convert mail record to alias instead of removing it
     * 
     * @param string $id
     * @return string
     */
    public function actionDeleteConfirmation($id){
        $model = $this->findModel($id);

        // Dynamic model for validation Alias input
        $modelAliases = new \yii\base\DynamicModel(['id', 'aliases']);
        $modelAliases->addRule(['aliases', 'id'], 'required')
            ->addRule(['aliases'], EmailListValidator::className());

        if ($model) {
            if($modelAliases->load(Yii::$app->request->post()) 
                && $modelAliases->validate()) {
                $model->AddAliases($modelAliases->aliases);
                try {
                    $model->ConvertToAlias();
                    return $this->redirect(['view', 'id' => $model->id]);
                } catch (Exception $e) {
                    return 'fail';
                }
            } else {
                // Find mailboxes which redirect his mail to this
                $dependedAddrz = MailRecord::GetDependingRecords($model->id);

                return $this->render('deleteConfirm', [
                    'modelAliases' => $modelAliases,
                    'model' =>  $model,
                    'depended' => $dependedAddrz
                ]);
            }
        }
    }
 
    /**
     * Finds the MailRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MailRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MailRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
