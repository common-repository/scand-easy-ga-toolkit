<?php

/**
 * @package Scand_Easy_GA_Toolkit
 * @version 1.0.0
 *
 * Scand_Easy_GA_Toolkit_Javascript_Helper class
 *
 * Helper class that generates javascript code for Easy Google Analytics Toolkit WordPress plugin
 */
class Scand_Easy_GA_Toolkit_Javascript_Helper
{
	protected $options;
	protected $testMode;
	protected $strategy;

	/**
	 * Scand_Easy_GA_Toolkit_Javascript_Helper constructor.
	 * @param $options
	 * @param bool $testMode
	 */
	public function __construct( $options, $testMode, $obj )
	{
		$this->options = $options;
		$this->testMode = $testMode;
		$this->strategy = $obj;
	}

	/**
	 * Main javascript drawing method
	 *
	 * @return string
	 */
	public function buildScript()
	{
		$str = $this->strategy->addScript( $this->options['id'], $this->testMode );

		$str .= $this->buildCreateCommand( $this->options, $this->testMode );

		if ( isset( $this->options['ssl'] ) && $this->options['ssl'] > 0 ) {
			$str .= $this->buildSetCommand( $this->testMode );
		}
		$str .= $this->buildSendCommand( $this->testMode );
		$str .= "function SC_GA_preLoad(){if(typeof jQuery !== 'function'){window.setTimeout(function(){SC_GA_preLoad()},100);return;}else{SC_GA_events(jQuery);}}" . PHP_EOL;
		$str .= "function SC_GA_events($){" . PHP_EOL;
		$str .= "$(document).ready(function($) {" . PHP_EOL;

		if ( isset( $this->options['error_404'] ) ) {
			$eventParams = array(
				'action'    => "'404'",
				'category'  => "error",
				'label'     => "'page: ' + document.location.pathname + document.location.search + ' ref: ' + document.referrer",
				'non_inter' => true,
			);
			$str .= $this->handleEvent( null, null, 'error_404', $eventParams, $this->testMode );
		}

		if ( isset( $this->options['downloads']['enabled'] ) ) {
			$eventParams = array(
				'action'   => "ext_at",
				'category' => "Download",
				'label'    => "href_at",
			);
			$str .= $this->handleEvent( $this->options['downloads']['file_ext'], null, 'download', $eventParams, $this->testMode );
		}

		if ( isset( $this->options['emails'] ) && $this->options['emails'] > 0 ) {
			$eventParams = array(
				'action'   => "href_at.substr(7)",
				'category' => "Email",
				'label'    => null,
			);
			$str .= $this->handleEvent( null, "'a[href^=\\'mailto:\\']'", 'email', $eventParams, $this->testMode );
		}

		if ( isset( $this->options['skype'] ) && $this->options['skype'] > 0 ) {
			$eventParams = array(
				'action'   => "href_at.substr(6)",
				'category' => "Skype",
				'label'    => null,
			);
			$str .= $this->handleEvent( null, "'a[href^=\\'skype:\\']'", 'skype', $eventParams, $this->testMode );
		}

		if ( isset( $this->options['phones'] ) && $this->options['phones'] > 0 ) {
			$eventParams = array(
				'action'   => "href_at.substr(4)",
				'category' => "Phone number",
				'label'    => null,
			);
			$str .= $this->handleEvent( null, "'a[href^=\\'tel:\\']'", 'phones', $eventParams, $this->testMode );
		}

		if ( isset( $this->options['outbound'] ) && $this->options['outbound'] > 0 ) {
			$eventParams = array(
				'action'   => "this.hostname",
				'category' => "Outbound",
			);
			if ( $this->options['tracking'] ) {
				$eventParams['label'] = "this.pathname";
			} else {
				$eventParams['label'] = "this.hostname + this.pathname";
			}
			$str .= $this->handleEvent( null, "'a[href^=\\'http:\\']'", 'outbound', $eventParams, $this->testMode );
		}

		if ( isset( $this->options['custom_event'] ) && count( $this->options['custom_event'] ) > 0 ) {
			$str .= $this->handleCustomEventExt( $this->options['custom_event'], $this->testMode );
		}

		$str .= "});}" . PHP_EOL;
		$str .= "SC_GA_preLoad();" . PHP_EOL . "</script>" . PHP_EOL;
		return $str;
	}

	/**
	 * Custom user events rendering method
	 *
	 * @param $arEvents
	 * @param bool $isTestMode
	 * @return string
	 */
	protected function handleCustomEventExt( $arEvents, $isTestMode )
	{
		return $this->strategy->buildFunctionString( $arEvents, $isTestMode );
	}

	/**
	 * Generate js code snippet for listening on download files events
	 *
	 * @param $strFileExt
	 * @param $link
	 * @param $eventType
	 * @param array $eventParams
	 * @param bool $isTestMode
	 * @return string
	 */
	protected function handleEvent( $strFileExt, $link, $eventType, $eventParams, $isTestMode )
	{
		return $this->strategy->addEvent( $strFileExt, $link, $eventType, $eventParams, $isTestMode );
	}

	/**
	 * @param $o
	 * @param bool $isTestMode
	 * @return string
	 */
	protected function buildCreateCommand( $o, $isTestMode )
	{
		return $this->strategy->addCreateCommand( $o, $isTestMode );
	}

	/**
	 * @param bool $isTestMode
	 * @return string
	 */
	protected function buildSendCommand( $isTestMode )
	{
		return $this->strategy->addSendCommand( $isTestMode );
	}

	/**
	 * @param bool $isTestMode
	 * @return string
	 */
	protected function buildSetCommand( $isTestMode )
	{
		return $this->strategy->addSetCommand( $isTestMode );
	}

}