<?php

use yii\db\Migration;

/**
 * Handles adding position to table `article`.
 */
class m170112_115144_add_position_column_to_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\Article::tableName(), 'position', $this->integer(2));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn(\centigen\i18ncontent\models\Article::tableName(), 'position');
    }
}
