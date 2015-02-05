<?php

class PredicatController extends CrudController
{
    public function actionView($id) {
        throw new CHttpException(400);
    }
    
    public function actionUpdate($id) {
        if (!is_null($id) && isset($_POST['Argument'])) {
            if (!is_null($model = $this->loadModel($id))) {
                $model->linkArguments($_POST['Argument'][0], $_POST['Argument'][1]);
            }
        }
        parent::actionUpdate($id);
    }
    
    public function getClass() {
        return new Predicat();
    }
}