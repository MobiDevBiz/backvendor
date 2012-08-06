/*!
 * Bootstrap Twipsy jQuery UI widget file.
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
		name: 'twipsy',
		/**
		 * The value of the tooltip id attribute.
		 * @type String
		 */
		tooltipId: 'twipsy',
		/**
		 * Indicates whether the tooltip is visible.
		 * @type Boolean
		 */
		visible: false,
		/**
		 * Widget options.
		 * - placement: The placement of the tooltip. Valid values are: "above", "right", "below" and "left".
		 * - showEvent: The event for showing the tooltip.
		 * - hideEvent: The event for hiding the tooltip.
		 * - offset: Pixel offset of the tooltip.
		 * - live: Indicates whether to use jQuery.live or jQuery.bind.
		 * @type Object
		 */
		options: {
			placement: 'above',
			showEvent: 'mouseenter',
			hideEvent: 'mouseleave',
			offset: 0,
			live: false
		},
		/**
		 * Creates the widget.
		 */
		_create: function() {
			var self = this,
				element = self.element,
				options = self.options,
				title = element.attr( 'title' ),
				binder = options.live ? 'live' : 'bind';

			if ( title && title.length > 0 ) {
				element.removeAttr( 'title' ); // remove the title to prevent it being displayed
				element.attr( 'data-title', title );

				element[ binder ]( options.showEvent, function() {
                    self.show();
                });
				element[ binder ]( options.hideEvent, function() {
                    self.hide();
                });
			}
		},
		/**
		 * Shows the tooltip.
		 */
		show: function() {
			if ( !this.visible ) {
				var tooltip = this._getTooltip(),
					position;

				tooltip.find( '.twipsy-inner' ).html( this.element.attr( 'data-title' ) );
				position = this._pos();
				tooltip.css( {
					top: position.top,
					left: position.left
				} ).show(); // todo: implement support for effects.

				this.visible = true;
			}

			return this;
		},
		/**
		 * Hides the tooltip.
		 */
		hide: function() {
			if ( this.visible ) {
				var tooltip = this._getTooltip();
				tooltip.hide(); // todo: implement support for effects.
				this.visible = false;
			}

			return this;
		},
		/**
		 * Calculates the position for the tooltip based on the element.
		 * @return {Object} The offset, an object with "top" and "left" properties.
		 */
		_pos: function() {
			var twipsy = this._getTooltip(),
				element = this.element,
				offset = element.offset(),
				top = 0,
				left = 0;

			switch ( this.options.placement ) {
				case 'above':
					top = offset.top - twipsy.outerHeight() - this.options.offset,
					left = offset.left + ( ( element.outerWidth() - twipsy.outerWidth() ) / 2 );
					break;

				case 'right':
					top = offset.top + ( ( element.outerHeight() - twipsy.outerHeight() ) / 2 );
					left = offset.left + element.outerWidth() - this.options.offset;
					break;

				case 'below':
					top = offset.top + element.outerHeight() + this.options.offset,
					left = offset.left + ( ( element.outerWidth() - twipsy.outerWidth() ) / 2 );
					break;

				case 'left':
					top = offset.top + ( ( element.outerHeight() - twipsy.outerHeight() ) / 2 );
					left = offset.left - twipsy.outerWidth() + this.options.offset;
					break;
			}

			return {
				left: left,
				top: top
			};
		},
		/**
		 * Creates the tooltip element and appends it to the body element.
		 * @returns {HTMLElement} The element.
		 */
		_createTooltip: function() {
			var tooltip = $( '<div class="twipsy">' )
				.attr( 'id', this.tooltipId )
				.addClass( this.options.placement )
				.appendTo( 'body' )
				.hide();

			$( '<div class="twipsy-arrow">' )
				.appendTo( tooltip );

			$( '<div class="twipsy-inner">' )
				.appendTo( tooltip );

			return tooltip;
		},
		/**
		 * Returns the tooltip element from the body element.
		 * The element is created if it doesn't already exist.
		 * @return {HTMLElement} The element.
		 */
		_getTooltip: function() {
			var tooltip = $( '#' + this.tooltipId );

			if ( tooltip.length === 0 ) {
				tooltip = this._createTooltip();
			}

			return tooltip;
		},
		/**
		 * Destructs this widget.
		 */
		_destroy: function() {
			this.element.unbind( this.options.showEvent );
			this.element.unbind( this.options.hideEvent );
		}
	} );

	/**
	 * BootTwipsy jQuery UI widget.
	 */
	$.widget( 'ui.bootTwipsy', widget );

} )( jQuery );