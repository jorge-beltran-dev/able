<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php
echo "<?php\n";
echo "App::import('Vendor', 'Funciones');\n";
echo "echo \$this->Form->create('{$modelClass}',array('url' => '','id'=>'index_form'));\n";
echo "?>\n"; 
?>
<div class="<?php echo $pluralVar; ?> index">
	<h2><?php echo "<?php echo __('{$pluralHumanName}'); ?>"; ?></h2>

	<?php
		if (!empty($associations['belongsTo'])) {
			echo "\t<?php\n";
			echo "\t?>\n";
		}
	?>

	<fieldset class="search">
		<legend><?php echo "<?php echo __('Búsqueda de {$pluralHumanName}'); ?>"; ?></legend>
	<?php
		echo "\t<?php\n";
		echo "\t\techo \$this->Form->create('{$modelClass}');\n";
		echo "\t\techo '<div id=\"open_search\">';\n";
		echo "\t\techo \$this->Form->input('open_search', array('label' => __('Búsqueda libre')));\n";
		echo "\t\techo '</div>';\n";
		echo "\t\techo '<div id=\"advanced_search\" class=\"hidden\">';\n";
		foreach ($fields as $field) {
			if (in_array($field, array('id', 'created', 'modified', 'active'))) {
				continue;
			}
			echo "\t\techo \$this->Form->input('{$field}');\n";
		}
		echo "\t\techo '</div>';\n";
		echo "\t\t\$link = \$this->Html->link(__('Filtro avanzado'), array(), array('class' => 'advanced_filter'));\n";
		echo "\t\techo \$this->Form->submit(__('Buscar'), array('after' => \$link));\n";
   	    echo "\t\techo \$this->Form->end();\n";
		echo "\t?>\n";
	?>
	</fieldset>

	<div class="index_actions">
	<ul>
		<li>
			<?php echo "<?php echo \$this->Html->link(
							\$this->Html->image('iconos/agregar.png', array('alt' => '')) . ' ' . __('Nuevo'),
							array('action' => 'add'),
							array('escape' => false)
						); ?>\n"; ?>
		</li>
		<li>
			<?php echo "<?php echo \$this->Html->link(
							\$this->Html->image('iconos/cross.png', array('alt' => '')) . ' ' . __('Eliminar'),
							array('action' => 'delete'),
							array('escape' => false, 'class' => 'index_form_link')
						); ?>\n"; ?>
		</li>

		<?php if (ClassRegistry::init($modelClass)->hasField('active')): 
		echo "<?php if(!empty(\${$pluralVar})):?>\n"; ?>
		<li>

		<?php
			echo "<?php echo \$this->Html->link(
					\$this->Html->image('iconos/unlocked.png',array('alt'=>'')).' '.__('Activar'),
					array('action'=>'set_active', 1),
					array('escape'=>false, 'class' => 'index_form_link')
				);?>\n"; 
		?>

		</li>
		<li>
		<?php
			echo "<?php echo \$this->Html->link(
					\$this->Html->image('iconos/locked.png',
					array('alt'=>'')).' '.__('Desactivar'),
					array('action'=>'set_active', 0),
					array('escape'=>false, 'class' => 'index_form_link')
				);?>\n";
		?>
		</li>
	<?php 
	echo  "<?php endif; ?>";
	endif;
	?>
	</ul>
	</div>

	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo "<?php echo \$this->Form->checkbox('main', array('id'=>'main')); ?>"; ?></th>
	<?php foreach ($fields as $field): 
		if (in_array($field, array($primaryKey, 'modified'))) {
			continue;
		}
	?>
		<th><?php echo "<?php echo \$this->Paginator->sort('{$field}'); ?>"; ?></th>
	<?php endforeach; ?>
		<th class="actions"><?php echo "<?php echo __('Acciones'); ?>"; ?></th>
	</tr>
	<?php
	echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
	echo "\t<tr>\n";
	echo "\t\t<td><?php echo \$this->Form->checkbox('Selected.'.\${$singularVar}['{$modelClass}']['{$primaryKey}'] , array('class'=>'selected')); ?></td>\n";
		foreach ($fields as $field) {
			if (in_array($field, array($primaryKey, 'modified'))) {
				continue;
			}
			$isKey = false;
			if (!empty($associations['belongsTo'])) {
				foreach ($associations['belongsTo'] as $alias => $details) {
					if ($field === $details['foreignKey']) {
						$isKey = true;
						echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
						break;
					}
				}
			}
			if ($isKey !== true) {
				if ($field == 'active') {
					echo "\t\t<td class=\"icon\">
						<?php if (\${$singularVar}['{$modelClass}']['{$field}']) {
							\$image = \$this->Html->image('iconos/unlocked.png', array('alt' => __('Desactivar')));
							echo \$this->Html->link(
								\$image, 
								array('action' => 'set_active', 0, \${$singularVar}['{$modelClass}']['{$primaryKey}']),
								array('escape' => false)
							);
						} else {
							\$image = \$this->Html->image('iconos/locked.png', array('alt' => __('Activar')));
							echo \$this->Html->link(
								\$image, 
								array('action' => 'set_active', 1, \${$singularVar}['{$modelClass}']['{$primaryKey}']),
								array('escape' => false)
							);
						}\n
					?></td>";
				} elseif (in_array(strtolower(ClassRegistry::init($modelClass)->getColumnType($field)), array('date', 'datetime'))) {
					echo "\t\t<td><?php echo mostrar_fecha(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n"; 
				} else {
					echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
				}
			}
		}
		echo "\t\t<td class=\"actions\">\n";
		echo "\t\t\t<?php echo \$this->Html->link(__('Ver'), array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t\t<?php echo \$this->Html->link(__('Editar'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
		echo "\t\t\t<?php echo \$this->Form->postLink(__('Eliminar'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, __('¿Confirma que desea eliminar %s?', \${$singularVar}['{$modelClass}']['{$displayField}'])); ?>\n";
		echo "\t\t</td>\n";
	echo "\t</tr>\n";

	echo "<?php endforeach; ?>\n";
	?>
	</table>
	<p>
	<?php echo "<?php
	echo \$this->Paginator->counter(array(
	'format' => __('Página {:page} de {:pages}, viendo {:current} registros de {:count} totales, empezando en {:start}, finalizando en {:end}')
	));
	?>"; ?>
	</p>
	<div class="paging">
	<?php
		echo "<?php\n";
		echo "\t\techo \$this->Paginator->prev('< ' . __('anterior'), array(), null, array('class' => 'prev disabled'));\n";
		echo "\t\techo \$this->Paginator->numbers(array('separator' => ''));\n";
		echo "\t\techo \$this->Paginator->next(__('siguiente') . ' >', array(), null, array('class' => 'next disabled'));\n";
		echo "\t?>\n";
	?>
	</div>
</div>
<?php echo "<?php echo \$this->Form->end(); ?>"; ?>
