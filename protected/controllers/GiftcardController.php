<?php

class GiftcardController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','admin','GetGiftcard','delete','undodelete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
            if (Yii::app()->request->isAjaxRequest) {
                Yii::app()->clientScript->scriptMap['*.js'] = false;
                echo CJSON::encode(array(
                    'status' => 'render',
                    'div' => $this->renderPartial('view', array('model' => $this->loadModel($id)), true, false),
                ));

                Yii::app()->end();
            } else {
                $this->render('view',array(
                        'model'=>$this->loadModel($id),
                ));
            }
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionCreate()
    {
        $model = new Giftcard;

        if (!Yii::app()->user->checkAccess('giftcard.create')) {
            throw new CHttpException(403, 'You are not authorized to perform this action');
        }

        if (isset($_POST['Giftcard'])) {
            $model->attributes = $_POST['Giftcard'];
            $this->setCardDate($model);


            if ($model->validate()) {
                if ($model->save()) {
                    Yii::app()->user->setFlash('success', '<strong>Well done!</strong> successfully saved.');
                    $this->redirect(array('create'));
                }
            }
        }

        $this->render('create', array('model' => $model));


    }

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (!Yii::app()->user->checkAccess('giftcard.update')) {
            throw new CHttpException(403, 'You are not authorized to perform this action');
        }

        if (isset($_POST['Giftcard'])) {
            $model->attributes = $_POST['Giftcard'];
            $this->setCardDate($model);

            if ($model->validate()) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    if ($model->save()) {
                        $transaction->commit();
                        Yii::app()->user->setFlash('success', '<strong>Well done!</strong> successfully saved.');
                        $this->redirect(array('admin'));

                    }
                } catch (Exception $e) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', '<strong>Oh snap!</strong> Change a few things up and try submitting again.' . $e);
                }
            }

        }

        $this->render('update', array('model' => $model));


    }

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
        if (!Yii::app()->user->checkAccess('giftcard.delete')) {
            throw new CHttpException(403, 'You are not authorized to perform this action');
        }

        if (Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
            Giftcard::model()->deleteGiftCard($id);

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if (!isset($_GET['ajax'])) {
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
		} else {
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
	}

    public function actionUndoDelete($id)
    {
        if (Yii::app()->user->checkAccess('giftcard.delete')) {
            if (Yii::app()->request->isPostRequest) {
                Giftcard::model()->undodeleteGiftCard($id);
                // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                if (!isset($_GET['ajax'])) {
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
                }
            } else {
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            }
        } else {
            throw new CHttpException(403, 'You are not authorized to perform this action');
        }
    }

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Giftcard');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
    public function actionAdmin()
    {
        $model = new Giftcard('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Giftcard'])) {
            $model->attributes = $_GET['Giftcard'];
        }

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('giftcard_PageSize', (int)$_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        if (isset($_GET['Archived'])) {
            Yii::app()->user->setState('giftcard_archived', $_GET['Archived']);
            unset($_GET['Archived']);
        }

        $model->giftcard_archived = Yii::app()->user->getState('giftcard_archived',
            Yii::app()->params['defaultArchived']);

        $this->render('admin', array(
            'model' => $model,
        ));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Giftcard the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Giftcard::model()->findByPk($id);
		if ($model===null) {
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Giftcard $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax']==='giftcard-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionGetGiftcard()
    {
        if (isset($_GET['term'])) {
            $term = trim($_GET['term']);
            $ret['results'] = Giftcard::getGiftcard($term);
            echo CJSON::encode($ret);
            Yii::app()->end();

        }
    }

    protected function setCardDate($model) {
        if ( $_POST['Giftcard']['year'] !== "" || $_POST['Giftcard']['month'] !== "" || $_POST['Giftcard']['day'] !== "" ) {
            $dob = $_POST['Giftcard']['year'] . '-' . $_POST['Giftcard']['month'] . '-' . $_POST['Giftcard']['day'];
            $model->start_date = $dob;
        }

        if ( $_POST['Giftcard']['e_year'] !== "" || $_POST['Giftcard']['e_month'] !== "" || $_POST['Giftcard']['e_day'] !== "" ) {
            $dob = $_POST['Giftcard']['e_year'] . '-' . $_POST['Giftcard']['e_month'] . '-' . $_POST['Giftcard']['e_day'];
            $model->end_date = $dob;
        }
    }
}