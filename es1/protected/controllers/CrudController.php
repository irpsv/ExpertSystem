<?php

abstract class CrudController extends CController
{
    /**
     * Действия обновления записи
     */
    public abstract function actionUpdate($id);
    /**
     * Возвращает новый экземпляр записи
     * @return CActiveRecord экземпляр записи
     */
    public abstract function getClass();

    public function actionView($id)
    {
        $class = $this->getClass();
        if (is_null($model = $class::model()->findByPk($id))) {
            throw new CHttpException(404);
        }
        $this->render('view', array("model" => $model));
    }

    public function actionList()
    {
        $model = new CActiveDataProvider(get_class($this->getClass()));
        $this->render('list', array("model" => $model));
    }

    public function actionCreate()
    {
        $this->actionUpdate(null);
    }
    
    public function actionDelete($id)
    {
        $model = $this->loadModel($id);
        if (!$model->isNewRecord) {
            $model->delete();
        }
    }
    
    /**
     * Создание новой, либо выгрузка записи с указанным ID
     * @param type $id
     * @return type
     */
    public function loadModel($id)
    {
        $newRecord = $this->getClass();
        if (is_null($id) || is_null($model = $newRecord::model()->findByPk($id))) {
            return $newRecord;
        }
        return $model;
    }
    
    /**
     * Инициализация модели
     * @param CActiveRecord $model
     * @return boolean TRUE - если форма инициорованна
     */
    public function initForm(&$model) {
        $className = get_class($model);
        if (isset($_POST[$className])) {
            $model->attributes = $_POST[$className];
            return true;
        }
        return false;
    }

    /**
     * Валидация модели
     * @param CActiveRecord $model
     */
    public function validate($model) {
        // AJAX валидация
        if (isset($_POST['ajax']) && $_POST['ajax']==  get_class($form)) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // Обычная валидация
        return $model->validate();
    }
}