# 図書館システム (WIP)
[仕様](docs/architecture.md)


## Installation
### 必要なファイル
- `backend/.env`
  ```
  POSTGRES_USER=
  POSTGRES_PASSWORD=
  POSTGRES_DB=
  ```

- `backend/yii2/.env`
  <!-- @TODO YII_COOKIE_VALIDATION_KEYの生成方法を記述 -->
  ```
  YII_COOKIE_VALIDATION_KEY=
  DB_USER=<backend/.envのPOSTGRES_USER>
  DB_PASS=<backend/.envのPOSTGRES_PASS>
  DB_NAME=<backend/.envのPOSTGRES_DB>
  TEST_DB_NAME=<テスト用のDB名>
  ```

- `frontend/nextjs/.env.local`
  <!-- @TODO AUTH_SECRETの生成方法を記述 -->
  ```
  AUTH_SECRET=
  ```
