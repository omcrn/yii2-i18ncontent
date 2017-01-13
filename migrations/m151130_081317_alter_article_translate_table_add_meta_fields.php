<?php

use yii\db\Migration;
use yii\db\Schema;

class m151130_081317_alter_article_translate_table_add_meta_fields extends Migration
{
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'meta_title', $this->string(512)->null());
        $this->addColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'meta_description', $this->string(512)->null());
        $this->addColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'meta_keywords', $this->string(512)->null());
    }

    public function down()
    {
        $this->dropColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'meta_title');
        $this->dropColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'meta_description');
        $this->dropColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'meta_keywords');
    }
}
