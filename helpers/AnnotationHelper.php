<?php
/**
 * Created by PhpStorm.
 * User: koco
 * Date: 2/6/2017
 * Time: 12:11 PM
 */

namespace centigen\i18ncontent\helpers;

/**
 * Class AnnotationHelper
 * @package centigen\i18ncontent\helpers
 */
class AnnotationHelper
{

    /**
     * parse public properties with default value and type of class
     * @param $className
     * @return array
     */
    public static function getPublicProperties($className)
    {
        $classInstance = new $className();

        $data = [];
        foreach (self::readClassProperties($className) as $prop) {
            preg_match('/\*\s+\@var\s+([^\s]+)/', $prop->getDocComment(), $matches);
            if ($prop->isPublic() && !$prop->isStatic()) {
                $data[] = [
                    'name' => $prop->getName(),
                    'type' => $matches[1],
                    'value' => $classInstance->{$prop->getName()}
                    ];
            }
        }
        return $data;
    }


    /**
     * @param $className
     * @return \ReflectionProperty[]
     */
    protected static function readClassProperties($className)
    {
        $r = new \ReflectionClass($className);
        return $r->getProperties();
    }

}