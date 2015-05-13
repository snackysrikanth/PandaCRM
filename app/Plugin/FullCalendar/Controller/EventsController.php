<?php
/*
 * Controller/EventsController.php
 * CakePHP Full Calendar Plugin
 *
 * Copyright (c) 2010 Silas Montgomery
 * http://silasmontgomery.com
 *
 * Licensed under MIT
 * http://www.opensource.org/licenses/mit-license.php
 */

class EventsController extends FullCalendarAppController {

	var $name = 'Events';

        var $paginate = array(
            'limit' => 15
        );

        function index() {
        $searched = false;
        if ($this->passedArgs) {
        	$args = $this->passedArgs;
        	if(isset($args['search_title'])){
        		$searched = true;
        	}
        }
        $this->set('searched',$searched);
        
		$this->Prg->commonProcess();
		$this->Event->recursive = 1;
		$this->paginate = array(
				'conditions' => $this->Event->parseCriteria($this->passedArgs),
				'limit' => 15
		);
		$this->set('events', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid event', true),'alert');
			$this->redirect(array('action' => 'index'));
		}
		$this->set('event', $this->Event->read(null, $id));
	}

	function add() {
		if (!empty($this->request->data)) {
			$this->request->data['Event']['user_id'] = $this->Auth->user('id');
			$this->Event->create();
			if ($this->Event->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The event has been saved', true),'alert',array('class'=>'alert-success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true),'alert',array('class'=>'alert-error'));
			}
		}
		$this->set('eventTypes', $this->Event->EventType->find('list'));
		$this->set('contacts',$this->Event->Contact->find('list'));
		$this->set('users',$this->Event->User->find('list'));
	}

	function edit($id = null) {
		if (!$id && empty($this->request->data)) {
			$this->Session->setFlash(__('Invalid event', true),'alert',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Event->id = $id;
		$user_id = $this->Event->read('Event.user_id');
		if ($user_id['Event']['user_id'] != $this->Auth->user('id')) {
			$this->Session->setFlash(__('Oops! Looks like you are not the owner of Event. Only the user corresponding to the event can edit it.'),'alert',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->request->data)) {
			if ($this->Event->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The event has been saved', true),'alert',array('class'=>'alert-success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event could not be saved. Please, try again.', true),'alert',array('class'=>'alert-error'));
			}
		}
		if (empty($this->request->data)) {
			$this->request->data = $this->Event->findById($id);
		}
		$this->set('eventTypes', $this->Event->EventType->find('list'));
		$this->set('contacts',$this->Event->Contact->find('list'));
		$this->set('users',$this->Event->User->find('list'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for event', true),'alert',array('class'=>'alert-error'));
			$this->redirect(array('action'=>'index'));
		}
		
		$this->Event->id = $id;
		$user_id = $this->Event->read('Event.user_id');
		
		if ($user_id['Event']['user_id'] == $this->Auth->user('id')) {
			if ($this->Event->delete($id)) {
				$this->Session->setFlash(__('Event deleted', true),'alert');
				$this->redirect(array('action'=>'index'));
			}
			$this->Session->setFlash(__('Event was not deleted', true),'alert',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index'));
		}
		else {
			$this->Session->setFlash(__('Oops! Looks like you are not the owner of Event. Only the user corresponding to the event can delete it.', true),'alert',array('class'=>'alert-error'));
			$this->redirect(array('action' => 'index'));
		}
		
	}

        // The feed action is called from "webroot/js/ready.js" to get the list of events (JSON)
	function feed($id=null) {
		$this->layout = "ajax";
		$vars = $this->params['url'];
		$conditions = array('conditions' => array('UNIX_TIMESTAMP(start) >=' => $vars['start'], 'UNIX_TIMESTAMP(start) <=' => $vars['end']));
		$events = $this->Event->find('all', $conditions);
		foreach($events as $event) {
			if($event['Event']['all_day'] == 1) {
				$allday = true;
				$end = $event['Event']['start'];
			} else {
				$allday = false;
				$end = $event['Event']['end'];
			}
			
			$event_contacts = "";
			$event_users = "";
			
			if(!empty($event['Contact'])){
				foreach($event['Contact'] as $contact){
					$event_contacts .= $contact['full_name'] . ', ';
				}
			}
			
			if(!empty($event['User'])){
				foreach($event['User'] as $user){
					$event_users .= $user['full_name'] . ', ';
				}
			}
			
			$data[] = array(
					'id' => $event['Event']['id'],
					'title'=>$event['Event']['title'],
					'start'=>$event['Event']['start'],
					'end' => $end,
					'allDay' => $allday,
					'url' => Router::url('/',true) . '/full_calendar/events/view/'.$event['Event']['id'],
					'details' => $event['Event']['details'],
					'className' => $event['EventType']['color'],
					'toolTip' => $event['Event']['details'] . '<br /><strong>Contacts: </strong>' . $event_contacts . '<br /><strong>Users: </strong>' . $event_users
			);
		}
		$this->set("json", json_encode($data));
	}

        // The update action is called from "webroot/js/ready.js" to update date/time when an event is dragged or resized
	function update() {
		$vars = $this->params['url'];
		$this->Event->id = $vars['id'];
		$this->Event->saveField('start', $vars['start']);
		$this->Event->saveField('end', $vars['end']);
		$this->Event->saveField('all_day', $vars['allday']);
	}

}
?>
