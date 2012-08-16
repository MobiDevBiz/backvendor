/*!
 * Bootstrap Tabs jQuery UI widget file.
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
		name: 'tabs',
		/**
		 * Widget options.
		 * @type Object
		 */
		options: {
		},
		/**
		 * Creates the widget.
		 */
		_create: function() {
            var self = this,
                element = self.element,
                options = self.options,
                title = self.element.attr( 'title' );

            element.bind('click', function( event ) {
                self._tab( event );
            });
		},
        _activate: function( element, container ) {
            container.find( '> .active' )
                    .removeClass( 'active' )
                    .find( '> .dropdown-menu > .active' )
                    .removeClass( 'active' );

            element.addClass( 'active' );

            if ( element.parent( '.dropdown-menu' ) ) {
                element.closest( 'li.dropdown' ).addClass( 'active' );
            }
        },
        _tab: function( event ) {
            var self = this,
                element = self.element,
                ul = element.closest( 'ul:not(.dropdown-menu)' ),
                href = element.attr( 'href' ),
                previous, pane;

            if ( /^#\w+/.test( href ) ) {
                event.preventDefault();

                if ( !element.parent( 'li' ).hasClass( 'active' ) ) {
                    previous = ul.find( '.active a' ).last()[0];
                    pane = $( href );

                    self._activate( element.parent( 'li' ), ul );
                    self._activate( pane, pane.parent() );

                    element.trigger( {
                        type: 'change',
                        relatedTarget: previous
                    } );
                }
            }
        },
		/**
		 * Destructs this widget.
		 */
		_destroy: function() {
			// Nothing here yet...
		}
	} );

	/**
	 * BootTabs jQuery UI widget.
	 */
	$.widget( 'ui.bootTabs', widget );

} )( jQuery );