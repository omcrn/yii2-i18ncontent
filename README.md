## Installation


  - 2. Run "composer update"
  - 3. Make sure you have "user" table in your database. If you do not have, create it with primary key name "id".
  - 4. Run "php console/yii migrate --migrationPath=@vendor/omcrn/yii2-i18ncontent/migrations" from projects root directory
  - 5. Add the following code in configuration file
  
```php
"modules" => [
    '...',
    'i18ncontent' => [
        'class' => 'centigen\i18ncontent\Module',
        'userClass' => 'common\models\User', //User model class. If you do not have user model, generate it from user table. Make sure this models extends \yii\db\ActiveRecord class
        'defaultLayout' => '/admin', //Default layout which will be used for rendering i18ncontent pages
        'mediaUrlPrefix' => null, //In texts which may contain <img> or other media object tags (texts which come from WYSIWYG editors)
                                 // `$mediaUrlPrefix` strings are replaced with `$mediaUrlReplacement` string when calling `Html::encodeMediaItemUrls`
                                 // and vice versa when calling `Html::decodeMediaItemUrls`
        'mediaUrlReplacement' => '{{media_item_url_prefix}}' //See `$mediaUrlPrefix`
    ],
],
"components" => [
    '...',
    "i18n" => [
        "translations" => [
            '...',
            'i18ncontent' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@vendor/omcrn/yii2-i18ncontent/messages',
            ],
        ]
    ]
]
```

  - 6. Add "availableLocales" array in application configuration "params" array

```php
'params' => [
    '...',
    'availableLocales' => [
        'en-US' => 'English',
        'ru-RU' => 'Русский',
        'ka-GE' => 'ქართული (KA)'
    ],
]
```