<?php

/**
 * @package Scand_Easy_GA_Toolkit
 * @version 1.0.0
 *
 * Scand_Easy_GA_Toolkit_Admin class
 *
 * Helper class that displays and process data in admin section of Easy Google Analytics Toolkit WordPress plugin
 */
class Scand_Easy_GA_Toolkit_Admin
{
	const TRACKING_GA = 0;
	const TRACKING_GTAG = 1;

	const HIT_TYPE_EVENT = 'event';
	const HIT_TYPE_SOCIAL = 'social';
	const HIT_TYPE_TIMING = 'timing';

	const TEMPLATE_KEY = 'TEMPLATE_KEY';

	protected $_mode;
	protected $file_ext = "7z, avi, csv, doc, docx, exe, mov, mp3, pdf, pdn, pez, pot, ppt, pptx, psd, pub, rar, tar, txt, wav, wma, wmv, wwf, xls, xlsx, zip";

	/**
	 * Scand_Easy_GA_Toolkit_Admin constructor.
	 */
	public function __construct()
	{
		$opt = $this->getOption();
		$this->_mode = $opt['mode'];
	}

	/**
	 * Main method that invoke dr
	 *
	 * @return string
	 */
	public function drawJSSnippet()
	{
		$oStrategy = null;
		$options = $this->getOption();
		if ( $options['tracking'] ) {
			$oStrategy = new Scand_Easy_GA_Toolkit_Gtag();
		} else {
			$oStrategy = new Scand_Easy_GA_Toolkit_Analytics();
		}
		$oHelper = new Scand_Easy_GA_Toolkit_Javascript_Helper( $options, $this->_mode, $oStrategy );
		return $oHelper->buildScript();
	}

	/**
	 * Displays settings template and handle POST data on saving
	 */
	public function showForm()
	{
		$bSuccess = 0;
		if ( isset( $_POST['scand_easy_ga_toolkit_submit'] ) &&
			wp_verify_nonce( $_POST[ SCAND_EASY_GA_TOOLKIT_NONCE ], 'scand-easy-ga-toolkit-save-settings' )
		) {

			$flag = 0;
			$arData = $_POST[ SCAND_EASY_GA_TOOLKIT_INPUT ];
			$arRes = $this->validateInput( $arData );
			$arCustom = array();
			if ( array_key_exists( 'custom_event', $arData ) ) {
				$pos = 1;
				$custom_events = array();
				foreach ( $arData['custom_event'] as $key => &$event ) {
					if ( self::TEMPLATE_KEY == $key ) continue;

					switch ( $event['hittype'] ) {
						case self::HIT_TYPE_EVENT:
							$o = new Scand_Easy_GA_Toolkit_Event_Validator();
							break;
						// TODO: next version
						/*case self::HIT_TYPE_SOCIAL:
							$o = new Scand_Easy_GA_Toolkit_Social_Validator();
							break;
						case self::HIT_TYPE_TIMING:
							$o = new Scand_Easy_GA_Toolkit_Timing_Validator();
							break;*/
						default:
							continue;
					}

					$o->processData( $event );
					$arCustom['event_error'][ $event['selector'] ] = $o->validData();
					$flag = count( $arCustom['event_error'][ $event['selector'] ] ) > 0;
					unset( $event );
					$custom_events[ $pos ] = $o->replaceByValid();
					$pos++;
				}
				$arData['custom_event'] = $custom_events;
			}
			if ( count( $arRes ) > 0 || $flag > 0 ) {
				if ( ! isset( $arData['downloads']['enabled'] ) ) {
					$arData['downloads']['file_ext'] = $this->file_ext;
				}
				$arError = $arData + $arRes + $arCustom;
				print $this->renderTemplate( 'main.php', $arError );
				return;
			} else {
				//save data
				if ( isset( $arData['downloads']['file_ext'] ) ) {
					$files = $arData['downloads']['file_ext'];
					$arData['downloads']['file_ext'] = preg_replace( '/\./', '', $files );
				}
				if ( $this->updateOption( $arData ) ) {
					$bSuccess = 1;
				}
			}
		}
		$options = $this->getOption();
		if ( $bSuccess ) {
			$options['success'] = $bSuccess;
		}
		if ( ! isset( $options['downloads']['enabled'] ) ) {
			$options['downloads']['file_ext'] = $this->file_ext;
		}
		print $this->renderTemplate( 'main.php', $options );
	}

	/**
	 * Actions to be taken prior to page loading. This is after headers have been set.
	 * call on load-$hook
	 * This calls the add_meta_boxes hooks, adds screen options and enqueues the postbox.js script.
	 *
	 * @param $options
	 */
	public function page_actions( $options )
	{
		if ( isset( $options['custom_event'] ) ) {
			foreach ( $options['custom_event'] as $key => $event ) {
				$title = __( 'Event', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ) . ": ";
				if ( ! empty( $event['event'] ) && ! empty( $event['selector'] ) ) {
					$title .= "<span style='color: dodgerblue'>" . $event['event'] . "</span>"
						. " " . __( 'for', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ) . " "
						. "<span style='color: green'>" . $event['selector'] . "</span>";
				} else {
					$title .= __( 'not defined', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN );
				}
				$event['mode'] = $options['mode'];
				$event['tracking'] = $options['tracking'];
				add_meta_box( "scand-custom-events-box-" . $key, $title, array( $this, "drawCustomEventTable" ), '', "normal", "high", array( $key, $event ) );
			}
		}
	}

	/**
	 * Displays table with custom event in meta box area
	 *
	 * @param $args
	 */
	public function drawCustomEventTable( $post, $args )
	{
		$key = $args['args'][0];
		$event = $args['args'][1];
		include SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_PATH . 'views/_custom_event_area.php';
	}

	/**
	 * Fetch template and populate it with data
	 *
	 * @param $file
	 * @param $viewData
	 * @return string
	 */
	protected function renderTemplate( $file, $viewData )
	{
		$this->page_actions( $viewData );
		ob_start();
		include SCAND_EASY_GA_TOOLKIT_INCLUDE_DIR_PATH . 'views/' . $file;
		$template = ob_get_contents();
		@ob_end_clean();
		return $template;
	}

	/**
	 * Main data input validation entry point
	 *
	 * @param $data
	 * @return array
	 */
	protected function validateInput( $data )
	{
		$arError = array();
		if ( ! $this->validateGoogleID( $data['id'] ) ) {
			$arError['error']['id'] = __( "Invalid google Tracking ID", SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN );
		}
		if ( isset( $data['downloads']['file_ext'] ) && ! $this->validateFileExt( $data['downloads']['file_ext'] ) ) {
			$arError['error']['file_ext'] = __( "Invalid file name extensions", SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN );
		}
		return $arError;
	}

	/**
	 * check Google Analytics tracking ID for being a valid one
	 *
	 * @param $id
	 * @return bool
	 */
	protected function validateGoogleID( $id )
	{
		return preg_match( "/^ua-\d{4,9}-\d{1,4}$/i", strval( $id ) ) ? true : false;
	}

	/**
	 * Filename extensions validation
	 *
	 * @param $strExt
	 * @return bool
	 */
	protected function validateFileExt( $strExt )
	{
		$strExt = preg_replace( '/\./', '', $strExt );
		$pattern = "/^[a-zA-Z,\s0-9]+$/";
		$output = preg_replace( '!\s+!', ' ', $strExt );
		$aC = preg_match_all( "/,/", $output, $m1 );
		$aS = preg_match_all( "/\s/", $output, $m2 );
		return ( $aC == $aS && preg_match( $pattern, $output ) ) ? true : false;
	}

	/**
	 * Custom wrapper on WP function
	 *
	 * @return array|mixed|void
	 */
	protected function getOption()
	{
		$option = get_option( SCAND_EASY_GA_TOOLKIT_OPTIONS );
		return ( false === $option ) ? array() : $option;
	}

	/**
	 * Custom wrapper on WP function that updates plugins options
	 *
	 * @param $options
	 * @return bool
	 */
	protected function updateOption( $options )
	{
		return (bool)update_option( SCAND_EASY_GA_TOOLKIT_OPTIONS, $options );
	}

	public static function prepareInputValue( $str )
	{
		return stripslashes( $str );
	}

	public static function printOptionSelected( $option, $value )
	{
		echo $option == $value ? ' selected="selected"' : '';
	}

	public static function printCheckboxChecked( $option, $value )
	{
		echo $option == $value ? ' checked="checked"' : '';
	}

	public static function printFieldDisabled( $option, $value )
	{
		echo $option == $value ? ' disabled="disabled"' : '';
	}

	public static function printFieldHidden( $option, $value )
	{
		echo $option == $value ? ' hidden' : '';
	}

	public static function getDefaultPreviewData()
	{
		return array(
			self::TEMPLATE_KEY => array(
				"hittype"         => 'event',
				"event"           => '{{EVENT}}',
				"selector"        => '{{SELECTOR}}',
				"category"        => '{{CATEGORY}}',
				"action"          => '{{ACTION}}',
				"label"           => '{{LABEL}}',
				"value"           => '{{VALUE}}',
				"non_inter"       => 'off',
				"prevent_default" => '{{PREVENT}}',
				"javascript"      => '{{JAVASCRIPT}}',
				"label_type"      => 'str',
				"value_type"      => 'int',
				"is_default"      => true,
			),
		);
	}
}