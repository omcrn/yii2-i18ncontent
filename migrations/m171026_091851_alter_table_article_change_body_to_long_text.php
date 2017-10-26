<?php

use yii\db\Migration;

class m171026_091851_alter_table_article_change_body_to_long_text extends Migration
{
    public function up()
    {
        $this->alterColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'body', 'LONGTEXT');
    }

    public function down()
    {
        $this->alterColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(), 'body', $this->text());
    }

}
