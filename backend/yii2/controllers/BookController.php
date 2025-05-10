<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use app\models\BookSku;

class BookController extends ActiveController {
  public $modelClass = 'app\models\Book';

  public function actions() {
    $actions = parent::actions();
    unset($actions['index']);
    unset($actions['create']);
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

  public function actionCreate() {
    $transaction = Yii::$app->db->beginTransaction();
    try {
      $model = new $this->modelClass;
      if (!$model->load(Yii::$app->request->post(), '') || !$model->save()) {
        $transaction->rollBack();
        Yii::$app->response->setStatusCode(422);
        return $model->getErrors();
      }

      // BookSkuの作成
      $bookSku = new BookSku();
      $bookSku->book_id = $model->id;
      if (!$bookSku->save()) {
        $transaction->rollBack();
        Yii::$app->response->setStatusCode(422);
        return $bookSku->getErrors();
      }

      $transaction->commit();
      Yii::$app->response->setStatusCode(201);

      return $model;
    } catch (\Throwable $e) {
      $transaction->rollBack();
      Yii::error("トランザクション失敗: " . $e->getMessage(), __METHOD__);
      Yii::$app->response->setStatusCode(500);
      return ['error' => 'サーバーエラーが発生しました'];
    }
  }
}
