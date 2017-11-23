<?php

use yii\db\Migration;

/**
 * Class m171113_085608_add_extra_description_to_article_translation
 */
class m171113_085608_add_extra_description_to_article_translation extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(),'extra_description',$this->text()->after('short_description'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn(\centigen\i18ncontent\models\ArticleTranslation::tableName(),'extra_description');
    }

}
