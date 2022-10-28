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
$boolShowLast = $params->get('boolShowLast', 1);
?>
<ul itemscope itemtype="https://schema.org/BreadcrumbList"
    class="breadcrumbies qlbreadnavi<?php echo $strModuleclass_sfx; ?> <?php echo $params->get('strDirection', 'horizontal'); ?>">
    <?php if (1 == $params->get('boolShowHere', 1)) : ?>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="showHere active">
            <?php echo JText::_('MOD_BREADCRUMBS_HERE'); ?>&#160;
        </li>
    <?php elseif (2 == $params->get('boolShowHere', 2)) : ?>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="showHere active">
            <span class="icon-location"></span>
        </li>
    <?php endif; ?>

    <?php
    // Get rid of duplicated entries on trail including home page when using multilanguage

    // Make a link if not the last item in the breadcrumbs
    //echo '<pre>#';print_r($arrList);die;
    // Generate the trail
    foreach ($arrList as $numKey => $objItem) :
        if ($numKey != $numKeyLastItem) :
            // Render all but last item - along with separator
            ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
                class="item<?php if (isset($objItem->id)) echo $objItem->id; ?> content">
                <?php
                //echo '<pre>';print_r($objItem);die;
                if (!empty($objItem->link)) : ?>
                    <a itemprop="item" href="<?php echo $objItem->flink; ?>" class="pathway">
                        <span itemprop="name"><?php echo trim(html_entity_decode($objItem->title)); ?></span>
                    </a>
                <?php else : ?>
                    <span itemprop="name"><?php trim($objItem->name); ?></span>
                <?php endif; ?>
                <?php if (($numKey != $numKeyPenultimateItem) || $boolShowLast) : ?>
                    <span class="divider">
                        <?php echo $strSeparator; ?>
                    </span>
                <?php endif; ?>
                <meta itemprop="position" content="<?php echo $numKey + 1; ?>">
            </li>
        <?php elseif ($boolShowLast) :
            // Render last item if reqd. ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" class="active">
                <span class="dull" itemprop="name"><?php echo $objItem->title; ?></span>
                <meta itemprop="position" content="<?php echo $numKey + 1; ?>">
            </li>
        <?php endif;
    endforeach; ?>
</ul>
