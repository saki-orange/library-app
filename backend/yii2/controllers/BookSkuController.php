<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

class BookSkuController extends ActiveController {
  public $modelClass = 'app\models\BookSku';

  public function actions() {
    $actions = parent::actions();
    unset($actions['index']);
    return $actions;
  }

  public function actionIndex() {
    $query = $this->modelClass::find();

    $request = Yii::$app->request;
    if ($bookId = $request->get('book_id')) {
      if (!\thamtech\uuid\helpers\UuidHelper::isValid($bookId)) {
        throw new \yii\web\BadRequestHttpException('Invalid book ID format.');
      }
      $query->andWhere(['book_id' => $bookId]);
    }

    return $query->all();
  }
}
