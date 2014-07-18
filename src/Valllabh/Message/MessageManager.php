<?php namespace Valllabh\Message;

use \Session;
use \Config;

use \BadMethodCallException;
use \Illuminate\Support\MessageBag;


class MessageManager {

	private $messages;
	private $types;
	private $default_group;

	public function __construct(){
		$this->messages = array();
		$this->types = Config::get('message::types');
		$this->default_group = Config::get('message::default_group');

		$this->var_temp = 'tmp';
		$this->var_flash = 'flash';

		$this->session_key = 'valllabh.messages';

		$this->restoreFlashMessages();

	}

	public function __call($name, $arguments) {
		$type = $this->prepareMessageType( $name );

		if( $this->isValidMessageType( $type ) ) {
			return $this->message( [
				'type' => $type,
				'messages' => isset( $arguments[0] ) ? $arguments[0] : NULL,
				'flash' => isset( $arguments[1] ) ? $arguments[1] : false,
				'group' => isset( $arguments[2] ) ? $arguments[2] : $this->default_group,
			] );
		}

		throw new BadMethodCallException("Call to undefined method ".__CLASS__."::$name()");
	}

	private function isValidMessageType( $type ){
		if( isset( $this->types[ $type ] ) ){
			return true;
		}
		$type = $this->prepareMessageType( $type );
		return isset( $this->types[ $type ] );
	}

	private function prepareMessageType( $type ){
		$return = false;
		foreach( $this->types as $t => $alias ){
			if( in_array( $type, $alias ) ){
				$return = $t;
				break;
			}
		}
		return $return;
	}

	private function restoreFlashMessages(){
		$session_data = Session::get( $this->session_key );
		$session_data = $session_data ? $session_data : array();
		$this->messages[ $this->var_temp ] = $session_data;
	}

	private function storeFlashMessages(){
		$flash_data = isset( $this->messages[ $this->var_flash ] ) ? $this->messages[ $this->var_flash ] : NULL;
		Session::flash( $this->session_key, $flash_data );
	}

	private function message( $a ){
		extract( $a );

		if( ! $messages ){
			return false;
		}

		if ( $messages instanceof MessageBag ) {
			$messages = $messages->all();
		} else {
			$messages = (array) $messages;
		}

		$store = $flash ? $this->var_flash : $this->var_temp;

		if( ! isset( $this->messages[ $store ][ $group ][ $type ] ) ){
			$this->messages[ $store ][ $group ][ $type ] = new MessageBag();
		}

		$this->messages[ $store ][ $group ][ $type ]->merge( $messages );

		$this->storeFlashMessages();
	}

	public function get( $type, $group = NULL, $store = NULL ){

		$type = $this->prepareMessageType( $type );
		$group = $group ? $group : $this->default_group;
		$store = $store ? $store : $this->var_temp;

		if( $this->isValidMessageType( $type ) && isset( $this->messages[ $store ] ) && isset( $this->messages[ $store ][ $group ] ) && isset( $this->messages[ $store ][ $group ][ $type ] ) ){

			return $this->messages[ $store ][ $group ][ $type ];

		}
		return new MessageBag();
	}

}