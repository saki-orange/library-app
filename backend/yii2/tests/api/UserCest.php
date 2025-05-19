<?php

use \PHPUnit\Framework\Assert;

class UserCest {
    const SCHEMA = [
        'id' => 'string',
        'name' => 'string',
        'email' => 'string:email',
        'password' => 'string',
    ];

    public function _fixtures() {
        return [
            'users' => ['class' => \app\tests\fixtures\UserFixture::class, 'dataFile' => '@app/tests/fixtures/data/user1.php'],
        ];
    }

    public function _before(ApiTester $I) {
    }

    public function getAllUsers(ApiTester $I) {
        $I->sendGET('/users');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
    }

    public function getUsersByEmail(ApiTester $I) {
        $I->sendGET('/users', ['email' => 'user1@example.com']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
        // 1件のみデータが取得できることを確認
        Assert::assertEquals(1, count($I->grabDataFromResponseByJsonPath('$')[0]));
        $I->seeResponseContainsJson(['email' => 'user1@example.com'], '$[0]');
    }

    public function getNotFoundUser(ApiTester $I) {
        $I->sendGET('/users', ['email' => 'NotFound@example.com']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        Assert::assertEquals(0, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }
}
