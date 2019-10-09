<?php
namespace Builder\PageBundle\Controller\Builder;

//UTILS pour builder
class Utils
{
 
    static function filterPageContentPositionStartWith($pageContents, $str)
    {
        $res = [];
        $r = 0;
        for ($i = 0; $i < count($pageContents); $i++) {
            if (Utils::startsWith($pageContents[$i]->getPosition(), $str))
                {
                $res[$r] = $pageContents[$i];
                $r = $r + 1;
            }
        }
        return $res;
    }

    static function filterOnePageContentPositionStartWith($pageContents, $str)
    {
        for ($i = 0; $i < count($pageContents); $i++) {
            if (Utils::startsWith($pageContents[$i]->getPosition(), $str))
                {
                return $pageContents[$i];
            }
        }
        return null;
    }

    static function startsWith($str, $needle)
    {
        return substr($str, 0, strlen($needle)) === $needle;
    }
}