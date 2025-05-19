<?php
return [
  'user1' => [
    'id' => "21d3ee37-0abd-4d3e-a4df-2dda84999ee0",
    'name' => 'user1',
    'email' => 'user1@example.com',
    'password' => Yii::$app->security->generatePasswordHash('user1'),
  ],
  'user2' => [
    'id' => "3012df31-3593-4468-95b4-59f46fd7b13f",
    'name' => 'user2',
    'email' => 'user2@example.com',
    'password' => Yii::$app->security->generatePasswordHash('user2'),
  ],
  'user3' => [
    'id' => "46318d0f-edcb-4f0e-b005-ce0d9bf0d525",
    'name' => 'user3',
    'email' => 'user3@example.com',
    'password' => Yii::$app->security->generatePasswordHash('user3'),
  ],
];
