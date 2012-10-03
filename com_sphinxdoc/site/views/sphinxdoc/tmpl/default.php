<?php
/**
 * @package     sphinxdoc
 * @subpackage  com_sphinxdoc
 * @copyright   Copyright (C) 2012 bibib21000.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params     = $this->item->params;

?>

<div class="article">
    <?php if ($params->get('show_title')) : ?>
        <h2 class="contentheading">
            <?php echo $this->escape($this->item->documentation); ?>
        </h2>
    <?php endif; ?>

<?php $useToolbar = ( ($params->get('show_print_icon')) or
		 ($params->get('show_email_icon')) or
		 ($params->get('show_create_date')) or
         ($params->get('show_author')) or
         ($params->get('show_modify_date'))) ?>
<?php if ($useToolbar) : ?>
    <div class="jsn-article-toolbar">
<?php endif; ?>

<?php if ($params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
    <ul class="jsn-article-tools">
        <?php if ($params->get('show_print_icon')) : ?>
            <li class="print-icon">
                <a href="#" class="jsn-article-print-button" title="Imprimer" onclick="window.open(this.href,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;" rel="nofollow">&nbsp;</a>                       </li>
            </li>
        <?php endif; ?>

        <?php if ($params->get('show_email_icon')) : ?>
            <li class="email-icon">
                <a href="#" class="jsn-article-email-button" title="E-mail" onclick="window.open(this.href,'win2','width=400,height=350,menubar=yes,resizable=yes'); return false;">&nbsp;</a>                      </li>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>


<?php $useDefList = ( ($params->get('show_create_date'))
    or ($params->get('show_author'))
    or ($params->get('show_modify_date'))); ?>

<?php if ($useDefList) : ?>
        <div class="jsn-article-info">
<?php endif; ?>

<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
	<p class="small author">
	<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
	<?php if (!empty($this->item->contactid) && $params->get('link_author') == true): ?>
	<?php
		$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
		$menu = JFactory::getApplication()->getMenu();
		$item = $menu->getItems('link', $needle, true);
		$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
	?>
		<?php echo JText::sprintf('COM_SPHINXDOC_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)); ?>
	<?php else: ?>
		<?php echo JText::sprintf('COM_SPHINXDOC_WRITTEN_BY', $author); ?>
	<?php endif; ?>
	</p>
<?php endif; ?>
<?php if ($params->get('show_create_date')) : ?>
            <p class="createdate">
            <?php echo JText::sprintf('COM_SPHINXDOC_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC2'))); ?>
            </p>
<?php endif; ?>
<?php if ($params->get('show_modify_date')) : ?>
            <p class="createdate">
            <?php echo JText::sprintf('COM_SPHINXDOC_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC2'))); ?>
            </p>
<?php endif; ?>
<?php if ($useDefList) : ?>
        </div>
<?php endif; ?>
<?php if ($useToolbar) : ?>
   </div>
<?php endif; ?>

<?php if ($params->get('show_index')) : ?>
    <div id="article-index">
        <ul>
            <?php
            //Iterate over the extracted links and display their URLs
            foreach ($this->item->pages as $cle => $element){
            ?>
                <li><a href="<?php echo $element['fulllink'];?>"
                    class="toclink active">
                    <?php echo $element['title'];?></a></li>
            <?php
            }
            ?>
        </ul>
    </div>
<?php endif; ?>

    <div class="jsn-article-sphinxdoc">
        <?php echo SphinxDocHelper::updateImg($this->item->text,$this->item->directory);?>
    </div>
</div>
