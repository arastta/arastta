<?php

class EventMenuBasic extends Event {

    public function preAdminMenuRender(&$menu) {
		if ($this->session->data['theme'] != 'basic') {
			return true;
		}

		$menu->removeMenuItem('recurring', 	'catalog');
		$menu->removeMenuItem('filter', 	'catalog');
		$menu->removeMenuItem('attribute', 	'catalog');
		$menu->removeMenuItem('download', 	'catalog');
	}
}