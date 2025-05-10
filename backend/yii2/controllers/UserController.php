<?php

namespace app\controllers;

use yii\rest\ActiveController;
use Yii;

class UserController extends ActiveController {
  public $modelClass = 'app\models\User';

  public function actions() {
    $actions = parent::actions();
    unset($actions['index']);
    return $actions;
  }

  public function actionIndex() {
    $query = $this->modelClass::find();

    $request = Yii::$app->request;

    if ($email = $request->get('email')) {
      $query->andWhere(['email' => $email]);
    }

    return $query->all();
  }

  // public function actionByEmail($email) {
  //   $query = $this->modelClass::find()->where(['email' => $email]);

  //   if ($query->count() === 0) {
  //     throw new \yii\web\NotFoundHttpException("User not found.");
  //   }

  //   return $query->one();
  // }

  // public function actionCreate() {
  //   $model = new $this->modelClass();
  //   Yii::warning(Yii::$app->request->post());

  //   if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
  //     Yii::$app->response->setStatusCode(201);
  //     return $model;
  //   } else {
  //     Yii::$app->response->setStatusCode(422);
  //     return $model->getErrors();
  //   }
  // }

  // public function actionUpdate($id) {
  //   $model = $this->modelClass::findOne($id);

  //   if (!$model) {
  //     throw new \yii\web\NotFoundHttpException("User not found.");
  //   }

  //   if ($model->load(\Yii::$app->request->post(),'') && $model->save()) {
  //     return $model;
  //   } else {
  //     return $model->getErrors();
  //   }
  // }

  // public function actionDelete($id) {
  //   $model = $this->modelClass::findOne($id);

  //   if (!$model) {
  //     throw new \yii\web\NotFoundHttpException("User not found.");
  //   }

  //   if ($model->delete()) {
  //     return ['status' => 'success'];
  //   } else {
  //     return ['status' => 'error', 'message' => 'Failed to delete user.'];
  //   }
  // }
}
