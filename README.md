# Conduit バックエンドAPI
- ブログ記事サイトのリソースに対して、取得・挿入・更新・削除を行うことができます。
- またデータの挿入・更新・削除においては、JWTを利用したユーザー認証による管理が可能となります。
- APIを利用する上でユーザー、記事に関するリソースへのエンドポイント、HTTPメソッド、ユーザー認証の要不要に関して記述しています。
- ユーザー認証が必要なリソースへのアクセスには、JWTトークンを利用します。
- ユーザー登録、もしくはログインを行なってJWTトークンを取得して、HTTPヘッダーに追加してリソースへのリクエストを行なってください。
- リクエスト、レスポンスデータがあるものに関しては、JSONデータを利用します。
- 以下からリソースごとのエンドポイント、HTTPメソッド、説明を記述します。

## リソース
1. ユーザー
記事の投稿や更新、削除といった胥吏を行う際に必要なユーザーの管理を行います。
| HTTP method | Endpoint          | Request                                | Response                    |
| POST        | /api/users/       | "user":{"username","email","password"} | "user":{"username","email"} |
| POST        | /api/users/login/ | "user":{"email","password"}            | "user":{"username","email"} |
ログアウト、リフレッシュのエンドポイントは残念ながら、実装を忘れていました。
現状では、60分間まってもらえればログアウトすることができます。このように時間が解決してくれると思います。申し訳ありません。

2. 記事
記事の取得、投稿、更新、削除に関するリソース処理のエンドポイント
| HTTP method | Endpoint             | Auth | Request                                          | Response                |
| GET         | /api/articles/{slug} | No   |                                                  | Req + favorite + author |
| POST        | /api/articles/       | Yes  | "article":{"title","description","body","TagList"} | Req + favorite + author |
| PUT         | /api/articles/{slug} | Yes  | "article":{"title"or"description"or"body"}       | Req + favorite + author |
| DELETE      | /api/articles/{slug} | Yes  |                                                  | No content              |
| post        | /api/articles/{slug} | Yes  |                                                  |                         |

3. お気に入り登録
| HTTP method | Endpoint                 | Auth | Request | Response                                                               |
| POST        | articles/{slug}/favorite | Yes  |         | "article":{"title","description","body","TagList","favorite","author"} |
| DELETE      | articles/{slug}/favorite | Yes  |         | No content                                                             |