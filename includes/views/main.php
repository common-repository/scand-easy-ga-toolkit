<h2 class="scand-easy-ga-main-header"><?php _e( 'Easy Google Analytics Toolkit', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></h2>

<h3 class="scand-easy-ga-sub-header"><?php _e( 'General settings', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></h3>

<?php if ( isset ( $viewData['error'] ) ) : ?>
	<div class="error">
		<?php foreach ( $viewData['error'] as $error ) : ?>
			<h3 class="scand-easy-ga-sub-header"><?php echo $error; ?></h3>
		<?php endforeach; ?>
	</div>
<?php elseif ( isset( $viewData['success'] ) ) : ?>
	<div class="updated notice">
		<h3 class="scand-easy-ga-sub-header"><?php _e( 'Settings saved!', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></h3>
	</div>
<?php endif; ?>
<div id="scand-popup-shadow"></div>
<div id="scand-popup">
	<button id="close-popup">&times;</button>
	<div id="scand-description" class="description">
		<dl>
			<dt>
			<h4><?php _e( 'JavaScript items', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></h4></dt>
			<dd><?php _e( 'Bound event: a JavaScript event linked with page elements', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'CSS selector: a page element that will interact', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'preventDefault: allow or forbid element regular behaviour', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'Area for the JavaScript code: becomes visible if you set a data type of GA label and GA value as a JS variable', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
		</dl>
		<dl>
			<dt>
			<h4><?php _e( 'GA items', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></h4></dt>
			<dd><?php _e( 'Hit type: a click type generated on page', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'Category: an object that is interacted with ', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'Action: a type of interaction', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'NI: a non-interaction marker', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'Label: useful for categorizing events', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'Value: A numeric value associated with the event', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
			<dd><?php _e( 'Label/Value type: defines a type of input field data that will be treated as a string literal or a JavaScript variable', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
		</dl>
		<dl>
			<dt>
			<h4><?php _e( 'Example', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></h4></dt>
			<dd style="max-width: 760px;"><?php _e( 'In order to track a click event, you have to type the \'click\' value in the \'bound event\' input field, then enter any CSS selector you\'d like to be tracked down in the \'CSS selector\' input field, and specify the preventDefault behaviour in an appropriate dropdown box by selecting a \'yes\' or \'no\' option . After that, fill in the input fields related to the Google Analytics functionality.', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></dd>
		</dl>
		<p><?php _e( 'For more details you can visit ', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
			<a href="https://developers.google.com/analytics/devguides/collection/analyticsjs/events"
			   target="_blank">Google Analytics</a>
		</p>
	</div>
</div>

<form name="scand-easy-ga-toolkit-options" id="scand-easy-ga-toolkit-form" method="POST" action="">
	<div class="scand-ga-error">
		<div class="error notice" style="display:none;"><p></p></div>
		<input type="hidden" name="request_type" value="<?php echo $_SERVER['REQUEST_METHOD']; ?>" />
		<table class="form-table">
			<tr valign="top">
				<th scope="row"
					class="table-column"><?php _e( 'Running mode', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></th>
				<td class="scand-form-item5">
					<div class="scand-analytics-radio">
						<input type="radio" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[mode]" value="0"
							<?php Scand_Easy_GA_Toolkit_Admin::printCheckboxChecked( $viewData["mode"], 0 ) ?> />
						<span
								class="scand-ga-field-label"><?php _e( 'Production (full functionality is available)', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
					</div>
					<div class="scand-analytics-radio">
						<input type="radio" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[mode]" value="1"
							<?php Scand_Easy_GA_Toolkit_Admin::printCheckboxChecked( $viewData["mode"], 1 ) ?> />
						<span
								class="scand-ga-field-label"><?php _e( 'Development (GA functions are disabled and replaced with console output)', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"
					class="table-column"><?php _e( 'Tracking mode', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></th>
				<td class="scand-form-item5">
					<div class="scand-analytics-radio scand-analytics-radio-tracking">
						<input type="radio" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[tracking]"
							   value="<?php echo Scand_Easy_GA_Toolkit_Admin::TRACKING_GA; ?>"
							<?php Scand_Easy_GA_Toolkit_Admin::printCheckboxChecked( $viewData["tracking"], 0 ) ?> />
						<span
								class="scand-ga-field-label">  <?php _e( 'Universal Analytics (analytics.js)', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
						<span class="scand-ga-error"></span>
					</div>
					<div class="scand-analytics-radio scand-analytics-radio-tracking">
						<input type="radio" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[tracking]"
							   value="<?php echo Scand_Easy_GA_Toolkit_Admin::TRACKING_GTAG; ?>"
							<?php Scand_Easy_GA_Toolkit_Admin::printCheckboxChecked( $viewData["tracking"], 1 ) ?> />
						<span
								class="scand-ga-field-label">  <?php _e( 'Global Site Tag (gtag.js)', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
					</div>
				</td>

			</tr>
			<tr valign="top">
				<th scope="row"
					class="table-column"><?php _e( 'Google Analytics tracking ID', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></th>
				<td class="scand-form-item">
					<input id="ga-tracking-id" type="text" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[id]"
						   value="<?php echo $viewData['id'] ?>" />
					<span
							class="scand-ga-id-label"><?php _e( 'Add Google Analytics tracking ID (UA-XXXXXXX-YY)', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Force SSL', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></th>
				<td>
					<input type="checkbox" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[ssl]" value="1"
						<?php if ( $viewData['ssl'] ) echo "checked=\"checked\""; ?> />
					<span>
                    <?php _e( 'Setting Force SSL to true will force HTTP pages to also send all beacons using HTTPS.', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
                </span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Event Tracking', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></th>
				<td>
					<p>
						<input type="checkbox" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[error_404]"
							   class="anb-event"
							   value="1" <?php if ( $viewData['error_404'] ) echo "checked=\"checked\""; ?> />
						<span
								class="scand-event-type"><?php _e( 'Error 404', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
						<span
								class="scand-event-notification<?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( isset( $viewData['emails'] ), false ) ?>">
                        (<i><?php _e( 'occurs when moving to a non-existent address', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></i>)
					</p>

					<p>
						<input type="checkbox" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[emails]"
							   class="anb-event"
							   value="1" <?php if ( $viewData['emails'] ) echo "checked=\"checked\""; ?> />
						<span class="scand-event-type"><?php _e( 'Emails', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
						<span
								class="scand-event-notification<?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( isset( $viewData['emails'] ), false ) ?>">
                        (<i><?php esc_html_e( 'looks like <a href="mailto:contact@scand.com">mail me</a>', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></i>)
                    </span>
					</p>

					<p>
						<input type="checkbox" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[phones]"
							   class="anb-event"
							   value="1" <?php if ( $viewData['phones'] ) echo "checked=\"checked\""; ?> />
						<span
								class="scand-event-type"><?php _e( 'Phone Numbers', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>

						<span
								class="scand-event-notification<?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( isset( $viewData['phones'] ), false ) ?>">
                        (<i><?php esc_html_e( 'looks like <a href="tel:+375172560877">contact us</a>', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></i>)
                    </span>
					</p>

					<p>
						<input type="checkbox" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[skype]" class="anb-event"
							   value="1" <?php if ( $viewData['skype'] ) echo "checked=\"checked\""; ?> />
						<span class="scand-event-type"><?php _e( 'Skype', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>

                        </span>
						<span
								class="scand-event-notification<?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( isset( $viewData['skype'] ), false ) ?>">
                        (<i><?php esc_html_e( 'looks like <a href="skype:username">Call us by skype</a>', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></i>)
                    </span>
					</p>

					<p>
						<input type="checkbox" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[outbound]"
							   class="anb-event"
							   value="1" <?php Scand_Easy_GA_Toolkit_Admin::printCheckboxChecked( isset( $viewData['outbound'] ), true ) ?> />
						<span class="scand-event-type"><?php _e( 'Outbound', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
						<span
								class="scand-event-notification <?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( isset( $viewData['outbound'] ), false ) ?>">
                        (<i><?php esc_html_e( 'looks like <a href="http://scand.com"></a>  or <a href="https://scand.com"></a>', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></i>)
                    </span>
					</p>

					<p>
						<input type="checkbox" name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[downloads][enabled]"
							   class="anb-event" id="easy-ga-toolkit-download-event"
							   value="1" <?php Scand_Easy_GA_Toolkit_Admin::printCheckboxChecked( isset( $viewData['downloads']['enabled'] ), true ) ?> />
						<span
								class="scand-event-type"><?php _e( 'Downloads', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></span>
						<span
								class="scand-event-notification <?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( isset( $viewData['downloads']['enabled'] ), false ) ?>">
                            (<i><?php _e( 'file extensions without a dot that will be tracked on hit', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></i>)</span>
					<div id="scand-download-ext"
						 class="<?php Scand_Easy_GA_Toolkit_Admin::printFieldHidden( isset( $viewData['downloads']['enabled'] ), false ) ?>">
						<input type="text" class="download-input"
							   name="<?php echo SCAND_EASY_GA_TOOLKIT_INPUT ?>[downloads][file_ext]"
							   value="<?php echo $viewData['downloads']['file_ext']; ?>" <?php Scand_Easy_GA_Toolkit_Admin::printFieldDisabled( isset( $viewData['downloads']['enabled'] ), false ) ?> /><br />
					</div>
					</p>
				</td>
			</tr>
			<tr>
				<th class="scand-custom-events">
					<h3 class="scand-custom-events-header scand-easy-ga-sub-header"><?php _e( 'Custom Events', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>
						<button id="scand-show-more">
							<span class="question">?</span>
						</button>
					</h3>
				</th>
			</tr>
		</table>

		<?php if ( isset( $viewData['event_error'] ) ) : ?>
			<?php foreach ( $viewData['event_error'] as $key => $error ) : ?>
				<?php $str = implode( ", ", $error );
				if ( strlen( $str ) > 0 ) : ?>
					<div class="error">
						<h3 class="scand-easy-ga-sub-header"><?php echo Scand_Easy_GA_Toolkit_Admin::prepareInputValue( $key ) . " : " . $str; ?></h3>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php
		wp_nonce_field( 'some-action-nonce' );
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		?>
		<div class="easy-ga-custom-events">
			<div id="poststuff" class="scand-poststuff">
				<div id="postbox-container-1" class="postbox-container">
					<?php do_meta_boxes( '', 'normal', null ); ?>
				</div>
			</div>
		</div>
		<button type="button" id="scand-add-custom-event"
				class="button-primary"><?php _e( 'Add event', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?></button>
		<p class="submit">
			<input type="submit" id="settings-form-submit" class="button-primary"
				   value="<?php _e( 'Save Changes', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN ); ?>" />
			<input type="hidden" name="scand_easy_ga_toolkit_submit" value="submit" />
			<?php wp_nonce_field( 'scand-easy-ga-toolkit-save-settings', SCAND_EASY_GA_TOOLKIT_NONCE ); ?>
		</p>
</form>

<div id="postbox_template" class="hidden">
	<?php
	$data = array(
		"hittype"         => 'event',
		"event"           => '',
		"selector"        => '',
		"category"        => '',
		"action"          => '',
		"label"           => '',
		"value"           => '',
		"non_inter"       => '',
		"prevent_default" => '',
		"javascript"      => '',
		"label_type"      => 'str',
		"value_type"      => 'int',
		"tracking"        => $viewData['tracking'],
		"mode"            => $viewData['mode'],
		"is_default"      => true,
	);

	global $wp_meta_boxes;
	$wp_meta_boxes = array();

	$title = __( 'Event: not defined', SCAND_EASY_GA_TOOLKIT_TEXTDOMAIN );
	add_meta_box( "scand-custom-events-box-template", $title, array( $this, "drawCustomEventTable" ), '', "normal", "high", [ Scand_Easy_GA_Toolkit_Admin::TEMPLATE_KEY, $data ] );
	do_meta_boxes( '', 'normal', null );
	?>
</div>