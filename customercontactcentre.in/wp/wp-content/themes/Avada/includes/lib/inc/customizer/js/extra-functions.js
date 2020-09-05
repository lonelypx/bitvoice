/* jshint -W098 */
/* global FusionApp, fusionSanitize */
/* eslint no-unused-vars: 0 */
var fusionTriggerResize = _.debounce( fusionResize, 300 ),
	fusionTriggerScroll = _.debounce( fusionScroll, 300 ),
	fusionTriggerLoad   = _.debounce( fusionLoad, 300 );

/**
 * Gets the customizer settings, or gusionApp settings.
 *
 * @since 2.0
 * @return {Object}
 */
function fusionCustomizerGetSettings() {
	var settings = {};
	if ( 'undefined' !== typeof window.wp && 'undefined' !== typeof window.wp.customize ) {
		return window.wp.customize.get();
	}
	if ( 'undefined' !== typeof FusionApp ) {
		if ( 'undefined' !== typeof FusionApp.settings ) {
			settings = jQuery.extend( settings, FusionApp.settings );
		}
		if ( 'undefined' !== typeof FusionApp.data && 'undefined' !== typeof FusionApp.data.postMeta ) {
			settings = jQuery.extend( settings, FusionApp.data.postMeta );
		}
	}
	return settings;
}

/**
 * Adjusts the brightness of a color,
 *
 * @since 2.0.0
 * @param {string} value - The color we'll be adjusting.
 * @param {string|number} adjustment - By how much we'll be adjusting.
 *                                        Positive numbers increase lightness.
 *                                        Negative numbers decrease lightness.
 * @return {string} - RBGA color, ready to be used in CSS.
 */
function fusionCustomizerColorLightnessAdjust( value, adjustment ) {
	var color  = jQuery.Color( value ),
		adjust = Math.abs( adjustment ),
		neg    = ( 0 > adjust );

	if ( 1 < adjust ) {
		adjust = adjust / 100;
	}
	if ( neg ) {
		return color.lightness( '-=' + adjust ).toRgbaString();
	}
	return color.lightness( '+=' + adjust ).toRgbaString();
}

/**
 * Sets the alpha channel of a color,
 *
 * @since 2.0.0
 * @param {string} value - The color we'll be adjusting.
 * @param {string|number} adjustment - The alpha value.
 * @return {string} - RBGA color, ready to be used in CSS.
 */
function fusionCustomizerColorAlphaSet( value, adjustment ) {
	var color  = jQuery.Color( value ),
		adjust = Math.abs( adjustment );

	if ( 1 < adjust ) {
		adjust = adjust / 100;
	}
	return color.alpha( adjust ).toRgbaString();
}

/**
 * Gets a readable color based on threshold.
 *
 * @since 2.0.0
 * @param {string} value - The color we'll be basing our calculations on.
 * @param {string} args - JSON-formatted arguments.
 * @return {string}
 */
function fusionCustomizerColorReadable( value, args ) {
	var color     = jQuery.Color( value ),
		threshold = Math.abs( args.threshold );

	if ( 1 < threshold ) {
		threshold = threshold / 100;
	}
	if ( color.lightness() < threshold ) {
		return args.dark;
	}
	return args.light;
}

/**
 * Returns a string when the color is transparent.
 *
 * @since 2.0.0
 * @param {string} value - The color.
 * @param {Object} args - An object with the values we'll return depending if transparent or not.
 * @param {string} args.transparent - The value to return if transparent. Use "$" to return the value.
 * @param {string} args.opaque - The value to return if color is not transparent. Use "$" to return the value.
 * @return {string}
 */
function fusionReturnStringIfTransparent( value, args ) {
	var color;
	if ( 'transparent' === value ) {
		return ( '$' === args.transparent ) ? value : args.transparent;
	}
	color = jQuery.Color( value );

	if ( 0 === color.alpha() ) {
		return ( '$' === args.transparent ) ? value : args.transparent;
	}
	return ( '$' === args.opaque ) ? value : args.opaque;
}

/**
 * Returns a string when the color is solid (alpha = 1).
 *
 * @since 2.0.0
 * @param {string} value - The color.
 * @param {Object} args - An object with the values we'll return depending if transparent or not.
 * @param {string} args.transparent - The value to return if transparent.
 * @param {string} args.opaque - The value to return if color is opaque.
 * @return {string}
 */
function fusionReturnStringIfSolid( value, args ) {
	var color;
	if ( 'transparent' === value ) {
		return args.transparent;
	}
	color = jQuery.Color( value );

	if ( 1 === color.alpha() ) {
		return args.opaque;
	}

	return args.transparent;
}

/**
 * Return 1/0 depending on whether the color has transparency or not.
 *
 * @since 2.0
 * @param {string} value - The color.
 * @return {number}
 */
function fusionReturnColorAlphaInt( value ) {
	return fusionReturnStringIfSolid( value, {
		opaque: 0,
		transparent: 1
	} );
}

/**
 * This doesn't change the value.
 * What it does is set the window[ args.globalVar ][ args.id ] to the value.
 * After it is set, we use jQuery( window ).trigger( args.trigger );
 * If we have args.runAfter defined and it is a function, then it runs as well.
 *
 * @param {mixed}  value - The value.
 * @param {Object} args - An array of arguments.
 * @param {string} args.globalVar - The global variable we're setting.
 * @param {string} args.id - If globalVar is a global Object, then ID is the key.
 * @param {Array}  args.trigger - An array of actions to trigger.
 * @param {Array}  args.runAfter - An array of callbacks that will be triggered.
 * @param {Array}  args.condition - [setting,operator,setting_value,value_pattern,fallback].
 * @param {Array}  args.condition[0] - The setting we want to check.
 * @param {Array}  args.condition[1] - The comparison operator (===, !==, >= etc).
 * @param {Array}  args.condition[2] - The value we want to check against.
 * @param {Array}  args.condition[3] - The value-pattern to use if comparison is a success.
 * @param {Array}  args.condition[3] - The value-pattern to use if comparison is a failure.
 * @return {mixed} - Same as the input value.
 */
function fusionGlobalScriptSet( value, args ) {

	// If "choice" is defined, make sure we only use that key of the value.
	if ( ! _.isUndefined( args.choice ) && ! _.isUndefined( value[ args.choice ] ) ) {
		value = value[ args.choice ];
	}

	if ( ! _.isUndefined( args.callback ) && ! _.isUndefined( window[ args.callback ] ) && _.isFunction( window[ args.callback ] ) ) {
		value = window[ args.callback ]( value );
	}

	if ( _.isUndefined( window.frames[ 0 ] ) ) {
		return value;
	}

	if ( args.condition && args.condition[ 0 ] && args.condition[ 1 ] && args.condition[ 2 ] && args.condition[ 3 ] && args.condition[ 4 ] ) {
		switch ( args.condition[ 1 ] ) {
		case '===':
			if ( fusionSanitize.getOption( args.condition[ 0 ] ) === args.condition[ 2 ] ) {
				value = args.condition[ 2 ].replace( /\$/g, value );
			} else {
				value = args.condition[ 3 ].replace( /\$/g, value );
			}
			break;
		}
	}

	// If the defined globalVar is not defined, make sure we define it.
	if ( _.isUndefined( window.frames[ 0 ][ args.globalVar ] ) ) {
		window.frames[ 0 ][ args.globalVar ] = {};
	}

	if ( _.isUndefined( args.id ) ) {

		// If the id is not defined in the vars, then set globalVar to the value.
		window.frames[ 0 ][ args.globalVar ] = value;
	} else {

		// All went well, set the value as expected.
		window.frames[ 0 ][ args.globalVar ][ args.id ] = value;
	}

	// Trigger actions defined in the "trigger" argument.
	if ( ! _.isUndefined( args.trigger ) ) {
		_.each( args.trigger, function( eventToTrigger ) {
			fusionTriggerEvent( eventToTrigger );
			if ( 'function' === typeof window[ eventToTrigger ] ) {
				window[ eventToTrigger ]();
			} else if ( 'function' === typeof window.frames[ 0 ][ eventToTrigger ] ) {
				window.frames[ 0 ][ eventToTrigger ]();
			}
		} );
	}

	// Run functions defined in the "runAfter" argument.
	if ( ! _.isUndefined( args.runAfter ) ) {
		_.each( args.runAfter, function( runAfter ) {
			if ( _.isFunction( runAfter ) ) {
				window.frames[ 0 ][ runAfter ]();
			}
		} );
	}

	return value;
}

/**
 * Triggers an event.
 *
 * @param {string} eventToTrigger - The event to trigger.
 * @return {void}
 */
function fusionTriggerEvent( eventToTrigger ) {
	if ( 'resize' === eventToTrigger ) {
		fusionTriggerResize();
	} else if ( 'scroll' === eventToTrigger ) {
		fusionTriggerScroll();
	} else if ( 'load' === eventToTrigger ) {
		fusionTriggerLoad();
	} else {
		window.frames[ 0 ].dispatchEvent( new Event( eventToTrigger ) );
	}
}

/**
 * Triggers the "resize" event.
 *
 * @return {void}
 */
function fusionResize() {
	window.frames[ 0 ].dispatchEvent( new Event( 'resize' ) );
}

/**
 * Triggers the "scroll" event.
 *
 * @return {void}
 */
function fusionScroll() {
	window.frames[ 0 ].dispatchEvent( new Event( 'scroll' ) );
}

/**
 * Triggers the "load" event.
 *
 * @return {void}
 */
function fusionLoad() {
	window.frames[ 0 ].dispatchEvent( new Event( 'load' ) );
}

/**
 * Calculates media-queries.
 * This is a JS port of the PHP Fusion_Media_Query_Scripts::get_media_query() method.
 *
 * @since 2.0
 * @param {Object} args - Our arguments.
 * @param {string} context - Example: 'only screen'.
 * @param {boolean} addMedia - Whether we should prepend "@media" or not.
 * @return {string}
 */
function fusionGetMediaQuery( args, context, addMedia ) {
	var masterQueryArray = [],
		query            = '',
		queryArray;

	if ( ! context ) {
		context = 'only screen';
	}
	queryArray = [ context ],

	_.each( args, function( when ) {

		// If an array then we have multiple media-queries here
		// and we need to process each one separately.
		if ( 'string' !== typeof when[ 0 ] ) {
			queryArray = [ context ];
			_.each( when, function( subWhen ) {

				// Make sure pixels are integers.
				if ( subWhen[ 1 ] && -1 !== subWhen[ 1 ].indexOf( 'px' ) && -1 === subWhen[ 1 ].indexOf( 'dppx' ) ) {
					subWhen[ 1 ] = parseInt( subWhen[ 1 ], 10 ) + 'px';
				}
				queryArray.push( '(' + subWhen[ 0 ] + ': ' + subWhen[ 1 ] + ')' );
			} );
			masterQueryArray.push( queryArray.join( ' and ' ) );
		} else {

			// Make sure pixels are integers.
			if ( when[ 1 ] && -1 !== when.indexOf( 'px' ) && -1 === when.indexOf( 'dppx' ) ) {
				when[ 1 ] = parseInt( when[ 1 ], 10 ) + 'px';
			}
			queryArray.push( '(' + when[ 0 ] + ': ' + when[ 1 ] + ')' );
		}
	} );

	// If we've got multiple queries, then need to be separated using a comma.
	if ( ! _.isEmpty( masterQueryArray ) ) {
		query = masterQueryArray.join( ', ' );
	}

	// If we don't have multiple queries we need to separate arguments with "and".
	if ( ! query ) {
		query = queryArray.join( ' and ' );
	}

	if ( addMedia ) {
		return '@media ' + query;
	}
	return query;
}

/**
 * Returns the media-query
 *
 * @since 2.0.0
 * @param {Array} queryID - The query-ID.
 * @return {string} - The media-query.
 */
function fusionReturnMediaQuery( queryID ) {
	var breakpointRange = 360,
		sideheaderWidth = 0,
		settings        = fusionCustomizerGetSettings(),
		mainBreakPoint,
		sixColumnsBreakpoint,
		fiveColumnsBreakpoint,
		fourColumnsBreakpoint,
		threeColumnsBreakpoint,
		twoColumnsBreakpoint,
		oneColumnBreakpoint,
		breakpointInterval;

	if ( 'top' !== settings.header_position ) {
		sideheaderWidth = parseInt( settings.side_header_width, 10 );
	}

	mainBreakPoint = parseInt( settings.grid_main_break_point, 10 );
	if ( 640 < mainBreakPoint ) {
		breakpointRange = mainBreakPoint - 640;
	}

	breakpointInterval = parseInt( breakpointRange / 5, 10 );

	sixColumnsBreakpoint   = mainBreakPoint + sideheaderWidth;
	fiveColumnsBreakpoint  = sixColumnsBreakpoint - breakpointInterval;
	fourColumnsBreakpoint  = fiveColumnsBreakpoint - breakpointInterval;
	threeColumnsBreakpoint = fourColumnsBreakpoint - breakpointInterval;
	twoColumnsBreakpoint   = threeColumnsBreakpoint - breakpointInterval;
	oneColumnBreakpoint    = twoColumnsBreakpoint - breakpointInterval;

	switch ( queryID ) {
	case 'fusion-max-1c':
		return fusionGetMediaQuery( [ [ 'max-width', oneColumnBreakpoint + 'px' ] ] );
	case 'fusion-max-2c':
		return fusionGetMediaQuery( [ [ 'max-width', twoColumnsBreakpoint + 'px' ] ] );
	case 'fusion-min-2c-max-3c':
		return fusionGetMediaQuery( [
			[ 'min-width', twoColumnsBreakpoint + 'px' ],
			[ 'max-width', threeColumnsBreakpoint + 'px' ]
		] );
	case 'fusion-min-3c-max-4c':
		return fusionGetMediaQuery( [
			[ 'min-width', threeColumnsBreakpoint + 'px' ],
			[ 'max-width', fourColumnsBreakpoint + 'px' ]
		] );
	case 'fusion-min-4c-max-5c':
		return fusionGetMediaQuery( [
			[ 'min-width', fourColumnsBreakpoint + 'px' ],
			[ 'max-width', fiveColumnsBreakpoint + 'px' ]
		] );
	case 'fusion-min-5c-max-6c':
		return fusionGetMediaQuery( [
			[ 'min-width', fiveColumnsBreakpoint + 'px' ],
			[ 'max-width', sixColumnsBreakpoint + 'px' ]
		] );
	case 'fusion-min-shbp':
		return fusionGetMediaQuery( [ [ 'min-width', parseInt( settings.side_header_break_point, 10 ) + 'px' ] ] );
	case 'fusion-max-shbp':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( settings.side_header_break_point, 10 ) + 'px' ] ] );
	case 'fusion-max-sh-shbp':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( sideheaderWidth + parseInt( settings.side_header_break_point, 10 ), 10 ) + 'px' ] ] );
	case 'fusion-max-sh-cbp':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( sideheaderWidth + parseInt( settings.content_break_point, 10 ), 10 ) + 'px' ] ] );
	case 'fusion-max-sh-sbp':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( sideheaderWidth + parseInt( settings.sidebar_break_point, 10 ), 10 ) + 'px' ] ] );
	case 'fusion-max-shbp-retina':
		return fusionGetMediaQuery( [
			[
				[ 'max-width', parseInt( settings.side_header_break_point, 10 ) + 'px' ],
				[ '-webkit-min-device-pixel-ratio', '1.5' ]
			],
			[
				[ 'max-width', parseInt( settings.side_header_break_point, 10 ) + 'px' ],
				[ 'min-resolution', '144dpi' ]
			],
			[
				[ 'max-width', parseInt( settings.side_header_break_point, 10 ) + 'px' ],
				[ 'min-resolution', '1.5dppx' ]
			]
		] );
	case 'fusion-max-sh-640':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( sideheaderWidth + 640, 10 ) + 'px' ] ] );
	case 'fusion-max-shbp-18':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( parseInt( settings.side_header_break_point, 10 ) - 18, 10 ) + 'px' ] ] );
	case 'fusion-max-shbp-32':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( parseInt( settings.side_header_break_point, 10 ) - 32, 10 ) + 'px' ] ] );
	case 'fusion-min-sh-cbp':
		return fusionGetMediaQuery( [ [ 'min-width', parseInt( sideheaderWidth + parseInt( settings.content_break_point, 10 ), 10 ) + 'px' ] ] );
	case 'fusion-max-sh-965-woo':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( sideheaderWidth + 965, 10 ) + 'px' ] ] );
	case 'fusion-max-sh-900-woo':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( sideheaderWidth + 900, 10 ) + 'px' ] ] );
	case 'fusion-max-cbp':
		return fusionGetMediaQuery( [ [ 'max-width', parseInt( settings.content_break_point, 10 ) + 'px' ] ] );
	case 'fusion-min-768-max-1024':
		return fusionGetMediaQuery( [
			[ 'min-device-width', '768px' ],
			[ 'max-device-width', '1024px' ]
		] );
	case 'fusion-min-768-max-1024-p':
		return fusionGetMediaQuery( [
			[ 'min-device-width', '768px' ],
			[ 'max-device-width', '1024px' ],
			[ 'orientation', 'portrait' ]
		] );
	case 'fusion-min-768-max-1024-l':
		return fusionGetMediaQuery( [
			[ 'min-device-width', '768px' ],
			[ 'max-device-width', '1024px' ],
			[ 'orientation', 'landscape' ]
		] );
	case 'fusion-max-640':
		return fusionGetMediaQuery( [ [ 'max-device-width', '640px' ] ] );
	case 'fusion-max-768':
		return fusionGetMediaQuery( [ [ 'max-width', '782px' ] ] );
	case 'fusion-max-782':
		return fusionGetMediaQuery( [ [ 'max-width', '782px' ] ] );
	default:

		// FIXME: Default not needed, we only use it while developing.
		// This case should be deleted.
		console.info( 'MEDIA QUERY ' + queryID + ' NOT FOUND' );
	}
}

/**
 * Get page option value.
 * This is a port of the fusion_get_page_option() PHP function.
 * We're skipping the 3rd param of the PHP function (post_ID)
 * because in JS we're only dealing with the current post.
 *
 * @param {string} option - ID of page option.
 * @return {string} - Value of page option.
 */
function fusionGetPageOption( option ) {
	if ( option ) {
		if ( 0 !== option.indexOf( 'pyre_' ) ) {
			option = 'pyre_' + option;
		}

		if ( ! _.isUndefined( FusionApp ) && ! _.isUndefined( FusionApp.data.postMeta ) && ! _.isUndefined( FusionApp.data.postMeta[ option ] ) ) {
			return FusionApp.data.postMeta[ option ];
		}
	}
	return '';
}

/**
 * Get theme option or page option.
 * This is a port of the fusion_get_option() PHP function.
 * We're skipping the 3rd param of the PHP function (post_ID)
 * because in JS we're only dealing with the current post.
 *
 * @param {string} themeOption - Theme option ID.
 * @param {string} pageOption - Page option ID.
 * @param {number} postID - Post/Page ID.
 * @return {string} - Theme option or page option value.
 */
function fusionCustomizerGetOption( themeOption, pageOption ) {
	var pageVal  = fusionGetPageOption( pageOption ),
		themeVal = ( 'undefined' !== typeof fusionCustomizerGetSettings()[ themeOption ] ) ? fusionCustomizerGetSettings()[ themeOption ] : '';

	if ( themeOption && pageOption && 'default' !== pageVal && ! _.isEmpty( pageVal ) ) {
		return pageVal;
	}
	return -1 === themeVal.indexOf( '/' ) ? themeVal.toLowerCase() : themeVal;
}

/**
 * Determine if site_width is using % values.
 *
 * @since 2.0
 * @param {string} value - The value.
 * @return {string}
 */
function fusionIsSiteWidthPercent( value ) {
	if ( fusionCustomizerGetSettings().site_width && fusionCustomizerGetSettings().site_width.indexOf( '%' ) ) {
		return value;
	}
	return '';
}

/**
 * Gets the units from a value.
 *
 * @since 2.0
 * @param {string} value - The value.
 * @return {string}
 */
function fusionGetUnitsFromValue( value ) {
	return 'string' === typeof value ? value.replace( /\d+([,.]\d+)?/g, '' ) : value;
}

/**
 * Gets numeric value.
 *
 * @since 2.0
 * @param {string} value - The value.
 * @return {string}
 */
function fusionGetNumericValue( value ) {
	return parseFloat( value );
}

/**
 * Get the horizontal padding for the 100% width.
 * This corresponds to the "$hundredplr_padding" var
 * in previous versions of Avada's dynamic-css PHP implementation.
 *
 * @since 2.0
 * @return {string}
 */
function fusionGetPercentPaddingHorizontal( value, fallback ) {
	value = fusionCustomizerGetOption( 'hundredp_padding', 'hundredp_padding' );
	return ( value ) ? value : fallback;
}

/**
 * Get the horizontal negative margin for 100%.
 * This corresponds to the "$hundredplr_padding_negative_margin" var
 * in previous versions of Avada's dynamic-css PHP implementation.
 *
 * @since 2.0
 * @param {string} value - The value.
 * @param {string} fallback - The value to return as a fallback.
 * @return {string}
 */
function fusionGetPercentPaddingHorizontalNegativeMargin() {
	var padding        = fusionGetPercentPaddingHorizontal(),
		paddingValue   = fusionGetNumericValue( padding ),
		paddingUnit    = fusionGetUnitsFromValue( padding ),
		negativeMargin = '',
		fullWidthMaxWidth;

	negativeMargin = '-' + padding;

	if ( '%' === paddingUnit ) {
		fullWidthMaxWidth = 100 - ( 2 * paddingValue );
		negativeMargin    = paddingValue / fullWidthMaxWidth * 100;
		negativeMargin    = '-' + negativeMargin + '%';
	}
	return negativeMargin;
}

/**
 * Get the horizontal negative margin for 100%, if the site-width is using %.
 *
 * @since 2.0
 * @param {string} value - The value.
 * @param {string} fallback - The value to return as a fallback.
 * @return {string}
 */
function fusionGetPercentPaddingHorizontalNegativeMarginIfSiteWidthPercent( value, fallback ) {
	return ( fusionIsSiteWidthPercent() ) ? fusionGetPercentPaddingHorizontalNegativeMargin() : fallback;
}

function fusionRecalcAllMediaQueries() {
	var prefixes = [
			'',
			'avada-',
			'fb-'
		],
		suffixes = [
			'',
			'-bbpress',
			'-gravity',
			'-ec',
			'-woo',
			'-sliders',
			'-eslider',
			'-not-responsive',
			'-cf7'
		],
		queries  = [
			'max-sh-640',
			'max-1c',
			'max-2c',
			'min-2c-max-3c',
			'min-3c-max-4c',
			'min-4c-max-5c',
			'min-5c-max-6c',
			'max-shbp',
			'max-shbp-18',
			'max-shbp-32',
			'max-sh-shbp',
			'min-768-max-1024-p',
			'min-768-max-1024-l',
			'max-sh-cbp',
			'min-sh-cbp',
			'max-sh-sbp',
			'max-640',
			'min-shbp'
		],
		id,
		el,
		currentQuery,
		newQuery;

	// We only need to run this loop once.
	// Store in window.allFusionMediaIDs to improve performance.
	if ( ! window.allFusionMediaIDs ) {
		window.allFusionMediaIDs = {};

		queries.forEach( function( query ) {
			prefixes.forEach( function( prefix ) {
				suffixes.forEach( function( suffix ) {
					window.allFusionMediaIDs[ prefix + query + suffix + '-css' ] = query;
				} );
			} );
		} );
	}

	for ( id in window.allFusionMediaIDs ) { // eslint-disable-line guard-for-in
		el = window.frames[ 0 ].document.getElementById( id );
		if ( el ) {
			currentQuery = el.getAttribute( 'media' );
			newQuery     = fusionReturnMediaQuery( 'fusion-' + window.allFusionMediaIDs[ id ] );
			if ( newQuery !== currentQuery ) {
				el.setAttribute( 'media', newQuery );
			}
		}
	}
}

function fusionRecalcVisibilityMediaQueries() {
	var mediaQueries = {
			small: fusionGetMediaQuery( [ [ 'max-width', parseInt( fusionCustomizerGetOption( 'visibility_small' ), 10 ) + 'px' ] ] ),
			medium: fusionGetMediaQuery( [
				[ 'min-width', parseInt( fusionCustomizerGetOption( 'visibility_small' ), 10 ) + 'px' ],
				[ 'max-width', parseInt( fusionCustomizerGetOption( 'visibility_medium' ), 10 ) + 'px' ]
			] ),
			large: fusionGetMediaQuery( [ [ 'min-width', parseInt( fusionCustomizerGetOption( 'visibility_medium' ), 10 ) + 'px' ] ] )
		},
		css = {
			small: mediaQueries.small + '{body:not(.fusion-builder-ui-wireframe) .fusion-no-small-visibility{display:none !important;}}',
			medium: mediaQueries.medium + '{body:not(.fusion-builder-ui-wireframe) .fusion-no-medium-visibility{display:none !important;}}',
			large: mediaQueries.large + '{body:not(.fusion-builder-ui-wireframe) .fusion-no-large-visibility{display:none !important;}}'
		};
	if ( jQuery( '#fb-preview' ).contents().find( 'head' ).find( '#css-fb-visibility' ).length ) {
		jQuery( '#fb-preview' ).contents().find( 'head' ).find( '#css-fb-visibility' ).remove();
	}
	jQuery( '#fb-preview' ).contents().find( 'head' ).append( '<style type="text/css" id="css-fb-visibility">' + css.small + css.medium + css.large + '</style>' );
}
