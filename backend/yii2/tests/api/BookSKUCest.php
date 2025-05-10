<?php

use \PHPUnit\Framework\Assert;

class BookSKUCest {
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

    public function getAllBookSKU(ApiTester $I) {
        $I->sendGET('/book-sku');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
    }

    public function getBookSKUByBookId(ApiTester $I) {
        $I->sendGET('/book-sku', ['book_id' => $this->book_id_example]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
        // 2件データが取得できることを確認
        Assert::assertEquals(2, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getUndefinedBookSKU(ApiTester $I) {
        $I->sendGET('/book-sku', ['book_id' => 'UndefinedBookId']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        Assert::assertEquals(0, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }
}
