<?php

Yii::import("prospector.models.*");

/**
 * Семантическая сеть. Данный объект служит для работы с гипотезами и связями
 */
class SemanticNetwork {
    /**
     * Целевые гипотезы
     * @var array массив в формате:
     *  array(
     *      'id гипотезы' => array(
     *          'eps' => 'целевое значение шансов',
     *      ),
     *  );
     */
    private $target_hypotheses;
    public function getTargetHypothesis() {
        return $this->target_hypotheses;
    }

    /**
     * Список отклонений для целевый гипотез
     * @var array
     */
    private $deviations;
    public function initDeviation() {
        $this->deviations = array();
    }
    
    //
    // Функции для организации согласования базы
    //
    private static $name_old_table = "Hypothesis";
    private static $name_backup_table = "Hypothesis_backup";
    private static $name_revpas_table = "Hypothesis_reverse_passage";
    /**
     * Создает клон таблицы
     * @param string $old_table имя таблицы для которой нужно сделать клона
     * @param string $new_table имя backup (оригинала) таблицы
     */
    private static function cloneTable($old_table, $new_table) {
        Yii::app()->db->createCommand("PRAGMA foreign_keys=off")->execute();
        Yii::app()->db->createCommand("ALTER TABLE {$old_table} RENAME TO {$new_table}")->execute();
        Yii::app()->db->createCommand(
            "CREATE TABLE {$old_table} (id INTEGER NOT NULL PRIMARY KEY, name TEXT, odds REAL, c_value REAL)"
        )->execute();
        Yii::app()->db->createCommand("INSERT INTO {$old_table} SELECT * FROM {$new_table}")->execute();
        Yii::app()->db->createCommand("PRAGMA foreign_keys=on")->execute();
    }
    /**
     * Удаляет клон таблицы и 
     * @param string $old_table имя таблицы для которой нужно сделать клона
     * @param string $new_table имя backup (оригинала) таблицы
     */
    private static  function deleteCloneTable($old_table, $new_table) {
        Yii::app()->db->createCommand("PRAGMA foreign_keys=off")->execute();
        Yii::app()->db->createCommand("DROP TABLE {$old_table}")->execute();
        Yii::app()->db->createCommand("ALTER TABLE {$new_table} RENAME TO {$old_table}")->execute();
        Yii::app()->db->createCommand("PRAGMA foreign_keys=on")->execute();
    }

    /**
     * Создание новой семантической сети
     * @param array $target_hypotheses целевые гипотезы
     */
    public static function create(array $target_hypotheses) {
        $sn = new SemanticNetwork();
        $sn->target_hypotheses = $target_hypotheses;
        // создание BACKUP'a таблицы
        self::cloneTable(self::$name_old_table, self::$name_backup_table);
        return $sn;
    }
    
    /**
     * Удаление сети
     */
    public static function destroy() {
        try {
            Yii::app()->db->createCommand("SELECT * FROM ". self::$name_backup_table ." LIMIT 1")->execute();
        } catch (Exception $ex) {
            // если база BACKUP не существует, то на выход
            return;
        }
        self::deleteCloneTable(self::$name_old_table, self::$name_backup_table);
    }
    
    /**
     * Загрузка существующей сети
     */
    public static function load() {
        if (isset($_SESSION["SemanticNetwork"]) || session_start()) {
            return $_SESSION["SemanticNetwork"];
        }
    }
    
    /**
     * Сохранение текущей сети
     */
    public function save() {
        if (isset($_SESSION["SemanticNetwork"]) || session_start()) {
            $_SESSION["SemanticNetwork"] = $this;
        }
    }
    
    /**
     * Возвращает гипотезы которые еще не достигли необходимой точности
     * @return Hypothesis[] список гипотез, либо NULL если все гипотезы достигли заданной точности
     */
    public function leaderHypotheses() {
        $result = array();
        foreach ($this->target_hypotheses as $id_hyp => $data) {
            $h = Hypothesis::model()->findByPk($id_hyp);
            if (($data['eps'] - $h->odds) > 0)
                $result[] = $h;
        }
        return $result;
    }
    
    /**
     * Прямой проход (сохраняющий изменения)
     * @param integer $id_quest ID вопроса ОТ которого идет проход
     * @param numeric $c_value значение веса
     * @param integer $id_hyp ID гипотезы до которой идет подьем, если NULL то до корня
     */
    public function directPassage($id_quest, $c_value, $id_hyp = null) {
        $quest = Quest::model()->findByPk($id_quest);
        $quest->c_value = $c_value;
        $quest->save();
        $quest->moveToUp($id_hyp);
    }

    /**
     * Обратный проход
     * @return Quest    возвращает вопрос,
     *                  который в наибольшей степени изменяет лидирующую гипотезу,
     *                  либо NULL если вопросы кончились.
     */
    public function reversePassage() {
        if (empty($hyps = $this->leaderHypotheses())) {
            return null;
        }
        $h = $hyps[0];
        $max_deviation = -1;
        $nextquest = null;
        if ($h->c_value > $this->target_hypotheses[$h->id]) {
            return null;
        }
        // устанавливаем неограниченное время выполнения
        set_time_limit(0);
        //
        foreach ($h->getQuestionsNotAsked() as $quest) {
            $odds = 0.0;
           // YiiBase::beginProfile("passage");
            if ($h->c_value < 0) {
                $odds = $this->pseudoDirectPassage($quest, $h->id, -5);
            }
            else {
                $odds = $this->pseudoDirectPassage($quest, $h->id, 5);
            }
            if (($val = abs($odds - $h->odds)) > $max_deviation) {
                $max_deviation = $val;
                $nextquest = $quest;
            }
         //   YiiBase::endProfile("passage");
        }
        return $nextquest;
    }
    
    /**
     * Псевдо прямой проход. Производит прямой проход для МАХ или MIN значения
     * коэффициента С (вес). Затем делает прямой проход со старым значением.
     * @param Hypothesis $quest вопрос для которого производиться проход
     * @param integer $id_hyp ID гипотезы до которой производиться проход
     * @param double $c_value   значение веса (С) с которым нужно проводить псевдо проход
     * @return real значение шансы целевой гипотезы
     */
    private function pseudoDirectPassage(Quest & $quest, $id_hyp, $c_value) {
        self::cloneTable(self::$name_old_table, self::$name_revpas_table);
        $this->directPassage($quest->id, $c_value, $id_hyp);
        $odds = Hypothesis::model()->findByPk($id_hyp)->odds;
        self::deleteCloneTable(self::$name_old_table, self::$name_revpas_table);
        return $odds;
    }
}