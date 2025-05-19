<?php

class PostLoanCest {
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

    public function createInvalidSkuIdLoan(ApiTester $I) {
        $I->sendPOST('/loans', [
            'sku_id' => 'NotUuid',
            'user_id' => $this->user_id_example,
        ]);
        $I->seeResponseCodeIs(400);
    }

    public function createInvalidUserIdLoan(ApiTester $I) {
        $I->sendPOST('/loans', [
            'sku_id' => $this->sku_id_example,
            'user_id' => 'NotUuid',
        ]);
        $I->seeResponseCodeIs(400);
    }

    public function createNotFoundSkuIdLoan(ApiTester $I) {
        $I->sendPOST('/loans', [
            'sku_id' => '5890442b-1363-4330-91b6-35696343a5e4',
        ]);
        $I->seeResponseCodeIs(422);
    }

    public function createNotFoundUserIdLoan(ApiTester $I) {
        $I->sendPOST('/loans', [
            'user_id' => '5890442b-1363-4330-91b6-35696343a5e4',
        ]);
        $I->seeResponseCodeIs(422);
    }

    /**
     * 他の人が予約中の本は貸出できないことを確認
     */

    /**
     * 取置中の本は貸出できないことを確認
     * 自分が取置中の場合は，取置テーブルから削除してから貸出
     */

    /**
     * 正常に貸出できることを確認
     * 貸出した本の返却日が現在の日付から貸出期間を加算した値で初期化されていることを確認
     */

    /**
     * 貸出冊数を超えて貸出できないことを確認
     */

    /**
     * 他の人が予約中の本は貸出延長できないことを確認
     */
}
