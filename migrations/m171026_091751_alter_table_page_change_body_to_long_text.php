<?php

use yii\db\Migration;

class m171026_091751_alter_table_page_change_body_to_long_text extends Migration
{
    public function up()
    {
        $this->alterColumn(\centigen\i18ncontent\models\PageTranslation::tableName(), 'body', 'LONGTEXT');
    }

    public function down()
    {
        $this->alterColumn(\centigen\i18ncontent\models\PageTranslation::tableName(), 'body', $this->text());
    }

}
