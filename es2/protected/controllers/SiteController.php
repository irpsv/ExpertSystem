<?php

Yii::import("prospector.classes.*");
Yii::import("tezaurus.models.*");

class SiteController extends CController {
    public function actionIndex() {
        $this->render("index");
    }    
    
    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        echo "<h1>{$error['code']}</h1>";
        echo "<p>{$error['message']}</p>";
    }
    
    /**
     * Выбор понятий для выделения гипотез
     */
    public function actionStart() {
        $this->render("start", array("model" => Symbol::listNames()));
    }
    
    public function actionInithypothesis() {
        // если гипотезы установлены
        if (isset($_POST['hypothesis'])) {
            $es = ExpertSystem::create($_POST['hypothesis']);
            $es->save();
            $this->redirect(array("site/nextquest"));
        }
        // иначе
        if (!isset($_POST['symbols'])) {
            throw new CHttpException(400);
        }
        $form = new ListHypothesis();
        $form->setSymbols($_POST['symbols']);
        $this->render("inithypothesis", array("form" => $form));
    }
    
    public function actionNextquest() {
        $es = ExpertSystem::load();
        $quest = $es->nextQuest();
        if (is_null($quest)) {
            $this->redirect(array("site/end"));
        }
        $this->render("nextquest", array("quest" => $quest, "answers" => $quest->getAnswers()));
    }
    
    public function actionAddanswer($id_quest, $c_value) {
        $es = ExpertSystem::load();
        $es->addAnswer($id_quest, $c_value);
        $es->save();
        $this->redirect(array("site/nextquest"));
    }
    
    public function actionEnd() {
        $es = ExpertSystem::load();
        // вывод информации
        $model = $es->getInfo();
        //
        $es->destroy();
        $this->render("end", array("model" => $model));
    }
}