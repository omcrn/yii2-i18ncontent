<?php

use yii\db\Schema;
use yii\db\Migration;

class m150814_064233_page extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(\centigen\i18ncontent\models\Page::tableName(), [
            'id' => Schema::TYPE_PK,
            'slug' => Schema::TYPE_STRING . '(2048) NOT NULL',
            'view' => Schema::TYPE_STRING . '(255)',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
        ], $tableOptions);

        $this->createTable(\centigen\i18ncontent\models\PageTranslation::tableName(), [
            'id' => $this->primaryKey(11),
            'page_id' => $this->integer(11)->notNull(),
            'locale' => $this->string(15)->notNull(),
            'title' => $this->string(512)->notNull(),
            'body' => $this->text()->notNull(),
            'meta_title' => $this->string(512),
            'meta_keywords' => $this->string(512),
            'meta_description' => $this->string(512),
        ]);

        $this->insert(\centigen\i18ncontent\models\Page::tableName(), [
            'slug' => 'about',
            'status' => \centigen\i18ncontent\models\Page::STATUS_PUBLISHED,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        $this->insert(\centigen\i18ncontent\models\PageTranslation::tableName(), [
            'page_id' => 1,
            'locale' => 'en-US',
            'title' => 'About us',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce sagittis, turpis sit amet molestie elementum, ante tortor vehicula risus, et finibus ipsum magna et ipsum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent erat lacus, facilisis eget vestibulum in, consequat in purus. Mauris venenatis non augue vitae auctor. Suspendisse eu erat et tortor condimentum accumsan. Pellentesque orci tortor, dapibus a sodales sed, mollis ut tellus. Sed dapibus diam non diam pharetra, at varius ipsum efficitur. Fusce congue ipsum ante. Fusce ipsum metus, posuere eget vestibulum at, hendrerit vel odio.'
        ]);

        $this->insert(\centigen\i18ncontent\models\PageTranslation::tableName(), [
            'page_id' => 1,
            'locale' => 'ru-RU',
            'title' => 'О нас',
            'body' => 'В профессиональной сфере часто случается так, что личные или корпоративные клиенты заказывают, чтобы публикация была сделана и представлена еще тогда, когда фактическое содержание все еще не готово. Вспомните новостные блоги, где информация публикуется каждый час в живом порядке. Тем не менее, читатели склонны к тому, чтобы быть отвлеченными доступным контентом, скажем, любым текстом, который был скопирован из газеты или интернета. Они предпочитают сконцентрироваться на тексте, пренебрегая разметкой и ее элементами. К тому же, случайный текст подвергается риску быть неумышленно смешным или оскорбительным, что является неприемлемым риском в корпоративной среде. Lorem ipsum, а также ее многие варианты были использованы в работе начиная с 1960-ых, и очень даже похоже, что еще с 16-го века.'
        ]);

        if ($this->db->driverName === 'mysql') {
            $this->createIndex('IDX_page_translation_page_id', \centigen\i18ncontent\models\PageTranslation::tableName(), 'page_id');
            $this->addForeignKey('FK_page_translation_page_id', \centigen\i18ncontent\models\PageTranslation::tableName(), 'page_id', \centigen\i18ncontent\models\Page::tableName(), 'id', 'cascade', 'cascade');
        }
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_page_translation_page_id', \centigen\i18ncontent\models\PageTranslation::tableName());
        $this->dropTable(\centigen\i18ncontent\models\PageTranslation::tableName());
        $this->dropTable(\centigen\i18ncontent\models\Page::tableName());
    }

}
