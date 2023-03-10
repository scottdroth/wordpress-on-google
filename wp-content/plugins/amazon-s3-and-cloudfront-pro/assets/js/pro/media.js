(function( $, _ ) {

	// Local reference to the WordPress media namespace.
	var media = wp.media;

	/**
	 * A button for offload actions
	 *
	 * @constructor
	 * @augments wp.media.view.Button
	 * @augments wp.media.View
	 * @augments wp.Backbone.View
	 * @augments Backbone.View
	 */
	media.view.OffloadButton = media.view.Button.extend( {
		defaults: {
			text: '',
			style: 'primary',
			size: 'large',
			disabled: false
		},

		initialize: function( options ) {
			if ( options ) {
				this.options = options;
				this.defaults.text = as3cfpro_media.strings[ this.options.action ];
			}

			this.options = _.extend( {}, this.defaults, this.options );

			media.view.Button.prototype.initialize.apply( this, arguments );

			this.controller.on( 'selection:toggle', this.toggleDisabled, this );
			this.controller.on( 'select:activate', this.toggleDisabled, this );
		},

		render: function() {
			media.view.Button.prototype.render.apply( this, arguments );
			this.toggleDisabled();

			return this;
		},

		toggleDisabled: function() {
			var selection = this.controller.state().get( 'selection' ).length > 0;

			if ( !selection ) {
				this.$el.addClass( 'disabled' );
			} else {
				this.$el.removeClass( 'disabled' );
			}
		},

		click: function( e ) {
			e.preventDefault();

			var selection = this.controller.state().get( 'selection' );
			var models = selection.models;
			var library = this.controller.state().get( 'library' );

			if ( this.$el.hasClass( 'disabled' ) || !selection.length ) {
				return;
			}

			var payload = {
				_ajax_nonce: as3cfpro_media.nonces[ this.options.scope + '_' + this.options.action ],
				scope: this.options.scope,
				s3_action: this.options.action,
				ids: _.pluck( models, 'id' )
			};

			this.startOffloadAction();
			this.fireOffloadAction( payload )
				.done( function() {
					_.each( models, function( model ) {

						// Refresh the attributes for each model from the server.
						model.fetch();
					} );

					// Refresh the grid view
					library._requery( true );
				} );
		},

		startOffloadAction: function() {
			$( '.media-toolbar .spinner' ).css( 'visibility', 'visible' ).show();
			$( '.media-toolbar-secondary .button' ).addClass( 'disabled' );
			$( '.offload-buttons__submenu' ).addClass( 'hidden' );
		},

		/**
		 * Send the offload action request via ajax.
		 *
		 * @param {object} payload
		 *
		 * @return {$.promise}      A jQuery promise that represents the request,
		 *                          decorated with an abort() method.
		 */
		fireOffloadAction: function( payload ) {
			return wp.ajax.send( 'as3cfpro_process_media_action', { data: payload } )
				.done( _.bind( this.returnOffloadAction, this ) );
		},

		returnOffloadAction: function( response ) {
			if ( response && '' !== response ) {
				$( '.as3cf-notice' ).remove();
				$( '#wp-media-grid h1' ).after( response );
			}

			this.controller.trigger( 'selection:action:done' );
			$( '.media-toolbar .spinner' ).attr( 'style', '' ).hide();
		}
	} );

	/**
	 * A toggle button for offload dropdown button
	 *
	 * @constructor
	 * @augments wp.media.view.Button
	 * @augments wp.media.View
	 * @augments wp.Backbone.View
	 * @augments Backbone.View
	 */
	media.view.OffloadToggle = media.view.Button.extend( {
		className: 'offload-buttons__toggle',
		defaults: {
			style: 'primary',
			size: 'large',
			priority: -80,
			disabled: true
		},

		initialize: function() {
			media.view.Button.prototype.initialize.apply( this, arguments );
			this.controller.on( 'selection:toggle', this.toggleDisabled, this );
			this.controller.on( 'select:activate', this.toggleDisabled, this );

			$( 'body' ).on( 'click', this.blur );

			return this;
		},

		click: function( e ) {
			e.preventDefault();

			if ( this.$el.hasClass( 'disabled' ) ) {
				return;
			}

			var toolbar = this.controller.content.get().toolbar;
			this.$el.toggleClass( 'opened' );
			toolbar.$( '.offload-buttons__submenu' ).toggleClass( 'hidden' );
		},

		blur: function() {
			var $toggle = $( '.media-toolbar .offload-buttons__toggle' );
			var $submenu = $( '.media-toolbar .offload-buttons__submenu' );

			if ( $toggle.hasClass( 'opened' ) && !$submenu.parent().is( ':active, :hover' ) ) {
				$submenu.addClass( 'hidden' );
				$toggle.removeClass( 'opened' );
			}
		},

		toggleDisabled: function() {
			var selection = this.controller.state().get( 'selection' ).length > 0;
			this.model.set( 'disabled', !selection );

			if ( !selection ) {
				this.$el.addClass( 'disabled' );
			} else {
				this.$el.removeClass( 'disabled' );
			}
		}
	} );

	/**
	 * Show and hide the offload dropdown button for the grid view only.
	 */
	var wpSelectModeToggleButton = media.view.SelectModeToggleButton;

	/**
	 * Extend the SelectModeToggleButton functionality to show and hide
	 * the offload dropdown button when the Bulk Select button is clicked
	 */
	media.view.SelectModeToggleButton = wpSelectModeToggleButton.extend( {
		toggleBulkEditHandler: function() {
			wpSelectModeToggleButton.prototype.toggleBulkEditHandler.call( this, arguments );
			var $toolbar = this.controller.content.get().toolbar;
			var $buttons = $toolbar.$( '.offload-buttons' );
			var $submenu = $buttons.find( '.offload-buttons__submenu' );

			if ( this.controller.isModeActive( 'select' ) ) {
				$buttons.addClass( 'visible' );
			} else {
				$buttons.removeClass( 'visible' );
			}

			// Applies children's width to dropdown button
			if ( $submenu.length ) {
				$buttons.width( $submenu.outerWidth() );
			}
		}
	} );

	/**
	 * A filter for Locations
	 */
	media.view.OffloadLocationFilter = wp.media.view.AttachmentFilters.extend( {
		id: 'media-attachment-as3cf-location-filter',

		// We override the default initialize function to support optgroups
		initialize: function() {
			this.createFilters();
			_.extend( this.filters, this.options.filters );

			var html = '';
			_( as3cfpro_media.filters.as3cf_location.options ).each( function( option, key ) {
				if ( typeof option === 'string' ) {
					html += $( '<option></option>' ).val( key ).html( option ).wrap( '<p>' ).parent().html();
				} else {
					html += '<optgroup label="' + key + '">';
					_( option ).each( function( sub_option, sub_key ) {
						html += $( '<option></option>' ).val( sub_key ).html( sub_option ).wrap( '<p>' ).parent().html();
					} );
					html += '</optgroup>';
				}
			} );

			this.$el.html( html );

			this.listenTo( this.model, 'change', this.select );
			this.select();
		},


		createFilters: function() {
			var filters = {};

			_( as3cfpro_media.filters.as3cf_location.options ).each( function( option, key ) {
				if ( typeof option === 'string' ) {
					filters[ key ] = {
						text: option,
						props: { as3cf_location: key },
						priority: 10,
					};
				} else {
					_( option ).each( function( sub_option, sub_key ) {
						filters[ sub_key ] = {
							text: sub_option,
							props: { as3cf_location: sub_key },
							priority: 10,
						};
					} );
				}
			} );

			this.filters = filters;
		},
	} );

	/**
	 * A filter for Access
	 */
	media.view.OffloadAccessFilter = wp.media.view.AttachmentFilters.extend( {
		id: 'media-attachment-as3cf-access-filter',

		createFilters: function() {
			var filters = {};

			_( as3cfpro_media.filters.as3cf_access.options ).each( function( value, key ) {
				filters[ key ] = {
					text: value,
					props: { as3cf_access: key },
					priority: 10,
				};
			} );

			this.filters = filters;
		},
	} );

	/**
	 * Extend the AttachmentsBrowser toolbar to add the offload dropdown button and
	 * our filters for location and access
	 */
	var wpAttachmentsBrowser = media.view.AttachmentsBrowser;
	media.view.AttachmentsBrowser = wpAttachmentsBrowser.extend( {
		createToolbar: function() {
			wpAttachmentsBrowser.prototype.createToolbar.call( this );

			var default_button_action = as3cfpro_media.actions.bulk.shift();

			var default_button = new media.view.OffloadButton( {
				action: default_button_action,
				classes: 'offload-buttons__action-default',
				scope: 'bulk',
				controller: this.controller
			} ).render();

			if ( as3cfpro_media.actions.bulk.length ) {
				var buttons = [];

				_( as3cfpro_media.actions.bulk ).each( function( action ) {
					buttons.push(
						new media.view.OffloadButton( {
							action: action,
							classes: 'offload-buttons__action',
							scope: 'bulk',
							controller: this.controller
						} ).render()
					);
				}.bind( this ) );

				var dropdown_toggle = new media.view.OffloadToggle( {
					controller: this.controller
				} ).render();

				var buttons_submenu = new media.view.ButtonGroup( {
					buttons: buttons,
					classes: 'offload-buttons__submenu hidden'
				} ).render();

				// Add the buttons
				this.toolbar.set( 'OffloadButtons', new media.view.ButtonGroup( {
					buttons: [default_button, buttons_submenu, dropdown_toggle],
					classes: 'offload-buttons',
					priority: -80
				} ) );
			}

			// Add the locations filter
			this.toolbar.set( 'offloadLocationFilter', new media.view.OffloadLocationFilter( {
				controller: this.controller,
				model: this.collection.props,
				priority: -75
			} ) );

			// Add the access filter
			this.toolbar.set( 'offloadAccessFilter', new media.view.OffloadAccessFilter( {
				controller: this.controller,
				model: this.collection.props,
				priority: -75
			} ) );

			this.toolbar.render();
		}
	} );
})( jQuery, _ );
