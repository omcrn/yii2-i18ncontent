yii2-i18ncontent
================

yii2-i18ncontent is yii2 module for creating several types of contents in different languages.
It support creating the following contents:

 - Translatable article categories and articles
 - Translatable Pages
 - Carousels with translatable caption texts
 - Translatable portions of text.
 - Menu contents (Non translatable yet)

### Installation

  1. Run `composer require omcrn/yii2-i18ncontent` or add `"omcrn/yii2-i18ncontent": "^1.0.0"` in your projects `composer.json`.
  2. Make sure you have `user` table in your database with primary key `id`.
  3. Run migrations to create tables by `php console/yii migrate --migrationPath=@yii/i18n/migrations` from projects root directory
  4. Run migrations to create tables by `php console/yii migrate --migrationPath=@vendor/omcrn/yii2-i18ncontent/migrations` from projects root directory
 
### Configuration
  
Add the following code in projects configuration file under `modules` section

```php
'i18ncontent' => [
    'class' => 'centigen\i18ncontent\Module',
    'userClass' => 'common\models\User', //User model class. If you do not have user model, generate it from user table. Make sure this models extends \yii\db\ActiveRecord class
    'defaultLayout' => '/admin', //Default layout which will be used for rendering i18ncontent pages
    'mediaUrlPrefix' => null, //In texts which may contain <img> or other media object tags (texts which come from WYSIWYG editors)
                             // `$mediaUrlPrefix` strings are replaced with `$mediaUrlReplacement` string when calling `Html::encodeMediaItemUrls`
                             // and vice versa when calling `Html::decodeMediaItemUrls`
    'mediaUrlReplacement' => '{{media_item_url_prefix}}' //See `$mediaUrlPrefix`
],
```

Add the following code in project's configuration file under `components` section

```php
"i18n" => [
    "translations" => [
        '...',
        'i18ncontent' => [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@vendor/omcrn/yii2-i18ncontent/messages',
        ],
    ]
]
'formatter' => [
    'class' => 'centigen\base\i18n\Formatter'
],
```

Add `availableLocales` array to application configuration `params` array.

For each language listed here tab will be displayed to provide content.

```php
'params' => [
    '...',
    'availableLocales' => [
        'en-US' => 'English',
        'ru-RU' => 'Русский'
        ...
    ],
]
```

### Urls for administration

Append the following urls to the domain to see administration pages

| Content                |                  Url                 |
|------------------------|--------------------------------------|
| Article categories     | i18ncontent/article-category/index   |
| Articles               | i18ncontent/article/index            |
| Pages                  | i18ncontent/page/index               |
| Text widgets           | i18ncontent/widget-text/index        |
| Carousels              | i18ncontent/widget-carousel/index    |
| Menus                  | i18ncontent/widget-menu/index        |
