<?php

class LinkController extends CrudController
{
    public function actionView($id) {
        throw new CHttpException(400);
    }
    
    public function getClass() {
        return new Link();
    }
    
    /**
     * Перед удалением записи сначала удаляем строки из таблицы дочерних элементов
     * @param type $id
     */
    public function actionDelete($id) {
        $sql = "DELETE FROM LinkChildHyps WHERE id_link = {$id}";
        Yii::app()->db->createCommand($sql)->execute();
        parent::actionDelete($id);
    }
    
    public function actionUpdate($id) {
        if (is_null($model = $this->loadModel($id))) {
            throw new CHttpException(404);
        }
        $isNewRecord = $model->isNewRecord;
        if ($this->initForm($model) && isset($_POST['ChildLinks'])
                && $this->validate($model) && $model->save()
                && $this->initLinks($_POST['ChildLinks'], $model))
        {
            if ($isNewRecord)
                $this->redirect(array("{$this->id}/update", "id" => $model->id));
        }
        $this->render('update', array('model' => $model));
    }
    
    /**
     * Устанавливает связи между родительской и дочерней гипотезой
     * @param array $hypotheses список дочерних гипотез
     * @param Link $model связь для которой строятся линки на дочерние гипотезы
     */
    private function initLinks(array $hypotheses, $model) {
        if (empty($hypotheses)) {
            return false;
        }
        $sql = "INSERT INTO LinkChildHyps VALUES(:link, :hyp)";
        foreach ($hypotheses as $id_hyp) {
            $cmd = Yii::app()->db->createCommand($sql);
            $cmd->bindValue(":link", $model->id);
            $cmd->bindValue(":hyp", $id_hyp, PDO::PARAM_INT);
            $cmd->execute();
            if ($model->id_type == TypeLink::TYPE_PRODUCTION) {
                break;
            }
        }
        return true;
    }
}