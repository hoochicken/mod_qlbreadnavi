<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
//echo '<pre>';print_r($arrSubmenu);die;
?>
<?php if(!empty($strSeparatorBreadcrumb2Submenu)) :?>
    <div class="divider dividerBreadcrumb2Submenu">
        <?php echo $strSeparatorBreadcrumb2Submenu; ?>
    </div>
<?php endif; ?>
<ul class="submenu qlbreadnavi<?php echo $strModuleclass_sfx; ?> <?php echo $params->get('strDirectionSubmenu', 'horizontal'); ?>">
    <?php foreach($arrSubmenu as $numKey => $objSubmenuItem): ?>
        <?php if ('heading' == $objSubmenuItem->type || 'separator' == $objSubmenuItem->type) continue; ?>
        <li class="item<?php if (isset($objItem->id)) echo $objItem->id; ?> content" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a itemprop="item" href="<?php echo $objSubmenuItem->flink; ?>" class="pathway">
                <?php echo $objSubmenuItem->title; ?>
            </a>
            <?php if ($numKey < (count($arrSubmenu) - 1)) : ?>
                <span class="item<?php if (isset($objItem->id)) echo $objItem->id; ?> divider">
                    <?php echo $strSeparatorSub; ?>
                </span>
            <?php endif; ?>
            <meta itemprop="position" content="<?php echo $numKey + 1; ?>">
        </li>
    <?php endforeach; ?>
</ul>