<?php

use \PHPUnit\Framework\Assert;

class BookSkuCest {
    const SCHEMA = [
        'id' => 'string',
        'book_id' => 'string',
    ];
    protected string $book_id_example;

    public function _before(ApiTester $I) {
        $I->sendGET('/books');
        $I->seeResponseCodeIs(200);
        $this->book_id_example = $I->grabDataFromResponseByJsonPath('$[0].id')[0];
    }

    public function getAllBookSku(ApiTester $I) {
        $I->sendGET('/book-sku');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
        Assert::assertEquals(6, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getBookSkuByBookId(ApiTester $I) {
        $I->sendGET('/book-sku', ['book_id' => $this->book_id_example]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
        // 2件データが取得できることを確認
        Assert::assertEquals(2, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getNotFoundBookSku(ApiTester $I) {
        $I->sendGET('/book-sku', ['book_id' => '5890442b-1363-4330-91b6-35696343a5e4']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        Assert::assertEquals(0, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getInvalidBookIdBookSku(ApiTester $I) {
        $I->sendGET('/book-sku', ['book_id' => 'NotUuid']);
        $I->seeResponseCodeIs(400);
    }

    public function createNotFoundBookSku(ApiTester $I) {
        $I->sendPOST('/book-sku', [
            'book_id' => '5890442b-1363-4330-91b6-35696343a5e4',
        ]);
        $I->seeResponseCodeIs(422);
    }
}
