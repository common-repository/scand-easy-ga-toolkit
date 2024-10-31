(function ( $ ) {
	function ScandEasyGA() {
		var self = this;

		this.TRACKING_GA = 0;
		this.TRACKING_GTAG = 1;
		this.mode = 0;
		this.tracking = 1;
		this.formFieldName = '[name="' + scand_js_obj.input_name + '[custom_event]';
		this.allTextFields = {
			event: [],
			selector: [],
			category: [],
			action: [],
			label: [],
			value: [],
			javascript: []
		};
		this.allSelectFields = {
			prevent_default: [],
			label_type: [],
			value_type: []
		};
		this.allCheckBoxes = {
			non_inter: []
		};
		this.allFieldsID = [];
		this.allPreviewAreas = [];

		this.init = function () {
			this.$form = $( '#scand-easy-ga-toolkit-form' );
			this.$hiddenBox = $( '#postbox_template' ).find( '.postbox' );
			this.$postBoxes = $( '#postbox-container-1' ).find( '.postbox' );
			this.initFieldsID();
			this.initTextFields();
			this.initSelectFields();
			this.initCheckboxes();
			this.initTextAreas();
			this.initEventHandlers();
			this.setMode( $( 'input[name*=mode]:checked' ).val() );
			this.setTracking( $( 'input[name*=tracking]:checked' ).val() );
		};

		this.addNestedKey = function ( node, keys, value ) {
			var ndx = keys.length - 1;
			for ( var i = 0; i < ndx; ++i ) {
				var key = keys[ i ];
				if ( !node.hasOwnProperty( key ) ) {
					node[ key ] = {};
				}
				node = node[ key ];
			}
			node[ keys[ ndx ] ] = value;
		};

		this.addFormValue = function ( form_data, field ) {
			if ( field.name.indexOf( '[' ) > -1 ) {
				var keys = field.name.split( '[' ).map( function ( value ) {
					return value.replace( ']', '' );
				} );
				this.addNestedKey( form_data, keys, field.value );
			} else {
				form_data[ field.name ] = field.value;
			}
		};

		this.loadPreview = function () {
			var form_data = {};
			$.each( this.$form.serializeArray(), function ( i, field ) {
				self.addFormValue( form_data, field );
			} );
			$.ajax( {
				url: scand_js_obj.ajax_url,
				method: 'POST',
				type: 'JSON',
				data: {
					action: 'easy_ga_load_preview',
					_ajax_nonce: scand_js_obj.nonce,
					form_data: form_data
				},
				success: function ( response ) {
					if ( response.success ) {
						for ( var i in response.data.preview ) {
							$( '#scand-preview-area-' + i ).find( 'textarea' ).html( response.data.preview[ i ] );
						}
					}
				}
			} );
		};

		this.setTracking = function ( tracking ) {
			this.tracking = tracking;
		};

		this.setMode = function ( mode ) {
			this.mode = mode;
		};

		this.initFieldsID = function () {
			this.allFieldsID = this.$postBoxes.map( function () {
				return $( this ).attr( 'id' ).split( '-' ).slice( -1 ).join( '' );
			} )
		};

		this.initFields = function ( fields ) {
			for ( var field in fields ) {
				fields[ field ] = this.$postBoxes.map( function ( index ) {
					return $( this )
						.find( $( self.formFieldName + '[' + self.allFieldsID[ index ] + '][' + field + ']"]' ) );
				} );
			}
		};

		this.initTextFields = function () {
			this.initFields( this.allTextFields );
		};

		this.initSelectFields = function () {
			this.initFields( this.allSelectFields );
		};

		this.initCheckboxes = function () {
			this.initFields( this.allCheckBoxes );
		};

		this.initTextAreas = function () {
			this.allPreviewAreas = this.$postBoxes.map( function ( index ) {
				return $( this ).find( $( '#scand-preview-area-' + self.allFieldsID[ index ] + ' > textarea' ) );
			} );
		};

		this.initEventHandlers = function () {
			this.allPreviewAreas.each( function ( index ) {
				var checkBoxes = self.getCurrentValuesOfCheckBoxes( index );
				var textFields = self.getCurrentValuesOfTextFields( index );
				var selectFields = self.getCurrentValuesOfSelectFields( index );
				var $previewArea = $( this );
				for ( var textField in textFields ) {
					(function ( textField, index ) {
						self.allTextFields[ textField ][ index ].on( 'keyup', function () {
							textFields[ textField ] = $( this ).val();
							$previewArea = $previewArea
								.html( self.buildPreview( checkBoxes, textFields, selectFields ) );
						} );
					})( textField, index );
				}
				for ( var selectField in selectFields ) {
					(function ( selectField, index ) {
						self.allSelectFields[ selectField ][ index ].on( 'change', function () {
							selectFields[ selectField ] = $( this ).val();
							$previewArea = $previewArea
								.html( self.buildPreview( checkBoxes, textFields, selectFields ) );
						} );
					})( selectField, index );
				}
				for ( var checkBox in checkBoxes ) {
					(function ( checkBox, index ) {
						self.allCheckBoxes[ checkBox ][ index ].on( 'change', function () {
							checkBoxes[ checkBox ] = $( this ).is( ':checked' ) ? 'true' : 'false';
							$previewArea = $previewArea
								.html( self.buildPreview( checkBoxes, textFields, selectFields ) );
						} );
					})( checkBox, index );
				}
			} );
		};

		this.getCurrentValuesOfTextFields = function ( index ) {
			var textFields = {
				event: this.allTextFields.event[ index ].val(),
				selector: this.allTextFields.selector[ index ].val(),
				category: this.allTextFields.category[ index ].val(),
				action: this.allTextFields.action[ index ].val(),
				label: this.allTextFields.label[ index ].val(),
				value: this.allTextFields.value[ index ].val(),
				javascript: this.allTextFields.javascript[ index ].val()
			};
			return textFields;
		};

		this.getCurrentValuesOfSelectFields = function ( index ) {
			var selectFields = {
				prevent_default: this.allSelectFields.prevent_default[ index ].val(),
				label_type: this.allSelectFields.label_type[ index ].val(),
				value_type: this.allSelectFields.value_type[ index ].val()
			};
			return selectFields;
		};

		this.getCurrentValuesOfCheckBoxes = function ( index ) {
			var checkBoxes = {
				non_inter: this.allCheckBoxes['non_inter'][ index ].is( ':checked' ) ? 'true' : 'false'
			};
			return checkBoxes;
		};

		this.addEvent = function () {
			var id = (new Date()).getTime();
			var re = new RegExp( scand_js_obj.preview_template_key, 'g' );
			var $postBox = this.$hiddenBox.clone().removeClass( 'hide-if-js' );
			$postBox.find( '.scand-disabled-field' ).removeAttr( 'disabled' );
			$postBox.html( $postBox.html().replace( re, id ) );
			var $preview = $postBox.find( '.scand-preview-area > textarea' );
			var $containerWithPostboxes = $( '#postbox-container-1' ).find( '#normal-sortables' );
			var textFields = {
				event: '',
				selector: '',
				category: '',
				action: '',
				label: '',
				value: '',
				javascript: ''
			};
			var selectFields = {
				prevent_default: 'yes',
				label_type: 'str',
				value_type: 'int'
			};
			var checkBoxes = {
				non_inter: 'false'
			};

			$preview = $preview.html( this.buildPreview( checkBoxes, textFields, selectFields ) );
			$postBox.appendTo( $containerWithPostboxes );
			$postBox.find( '.hndle' ).on( 'click', this.switchPostBox );
			$postBox.find( '.handlediv' ).on( 'click', this.switchPostBox );
			for ( var textField in textFields ) {
				(function ( textField ) {
					$postBox
						.find( $( self.formFieldName + '[' + id + '][' + textField + ']"]' ) ).on( 'keyup', function () {
							textFields[ textField ] = $( this ).val();
							$preview = $preview.html( self.buildPreview( checkBoxes, textFields, selectFields ) );
					} );
				})( textField );
			}
			for ( var selectField in selectFields ) {
				(function ( selectField ) {
					$postBox
						.find( $( self.formFieldName + '[' + id + '][' + selectField + ']"]' ) ).on( 'change', function () {
						selectFields[ selectField ] = $( this ).val();
						$preview = $preview.html( self.buildPreview( checkBoxes, textFields, selectFields ) );
					} );
				})( selectField );
			}
			for ( var checkBox in checkBoxes ) {
				(function ( checkBox ) {
					$postBox
						.find( $( self.formFieldName + '[' + id + '][' + checkBox + ']"]' ) ).on( 'change', function () {
						checkBoxes[ checkBox ] = $( this ).is( ':checked' ) ? 'true' : 'false';
						$preview = $preview.html( self.buildPreview( checkBoxes, textFields, selectFields ) );
					} );
				})( checkBox );
			}
		};

		this.validateRequired = function () {
			var $inputs = $( '.postbox' ).find( '.scand-input[name*=category]' );
			if ( this.tracking == this.TRACKING_GA ) {
				$inputs.attr( 'required', 'required' );
				$inputs.parent().find( 'span' ).addClass( 'scand-required' ).addClass( 'required' );
			}
			else if ( this.tracking == this.TRACKING_GTAG ) {
				$inputs.removeAttr( 'required' );
				$inputs.parent().find( '.scand-required' ).removeClass( 'scand-required' );
				$inputs.parent().find( '.required' ).removeClass( 'required' );
			}
		};

		this.buildMetaboxTitle = function () {
			var $tr = $( this ).closest( 'tr' );
			var $event = $tr.find( '.js-scand-easy-ga-event' );
			var $selector = $tr.find( '.js-scand-easy-ga-selector' );
			var $title = $( '<span/>' ).text( scand_js_obj.custom_event_title );
			$title
				.html( $title.html()
					.replace( '{{EVENT}}', '<span style="color: dodgerblue">' + $event.val() + '</span>' ) );
			$title
				.html( $title.html()
					.replace( '{{SELECTOR}}', '<span style="color: green">' + $selector.val() + '</span>' ) );
			$( this ).closest( '.postbox' ).find( '.hndle' ).html( $title );
		};

		this.buildPreview = function ( checkBoxes, textFields, selectFields ) {
			var non_inter_val = checkBoxes['non_inter'] == 'true' ? 'on' : 'off';
			var $hiddenBox = this.$hiddenBox.clone();
			var category = textFields['category'];
			var $preview = $hiddenBox.find( '.scand-preview-area > textarea' ).html();
			$preview = $preview.replace( /{{EVENT}}/g, textFields['event'] )
				.replace( /{{SELECTOR}}/g, textFields['selector'] )
				.replace( /{{ACTION}}/g, textFields['action'] )
				.replace( /{{NON_INTER_VAL}}/g, non_inter_val );
			if ( this.tracking == this.TRACKING_GTAG ) {
				category = textFields['category'] == '' ? 'general' : textFields['category'];
			}
			$preview = $preview.replace( /{{CATEGORY}}/g, category );
			if ( this.tracking == this.TRACKING_GTAG ) {
				if ( checkBoxes['non_inter'] ) {
					$preview = $preview.replace( /{{NON_INTER_GTAG}}/g, checkBoxes['non_inter'] );
				}
			} else {
				if ( checkBoxes['non_inter'] == 'true' ) {
					$preview = $preview
						.replace( /{{NON_INTER}}/g, '{nonInteraction: ' + checkBoxes['non_inter'] + '}' );
				} else {
					$preview = $preview.replace( /, {{NON_INTER}}/g, '' );
				}
			}
			if ( selectFields['prevent_default'] == 'yes' ) {
				$preview = $preview.replace( /{{PREVENT}}/g, "event.preventDefault();" );
			} else {
				$preview = $preview.replace( /\t\t{{PREVENT}}\n/g, '' );
			}
			if ( textFields['javascript'] == '' ) {
				$preview = $preview.replace( /\t\t{{JAVASCRIPT}}\n/g, '' );
			} else {
				$preview = $preview.replace( /{{JAVASCRIPT}}/g, textFields['javascript'].replace( /\n/g, '\n\t\t' ) );
			}
			if ( textFields['value'] == '' ) {
				$preview = $preview.replace( /, {{EMPTY_VALUE}}/g, '' );
				$preview = $preview.replace( /{{VALUE}}/g, '' );
			} else {
				if ( this.tracking == this.TRACKING_GA ) {
					$preview = $preview.replace( /{{EMPTY_VALUE}}/g, "'{{VALUE}}'" );
				} else {
					$preview = $preview.replace( /{{EMPTY_VALUE}}/g, "'value': '{{VALUE}}'" );
				}
				if ( selectFields['value_type'] == 'var' ) {
					$preview = $preview.replace( /'{{VALUE}}'/g, textFields['value'] );
				} else {
					$preview = $preview.replace( /{{VALUE}}/g, textFields['value'] );
				}
			}
			if ( textFields['label'] == '' ) {
				$preview = $preview.replace( /, {{EMPTY_LABEL}}/g, '' );
				$preview = $preview.replace( /{{LABEL}}/g, '' );
			} else {
				if ( this.tracking == this.TRACKING_GA ) {
					$preview = $preview.replace( /{{EMPTY_LABEL}}/g, "'{{LABEL}}'" );
				} else {
					$preview = $preview.replace( /{{EMPTY_LABEL}}/g, "'event_label': '{{LABEL}}'" );
				}
				if ( selectFields['label_type'] == 'var' ) {
					$preview = $preview.replace( /'{{LABEL}}'/g, textFields['label'] );
				} else {
					$preview = $preview.replace( /{{LABEL}}/g, textFields['label'] );
				}
			}
			return $preview;
		};

		this.switchPostBox = function () {
			var show = $( this ).attr( "aria-expanded" ) != 'true';
			$( this ).attr( "aria-expanded", show );
			if ( $( this ).parent().hasClass( 'closed' ) ) {
				$( this ).parent().removeClass( 'closed' );
			} else {
				$( this ).parent().addClass( 'closed' );
			}
		};
	}

	var scand_easy_ga = new ScandEasyGA();

	$( document ).ready( function () {
		scand_easy_ga.init();

		$( document ).on( 'submit', '#scand-easy-ga-toolkit-form', function () {
			$( '#settings-form-submit' ).attr( 'disabled', 'disabled' );
			return true;
		} );

		$( document ).on( 'click', '#easy-ga-toolkit-download-event', function () {
			var $ext = $( '#scand-download-ext' );
			if ( $( this ).is( ':checked' ) ) {
				$ext.removeClass( 'hidden' );
				$ext.find( 'input' ).removeAttr( 'disabled' );
			}
			else {
				$ext.addClass( 'hidden' );
				$ext.find( 'input' ).attr( 'disabled', 'disabled' );
			}
		} );

		$( document ).on( 'change', 'input[name*=mode]', function () {
			scand_easy_ga.setMode( $( this ).val() );
			scand_easy_ga.loadPreview();
		} );

		$( document ).on( 'change', 'input[name*=tracking]', function () {
			scand_easy_ga.setTracking( $( this ).val() );
			scand_easy_ga.validateRequired();
			scand_easy_ga.loadPreview();
		} );

		$( document ).on( 'click', '#settings-form-submit', function () {
			$( '#postbox_template' ).find( 'input[required="required"]' ).attr( 'required', false );
		} );

		$( document ).on( 'click', '#scand-popup-shadow, #close-popup', function () {
			$( '#scand-popup-shadow' ).hide();
			$( '#scand-popup' ).hide();
		} );

		$( document ).on( 'click', '#scand-show-more', function ( e ) {
			e.preventDefault();
			$( '#scand-popup-shadow' ).show();
			$( '#scand-popup' ).show();
		} );

		$( document ).on( 'click', '#scand-add-custom-event', function () {
			scand_easy_ga.addEvent();
		} );

		$( document ).on( 'keyup', '.js-scand-easy-ga-event, .js-scand-easy-ga-selector', scand_easy_ga.buildMetaboxTitle );
		$( document ).on( 'change', '.anb-event', function ( event ) {
			this.checked ?
				$( this ).closest( 'p' ).find( '.scand-event-notification' ).show() :
				$( this ).closest( 'p' ).find( '.scand-event-notification' ).hide();
		} );

		$( document ).on( 'click', '.scand-custom-event-remove', function () {
			$( this ).closest( '.postbox' ).remove();
		} );

		$( document ).on( 'change', '.js-scand-select-javascript-area', function () {
			var $tr = $( this ).closest( 'tr' );
			var $valueField = $tr.find( '.scand-optional-value' );
			var label = $tr.find( '.scand-dropdown-label' ).find( 'select option:selected' ).val();
			var value = $tr.find( '.scand-dropdown-value' ).find( 'select option:selected' ).val();
			$tr = $tr.next();
			var $textrea = $tr.find( '.scand-textarea' );
			if ( value == 'int' ) {
				$valueField.attr( 'type', 'number' );
				if ( $valueField.val().search( /\D/gi ) != -1 ) {
					$valueField.val( '' );
				}
				$valueField.trigger( 'keyup' );
			} else {
				$valueField.attr( 'type', 'text' );
			}
			if ( value == 'var' || label == 'var' ) {
				$tr.removeClass( 'hidden' );
			} else {
				$tr.addClass( 'hidden' );
				$textrea.val( '' );
				$textrea.trigger( 'keyup' );
			}
		} );
	} );
})( jQuery );