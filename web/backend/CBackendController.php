<?php

/**
 * Description of isSuperadmin
 *
 * @author Dmitriy Lisovin
 * @property $modelName
 * @property $editable
 * @property $excludeFromGridView
 * @property $excludeFromDetailView
 * @property $excludeFromRelations
 * @property $notUseTitleOfRelation
 * @property $password
 * @property $title
 * @property $bool
 * @property $modelAlias
 * @property $modelAliasPlural
 * @property $deletable
 * @property $reverseOrder
 * @property $nuke
 * @property $selectable
 * @property $maxUploadedImageSize
 * @property $imageThumb
 * @property $dropDown
 * @property $datetime
 * @property $images
 * @property $link
 * @property $linksManyToManyRelation
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class CBackendController extends CController
{

    public static $defaultEntityConfig = array(
        // the name of model represented by this backend entity. REQUIRED
        'modelName' => null,
        // whether the records are allowed to be edited in backend
        'editable' => true,
        //fields that shouldn't be shown in GridViews on admin and view pages
        'excludeFromGridView' => array(),
        //fields that shouldn't be shown in DetailViews on view pages
        'excludeFromDetailView' => array(),
        // model relations that shouldn't be processed by backend 
        'excludeFromRelations' => array(),
        // model relations of BELONGS_TO type that shouldn't replace corresponding field with related model's title field
        'notUseTitleOfRelation' => array(),
        // model field that will be treated like a password
        'password' => array(),
        // Title field. Empty string or TRUE to use the second attribute, FALSE to use PrimaryKey
        'title' => false,
        // model fields that will be treated like  bool values
        'bool' => array(),
        // User-friendly alias of the entity that will be used in backend. FALSE to use modelName as alias.
        'modelAlias' => false,
        // Same as modelAlias but in plural (to avoid things like "Categorys"). FALSE to use modelAlias with 's' at the end
        'modelAliasPlural' => false,
        // whether the records are allowed to be deleted in backend
        'deletable' => true,
        // whether the records should be initially displayed in reverse order (i.e. ordered by Id descending)
        'reverseOrder' => false,
        // whether the records can be "nuked" - i.e. all records from the table deleted at once. Use with extreme caution
        'nuke' => false,
        //whether the records in the GridView can be selected and submitted to the server. 
        //0 - not selectable at all, 1 - only one record can be selected at a time, 2 or more - any number of records can be selected
        // if not 0, checkbox column appears in the Gridview
        'selectable' => 0,
        // maximum dimensions for the images uploaded into the model. Larger ones will be resized. 
        // Format: array('width' => $width, 'height'=> $height). FALSE to use default controller settings.
        'maxUploadedImageSize' => false,
        //parametrs for thumbnail photos
        //'imageThumb' => array( 'maxWidth' => $width, 'maxHeight' => $height ). FALSE to not use Thumbnails.
        'imageThumb' => array(
            'maxWidth' => 100,
            'maxHeight' => 100,
        ),
        // model fields that in create/edit form should have DropDownLists with preset data instead of text fields. 
        // Format: array( 'field1'=> array($value11=> $valueAlias11, ..., $value1n=> $valueAlias1n,), ... 'fieldm'=> array($valuem1=> $valueAliasm1, ..., $valuemn=> $valueAliasmn,),)
        'dropDown' => array(),
        // model fields that will be treated like  dates
        'datetime' => array(),
        // model fields that will be treated like  images
        'images' => array(),
        // fields that in Gridview and DetailView will be shown as http links (a field value is a part of URL or something)
        // Example: 'facebookLink' => 'http://www.facebook.com/profile.php?id={value}'
        'link' => array(),
        // an indication that the model is used as a link between two other models to solve MANY-TO-MANY relation. Format: array of two entities representing the models which this model links.
        // if used, in views of any of related models, other related model Gridview will be shown, instead of this model.
        'linksManyToManyRelation' => false,
    );
    public static $accessRules = array(
        array('allow', // allow all users to perform 'login' action
            'actions' => array('login', 'error'),
            'users' => array('*'),
        ),
        array('allow', // allow authenticated admins to perform 'backend', 'logout', 'view' and 'admin'actions
            'actions' => array('backend', 'logout', 'view', 'admin', 'index',),
            'users' => array('@'),
        ),
        array('allow', // allow superadmin user to do everything that isn't pursued by the law
            'actions' => array('create', 'update', 'delete', 'nuke', 'undelete', 'cleancache',),
            'users' => array('@'),
            'expression' => '$user->superadmin',
        ),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
    public static $configuration = array(
        // user-friendly exception descriptuions
        'exceptionDictionary' => array(
            '23000' => array(
                'text' => 'integrity constraint violation: ',
                'subCodes' => array(
                    '1062' => 'Duplicate entry error. You tried to duplicate some unique fields. Please make sure that data you are trying to insert do not already exist in the database.'
                )
            )
        ),
        // maximum dimensions for the images uploaded into the model. Larger ones will be resized. 
        // Format: array('width' => $width, 'height'=> $height). FALSE to use default controller settings.
        'maxUploadedImageSize' => array(
            'width' => 300,
            'height' => 300,
        ),
        
        // Text to show above HAS_MANY-related model's Gridview on View page
        // '{children}' and '{parent}' will be automatically changed to relatedModel.modelAliasPlural and thisModel.modelAlias, respectively
        'allChildrenText' => 'All {children} associated with this {parent}',
        // default amount of lines per page in GridViews
        'linesPerPage' => 20,
        // maximum lengh of a single word that wil not be chopped to fit in the Gridview boundaries
        'maxLineLength' => 40,
        // values that linesPerPage can be set to, via DropDown
        'pageSizesDropDownList' => array(
            10 => 10,
            20 => 20,
            50 => 50,
            100 => 100,
            200 => 200,
            500 => 500,
            1000 => 1000,
        ),
//        'primaryKey' => 'id',
    );
    public static $entityConfigDictionary = array(
        'admin' => array(
            'modelName' => 'Admin',
            'excludeFromGridView' => array('a_password', 'id'),
            'excludeFromDetailView' => array('a_password', 'id'),
            'password' => array('a_password'),
            'title' => 'name',
            'bool' => array('super_admin'),
        ),
        'history' => array(
            'modelName' => 'AdminActivityLog',
            'modelAlias' => "Admins' activity Log",
            'editable' => false,
            'deletable' => false,
            'reverseOrder' => true,
        ),
    );
    public $additionalMenuItems = array(),
            $attributes,
            $backup,
            $breadcrumbs,
            $dataProvider,
            $dropDownData,
            $entityConfig,
            $foreign = 0,
            $form,
            $entityId = null,
            $menu = array(),
            $model,
            $pageSize,
            $parentNames = array(),
            $parentEntity = null,
            $sub = false,
            $subs,
            $manyToManyLinkIds = array();
    protected $defaultMenuItems,
            $breadcrumbsItems;

    public function __get($name)
    {
        if (isset($this->entityConfig[$name]))
        {
            return $this->entityConfig[$name];
        }
    }

    public function __set($name, $value)
    {
        if (isset($this->entityConfig[$name]))
        {
            $this->entityConfig[$name] = $value;
        }
    }

    public function init()
    {
        $this->setDefaultEntitiesParams();
        return parent::init();
    }

    public function actionBackend()
    {

        $this->render('backend');
    }

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '/layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return self::$accessRules;
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->changeModelFromGET();
        $model = $this->loadModel($id);
        $this->subs = $this->getSubordinateDataProviders();
        $this->render('view', array(
            'model' => $model,
            'subs' => $this->subs,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = $this->changeModelFromGET();
        $fromRelative = isset($_GET['parentKey']);
        if ($fromRelative)
        {
            $model->{$_GET['parentKey']} = $_GET['parentValue'];
        }
        $this->performAjaxValidation($model);
        if (isset($_POST[$this->modelName]))
        {
            try
            {
                $model->attributes = $_POST[$this->modelName];
                $this->setDefaultLogoPath($model);
                $this->savePostprocessedAtributes();
                if ($model->save())
                {
                    Yii::app()->logger->logCreate();
                    $this->redirect(
                            ($fromRelative) ? array('view', 'entity' => $_GET['parentEntity'], 'id' => $_GET['parentValue']) : array('admin', 'entity' => $this->entityId)
                    );
                }
            }
            catch (Exception $ex)
            {
                Yii::app()->user->setFlash('error', $this->getUserFriendlyExceptionMessage($ex));
            }
        }
        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->changeModelFromGET();
        $model = $this->loadModel($id);
        $this->performAjaxValidation($model);
        if (isset($_POST[$this->modelName]))
        {
            try
            {
                $oldAttributes = $model->getAttributes();
                $model->attributes = $_POST[$this->modelName];
                $this->savePostprocessedAtributes($model);
                if ($model->save())
                {
                    Yii::app()->logger->logUpdate($oldAttributes);
                    $this->redirect(
                            (isset($_GET['parentEntity'])) ? array('view', 'entity' => $_GET['parentEntity'], $this->model->tableSchema->primaryKey => $_GET['parentKey']) : ((isset($_GET['prevAction'])) ? array('view', 'entity' => $this->entityId, 'id' => $this->getModelPk($model),) : array('admin', 'entity' => $this->entityId))
                    ); 
                }
            }
            catch (Exception $ex)
            {
                Yii::app()->user->setFlash('error', $this->getUserFriendlyExceptionMessage($ex));
            }
        }
        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request 
            if (!$this->deletable)
            {
                $this->changeModelFromGET();
                $this->loadModel($id)->delete();
                Yii::app()->logger->logDelete();
            }
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
            {
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array("admin", 'entity' => $this->entityId));
            }
        }
        else
        {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
    }

    public function actionUndelete($logID)
    {
        $log = AdminActivityLog::model()->findByPk($logID);
        if ($log->action == 'delete')
        {
            $text = str_replace('Attributes: "', '', substr($log->additional, 0, strlen($log->additional) - 2));
            $pairs = explode('", "', $text);

            $attributes = array();
            foreach ($pairs as $pair)
            {
                $data = explode('" = "', $pair);
                $attributes[$data[0]] = $data[1];
            }
            $model = $this->changeModel($log->entity);
            $model->attributes = $attributes;
            if ($model->save())
            {
                Yii::app()->logger->logUndelete();
                $this->redirect(array('view', 'entity' => $log->entity, 'id' =>$this->getModelPk($this->model)));
            }
        }
        $this->redirect(array('admin', 'entity' => 'history'));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {

        $this->redirect(array('backend'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = $this->changeModelFromGET();
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET[$this->modelName]))
        {
            $model->attributes = $_GET[$this->modelName];
            $model->setPrimaryKey($_GET[$this->modelName][$this->model->tableSchema->primaryKey]);
        }

        $dataProvider = $this->getRelationBasedDataProvider();
        $this->render('admin', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Aka THE BIG RED BUTTON. Nukes the table, deleting every record without confirm. Remove this from product.
     */
    public function actionNuke()
    {
        $model = $this->changeModelFromGET();
        $model->deleteAll();
        Yii::app()->logger->logNuke();
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
        {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array("admin", 'entity' => $this->entityId));
        }
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $this->entityId . '-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionLogin()
    {
        //        $this->layout='';
        $model = new AdminLoginForm;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        $request = Yii::app()->getRequest();
        if ($request->isAjaxRequest)
        {
            // ensure we're rendering this in a parent window, not an update div
            $this->renderPartial('loginRedirect', array(), false, true);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['AdminLoginForm']))
        {
            $model->attributes = $_POST['AdminLoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
            {
                Yii::app()->logger->logLogIn();
                $this->redirect(array('backend'));
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function actionLogout()
    {
        Yii::app()->logger->logLogOut();
        Yii::app()->user->logout();
        $this->redirect(array('backend'));
    }

    public function actionError()
    {
        if (Yii::app()->errorHandler->error)
            $this->render('error', $error);
    }

    protected function executeQuery($query)
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand($query);
        return $command;
    }

    protected function beforeRender($view)
    {
        $this->initBreadcrumbsAndMenu();
        return parent::beforeRender($view);
    }

    /**
     * Changes the current model, getting the entity ID from "$_GET['entity']"
     *  @param string $param Patameter, which is passed to the model constructor.
     * @return CModel A new instance of a model represening the entity.
     */
    public function changeModelFromGET($param = null)
    {
        if (isset($_GET['entity']))
        {
            return $this->changeModel($_GET['entity'], $param);
        }
        return;
    }

    /**
     * Excludes attributes, listed in "viewExceps" option, from view.
     */
    public function excludeSpecifiedColumns($excludeColumns)
    {
        foreach ($excludeColumns as $excep)
        {
            $key = array_search($excep, $this->attributes);
            if ($key != FALSE)
            {
                unset($this->attributes[$key]);
            }
        }
    }

    public function excludeSpecifiedColumnsFromGridView()
    {
        $this->excludeSpecifiedColumns($this->excludeFromGridView);
    }

    public function excludeSpecifiedColumnsFromDetailView()
    {
        $this->excludeSpecifiedColumns($this->excludeFromDetailView);
    }

    public function getUserFriendlyExceptionMessage(Exception $ex)
    {
        if (property_exists($ex, 'errorInfo'))
        {
            $error = $ex->errorInfo;
            if (isset($this->exceptionDictionary[$error[0]]) && isset($this->exceptionDictionary[$error[0]]['subCodes'][$error[1]]))
            {
                return $this->exceptionDictionary[$error[0]]['text'] . $this->exceptionDictionary[$error[0]]['subCodes'][$error[1]] . '<br>' . $error[2];
            }
            else
            {
                return $ex->getMessage() . '<br>NOTE: This exception is not listed in the dictionary. Please list it there.';
            }
        }
        else
        {
            return $ex->__toString();
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        if ($this->model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        $this->model = $this->model->findByPk($id);
        if ($this->model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        $this->backup = $this->model->getAttributes();
        return $this->model;
    }

    /** 
     * Changes the current model.
     * @param string $entity Id of a new "entity". Must be one of  "paramDictionary" top-level keys.
     * @param string $param Patameter, which is passed to the model constructor.
     * @return CModel A new instance of a model represening the entity.
     */

    public function changeModel($entity, $param = null)
    {
        if (isset(self::$entityConfigDictionary[$entity]))
        {
            $this->entityId = $entity;
            $this->setDefaultEntitiesParams();
            $this->entityConfig = $this->getEntityConfigById($this->entityId);

            $this->form = '_form/_' . $entity;
            if ($this->modelAlias === false)
            {
                $this->modelAlias = $this->modelName;
            }
            $this->setGridviewPageSize();
            if (class_exists($this->modelName))
            {
                $this->model = ($param != NULL) ? new $this->modelName($param) : new $this->modelName();
                $this->attributes = $this->model->attributenames();
                $this->setDropDown();
                return $this->model;
            }
            else
            {
                throw new Exception('Unknown model "' . $this->modelName . '"!');
            }
        }
        else
        {
            throw new Exception('Unknown entity "' . $entity . '"!');
        }
    }

    private function getEntityConfigById($entityId)
    {
        return self::$entityConfigDictionary[$entityId];
    }

    private function setDefaultEntitiesParams()
    {
        foreach (self::$entityConfigDictionary as $entityId => $entityConfig)
        {
            self::$entityConfigDictionary[$entityId] = CMap::mergeArray(self::$defaultEntityConfig, $entityConfig);
            if (self::$entityConfigDictionary[$entityId]['modelAlias'] === false)
            {
                self::$entityConfigDictionary[$entityId]['modelAlias'] = self::$entityConfigDictionary[$entityId]['modelName'];
            }
            if (self::$entityConfigDictionary[$entityId]['modelAliasPlural'] === false)
            {
                self::$entityConfigDictionary[$entityId]['modelAliasPlural'] = self::$entityConfigDictionary[$entityId]['modelAlias'] . 's';
            }
            if (self::$entityConfigDictionary[$entityId]['maxUploadedImageSize'] === false)
            {
                self::$entityConfigDictionary[$entityId]['maxUploadedImageSize'] = self::$configuration['maxUploadedImageSize'];
            }

            // add relations to 'excludeFromRelations' if the related model isn't added to entity list

            $model = new self::$entityConfigDictionary[$entityId]['modelName']();
            $relations = $model->relations();
            if ($relations != null)
            {
                foreach ($relations as $key => $relation)
                {
                    if ($this->getEntityByModelName($relation[1]) == null && $relation[0] == CActiveRecord::BELONGS_TO)
                        self::$entityConfigDictionary[$entityId]['excludeFromRelations'] = CMap::mergeArray(self::$entityConfigDictionary[$entityId]['excludeFromRelations'], array($key));
                }
            }
        }
    }

    public function refilterBoolAttributes($action = '')
    {
        foreach ($this->bool as $bool)
        {
            $bool_key = array_search($bool, $this->attributes);
            if ($bool_key != FALSE)
            {
                if ($action == 'admin')
                    $this->attributes[$bool_key] = array(
                        'name' => $this->attributes[$bool_key],
                        'value' => '($data->' . $this->attributes[$bool_key] . '==1)?"Yes":"No"',
                        'filter' => array(1 => 'Yes', 0 => 'No'),
                    );
                else
                    $this->attributes[$bool_key] = array(
                        'name' => $this->attributes[$bool_key],
                        'value' => ($this->model->{$this->attributes[$bool_key]} == 1) ? 'Yes' : 'No',
                    );
            }
        }
    }

    public function getLink($action = '')
    {
        foreach ($this->link as $attribute => $template)
        {
            $template_key = array_search($attribute, $this->attributes);
            if ($template_key != FALSE)
            {
                
                if ($action == 'admin')
                {
                    $text = BVUtils::formatMessage($template, array(
                            '{value}' => '$data->' . $this->attributes[$template_key],
                        ));
                    $this->attributes[$template_key] = array(
                        'name' => $this->attributes[$template_key],
                        'type' => 'raw',
                        'value' => 'CHtml::link($data->' . $this->attributes[$template_key] . ', "' . $text . '")',
                    );
                }
                else
                {
                    $text = BVUtils::formatMessage($template, array(
                            '{value}' =>$this->model->{$this->attributes[$template_key]},
                        ));
                    $this->attributes[$template_key] = array(
                        'name' => $this->attributes[$template_key],
                        'value' => CHtml::link($this->model->{$this->attributes[$template_key]}, $text),
                    );
                }
            }
        }
    }

    public function setRelativeAttributes($action = '')
    {
        $relates = $this->relations();
        foreach ($relates as $key => $relation)
        {
            if ($relation[0] == CActiveRecord::BELONGS_TO)
            {
                $number = array_search($key, $this->notUseTitleOfRelation);

                $excep = ($number === FALSE);

                $number = array_search($relation[2], $this->attributes);
                if ($number != FALSE)
                {
                    $relatedAttribute = $this->getTitleAttributeByModelName($relation[1]);
                    $otherLabel = call_user_func(array($relation[1], 'model'))->attributeLabels();
                    if ($action == 'admin')
                    {

                        $this->attributes[$number] =
                                array(
                                    'name' => $relation[2],
                                    'type' => 'raw',
                                    'value' => 'CHtml::link(Yii::app()->controller->chopString($data->' . ($excep ? $key . '->' . $relatedAttribute : $relation[2]) . '), array("view", "id"=>$data->' . $relation[2] . ', "entity" =>"' . $this->getEntityByModelName($relation[1]) . '"))',
                                    'header' => $excep ? $otherLabel[$relatedAttribute] : $this->model->getAttributeLabel($relation[2])
                        );
                    }
                    else
                    {
                        $this->attributes[$number] = array(
                            'label' => $excep ? $otherLabel[$relatedAttribute] : $this->model->getAttributeLabel($relation[2]),
                            'type' => 'raw',
                            'value' => CHtml::link($this->chopString($excep ? $this->model->{$key}->{ $relatedAttribute } : $this->model->{$relation[2]}), array(
                                'view',
                                'id' => $this->model->{$relation[2]},
                                'entity' => $this->getEntityByModelName($relation[1])
                            ))
                        );
                    }
                }
            }
        }
    }

    public function setDropDown()
    {
        $relates = $this->relations();
        foreach ($relates as $key => $relation)
        {
            if ($relation[0] == CActiveRecord::BELONGS_TO)
            {
                $number = array_search($relation[2], $this->entityConfig['excludeFromRelations']);
                $excep = ($number === FALSE);
                $number = array_search($relation[2], $this->attributes);
                if ($number != FALSE)
                {
                    $relatedAttribute = $this->getTitleAttributeByModelName($relation[1]);
                    $otherLabel = call_user_func(array($relation[1], 'model'))->attributeLabels();
                    $this->dropDownData[$key] = array();
                    $categories = call_user_func(array($relation[1], 'model'))->findAll();
                    foreach ($categories as $record)
                    {
                        $this->dropDownData[$relation[2]][$record->getPrimaryKey()] = $record->{$relatedAttribute};
                    }
                    asort($this->dropDownData[$key]);
                }
            }
        }
        If (isset($this->entityConfig['dropDown']))
        {
            foreach ($this->entityConfig['dropDown'] as $attribute => $dropDownList)
            {
                $this->dropDownData[$attribute] = $dropDownList;
            }
        }
    }

    public function getGridViewAttributes()
    {
        $this->attributes = $this->model->attributenames();
        $this->excludeSpecifiedColumnsFromGridView();
        $this->setRelativeAttributes('admin');
        $this->getLink('admin');
        $this->setLogo('admin');
        $this->refilterBoolAttributes('admin');
        $this->addButtonColumn($this->foreign);
        $this->addCheckBoxColumn();
        $this->chopData();
        return $this->attributes;
    }

    public function getDetailViewAttributes()
    {
        $this->attributes = $this->model->attributenames();
        $this->excludeSpecifiedColumnsFromDetailView();
        $this->setRelativeAttributes();
        $this->getLink();
        $this->setLogo();
        $this->refilterBoolAttributes();
        $this->chopDetailViewData();
        return $this->attributes;
    }

    public function setLogo($action = '')
    {
        foreach ($this->images as $imageAttribute)
        {
            $key = array_search($imageAttribute, $this->attributes);
            if ($key != FALSE)
            {
                $this->attributes[$key] = array(
                    'name' => $imageAttribute,
                    'header' => $this->model->getAttributeLabel($imageAttribute),
                    'type' => 'raw',
                );

                if ($action == 'admin')
                {
                    if ($this->imageThumb === false)
                    {
                        $image = 'CHtml::image(Yii::app()->imageRouter->getImageAbsoluteUrl($data->' . $imageAttribute . '),"", array("class"=>"image_preview_admin"))';
                    }
                    else
                    {
                        $image = 'CHtml::image(Yii::app()->imageRouter->getImageAbsoluteThumbUrl($data->' . $imageAttribute .'),"", array("class"=>"image_preview_admin"))';
                    }
                    $url = 'Yii::app()->controller->createUrl("view",array("id"=>$data->primaryKey, "entity" => "' . $this->entityId . '"))';
                    $link = 'CHtml::link('.$image.', '.$url.')';
                }
                else
                {
                    $url = Yii::app()->imageRouter->getImageAbsoluteUrl($this->model->{$imageAttribute});
                    $image = CHtml::image($url, "", array("class"=>"image_preview_view"));
                    $this->attributes[$key]['label'] = $this->model->getAttributeLabel($imageAttribute);
                    $link = CHtml::link($image, $url, array('target'=>'_blank'));
                }
                
                $this->attributes[$key]['value'] = $link;
                
            }
        }
    }

    private function addCheckBoxColumn()
    {
        if ($this->selectable > 0)
        {
            array_unshift($this->attributes, array(
                'class' => 'CCheckBoxColumn',
                'id' => 'selected' . $this->modelName
            ));
        }
    }

    private function addButtonColumn($foreignKey = 0)
    {
        $workingEntity = $this->parentEntity == null ? $this->entityId : $this->parentEntity;
        $workingConfig = $this->getEntityConfigById($workingEntity);
        $editable = $workingConfig['editable'];
        $deletable = $workingConfig['deletable']; //'.$updDelId.'
        $updDelId = $this->parentEntity == null ? '$data->primaryKey' : 'Yii::app()->controller->manyToManyLinkIds["' . $workingEntity . '"][$data->primaryKey]';
        $this->attributes[] = array(
            'class' => 'CButtonColumn',
            'viewButtonUrl' => 'Yii::app()->controller->createUrl("view",array("id"=>$data->primaryKey, "entity" => "' . $this->entityId . '"))',
            'updateButtonUrl' => 'Yii::app()->controller->createUrl("update",array("id"=>' . $updDelId . ', "entity" => "' . $workingEntity . '", ' . (($foreignKey != 0) ? '"parentEntity"=>Yii::app()->controller->entityId, "parentKey"=>' . $foreignKey . ',' : '') . '))',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete",array("id"=>' . $updDelId . ', "entity" => "' . $workingEntity . '"))',
            'template' => '{view}' . (($editable) ? '{update}' : '') . (($deletable) ? '{delete}' : ''),
        );
    }

    private function chopData()
    {
        $ladels = $this->model->attributeLabels();
        foreach ($this->attributes as $attribute => $value)
        {
            if (!is_array($value))
            {

                $this->attributes[$attribute] = array(
                    'name' => $value,
                    'type' => 'raw',
                    'header' => $ladels[$value],
                    'value' => 'Yii::app()->controller->chopString($data->' . $value . ')',
                );
            }
        }
    }

    public function chopDetailViewData()
    {
        $ladels = $this->model->attributeLabels();
        foreach ($this->attributes as $attribute => $value)
        {
            if (!is_array($value))
            {

                $this->attributes[$attribute] = array(
                    'name' => $value,
                    'label' => $ladels[$value],
                    'value' => $this->chopString($this->model->{$value}),
                );
            }
        }
    }

    private function setGridviewPageSize()
    {
        if (isset($_GET['pageSize']))
        {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);  // would interfere with pager and repetitive page size change
        }
        $this->pageSize = Yii::app()->user->getState('pageSize', SiteController::$configuration['linesPerPage']);
    }

    public function getEntityByModelName($modelName)
    {
        foreach (self::$entityConfigDictionary as $key => $value)
        {
            if ($value['modelName'] == $modelName)
            {
                return $key;
            }
        }
        return null;
    }

    public function getSubordinateDataProviders()
    {
        $oldEntity = $this->entityId;
        $this->model->refresh();
        $oldmodel = $this->model;

        $this->foreign = $this->getModelPk($oldmodel);
        $subordinates = array();
        $relates = $this->relations();
        foreach ($relates as $key => $relation)
        {
            if ($relation[0] == CActiveRecord::HAS_MANY)
            {
                $entity = $this->getEntityByModelName($relation[1]);
                $subModel = $this->changeModel($entity, 'search');
                $childForeignKey = '';
                if ($this->linksManyToManyRelation !== false)
                {
                    $furtherSubordinateEntity = $this->linksManyToManyRelation[0] != $oldEntity ? $this->linksManyToManyRelation[0] : $this->linksManyToManyRelation[1];
                    $childRelation = $this->getRelationByEntityId($furtherSubordinateEntity);
                    $childForeignKey = count($childRelation) > 0 ? $childRelation[2] : '';
                    $subModel = $this->changeModel($furtherSubordinateEntity, 'search');
                    $this->parentEntity = $entity;
                }
                $subModel->unsetAttributes();
                if (isset($_GET[$this->modelName]))
                    $subModel->attributes = $_GET[$this->modelName];
                $dataProvider = $this->getRelationBasedDataProvider();
                if (self::$entityConfigDictionary[$entity]['linksManyToManyRelation'] !== false)
                {
                    $ids = array();
                    $this->manyToManyLinkIds[$this->parentEntity] = array();
                    foreach ($oldmodel->{$key} as $childModel)
                    {
                        $ids[] = $childModel->{$childForeignKey};
                        $this->manyToManyLinkIds[$this->parentEntity][$childModel->{$childForeignKey}] = $childModel->id;
                    }
                    if (count($ids) == 0)
                    {
                        $ids[] = 0;
                    }
                    $dataProvider->getCriteria()->compare('t.'.$this->model->tableSchema->primaryKey, $ids);
                }
                else
                {
                    $dataProvider->getCriteria()->compare('t.' . $relation[2], $oldmodel->getPrimaryKey());
                    $this->entityConfig['viewExceps'][] = $relation[2];
                }
                $dataProvider->setPagination(array('pageSize' => $this->pageSize,));
                $subordinates[$key] = array(
                    'grid' => array(
                        'id' => $key . '-grid',
                        'dataProvider' => $dataProvider,
                        'selectableRows' => $this->selectable,
                        'filter' => $subModel,
                        'columns' => $this->getGridViewAttributes(),
                    ),
                    'createMenu' => array(
                        'editable' => self::$entityConfigDictionary[$entity]['editable'],
                        'model' => $this->modelName,
                        'entity' => $entity,
                        'foreignKey' => $relation[2],
                        'foreignValue' => $oldmodel->getPrimaryKey(),
                        'alias' => $this->modelAlias,
                        'aliasPlural' => $this->modelAliasPlural,
                    )
                );
            }
        }
        $this->changeModel($oldEntity);
        $this->model = $oldmodel;
        $this->foreign = 0;
        return $subordinates;
    }

    private function getRelationByEntityId($entityId)
    {
        $relations = $this->model->relations();
        foreach ($relations as $relation)
        {
            if ($relation[1] == self::$entityConfigDictionary[$entityId]['modelName'])
                return $relation;
        }
        return array();
    }

    public function checkAndSaveLogo()
    {
        foreach ($this->images as $logoAttribute)
        {
            if ($_FILES[$this->modelName]['name'][$logoAttribute] != '')
            {
                Yii::app()->imageRouter->setImageMaxSize($this->maxUploadedImageSize['width'], $this->maxUploadedImageSize['height']);
                Yii::app()->imageRouter->setImageThumb($this->imageThumb);
                Yii::app()->imageRouter->setSubfolder($this->entityId);
                if (Yii::app()->imageRouter->uploadImage($this->model, $logoAttribute))
                    $this->model->{$logoAttribute} = Yii::app()->imageRouter->getUploadedImagePath();
            }
            else
            {
                $this->model->{$logoAttribute} = $this->backup[$logoAttribute];
            }
        }
    }

    public function setDefaultLogoPath()
    {
        foreach ($this->images as $imageAttribute)
        {
            $this->model->{$imageAttribute} = 'images/logo/default.png';
        }
    }

    public function hashPassword()
    {
        foreach ($this->password as $attribute)
        {
            if ($this->model->{$attribute} != $_POST[$attribute])
                $this->model->{$attribute} = AdminIdentity::hashPassword($_POST[$attribute]);
        }
    }

    public function savePostprocessedAtributes()
    {
        $this->checkAndSaveLogo();
        $this->hashPassword($this->model);
    }

    public function renderForm()
    {
        if (file_exists('protected/views/site/_form/_' . $this->entityId . '.php'))
        {
            echo $this->renderPartial($this->form, array('model' => $this->model));
        }
        else
        {
            echo $this->renderPartial('_form/_default', array('model' => $this->model));
        }
    }

    private function openRender($form, $attribute)
    {
        echo '<div class="row">';
        echo $form->labelEx($this->model, $attribute);
    }

    private function closeRender($form, $attribute)
    {
        echo $form->error($this->model, $attribute);
        echo '</div>';
    }

    public function renderInputField($form, $attribute)
    {
        $this->openRender($form, $attribute);
        $this->renderInputFieldByType($form, $attribute);
        $this->closeRender($form, $attribute);
    }

    private function renderInputFieldByType($form, $attribute)
    {
        if (array_search($attribute, $this->password) !== FALSE)
        {
            echo CHtml::passwordField($attribute, $this->model->{$attribute}, array('size' => 45,));
            return;
        }
        if (array_search($attribute, $this->images) !== FALSE)
        {
            echo $form->fileField($this->model, $attribute);
            return;
        }
        if (array_search($attribute, $this->bool) !== FALSE)
        {
            echo $form->checkBox($this->model, $attribute);
            return;
        }
        if (isset($this->dropDownData[$attribute]))
        {
            echo $form->dropDownList($this->model, $attribute, $this->dropDownData[$attribute]);
            return;
        }
        if (array_search($attribute, $this->datetime) !== FALSE)
        {
            $this->widget('core.extensions.yii-backvendor.extensions.timepicker.EJuiDateTimePicker', array(
                'name' => $this->modelName . '[' . $attribute . ']',
                'value' => $this->model->{$attribute},
                'options' => array(
//                'minDate' => 1,
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'hh:mm:ss'
                ),
                'language' => ''// jquery plugin options
            ));
            return;
        }
        echo $form->textField($this->model, $attribute, array('size' => 45,));
        return;
    }

    public function renderMultipleInputFields($form, $attributes = array())
    {
        if (count($attributes) == 0)
        {
            $attributes = $this->model->attributeNames();
            foreach ($attributes as $key => $value)
            {
                if ($value == $this->model->tableSchema->primaryKey)
                    unset($attributes[$key]);
            }
            $this->renderMultipleInputFields($form, $attributes);
            return;
        }
        if (is_array($attributes))
        {
            foreach ($attributes as $attribute)
            {
                $this->renderInputField($form, $attribute);
            }
        }
        else
            $this->renderInputField($form, $attributes);
    }

    public function getTitleAttribute($entity = '')
    {
        if ($entity == '')
            $entity = $this->entityId;
        if (self::$entityConfigDictionary[$entity]['title'] !== false)
        {
            $otherName = call_user_func(array(self::$entityConfigDictionary[$entity]['modelName'], 'model'))->attributeNames();
            if (self::$entityConfigDictionary[$entity]['title'] == '')
                return $otherName[1];
            else
                return self::$entityConfigDictionary[$entity]['title'];
        }
        return call_user_func(array(self::$entityConfigDictionary[$entity]['modelName'], 'model'))->tableSchema->primaryKey;
    }

    public function getModelAliasByEntityId($entityId)
    {
        if (isset(self::$entityConfigDictionary[$entityId]))
        {
            $config = $this->getEntityConfigById($entityId);
            return $config['modelAlias'];
        }
        else
        {
            return false;
        }
    }

    public function getModelAliasPluralByEntityId($entityId)
    {
        if (isset(self::$entityConfigDictionary[$entityId]))
        {
            $config = $this->getEntityConfigById($entityId);
            return $config['modelAliasPlural'];
        }
        else
        {
            return false;
        }
    }

    public function getTitleAttributeByModelName($modelname = '')
    {
        if ($modelname == '')
            $modelname = $this->modelName;
        return $this->getTitleAttribute($this->getEntityByModelName($modelname));
    }

    public function chopString($subStr)
    {
        $subs = explode(' ', $subStr);
        $newStr = '';
        foreach ($subs as $sub)
        {
            $newStr.= $this->chopSubString($sub) . ' ';
        }
        return $newStr;
    }

    private function chopSubString($subStr)
    {
        $maxLength = self::$configuration['maxLineLength'];
        $arrayPartsOfSubStrings = array();
        $newSubString = '';
        $i = 0;
        if (strlen($subStr) > $maxLength)
        {
            while ($i < strlen($subStr))
            {
                $arrayPartsOfSubStrings[] = substr($subStr, $i, $maxLength);
                $i = $i + $maxLength;
            }
            foreach ($arrayPartsOfSubStrings as $value)
            {
                $newSubString .= $value;
                if (strlen($value) == $maxLength)
                {
                    $newSubString .= '<br />';
                }
            }
            return $newSubString;
        }
        else
        {
            return $subStr;
        }
    }

    private function relations()
    {
        $relations = $this->model->relations();

        foreach ($this->excludeFromRelations as $exception)
        {
            unset($relations[$exception]);
        }
        return $relations;
    }

    public function getRelationBasedDataProvider()
    {
        $relations = $this->relations();
        foreach ($this->notUseTitleOfRelation as $relateExcep)
        {
            unset($relations[$relateExcep]);
        }
        foreach ($relations as $relationName => $relation)
        {
            if ($this->getEntityByModelName($relation[1]) == null)
                unset($relations[$relation[1]]);
        }
        $this->attributes = $this->model->attributeNames();
        $criteriaAttributes = array();
        $criteriaWith = array();
        foreach ($this->attributes as $attribute)
        {
            $criteriaAttributes[$attribute] = 't.' . $attribute;
        }
        foreach ($relations as $relationName => $relation)
        {
            if ($relation[0] == CActiveRecord::BELONGS_TO)
            {
                $criteriaAttributes[$relation[2]] = $relationName . '.' . $this->getTitleAttributeByModelName($relation[1]);
            }
            $criteriaWith[] = $relationName;
        }
        $criteria = new CDbCriteria();
        $criteria->with = $criteriaWith;
        foreach ($criteriaAttributes as $fieldName => $attribute)
        {
            $criteria->compare($attribute, $this->model->{$fieldName}, true);
        }
        $this->dataProvider = new CActiveDataProvider($this->model, array(
                    'criteria' => $criteria,
                ));
        if ($this->reverseOrder)
            $this->dataProvider->setSort(array(
                'defaultOrder' => 't.'.$this->model->tableSchema->primaryKey.' DESC',
            ));
        $this->dataProvider->setPagination(array('pageSize' => self::$configuration['linesPerPage'],));
        return $this->dataProvider;
    }

    private function initBreadcrumbsAndMenu()
    {
        if ($this->entityId != null && $this->entityId != '')
        {
            $this->defaultMenuItems = array(
                'admin' => array(
                    array('label' => 'Create ' . $this->modelAlias, 'url' => array('create', 'entity' => $this->entityId), 'visible' => $this->editable),
                    array('label' => 'DELETE EVERYTHING', 'url' => '#', 'linkOptions' => array('submit' => array('nuke', 'entity' => $this->entityId), 'confirm' => 'Are you sure you want to cascade purge the entire table?', 'style'=>'color:red; background:#494B4D'), 'visible' => $this->nuke),
                ),
                'create' => array(
                    array('label' => 'Manage ' . $this->modelAliasPlural, 'url' => array('admin', 'entity' => $this->entityId)),
                ),
                'update' => array(
                    array('label' => 'Create ' . $this->modelAlias, 'url' => array('create', 'entity' => $this->entityId), 'visible' => $this->editable),
                    array('label' => 'View ' . $this->modelAlias, 'url' => array('view', 'id' => $this->getModelPk($this->model), 'entity' => $this->entityId)),
                    array('label' => 'Manage ' . $this->modelAliasPlural, 'url' => array('admin', 'entity' => $this->entityId)),
                ),
                'view' => array(
                    array('label' => 'Create ' . $this->modelAlias, 'url' => array('create', 'entity' => $this->entityId), 'visible' => $this->editable),
                    array('label' => 'Update ' . $this->modelAlias, 'url' => array('update', 'id' => $this->getModelPk($this->model), 'entity' => $this->entityId, 'prevAction' => 'view'), 'visible' => $this->editable),
                    array('label' => 'Manage ' . $this->modelAliasPlural, 'url' => array('admin', 'entity' => $this->entityId)),
                ),
            );
            $this->breadcrumbsItems = array(
                'admin' => array(
                    'Home' => array('backend'),
                    $this->modelAliasPlural,
                ),
                'backend' => array(
                    'Home',
                ),
                'create' => array(
                    'Home' => array('backend'),
                    $this->modelAliasPlural => array('admin', 'entity' => $this->entityId),
                    'Create',
                ),
                'update' => array(
                    'Home' => array('backend'),
                    $this->modelAliasPlural => array('admin', 'entity' => $this->entityId),
                    $this->getModelTitle($this->model) => array('view', $this->model->tableSchema->primaryKey => $this->getModelPk($this->model), 'entity' => $this->entityId),
                    'Update',
                ),
                'view' => array(
                    'Home' => array('backend'),
                    $this->modelAliasPlural => array('admin', 'entity' => $this->entityId),
                    $this->getModelTitle($this->model),
                ),
            );
            $this->additionalMenuItems = array(
                'view' => array(
                    'history' => array(
                        array(
                            'label' => 'Undelete',
                            'url' => array('undelete', 'logID' => $this->getModelPk($this->model)),
                        ),
                    ),
                ),
            );
        }
        $this->breadcrumbsItems ['backend'] = array(
            'Home',
        );
        $this->createBackendMenu();
        if (isset($this->breadcrumbsItems[$this->getAction()->getId()]))
        {
            $this->breadcrumbs = $this->breadcrumbsItems[$this->getAction()->getId()];
        }
        if (isset($this->defaultMenuItems[$this->getAction()->getId()]))
        {
            $this->menu = $this->defaultMenuItems[$this->getAction()->getId()];
        }
        $this->reconfigureMenu();
        if (isset($this->additionalMenuItems[$this->getAction()->getId()]) && isset($this->additionalMenuItems[$this->getAction()->getId()][$this->entityId]))
        {
            foreach ($this->additionalMenuItems[$this->getAction()->getId()][$this->entityId] as $additionalMenuItem)
            {
                $this->menu[] = $additionalMenuItem;
            }
        }
    }

    protected function mainMenuTemplate()
    {
        return array();
    }

    private function createBackendMenu()
    {
        $menuTemplate = CMap::mergeArray($this->mainMenuTemplate(), array(
            'System' => array(
                'admin',
                'history',
                ),
            ));
        foreach ($menuTemplate as $key => $menuEntity)
        {
            if ( !is_array($menuEntity) )
            {
                if (isset(self::$entityConfigDictionary[$menuEntity]))
                {
                    $this->defaultMenuItems['backend'][] = array('label' => $this->getModelAliasPluralByEntityId($menuEntity), 
                        'url' => array('admin', 'entity' => $menuEntity), 'visible' => !Yii::app()->user->isGuest);
                }
            }
            else
            {
                $subitems = array();
                foreach ($menuEntity as $subitem)
                {
                    if (isset(self::$entityConfigDictionary[$subitem]))
                    {
                        $subitems[] = array('label'=> $this->getModelAliasPluralByEntityId($subitem),
                            'url'=>array('admin', 'entity' => $subitem), 'visible' => !Yii::app()->user->isGuest);
                    }
                }
                $this->defaultMenuItems['backend'][] = array('label'=>$key, 'url'=>false, 'items'=>$subitems, 'visible' => !Yii::app()->user->isGuest);
            }
        }
    }

    public function createMainMenu()
    {
        $mainMenu = isset($this->defaultMenuItems['backend']) ? $this->defaultMenuItems['backend'] : array();
        array_unshift($mainMenu, array('label' => 'Home', 'url' => array('backend'), 'visible' => !Yii::app()->user->isGuest));
        $mainMenu[] = array('label' => 'Logout', 'url' => array('logout'), 'visible' => !Yii::app()->user->isGuest);
        $mainMenu[] = array('label' => 'Login', 'url' => array('login'), 'visible' => Yii::app()->user->isGuest);
        return $mainMenu;
    }

    public function getModelPk($model)
    {
        if (is_object($model))
        {
            return $model->getPrimaryKey();
        }
        else
        {
            return 0;
        }
    }
    
    public function getModelTitle($model)
    {
        if ( $this->title !== false )
        {
            try
            {
                return "#".$this->getModelPk($model)." - ".$model->{$this->title};
            }
            catch(Exception $e)
            {
                return $this->getModelPk($model);
            }
        }
        else
            return $this->getModelPk($model);
    }
    
    public function isSuperadmin()
    {
        if (!Yii::app()->user->isGuest)
        {
            $admin = Admin::model()->findByPk(Yii::app()->user->id);
            return ($admin->super_admin==0) ? false : true;
        }
        else return false;
    }
}