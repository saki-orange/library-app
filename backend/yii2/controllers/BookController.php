<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

class BookController extends ActiveController {
  public $modelClass = 'app\models\Book';

  public function actions() {
    $actions = parent::actions();
    unset($actions['index']);
    return $actions;
  }

  public function actionIndex() {
    $query = $this->modelClass::find();

    $request = Yii::$app->request;

    if ($title = $request->get('title')) {
      $query->andWhere(['like', 'title', $title]);
    }

    return $query->all();
  }
}
