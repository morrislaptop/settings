<?php echo $this->Html->css('tables'); ?>
<div class="configs index">
<h2>Config Values</h2>
<?php echo $this->Form->create('Config', array('controller' => 'config', 'action' => 'save')); ?>
<div id="filler"></div>
<table>
	<thead>
		<tr>
			<th class="headerLeft">Name</th>
			<th>Value</th>
			<th class="headerRight">&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><?php echo $this->Form->input('Config.0.name',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('Config.0.value',array('label'=>false)); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><?php echo $this->Form->submit(); ?></td>
		</tr>
	</tfoot>
	<tbody>
		<?php
			$i = 1;
			foreach ($configs as $config)
			{
				?>
				<tr>
					<td><?php echo $this->Form->input('Config.'.$i.'.id') . $this->Form->input('Config.'.$i.'.name',array('label'=>false)); ?></td>
					<td><?php echo $this->Form->input('Config.'.$i.'.value',array('label'=>false)); ?></td>
					<td class="actions"><?php echo $this->Html->link('Delete', 'delete/' . $config['Config']['id'], array('class' => 'delete'), 'Are your sure?'); ?></td>
				</tr>
				<?php
			    $i++;
			}
		?>
	</tbody>
</table>
</div>