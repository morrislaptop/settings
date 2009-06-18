<?php
	$javascript->link('/settings/js/jquery.selectboxes', false);
	$javascript->codeBlock('
		$(function() {
			$(".controller").change(function() {
				var tr = $(this).parents("tr");
				var action = $(".action", tr);
				var data = {
					controller: $(this).val()
				};
				action.html("<option>loading...</option>");
				action.ajaxAddOption("' . $html->url(array('action' => 'actions', 'ext' => 'json')) . '", data);
			});
		});
	', array('inline' => false));
?>
<?php echo $html->css('tables', false, false, false); ?>
<div class="routes index">
<h2>Routes</h2>
<?php echo $form->create('Routes', array('controller' => 'routes', 'action' => 'save')); ?>
<table>
	<thead>
		<tr>
			<th class="headerLeft">URL</th>
			<th>Controller</th>
			<th>Action</th>
			<th class="headerRight">&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><?php echo $form->input('Route.0.url', array('label' => false)); ?></td>
			<td><?php echo $form->input('Route.0.controller',array('label' => false, 'empty' => '-select-', 'class' => 'controller')); ?></td>
			<td>
				<?php echo $form->input('Route.0.action', array('label' => false, 'class' => 'action')); ?>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4"><?php echo $form->submit(); ?></td>
		</tr>
	</tfoot>
	<tbody>
		<?php
			$i = 1;
			foreach ($routes as $route)
			{
				$form->data = $route;
				?>
				<tr>
					<td><?php echo $form->input('Route.'.$i.'.url', array('label' => false)); ?></td>
					<td><?php echo $form->input('Route.'.$i.'.controller', array('label' => false, 'class' => 'controller')); ?></td>
					<td>
						<?php 
							$options = array_combine($route['Route']['actions'], $route['Route']['actions']);
							echo $form->input('Route.'.$i.'.action', array('label' => false, 'options' => $options, 'class' => 'action')); 
						?>
					</td>
					<td><?php echo $form->input('Route.'.$i.'.id') . $html->link('Delete', array('action' => 'delete', $route['Route']['id']), null, 'Are your sure?'); ?></td>
				</tr>
				<?php
			    $i++;
			}
		?>
	</tbody>
</table>
</div>