<?php
/**
 * User: zura
 * Date: 6/20/17
 * Time: 11:11 AM
 */

namespace centigen\i18ncontent\models\query;

use centigen\i18ncontent\models\WidgetText;
use yii\db\ActiveQuery;


/**
 * Class WidgetTextQuery
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package centigen\i18ncontent\models\query
 */
class WidgetTextQuery extends ActiveQuery
{
    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param null $bd
     * @return array|WidgetText|mixed|null|\yii\db\ActiveRecord
     * @internal param Connection $db
     */
    public function one($bd = null)
    {
        return parent::one($bd);
    }

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param null $db
     * @return WidgetText[]|array|\yii\db\ActiveRecord[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }



}