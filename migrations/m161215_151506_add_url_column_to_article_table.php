<?php

use yii\db\Migration;

/**
 * Handles adding url to table `article`.
 */
class m161215_151506_add_url_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\Article::tableName(), 'url', $this->string(2048));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn(\centigen\i18ncontent\models\Article::tableName(), 'url');
    }
}
