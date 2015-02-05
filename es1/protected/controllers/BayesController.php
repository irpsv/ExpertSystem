<?php

class BayesController extends CController
{
    /**
     *
     * @var BayesClass 
     */
    private $es;
    /**
     * Предварительная загрузка данных из кук
     */
    protected function beforeAction($action)
    {
        $preLoadActions = array('nextquest', 'result', 'addanswer');
        if (in_array(strtolower($action->id), $preLoadActions)) {
            $this->loadData();
        }
        return true;
    }
    
    public function actionIndex()
    {
        $this->render('index');
    }
    
    public function actionStart()
    {
        $this->resetData();
        //$this->redirect(array('bayes/initdivisions'));
        $this->render("start", array("model" => Symbol::listNames()));
    }
    
    public function actionInitdivisions()
    {
        if (isset($_POST['Division'])) {
            $this->initData();
            $this->es->initDivisions($_POST['Division']);
            $this->saveData();
            $this->redirect(array('bayes/nextquest'));
        }
        // по полученным понятиям
        $divisions = array();
        if (isset($_POST['symbols'])) {
            $divisions = Symbol::listDivisionNames($_POST['symbols']);
        }
        else {
            $divisions = Division::getNames();
        }
        $alters = Alternative::getNames();
        $this->render('initdivisions', array("alters" => $alters, "divisions" => $divisions));
    }
    
    public function actionNextquest()
    {
        // если какая то цель достигла альтернативы или кончались вопросы
        if ($this->es->isEndTest() || is_null($nextquest = $this->es->nextquest())) {
            $this->redirect(array('bayes/result'));
        }
        // вывод формы
        $this->render('quest', array(
            "id_quest" => $nextquest->id,
            "quest" => $nextquest->name,
            "answers" => Answer::findByQuest($nextquest->id)
        ));
    }
    
    public function actionAddanswer($id_quest, $id_answer)
    {
        if (!(is_numeric($id_quest) && is_numeric($id_answer))) {
            throw new CHttpException(400);
        }
        $this->es->addAnswer($id_quest, $id_answer);
        $this->saveData();
        $this->redirect(array('bayes/nextquest'));
    }
    
    public function actionResult()
    {
        if (is_null($this->es->id_lastanswer)) {
            $this->render('not_data');
            Yii::app()->end();
        }
        $alters = array();
        foreach ($this->es->getAlternatives() as $id_alter => $prob) {
            $name = Alternative::model()->findByPk($id_alter)->name;
            $alters[$name] = $prob;
        }
        //
        $division = array();
        foreach ($this->es->divisions as $id_division => $divAlters) {
            $divName = $id_division == 0 ? "Все разделы" : Division::model()->findByPk($id_division)->name;
            $division[$divName] = array();
            foreach ($divAlters as $id_alter => $data) {
                $alterName = Alternative::model()->findByPk($id_alter)->name;
                $division[$divName][$alterName] = $data;
            }
        }
        //
        $this->render('result', array("alters" => $alters, "divisions" => $division));
    }
    
    /**
     * Инициализация данных ЭС
     */
    private function initData()
    {
        $this->es = new BayesClass();
        $this->es->questions = array();
        $this->es->id_lastanswer = null;
        $alters = Alternative::queryAll();
        $probability = 1.0 / count($alters);
        $newAlter = array();
        foreach ($alters as $alter) {
            $newAlter[$alter['id']] = $probability;
        }
        $this->es->setAlternatives($newAlter);
    }
    
    /**
     * Отчистка данных ЭС из COOKIEs
     */
    private function resetData()
    {
        Yii::import('application.controllers.CoockieController');
        $cc = new CoockieController();
        $cc->reset(array("questions", "divisions", "id_lastanswer"));
    }
    
    /**
     * Загрузка данных из COOKIEs
     */
    private function loadData()
    {
        Yii::import('application.controllers.CoockieController');
        $cc = new CoockieController();
        $cc->load(array("questions", "divisions", "id_lastanswer"));
        $this->es = new BayesClass();
        $this->es->questions = $cc->questions;
        $this->es->divisions = $cc->divisions;
        $this->es->id_lastanswer = !is_null($cc->id_lastanswer) ? intval($cc->id_lastanswer) : null;
    }
    
    /**
     * Сохранение данных в COOKIEs
     */
    private function saveData()
    {
        Yii::import('application.controllers.CoockieController');
        $cc = new CoockieController();
        $cc->questions = $this->es->questions;
        $cc->divisions = $this->es->divisions;
        $cc->id_lastanswer = $this->es->id_lastanswer;
        $cc->save();
    }
}