<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): ?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
			<a href="<?php echo JRoute::_('index.php?option=com_sphinxdoc&task=sphinxdoc.edit&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->documentation); ?></a>
			<p class="smallsub">
				<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></p>
		</td>

		<td>
			<?php echo $item->category; ?>
		</td>
		<td>
			<?php echo $item->directory; ?>
		</td>
	</tr>
<?php endforeach; ?>
