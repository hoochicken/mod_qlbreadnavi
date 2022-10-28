<?php
/**
 * @package        mod_qlbreadnavi
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_qlbreadnavi
 */
class modQlbreadnaviHelper
{

    function __construct($module, $params = 0)
    {
        // Get the PathWay object from the application
        $this->objModule = $module;
        if (is_object($params)) {
            $this->objParams = $params;
        } else {
            $this->objParams = new stdClass();
        }
        $this->objApp = JFactory::getApplication();
        $this->objPathway = $this->objApp->getPathway();
        $this->arrItems = $this->objPathway->getPathWay();
        $this->objLang = JFactory::getLanguage();
        $this->objMenu = $this->objApp->getMenu();
        $this->objHome = $this->getHome();
    }

    /**
     * Retrieve breadcrumb items
     *
     * @param $item
     * @return array
     */
    public function getList($item)
    {
        $arrCrumbs = [];
        if (!isset($item->tree)) {
            return $arrCrumbs;
        }
        foreach ($item->tree as $k => $v) {
            $arrCrumbs[] = $this->workItem($this->objMenu->getItem($v));
        }
        if ($this->objParams->get('showHome', 1)) {
            $item = new stdClass;
            $item->title = htmlspecialchars($this->objParams->get('homeText', JText::_('MOD_BREADCRUMBS_HOME')));
            $item->link = JRoute::_('index.php?Itemid=' . $this->objHome->id);
            $item->flink = $item->link;
            array_unshift($arrCrumbs, $item);
        }
        return $arrCrumbs;
    }

    /**
     * @param $objItem
     * @return mixed
     */
    function getSubmenuitems($objItem)
    {
        $boolMenubound = $this->objParams->get('boolHomeBindToMenu');
        $strMenuName = $this->objParams->get('strHomeBoundMenu');

        $arrSubmenu = $this->objMenu->getItems('parent_id', $objItem->id);
        if (0 >= count($arrSubmenu) && isset($objItem->parent_id)) {
            $objParentItem = new stdClass;
            $objParentItem->id = $objItem->parent_id;
            $arrSubmenu = $this->getSubmenuitems($objParentItem);
            //echo '<pre>';print_r($arrSubmenu);die;
        }
        foreach ($arrSubmenu as $numKey => $objItem) {
            $objItem->title = stripslashes(htmlspecialchars($objItem->title, ENT_COMPAT, 'UTF-8'));
            if (1 == $boolMenubound && $strMenuName != $objItem->menutype) {
                unset($arrSubmenu[$numKey]);
                continue;
            }
            $arrSubmenu[$numKey] = $this->workItem($objItem);
        }
        //echo '<pre>';print_r($arrSubmenu);die;
        reset($arrSubmenu);
        return $arrSubmenu;
    }

    /**
     * @param $objItem
     * @return mixed
     */
    private function workItem($objItem)
    {
        $objItem->flink = $objItem->link;
        switch ($objItem->type) {
            case 'separator':
            case 'heading':
                // No further action needed.
                break;

            case 'url':
                if ((strpos($objItem->link, 'index.php?') === 0) && (strpos($objItem->link, 'Itemid=') === false)) {
                    // If this is an internal Joomla link, ensure the Itemid is set.
                    $objItem->flink = $objItem->link . '&Itemid=' . $objItem->id;
                }
                break;

            case 'alias':
                //echo '<pre>';print_r($item);echo $item->objParams->get('aliasoptions');die('ASD');
                $objItem->flink = 'index.php?Itemid=' . $objItem->getParams()->get('aliasoptions');
                break;

            default:
                $objItem->flink = 'index.php?Itemid=' . $objItem->id;
                break;
        }

        if (strcasecmp(substr($objItem->flink, 0, 4), 'http') && (strpos($objItem->flink, 'index.php?') !== false)) {
            $objItem->flink = JRoute::_($objItem->flink, true, $objItem->getParams()->get('secure'));
        } else {
            $objItem->flink = JRoute::_($objItem->flink);
        }
        return $objItem;
    }

    /**
     * @return mixed
     */
    private function getHome()
    {
        if (JLanguageMultilang::isEnabled()) {
            $this->objHome = $this->objMenu->getDefault($this->objLang->getTag());
        } else {
            $this->objHome = $this->objMenu->getDefault();
        }
        return $this->objHome;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        $objActive = $this->objMenu->getActive() ? $this->objMenu->getActive() : $this->objHome;
        return $objActive;
    }

    /**
     * @param string $strSeparator
     * @return string
     */
    public function setSeparator($strSeparator = '')
    {
        $strSeparator = htmlspecialchars($strSeparator, ENT_COMPAT, 'UTF-8');
        return $strSeparator;
    }

    /**
     * @return string
     */
    function getStyles()
    {
        $arrStyles = [];
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul';
        $arrStyles[] = '{';
        if (0 != $this->objParams->get('strBackgroundOpacity', '100')) {
            $bg = $this->objParams->get('strBackground', '#000');
            if (100 != $this->objParams->get('numBackgroundOpacity', '100')) $bg = $this->hex2rgb($this->objParams->get('strBackground', '#000'), (int)$this->objParams->get('numBackgroundOpacity', '100'), 'string');
            $arrStyles[] = 'background:' . $bg . ';';
        }
        $arrStyles[] = 'float:left;';
        $arrStyles[] = 'list-style-type:none;';
        $arrStyles[] = 'margin:0;';
        $arrStyles[] = '}';
        if (1 == $this->objParams->get('boolShowBreadcrumb', 0) && 1 == $this->objParams->get('boolShowSubmenu', 0)) {
            $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul.breadcrumbies';
            $arrStyles[] = '{';
            $arrStyles[] = 'border-bottom-width:' . (3 * $this->objParams->get('numBorderwidth', '1')) . 'px;';
            $arrStyles[] = 'border-bottom-style:' . $this->objParams->get('strBorderStyle', 'solid') . ';';
            $arrStyles[] = 'border-bottom-color:' . $this->objParams->get('strBorderColor', '#ff0000') . ';';
            $arrStyles[] = '}';
        }
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul.vertical li';
        $arrStyles[] = '{';
        $arrStyles[] = 'border-bottom-width:' . $this->objParams->get('numBorderwidth', '1') . 'px;';
        $arrStyles[] = 'border-bottom-style:' . $this->objParams->get('strBorderStyle', 'solid') . ';';
        $arrStyles[] = 'border-bottom-color:' . $this->objParams->get('strBorderColor', '#ff0000') . ';';
        $arrStyles[] = '}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul.horizontal li .divider';
        $arrStyles[] = '{';
        $arrStyles[] = 'color:' . $this->objParams->get('strBorderColor', '#ff0000') . ';';
        $arrStyles[] = '}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul li a,#qlbreadnavi' . $this->objModule->id . ' ul li span.dull';
        $arrStyles[] = '{';
        $arrStyles[] = 'padding:' . $this->objParams->get('numPaddingVertical', '0') . 'px ' . $this->objParams->get('numPaddingHorizontal', '0') . 'px;';
        $arrStyles[] = 'text-align:' . $this->objParams->get('strTextAlign', 'left') . ';';
        $arrStyles[] = '}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul li:first-child a,#qlbreadnavi' . $this->objModule->id . ' ul li:first-child span.dull';
        $arrStyles[] = '{';
        $arrStyles[] = 'padding-left:0;';
        $arrStyles[] = '}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul li span.dull';
        $arrStyles[] = '{';
        $arrStyles[] = 'color:' . $this->objParams->get('strTextColor', '#0000ff') . ';';
        $arrStyles[] = '}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul li.showHere';
        $arrStyles[] = '{';
        $arrStyles[] = 'color:' . $this->objParams->get('strShowHereColor', '#0000ff') . ';';
        $arrStyles[] = '}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' ul li a';
        $arrStyles[] = '{';
        $arrStyles[] = 'color:' . $this->objParams->get('strFontColor', '#ffffff') . ';';
        $arrStyles[] = '}';
        return implode("\n", $arrStyles);
    }

    /**
     * @return string
     */
    function getStylesDefault()
    {
        $arrStyles = [];
        $arrStyles[] = '.qlbreadnavi .toggler {display:block;width:auto;margin:0 auto;}';
        $arrStyles[] = '.qlbreadnavi .toggler .btn {display:block;width:auto;margin:0 auto;}';
        if (1 == $this->objParams->get('boolShowBreadcrumb', 0) && 1 == $this->objParams->get('boolShowSubmenu', 0) && 'vertical' === $this->objParams->get('strDirection', 'horizontal')) {
            $arrStyles[] = '.qlbreadnavi ul.breadcrumbies {border-bottom:1px solid #999;}';
        }
        return implode("\n", $arrStyles);
    }

    /**
     * @param $strHex
     * @param int $numOpacity
     * @param string $strType
     * @return array|string
     */
    function hex2rgb($strHex, $numOpacity = 1, $strType = 'array')
    {
        $strHex = str_replace('#', '', $strHex);

        if (strlen($strHex) == 3) {
            $r = hexdec(substr($strHex, 0, 1) . substr($strHex, 0, 1));
            $g = hexdec(substr($strHex, 1, 1) . substr($strHex, 1, 1));
            $b = hexdec(substr($strHex, 2, 1) . substr($strHex, 2, 1));
        } else {
            $r = hexdec(substr($strHex, 0, 2));
            $g = hexdec(substr($strHex, 2, 2));
            $b = hexdec(substr($strHex, 4, 2));
        }
        $arrRgb = [$r, $g, $b];
        //return implode(",", $rgb); // returns the rgb values separated by commas
        if ('array' == $strType) {
            return $arrRgb;
        } // returns an array with the rgb values
        $arrRgb[] = $numOpacity / 100;
        $strReturn = 'rgba(' . implode(',', $arrRgb) . ')';
        return $strReturn;
    }

    /**
     * @return string
     */
    function getStylesResponsive()
    {
        $arrStyles = [];
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' .collapse {height:inherit;}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' .toggler {display:none;}';
        $arrStyles[] = '@media(max-width:' . (integer)$this->objParams->get('numResponsiveLimit') . 'px)';
        $arrStyles[] = '{';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' .toggler {display:inherit;}';
        $arrStyles[] = '#qlbreadnavi' . $this->objModule->id . ' .collapse {height:0;}';
        $arrStyles[] = '}';
        return implode("\n", $arrStyles);
    }
}