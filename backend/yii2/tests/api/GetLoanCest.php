<?php

use \PHPUnit\Framework\Assert;

class GetLoanCest {
    const SCHEMA = [
        'id' => 'string',
        'sku_id' => 'string',
        'user_id' => 'string',
        'return_date' => 'string',
        'status' => 'string',
    ];
    protected string $sku_id_example;
    protected string $user_id_example;

    public function _before(ApiTester $I) {
        $I->sendGET('/book-sku');
        $I->seeResponseCodeIs(200);
        $this->sku_id_example = $I->grabDataFromResponseByJsonPath('$[0].id')[0];
        $I->sendGET('/users');
        $I->seeResponseCodeIs(200);
        $this->user_id_example = $I->grabDataFromResponseByJsonPath('$[0].id')[0];
    }

    public function getLoansBySkuId(ApiTester $I) {
        $I->sendGET('/loans', ['sku_id' => $this->sku_id_example]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
        // 0件または1件データを取得できることを確認
        Assert::assertLessThanOrEqual(1, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getLoansByUserId(ApiTester $I) {
        $I->sendGET('/loans', ['user_id' => $this->user_id_example]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
        // 2件データが取得できることを確認
        Assert::assertEquals(2, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getNotFoundSkuIdLoan(ApiTester $I) {
        $I->sendGET('/loans', ['sku_id' => '5890442b-1363-4330-91b6-35696343a5e4']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        Assert::assertEquals(0, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getNotFoundUserIdLoan(ApiTester $I) {
        $I->sendGET('/loans', ['user_id' => '5890442b-1363-4330-91b6-35696343a5e4']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        Assert::assertEquals(0, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getInvalidSkuIdLoan(ApiTester $I) {
        $I->sendGET('/loans', ['sku_id' => 'NotUuid']);
        $I->seeResponseCodeIs(400);
    }

    public function getInvalidUserIdLoan(ApiTester $I) {
        $I->sendGET('/loans', ['user_id' => 'NotUuid']);
        $I->seeResponseCodeIs(400);
    }
}
