<?php
/**
 * User: zura
 * Date: 3/24/18
 * Time: 3:28 PM
 */
namespace centigen\i18ncontent;

use yii\base\Component;
use yii\helpers\Url;

/**
 * Class I18nContent
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package centigen\i18ncontent
 */
class I18nContent extends Component
{

    /**
     * Identity User class name
     *
     * @var string
     */
    public $userClass = null;

    /**
     * In texts which may contain <img> or other media object tags (texts which come from WYSIWYG editors)
     * `$mediaUrlPrefix` strings are replaced with `$mediaUrlReplacement` string when calling `Html::encodeMediaItemUrls`
     * and vice versa when calling `Html::decodeMediaItemUrls`
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var string
     */
    public $mediaUrlPrefix = null;

    /**
     * See `$mediaUrlPrefix`
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var string
     */
    public $mediaUrlReplacement = '{{media_item_url_prefix}}';

    public function init()
    {
        parent::init();
        if (!$this->mediaUrlPrefix){
            $this->mediaUrlPrefix = Url::base(true);
        }
    }
}