<?php

class Scand_Easy_GA_Toolkit_Analytics extends Scand_Easy_GA_Toolkit_Base
{

	public function addScript( $id, $isTestMode = false )
	{
		$str = "<script type=\"text/javascript\">" . PHP_EOL;
		$str .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\r\n";
		$str .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();\r\na=s.createElement(o),";
		$str .= "m=s.getElementsByTagName(o)[0];\r\na.async=1;a.src=g;m.parentNode.insertBefore(a,m)";
		$str .= "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');" . PHP_EOL;
		return $str;
	}

	public function buildFunctionString( $arEvents, $isTestMode = false )
	{
		$output = '';
		foreach ( $arEvents as $event ) {
			$s = $this->buildHeadOfCustomEvent( $event );
			$ga = '';
			$ga .= self::DOUBLE_TAB . "%sga('send', '{$event['hittype']}'";
			$ga .= ", '{$event['category']}'";
			$ga .= " , '{$event['action']}'";

			if ( isset( $event['label'] ) && strlen( $event['label'] ) > 0 ) {
				if ( isset( $event['is_default'] ) ) {
					$ga .= ", {{EMPTY_LABEL}}";
				} else {
					$ga .= ( $event['label_type'] == 'var' ) ? ", {$event['label']}" : ", '{$event['label']}'";
				}

			}
			if ( isset( $event['value'] ) && strlen( $event['value'] ) > 0 ) {
				if ( isset( $event['is_default'] ) ) {
					$ga .= ", {{EMPTY_VALUE}}";
				} else {
					$ga .= ( $event['value_type'] == 'var' ) ? ", {$event['value']}" : ", '{$event['value']}'";
				}
			}

			if ( isset( $event['is_default'] ) ) {
				$ga .= ", {{NON_INTER}}";
			} elseif ( $event['non_inter'] == 'on' ) {
				$ga .= ", {nonInteraction: true}";
			}

			$ga .= ");" . PHP_EOL;

			if ( ! $isTestMode ) {
				$s .= sprintf( $ga, '' );
			} else {
				$s .= $this->buildEventConsole( 'custom_event', $event );
				$s .= sprintf( $ga, '//' );
			}

			$s .= self::TAB . "});" . PHP_EOL;
			$output .= $s;
		}
		return $output;
	}

	public function addCreateCommand( $o, $isTestMode = false )
	{
		$s = '';
		$ga = "%sga('create', '" . $o['id'] . "', 'auto');" . PHP_EOL;
		if ( $isTestMode ) {
			$s .= $this->addConsoleForCommands( 'create', "'{$o['id']}'", "'auto'" );
			$s .= sprintf( $ga, "//" );
		} else {
			$s .= sprintf( $ga, "" );
		}
		return $s;
	}

	public function addSetCommand( $isTestMode )
	{
		$s = '';
		$ga = "%sga('set', 'forceSSL', true);" . PHP_EOL;
		if ( $isTestMode ) {
			$s .= $this->addConsoleForCommands( 'set', "'forceSSL'", 'true' );
			$s .= sprintf( $ga, "//" );
		} else {
			$s .= sprintf( $ga, "" );
		}
		return $s;
	}


	public function addSendCommand( $isTestMode )
	{
		$s = '';
		$ga = "%sga('send', 'pageview');" . PHP_EOL;
		if ( $isTestMode ) {
			$s .= $this->addConsoleForCommands( 'send', "'pageview'", 'location.pathname' );
			$s .= sprintf( $ga, "//" );
		} else {
			$s .= sprintf( $ga, "" );
		}
		return $s;
	}

	public function addEvent( $strFileExt, $link, $eventType, $eventParams, $isTestMode = false )
	{
		$s = $this->buildHeadOfEvents( $strFileExt, $link, $eventType );
		$ga = self::DOUBLE_TAB . "%sga('send', 'event', '{$eventParams['category']}', {$eventParams['action']}";
		if ( $eventParams['label'] != null ) {
			$ga .= ", {$eventParams['label']}";
		}
		if ( isset( $eventParams['non_inter'] ) ) {
			$ga .= ", {nonInteraction: {$eventParams['non_inter']}}";
		}
		$ga .= ");" . PHP_EOL;

		if ( ! $isTestMode ) {
			$s .= sprintf( $ga, '' );
		} else {
			$s .= $this->buildEventConsole( $eventType, $eventParams );
			$s .= sprintf( $ga, '//' );
		}
		if ( $eventType == 'outbound' ) {
			$s .= self::DOUBLE_TAB . "}" . PHP_EOL;
		}
		if ( $eventType == 'error_404' ) {
			$s .= self::TAB . "}" . PHP_EOL;
		} else {
			$s .= self::TAB . "});" . PHP_EOL;
		}
		return $s;
	}

	public function buildEventConsole( $eventType, $eventParams )
	{
		$console = '';
		if ( $eventType == 'custom_event' ) {
			$nonInter = $eventParams['non_inter'] == 'on' ? 'on' : 'off';
			if ( isset( $eventParams['is_default'] ) ) {
				$nonInter = '{{NON_INTER_VAL}}';
			}
			$label = "''";
			$value = "''";
			if ( ! empty( $eventParams['label'] ) ) {
				$label = ( $eventParams['label_type'] == 'var' ) ? "{$eventParams['label']}" : "'{$eventParams['label']}'";
			}
			if ( ! empty( $eventParams['value'] ) ) {
				$value = ( $eventParams['value_type'] == 'var' ) ? "{$eventParams['value']}" : "'{$eventParams['value']}'";
			}
			$console .= self::DOUBLE_TAB . "console.log('send', '{$eventParams['hittype']}', " .
				"{'event_category': '{$eventParams['category']}', 'event_action': '{$eventParams['action']}', 'event_label': {$label}, 'value': {$value}, 'non_interaction': '{$nonInter}'});" . PHP_EOL;
		} else {
			$console = self::DOUBLE_TAB . "console.log('send', 'event', {'event_category': '{$eventParams['category']}', 'event_action': {$eventParams['action']}";
			if ( $eventParams['label'] != null ) {
				$console .= ", 'event_label': {$eventParams['label']}";
			}
			if ( $eventType == 'error_404' ) {
				$console .= ", 'non_interaction': {$eventParams['non_inter']}";
			}
			$console .= "});" . PHP_EOL;
		}
		return $console;
	}
}