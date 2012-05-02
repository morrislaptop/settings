<div class="translations index">
<h2>Translations</h2>
<?php echo $this->Form->create('Translation', array('controller' => 'translations', 'action' => 'save')); ?>
<div id="filler"></div>
<table>
	<thead>
		<tr>
			<th class="headerLeft">Language</th>
			<th>Domain</th>
			<th>Name</th>
			<th>Value</th>
			<th class="headerRight">&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><?php echo $this->Form->input('Translation.0.language',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('Translation.0.domain',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('Translation.0.name',array('label'=>false)); ?></td>
			<td><?php echo $this->Form->input('Translation.0.value',array('label'=>false)); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4"><?php echo $this->Form->submit(); ?></td>
		</tr>
	</tfoot>
	<tbody>
		<?php
			$i = 1;
			foreach ($translations as $translation)
			{
				?>
				<tr>
					<td><?php echo $this->Form->input('Translation.'.$i.'.language',array('label'=>false)); ?></td>
					<td><?php echo $this->Form->input('Translation.'.$i.'.domain',array('label'=>false)); ?></td>
					<td><?php echo $this->Form->input('Translation.'.$i.'.name', array('label'=>false)); ?></td>
					<td><?php echo $this->Form->input('Translation.'.$i.'.value', array('label'=>false)); ?></td>
					<td><?php echo $this->Form->input('Translation.'.$i.'.id') . $this->Html->link('Delete', 'delete/'.$translation['Translation']['id'], null, 'Are your sure?'); ?></td>
				</tr>
				<?php
			    $i++;
			}
		?>
	</tbody>
</table>
</div>