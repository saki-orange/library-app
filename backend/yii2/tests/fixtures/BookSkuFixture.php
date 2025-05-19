<?php

namespace app\tests\fixtures;

class BookSkuFixture extends \yii\test\ActiveFixture {
  public $modelClass = 'app\models\BookSku';
  public $depends = ['app\tests\fixtures\BookFixture'];
}
