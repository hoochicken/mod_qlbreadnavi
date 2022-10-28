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
?>

<div class="toggler">
    <button class="btn" data-toggle="collapse" data-target="#qlbreadnavi<?php echo $module->id; ?>content"><?php echo JText::_('MOD_QLBREADNAVI_MENU');?></button>
</div>