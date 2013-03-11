<?php
/**
 * Bake Template for Controller action generation.
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
 * @package       Cake.Console.Templates.default.actions
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

/**
 * <?php echo $admin ?>index method
 *
 * @return void
 */
	public function <?php echo $admin ?>index() {
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$conditions = $this->_parseSearch();
		$this->set('<?php echo $pluralName ?>', $this->paginate($conditions));
		$this->_setSelects();
	}

/**
 * <?php echo $admin ?>view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function <?php echo $admin ?>view($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
		$this->set('<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->find('first', $options));
	}

<?php $compact = array(); ?>
/**
 * <?php echo $admin ?>add method
 *
 * @return void
 */
	public function <?php echo $admin ?>add() {
		if ($this->request->is('post')) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__('<?php echo ucfirst(strtolower($currentModelName)); ?> saved.'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'));
<?php endif; ?>
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
		$this->render('<?php echo $admin ?>edit');
	}

<?php $compact = array(); ?>
/**
 * <?php echo $admin ?>edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function <?php echo $admin; ?>edit($id = null) {
		if (!$this-><?php echo $currentModelName; ?>->exists($id)) {
			throw new NotFoundException(__('Invalid <?php echo strtolower($singularHumanName); ?>'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this-><?php echo $currentModelName; ?>->save($this->request->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> has been saved'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(__('The <?php echo strtolower($singularHumanName); ?> has been saved.'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(__('The <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'));
<?php endif; ?>
			}
		} else {
			$options = array('conditions' => array('<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id));
			$this->request->data = $this-><?php echo $currentModelName; ?>->find('first', $options);
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

/**
 * <?php echo $admin ?>delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @param boolean $confirm
 * @return void
 */
	public function <?php echo $admin; ?>delete($id = null, $confirm = false) {
		if (!empty($id)) {
			$ids = array($id);
		} else {
			$ids = array();
		}
		
		if(!empty($this->request->data['Selected'])) {
			foreach ($this->request->data['Selected'] as $selected_id => $selected) {
				if ($selected) {
					$ids[] = $selected_id;
				}
			}
		}

		if (!empty($ids) && $confirm) {
			$error = false;
			foreach ($ids as $id) {
				$this-><?php echo $currentModelName; ?>->id = $id;
				if (!$this-><?php echo $currentModelName; ?>->delete()) {
					$error = true;	
				} 
				if ($error) {
<?php if ($wannaUseSession): ?>
					$this->Session->setFlash(__('Fallo al realizar la operación'));
<?php else: ?>
					$this->flash(__('Fallo al realizar la operación'), array('action' => 'index'));
<?php endif; ?>
				} else {
<?php if ($wannaUseSession): ?>
					$this->Session->setFlash(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> eliminados'));
<?php else: ?>
					$url = array('action' => 'index');
					$this->flash(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> eliminados'), $url);
<?php endif; ?>
				}
			}
			$this->redirect(array('action' => 'index'));
		} elseif (empty($ids)) {
			throw new NotFoundException(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> no encontrados'));	
			$this->redirect(array('action' => 'index'));
		}
		$<?php echo $pluralName; ?> = $this-><?php echo $currentModelName; ?>->find('all', array(
			'conditions' => array('<?php echo $currentModelName; ?>.<?php echo $primaryKey; ?>' => $ids)
		));
		$this->set(compact('<?php echo $pluralName; ?>'));

	}

<?php if (ClassRegistry::init($currentModelName)->hasField('active')): ?>
/**
 * <?php echo $admin ?>set_active method
 *
 * @throws NotFoundExceptiona
 * @throws MethodNotAllowedException
 * @param int $active
 * @param string $id
 * @return void
 */

	public function <?php echo $admin; ?>set_active($active = 0, $id = null) {		
		if (!empty($id)) {
			$ids = array($id);
		} else {
			$ids = array();
		}
		
		if(!empty($this->request->data['Selected'])) {
			foreach ($this->request->data['Selected'] as $selected_id => $selected) {
				if ($selected) {
					$ids[] = $selected_id;
				}
			}
		}

		if (!empty($ids)) {
			$error = false;
			foreach ($ids as $id) {
				$this-><?php echo $currentModelName; ?>->id = $id;
				if (!$this-><?php echo $currentModelName; ?>->save(compact('active'))) {
					$error = true;	
				} 
				if ($error) {
<?php if ($wannaUseSession): ?>
					$this->Session->setFlash(__('Fallo al realizar la operación'));
<?php else: ?>
					$this->flash(__('Fallo al realizar la operación'), array('action' => 'index'));
<?php endif; ?>
				} else {
<?php if ($wannaUseSession): ?>
					($active)?$this->Session->setFlash(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> activados')):$this->Session->setFlash(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> desactivados'));
<?php else: ?>
					$url = array('action' => 'index');
					($active)?$this->flash(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> activados'), $url):$this->flash(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> desactivados'), $url);
<?php endif; ?>
				}
			}
		} else {
			throw new NotFoundException(__('<?php echo ucfirst(strtolower($pluralHumanName)); ?> no encontrados'));	
		}
		$this->redirect(array('action' => 'index'));
	}

<?php endif; //hasField ative ?>
