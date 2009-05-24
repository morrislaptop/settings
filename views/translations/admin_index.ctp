<h2>Translations</h2>
<?php echo $form->create('Translation', array('controller' => 'translations', 'action' => 'save')); ?>
<table>
	<thead>
		<tr>
			<th>Language</th>
			<th>Name</th>
			<th>Value</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><?php echo $form->input('Translation.0.language',array('label'=>false)); ?></td>
			<td><?php echo $form->input('Translation.0.name',array('label'=>false)); ?></td>
			<td><?php echo $form->input('Translation.0.value',array('label'=>false)); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4"><?php echo $form->submit(); ?></td>
		</tr>
	</tfoot>
	<tbody>
		<?php
			$i = 1;
			foreach ($translations as $translation)
			{
				$form->data = $translation;
				?>
				<tr>
					<td><?php echo $form->input('Translation.'.$i.'.language',array('label'=>false)); ?></td>
					<td><?php echo $form->input('Translation.'.$i.'.id') . $form->input('Translation.'.$i.'.name', array('label'=>false)); ?></td>
					<td><?php echo $form->input('Translation.'.$i.'.value', array('label'=>false)); ?></td>
					<td><?php echo $html->link('Delete', 'delete/'.$translation['Translation']['id'], null, 'Are your sure?'); ?></td>
				</tr>
				<?php
			    $i++;
			}
		?>
	</tbody>
</table>