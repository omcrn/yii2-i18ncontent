<?php

use yii\db\Migration;

class m161226_153220_add_caption_column_to_carousel_item extends Migration
{
    public function up()
    {
        $this->addColumn(\centigen\i18ncontent\models\WidgetCarouselItem::tableName(), 'caption', $this->string(2048));
    }

    public function down()
    {
        $this->dropColumn(\centigen\i18ncontent\models\WidgetCarouselItem::tableName(), 'caption');
    }
}
