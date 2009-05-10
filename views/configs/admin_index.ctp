<h2>Config Values</h2>
<?php echo $form->create('Config', array('controller' => 'config', 'action' => 'save')); ?>
<table>
	<thead>
		<tr>
			<th>Name</th>
			<th>Value</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><?php echo $form->input('Config.0.name',array('label'=>false)); ?></td>
			<td><?php echo $form->input('Config.0.value',array('label'=>false)); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3"><?php echo $form->submit(); ?></td>
		</tr>
	</tfoot>
	<tbody>
		<?php
			$i = 1;
			foreach ($configs as $config)
			{
				$form->data = $config;
				?>
				<tr>
					<td><?php echo $form->input('Config.'.$i.'.id') . $form->input('Config.'.$i.'.name',array('label'=>false)); ?></td>
					<td><?php echo $form->input('Config.'.$i.'.value',array('label'=>false)); ?></td>
					<td><?php echo $html->link('Delete','delete/'.$config['Config']['id'],null,'Are your sure?'); ?></td>
				</tr>
				<?php
			    $i++;
			}
		?>
	</tbody>
</table>