<?php

use \PHPUnit\Framework\Assert;

class BookCest {
    const SCHEMA = [
        'id' => 'string',
        'title' => 'string',
        'author' => 'string',
        'publisher' => 'string',
        'published_date' => 'string',
        'isbn' => 'string',
        'image_url' => 'string',
    ];

    public function _before(ApiTester $I) {
    }

    public function getAllBooks(ApiTester $I) {
        $I->sendGET('/books');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
    }

    public function getBooksByTitleSample(ApiTester $I) {
        $I->sendGET('/books', ['title' => 'Sample']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(array_merge(self::SCHEMA, ['title' => 'string:regex(~Sample~)']), "$[*]");
        // 3件のみデータが取得できることを確認
        Assert::assertEquals(3, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }

    public function getBooksByTitleSample1(ApiTester $I) {
        $I->sendGET('/books', ['title' => 'Sample Book 1']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType(self::SCHEMA, "$[*]");
        // 1件のみデータが取得できることを確認
        Assert::assertEquals(1, count($I->grabDataFromResponseByJsonPath('$')[0]));
        $I->seeResponseContainsJson(['title' => 'Sample Book 1'], '$[0]');
    }

    public function getUndefinedBook(ApiTester $I) {
        $I->sendGET('/books', ['title' => 'UndefinedBook']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        Assert::assertEquals(0, count($I->grabDataFromResponseByJsonPath('$')[0]));
    }
}
