<?php

/**
 * Контроллер связи Гипотез и Понятий
 */
class LinkhsController extends CrudController
{
    public function actionView($id) {
        throw new CHttpException(404);
    }
    
    public function actionDelete($id) {
        throw new CHttpException(404);
    }
    
    public function getClass() {
        return new HS();
    }
    
    public function actionUpdate($id) {
        throw new CHttpException(404);
    }
    
    public function actionCreate() {
        $form = new LinkhsForm();
        if (isset($_POST['LinkhsForm'])) {
            $form->attributes = $_POST['LinkhsForm'];
            if ($form->validate() && $form->save()) {
                $this->redirect(array("linkhs/list"));
            }
        }
        $this->render("create", array("model" => $form));
    }
}