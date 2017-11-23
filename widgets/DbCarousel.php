<?php
/**
 * Eugine Terentev <eugine@terentev.net>
 */

namespace centigen\i18ncontent\widgets;

use centigen\i18ncontent\models\WidgetCarousel;
use centigen\i18ncontent\models\WidgetCarouselItem;
use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Carousel;
use yii\helpers\Html;

/**
 * Class DbCarousel
 * @package common\widgets
 */
class DbCarousel extends Carousel
{
    /**
     * @var
     */
    public $key;


    public $imgOptions = [];
    /**
     * @var array
     */
    public $controls = [
        '<span class="glyphicon glyphicon-chevron-left"></span>',
        '<span class="glyphicon glyphicon-chevron-right"></span>',
    ];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->key) {
            throw new InvalidConfigException;
        }
        $cacheKey = [
            WidgetCarousel::className(),
            $this->key
        ];
        $items = false;Yii::$app->cache->get($cacheKey);
        if ($items === false) {
            $items = [];
            $query = WidgetCarouselItem::find()
                ->joinWith('carousel')
                ->where([
                    '{{%widget_carousel_item}}.status' => 1,
                    '{{%widget_carousel}}.status' => WidgetCarousel::STATUS_ACTIVE,
                    '{{%widget_carousel}}.key' => $this->key,
                ])
                ->orderBy(['order' => SORT_ASC]);

           // var_dump($query->all());
            foreach ($query->all() as $k => $item) {
                /** @var $item \centigen\i18ncontent\models\WidgetCarouselItem */
                $carouselItem = [];
                $carouselItem['content'] = Html::img($item->getImageUrl() , $this->imgOptions);

                if ($item->url) {
                    $carouselItem['content'] = Html::a($carouselItem['content'], $item->url, ['target' => '_blank']);
                }
                if ($item->activeTranslation && $item->activeTranslation->caption) {
                    $carouselItem['caption'] = $item->activeTranslation->caption;
                }
                $items[] = $carouselItem;
            }
            //Yii::$app->cache->set($cacheKey, $items, 60 * 60 * 24 * 365);
        }
        $this->items = $items;


        //var_dump($this->items);exit;
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if (count($this->items) === 0) {
            return "";
        }
        if (count($this->items) === 1){
            $this->showIndicators = false;
            $this->controls = false;
        }
        return parent::run();
    }
}
