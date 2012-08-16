/*!
 * Bootstrap Alert jQuery UI widget file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @see http://twitter.github.com/bootstrap
 */

( function( $ ) {
	"use strict" // set strict mode

	var widget = $.extend( {}, $.ui.bootWidget.prototype, {
		/**
		 * The name of the widget.
		 * @type String
		 */
		name: 'alert',
		/**
		 * Widget options.
		 * - keys: The valid alert types.
		 * - template: The HTML template for displaying alerts.
		 * - displayTime: The time to display each alert.
		 * - closeTime: The duration for closing each alert.
		 * - closeText: The close link text.
		 * @type Object
		 */
		options: {
			keys: [ 'success', 'info', 'warning', 'error' ],
			template: '<div class="alert-message {key}"><p>{message}</p></div>',
			displayTime: 5000,
			closeTime: 350,
			closeText: '×'
		},
		/**
		 * Creates the widget.
		 */
		_create: function() {
			var self = this,
				alerts = self.element.find( '.alert-message' );

			for ( var i = 0, l = alerts.length; i < l; ++i ) {
				var alert = $( alerts[ i ] );
				self._initAlert( alert );
			}
		},
		/**
		 * Creates a new alert message.
		 * @param {String} key The message type, e.g. 'success'.
		 * @param {String} message The message.
		 */
		alert: function( key, message ) {
			if ( this.options.keys.indexOf( key ) !== -1 ) {
				var self = this,
					template = this.options.template;

				template = template.replace( '{key}', key );
				template = template.replace( '{message}', message );

				var alert = $( template );
				self._initAlert( alert );
				alert.appendTo( self.element );
			}

			return this;
		},
		/**
		 * Initializes the alert by appending the close link
		 * and by setting a time out for the close callback.
		 * @param {Object} alert The alert element.
		 */
		_initAlert: function( alert ) {
			var self = this,
				closeLink = self._createCloseLink( alert );

			closeLink.prependTo( alert );

			if ( self.options.displayTime > 0 ) {
				setTimeout( function() {
					self.close( alert );
				}, self.options.displayTime );
			}
		},
		/**
		 * Closes a specific alert message.
		 * @param {Object} alert The alert element.
		 */
		close: function( alert ) {
			if ( alert ) {
				alert.fadeOut( this.options.closeTime, function() {
					$( this ).html( '' );
				});
			}

			return this;
		},
		/**
		 * Creates the close link.
		 * @param {Object} alert The alert element.
		 */
		_createCloseLink: function( alert ) {
			var self = this;

			return $( '<a class="close" href="#">' + self.options.closeText + '</a>' )
				.bind( 'click', function( event ) {
					self.close( alert );
					event.preventDefault();
					return false;
				} );
		},
		/**
		 * Destructs this widget.
		 */
		_destroy: function() {
			// Nothing here yet...
		}
	} );

	/**
	 * BootAlert jQuery UI widget.
	 */
	$.widget( 'ui.bootAlert', widget );

} )( jQuery );