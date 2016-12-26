<?php

use yii\db\Migration;

class m161226_153220_add_caption_column_to_carousel_item extends Migration
{
    public function up()
    {
        $this->addColumn('{{%carousel_item}}', 'caption', $this->string(2048));
    }

    public function down()
    {
        $this->dropColumn('{{%carousel_item}}', 'caption');
    }
}
