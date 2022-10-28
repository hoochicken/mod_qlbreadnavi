<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip'); ?>
<div class="qlbreadnavi" id="qlbreadnavi<?php echo $module->id; ?>">
    <?php if ((bool)$params->get('boolResponsiveActive', 0)) {
        require JModuleHelper::getLayoutPath('mod_qlbreadnavi', 'default_toggler');
    } ?>
    <div id="qlbreadnavi<?php echo $module->id; ?>content"
         class="<?php if (1 == $params->get('boolResponsiveActive', 0)) echo 'collapse'; ?>">
        <?php
        if ((bool)$params->get('boolShowBreadcrumb', 1)) {
            require JModuleHelper::getLayoutPath('mod_qlbreadnavi', 'default_breadcrumbs');
        }
        if ((bool)$params->get('boolShowSubmenu', 1) && isset($arrSubmenu) && 0 < count($arrSubmenu)) {
            require JModuleHelper::getLayoutPath('mod_qlbreadnavi', 'default_submenu');
        }
        ?>
    </div>
</div>