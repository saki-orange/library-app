<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;

class LoanController extends ActiveController {
  public $modelClass = 'app\models\Book';

  public function actions() {
    $actions = parent::actions();
    unset($actions['index']);
    unset($actions['create']);
    unset($actions['delete']);
    return $actions;
  }

  public function actionIndex() {
    $query = $this->modelClass::find();

    $request = Yii::$app->request;

    if ($skuId = $request->get('sku_id')) {
      if (!\thamtech\uuid\helpers\UuidHelper::isValid($skuId)) {
        throw new \yii\web\BadRequestHttpException('Invalid book ID format.');
      }
      $query->andWhere(['sku_id' => $skuId]);
    }

    if ($userId = $request->get('user_id')) {
      if (!\thamtech\uuid\helpers\UuidHelper::isValid($userId)) {
        throw new \yii\web\BadRequestHttpException('Invalid book ID format.');
      }
      $query->andWhere(['user_id' => $userId]);
    }

    return $query->all();
  }


  /**
   * 貸出処理
   */
  public function actionCreate() {
    $model = new $this->modelClass;
    // 代入チェック
    if (!$model->load(Yii::$app->request->post(), '')) {
      Yii::$app->response->setStatusCode(422);
      return $model->getErrors();
    }

    try {
      if (!$model->lendBook()) {
        Yii::$app->response->setStatusCode(422);
        return $model->getErrors();
      }
    } catch (\Throwable $e) {
      Yii::$app->response->setStatusCode(500);
      return ['error' => 'サーバーエラーが発生しました'];
    }

    Yii::$app->response->setStatusCode(201);

    return $model;
  }


  /**
   * 貸出延長処理
   */
  public function actionExtend($id) {
    $model = $this->modelClass::findOne($id);

    if (!$model) {
      throw new \yii\web\NotFoundHttpException("Loan not found.");
    }

    try {
      if (!$model->extendReturnDate()) {
        Yii::$app->response->setStatusCode(422);
        return $model->getErrors();
      }
    } catch (\Throwable $e) {
      Yii::$app->response->setStatusCode(500);
      return ['error' => 'サーバーエラーが発生しました'];
    }

    Yii::$app->response->setStatusCode(200);
    return $model;
  }


  /**
   * 返却処理
   */
  public function actionDelete($id) {
    $model = $this->modelClass::findOne($id);

    if (!$model) {
      throw new \yii\web\NotFoundHttpException("Loan not found.");
    }

    try {
      if (!$model->returnBook()) {
        Yii::$app->response->setStatusCode(422);
        return $model->getErrors();
      }
    } catch (\Throwable $e) {
      Yii::$app->response->setStatusCode(500);
      return ['error' => 'サーバーエラーが発生しました'];
    }

    Yii::$app->response->setStatusCode(204);
    return $model;
  }
}
