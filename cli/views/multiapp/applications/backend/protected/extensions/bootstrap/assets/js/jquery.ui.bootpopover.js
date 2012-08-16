/*!
 * Bootstrap Popover jQuery UI widget file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @see http://twitter.github.com/bootstrap
 */

( function( $ ) {
	"use strict" // set strict mode

	var widget = $.extend( {}, $.ui.bootTwipsy.prototype, {
		/**
		 * The name of the widget.
		 * @type String
		 */
		name: 'popover',
		/**
		 * The value of the tooltip id attribute.
		 * @type String
		 */
		tooltipId: 'popover',
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
		 * Shows the tooltip.
		 */
		show: function() {
			if ( !this.visible ) {
				var tooltip = this._getTooltip(),
					position;
				
				tooltip.find( '.title' ).html( this.element.attr( 'data-title' ) );
				tooltip.find( '.content p' ).html( this.element.attr( 'data-content' ) );
				position = this._pos();
				tooltip.css( {
					top: position.top,
					left: position.left
				} ).show(); // todo: implement support for effects.

				this.visible = true;
			}
		},
		/**
		 * Creates the tooltip element and appends it to the body element.
		 * @returns {HTMLElement} The element.
		 */
		_createTooltip: function() {
			var tooltip = $( '<div class="popover">' )
				.attr( 'id', this.tooltipId )
				.addClass( this.options.placement )
				.appendTo( 'body' )
				.hide();

			$( '<div class="arrow">' )
				.appendTo( tooltip );

			$( '<div class="inner"><h3 class="title"></h3><div class="content"><p></p></div>' )
				.appendTo( tooltip );

			return tooltip;
		}
	} );

	/**
	 * BootPopover jQuery UI widget.
	 */
	$.widget( 'ui.bootPopover', widget );

} )( jQuery );