<?php
namespace centigen\i18ncontent;
use yii\helpers\Url;

/**
 * Class I18nContentModule
 *
 * @author zura
 * @package centigen\i18ncontent
 */
class Module extends \yii\base\Module
{
    /**
     * Identity User class name
     *
     * @var string
     */
    public $userClass = null;

    /**
     * Default layout which will be used in all actions
     *
     * @var string
     */
    public $defaultLayout = null;

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

    public function __construct($id, $parent = null, $config = [])
    {
        parent::__construct($id, $parent, $config);
        if (!$this->mediaUrlPrefix){
            $this->mediaUrlPrefix = Url::base(true);
        }
    }

    public function missingTranslation()
    {
        // @todo
    }
}