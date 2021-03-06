<?php

namespace libs;

use libs\ImageManager;

use model\database\IRenderableWeekEntity;
use model\database\views\ReservationExtended,
	model\database\tables\Event;

class URLgen {

	const ADDR_SEP = '/';
	const LOGO_FILENAME = 'clh.png';

	var $urlPrefix;
	
	protected $controller, $action;
	protected $urlOk;

	public function __construct($prefix) {
		$this->urlPrefix = $prefix;
		$url = $this->handleUrl();
		$this->controller = $url['controller'];
		$this->action = $url['action'];
		$this->urlOk = !$url['redirect'];
	}

	protected function handleUrl(){
		$controller = filter_input(INPUT_GET, 'controller');
		$action = filter_input(INPUT_GET, 'action');
		$redirect = (empty($action) || empty($controller));
		return ['controller' => $controller, 'action' => $action, 'redirect' => $redirect];
	}

	public function ajaxUrl($action = null) {
		return $this->url(['controller' => 'ajax',
					'action' => $action ? : \controllers\AjaxController::WILDCARD]);
	}

	public function url($params = null) {
		$return = $this->urlPrefix;
		if (empty($params)) {
			return $return;
		}
		$first = true;
		foreach ($params as $parKey => $parVal) {
			$return.=($first ? "?" : "&") . "$parKey=$parVal";
			$first = false;
		}
		return $return;
	}

	public function loginUrl() {
		return $this->urlPrefix . "webauth/";
	}

	public function fbSub() {
		$pars = ['controller' => 'ohlas', 'action' => 'pridat'];
		return $this->url($pars);
	}

	public function urlReserve($game_type_id) {
		$params = ['controller' => 'rezervace', 'action' => 'vypis', 'game_id' => $game_type_id];
		return $this->url($params);
	}

	public function css($file) {
		return $this->urlPrefix . "css/" . $file;
	}

	public function js($file) {
		return $this->urlPrefix . "js/" . $file;
	}

	public function img($file) {
		return $this->urlPrefix . ImageManager::IMG_WWW_FOLDER . $file;
	}

	public function gImg($game_type_id) {
		$filename = sprintf("game_%03d", $game_type_id);
		$path = ImageManager::get($filename);
		return $this->img($path);
	}

	public function appLogo() {
		return $this->img(self::LOGO_FILENAME);
	}

	public function gDet($game_type_id, $highlight = null) {
		$args = [ 'controller' => 'vypis',
			'action' => 'detailHry',
			'id' => $game_type_id];
		if ($highlight) {
			$args['highlight'] = $highlight;
		}
		return $this->url($args);
	}
	
	/**
	 * 
	 * @param IRenderableWeekEntity $entity
	 * @return type
	 */
	public function weDetail($entity) {
		$type = $entity->getType();
		$entity_id = $entity->getID();
		switch ($type) {
			default: 
				return null;
			case ReservationExtended::TYPE:
				$args = [ 'controller' => 'rezervace',
					'action' => 'detail',
					'id' => $entity_id];
				break;
			case Event::TYPE:
				$args = [ 'controller' => 'udalost',
					'action' => 'zobrazit',
					'id' => $entity_id];
				break;
		}
		return $this->url($args);
	}

	public function uProfile($orion_login) {
		$args = [ 'controller' => 'uzivatel',
			'action' => 'zobrazitProfil',
			'login' => $orion_login];
		return $this->url($args);
	}

	public function getController() {
		return $this->controller;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function isUrlOk(){
		return $this->urlOk;
	}

}
