<?php
/**
 * @package        mod_qlbreadnavi
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if (1 != $params->get('boolShowBreadcrumb', 1) && 1 != $params->get('boolShowSubmenu', 1)) {
    return;
}
$params->set('moduleclass_sfx', $params->get('moduleclass_sfx') . ' ' . $params->get('strBootstrapModuleClass'));

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

// Get the breadcrumbs
$obj_qlbreadnavi = new modQlbreadnaviHelper($module, $params);
$objActive = $obj_qlbreadnavi->getActive();
$arrList = $obj_qlbreadnavi->getList($objActive);

$objDoc = JFactory::getDocument();
$objDoc->addStyleDeclaration($obj_qlbreadnavi->getStylesDefault());
if (1 == $params->get('boolStyles', 0)) {
    $objDoc->addStyleDeclaration($obj_qlbreadnavi->getStyles());
}
if (1 == $params->get('boolResponsiveActive', 0)) {
    $objDoc->addStyleDeclaration($obj_qlbreadnavi->getStylesResponsive());
}
if (1 == $params->get('boolStylesheets', 1)) {
    JHtml::stylesheet('mod_qlbreadnavi/styles.css', false, true, false);
}

$numCount = count($arrList);
for ($i = 0; $i < $numCount; $i++) {
    if ($i == 1 && !empty($arrList[$i]->link) && !empty($arrList[$i - 1]->link) && $arrList[$i]->link == $arrList[$i - 1]->link) {
        unset($arrList[$i]);
    }
}
end($arrList);
$numKeyLastItem = key($arrList);
prev($arrList);
$numKeyPenultimateItem = key($arrList);
$numKeyPenultimteItem = key($arrList);

$objActive = $obj_qlbreadnavi->getActive();
$arrSubmenu = $obj_qlbreadnavi->getSubmenuitems($objActive);
//JHtml::stylesheet('mod_qlbreadnavi/styles.css', false, true, false);

// Set the default separator
$strSeparator = $obj_qlbreadnavi->setSeparator($params->get('strSeparator'));
$strSeparatorBreadcrumb2Submenu = $obj_qlbreadnavi->setSeparator($params->get('strSeparatorBreadcrumb2Submenu'));
$strSeparatorSub = $obj_qlbreadnavi->setSeparator($params->get('strSeparatorSub'));
$strModuleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_qlbreadnavi', $params->get('layout', 'default'));
