<?php

class DeleteLoanCest {
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

    /**
     * 予約が入っている本が返却後に取置処理中になることを確認
     */
}
