<?php
	$this->Html->script('/settings/js/jquery.selectboxes', false);
	$this->Html->scriptBlock('
		$(function() {
			$(".controller").change(function() {
				var tr = $(this).parents("tr");
				var action = $(".action", tr);
				var data = {
					controller: $(this).val()
				};
				action.html("<option>loading...</option>");
				//action.ajaxAddOption("' . $this->Html->url(array('action' => 'actions', 'ext' => 'json')) . '", data);
				$.getJSON("' . $this->Html->url(array('action' => 'actions', 'ext' => 'json')) . '", data, function(r) {
					$(action).html("");
					$(action).addOption(r.actions);
				})
			});
		});
	', array('inline' => false));
?>
<div class="routes index">
<h2>Routes</h2>
<?php echo $this->Form->create('Routes', array('controller' => 'routes', 'action' => 'save')); ?>
<div id="filler"></div>
<table>
	<thead>
		<tr>
			<th class="headerLeft">URL</th>
			<th>Controller</th>
			<th>Action</th>
			<th>Extra</th>
			<th class="headerRight">&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td><?php echo $this->Form->input('Route.0.url', array('label' => false)); ?></td>
			<td><?php echo $this->Form->input('Route.0.controller',array('label' => false, 'empty' => '-select-', 'class' => 'controller')); ?></td>
			<td>
				<?php echo $this->Form->input('Route.0.action', array('label' => false, 'class' => 'action')); ?>
			</td>
			<td><?php echo $this->Form->input('Route.0.extra', array('label' => false)); ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4"><?php echo $this->Form->submit(); ?></td>
		</tr>
	</tfoot>
	<tbody>
		<?php
			$i = 1;
			foreach ($routes as $route)
			{
				?>
				<tr>
					<td><?php echo $this->Form->input('Route.'.$i.'.url', array('label' => false)); ?></td>
					<td><?php echo $this->Form->input('Route.'.$i.'.controller', array('label' => false, 'class' => 'controller')); ?></td>
					<td>
						<?php 
							$options = array_combine($route['Route']['actions'], $route['Route']['actions']);
							echo $this->Form->input('Route.'.$i.'.action', array('label' => false, 'options' => $options, 'class' => 'action')); 
						?>
					</td>
					<td><?php echo $this->Form->input('Route.'.$i.'.extra', array('label' => false)); ?></td>
					<td><?php echo $this->Form->input('Route.'.$i.'.id') . $this->Html->link('Delete', array('action' => 'delete', $route['Route']['id']), null, 'Are your sure?'); ?></td>
				</tr>
				<?php
			    $i++;
			}
		?>
	</tbody>
</table>
</div>