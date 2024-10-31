<?php

/**
 * @package Scand_Easy_GA_Toolkit
 * @version 1.0.0
 *
 * Class Scand_Easy_GA_Toolkit_Event_Validator
 *
 * Validation class for event hit type
 */
class Scand_Easy_GA_Toolkit_Event_Validator extends Scand_Easy_GA_Toolkit_Validator
{
	protected $event;
	protected $selector;
	protected $category;
	protected $action;
	protected $label;
	protected $value;
	protected $javascript;
	protected $non_inter;
	protected $prevent_default;

	protected $label_type;
	protected $value_type;

	/**
	 * Scand_Easy_GA_Toolkit_Event_Validator constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->mandatory = array(
			'selector',
			'category',
			'action',
		);
	}

	/**
	 * @param $data
	 */
	public function processData( $data )
	{
		if ( array_key_exists( 'event', $data ) ) {
			$this->setEvent( $this->stripQuotes( $data['event'] ) );
		}
		if ( array_key_exists( 'selector', $data ) ) {
			$this->setSelector( trim( $data['selector'] ) );
		}
		if ( array_key_exists( 'category', $data ) ) {
			$this->setCategory( $this->stripQuotes( $data['category'] ) );
		}
		if ( array_key_exists( 'action', $data ) ) {
			$this->setAction( $this->stripQuotes( $data['action'] ) );
		}
		if ( array_key_exists( 'label', $data ) ) {
			$this->setLabel( $this->stripQuotes( $data['label'] ) );
		}
		if ( array_key_exists( 'value', $data ) ) {
			$this->setValue( $this->stripQuotes( $data['value'] ) );
		}

		if ( array_key_exists( 'non_inter', $data ) ) {
			$this->setNonInteraction( 'on' );
		} else {
			$this->setNonInteraction( 'off' );
		}

		if ( array_key_exists( 'prevent_default', $data ) ) {
			$this->setPreventDefault( $data['prevent_default'] );
		}
		if ( array_key_exists( 'javascript', $data ) ) {
			$this->setJavascript( $this->stripQuotes( $data['javascript'] ) );
		}
		if ( array_key_exists( 'label_type', $data ) ) {
			$this->setLabelType( $data['label_type'] );
		}
		if ( array_key_exists( 'value_type', $data ) ) {
			$this->setValueType( $data['value_type'] );
		}
	}

	public function setEvent( $event, $filter = false )
	{
		$this->event = ( $filter ) ? sanitize_text_field( $event ) : $event;
	}

	public function setSelector( $selector, $filter = false )
	{
		$this->selector = ( $filter ) ? sanitize_text_field( $selector ) : $selector;
	}

	public function setCategory( $category, $filter = false )
	{
		$this->category = ( $filter ) ? sanitize_text_field( $category ) : $category;
	}

	public function setAction( $action, $filter = false )
	{
		$this->action = ( $filter ) ? sanitize_text_field( $action ) : $action;
	}

	public function setLabel( $label, $filter = false )
	{
		//$label = sanitize_text_field($label);
		$this->label = mb_strlen( $label ) > 0 ? $label : null;
		if ( $filter ) {
			$this->label = sanitize_text_field( $label );
		}
	}

	public function setValue( $value )
	{
		$value = sanitize_text_field( $value );
		$this->value = mb_strlen( $value ) > 0 ? $value : null;
		if ( preg_match( '/^\d+$/', $value ) ) {
			$this->value = (int)$value;
		}
	}

	public function setNonInteraction( $val )
	{
		$this->non_inter = $val;
	}

	public function setPreventDefault( $value )
	{
		$this->prevent_default = $value;
	}

	public function setJavascript( $value )
	{
		$this->javascript = $value;
	}

	protected function setLabelType( $value )
	{
		$this->label_type = $value;
	}

	protected function setValueType( $value )
	{
		$this->value_type = $value;
	}

	/**
	 * Main validation method
	 *
	 * @return array
	 */
	public function ValidData()
	{
		$this->setEvent( $this->event, true );
		$this->setSelector( $this->selector, true );
		$this->setCategory( $this->category, true );
		$this->setAction( $this->action, true );
		$this->setLabel( $this->label, true );
		/*if ( ! is_null($this->value) && ! is_int($this->value) ) {
			$this->error[] = __('eventValue must be an integer', SCAND_SIMPLE_ANALYTICS_TEXTDOMAIN );
		}*/
		return $this->error;
	}

	/**
	 * Return processed data to caller method
	 *
	 * @return array
	 */
	public function replaceByValid()
	{
		return array(
			'hittype'         => Scand_Easy_GA_Toolkit_Admin::HIT_TYPE_EVENT,
			'event'           => $this->event,
			'selector'        => $this->selector,
			'category'        => $this->category,
			'action'          => $this->action,
			'label'           => ( is_null( $this->label ) ) ? '' : $this->label,
			'value'           => ( is_null( $this->value ) ) ? '' : $this->value,
			'non_inter'       => $this->non_inter,
			'prevent_default' => $this->prevent_default,
			'javascript'      => $this->javascript,
			'label_type'      => $this->label_type,
			'value_type'      => $this->value_type,
		);
	}
}