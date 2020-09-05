/* global fusionAllElements */
var FusionPageBuilder = FusionPageBuilder || {};

( function() {

	jQuery( document ).ready( function() {

		// Countdown view
		FusionPageBuilder.fusion_countdown = FusionPageBuilder.ElementView.extend( {

			/**
			 * Runs during render() call.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			onRender: function() {
				var that = this;

				jQuery( window ).on( 'load', function() {
					that.afterPatch();
				} );
			},

			/**
			 * Runs before element is removed.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforeRemove: function() {
				this.beforePatch();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			beforePatch: function() {
				var countdown = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.fusion-countdown-counter-wrapper' ) );

				countdown.stopCountDown();
				countdown.removeData();
			},

			/**
			 * Runs after view DOM is patched.
			 *
			 * @since 2.0
			 * @return {void}
			 */
			afterPatch: function() {
				var countdown = jQuery( '#fb-preview' )[ 0 ].contentWindow.jQuery( this.$el.find( '.fusion-countdown-counter-wrapper' ) );

				setTimeout( function() {
					countdown.stopCountDown();
					countdown.fusion_countdown();
				}, 300 );
			},

			/**
			 * Modify template attributes.
			 *
			 * @since 2.0
			 * @param {Object} atts - The attributes.
			 * @return {Object}
			 */
			filterTemplateAtts: function( atts ) {
				var wrapperAttributes      = {},
					counterAttributes      = {},
					countdownShortcodeLink = {},
					headingAttr            = {},
					subHeadingAttr         = {},
					dashhtml               = '',
					styles                 = '',
					headingText            = '',
					subheadingText         = '',
					linkText               = '',
					elementContent         = '';

				// Validate values.
				this.validateValues( atts.values );

				// Create attribute objects
				wrapperAttributes      = this.buildWrapperAtts( atts.values );
				counterAttributes      = this.buildCounterAtts( atts.values, atts.extras );
				countdownShortcodeLink = this.buildLinkAtts( atts.values, atts.extras );
				dashhtml               = this.buildDashHtml( atts.values, atts.extras );
				styles                 = this.buildStyles( atts.values );
				headingAttr            = this.buildHeadingAttr( atts.values );
				subHeadingAttr         = this.buildSubHeadingAttr( atts.values );
				headingText            = atts.values.heading_text;
				subheadingText         = atts.values.subheading_text;
				linkText               = atts.values.link_text;
				elementContent         = atts.values.element_content;

				// Reset atts.
				atts = {};

				// Build attributes.
				atts.wrapperAttributes      = wrapperAttributes;
				atts.counterAttributes      = counterAttributes;
				atts.countdownShortcodeLink = countdownShortcodeLink;
				atts.dashhtml               = dashhtml;
				atts.styles                 = styles;
				atts.headingAttr            = headingAttr;
				atts.subHeadingAttr         = subHeadingAttr;
				atts.heading_text           = headingText;
				atts.subheading_text        = subheadingText;
				atts.link_text              = linkText;
				atts.element_content        = elementContent;

				// Any extras that need passed on.
				atts.cid = this.model.get( 'cid' );

				return atts;
			},

			/**
			 * Modify the values.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {void}
			 */
			validateValues: function( values ) {
				values.border_radius = _.fusionValidateAttrValue( values.border_radius, 'px' );

				if ( 'default' === values.link_target ) {
					values.link_target = fusionAllElements.fusion_countdown.link_target;
				}
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {Object}
			 */
			buildWrapperAtts: function( values ) {
				var wrapperAttributes = {
					class: 'countdown-shortcode fusion-countdown fusion-countdown-cid' + this.model.get( 'cid' )
				};

				wrapperAttributes = _.fusionVisibilityAtts( values.hide_on_mobile, wrapperAttributes );

				if ( ! values.background_image && ( ! values.background_color || 'transparent' === values.background_color ) ) {
					wrapperAttributes[ 'class' ] += ' fusion-no-bg';
				}

				if ( values[ 'class' ] ) {
					wrapperAttributes[ 'class' ] += ' ' + values[ 'class' ];
				}

				if ( values.id ) {
					wrapperAttributes.id = values.id;
				}

				return wrapperAttributes;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @param {Object} extras - Extra params.
			 * @return {Object}
			 */
			buildCounterAtts: function( values, extras ) {
				var counterAttributes = {
						class: 'fusion-countdown-counter-wrapper countdown-shortcode-counter-wrapper',
						id: 'fusion-countdown-cid' + this.model.get( 'cid' )
					},
					s,
					date,
					month;

				if ( 'site_time' === values.timezone ) {
					counterAttributes[ 'data-gmt-offset' ] = extras.gmt_offset;
				}
				function pad( num, size ) {
					s = '000000000' + num;
					return s.substr( s.length - size );
				}
				if ( values.countdown_end ) {
					date  = new Date( values.countdown_end );
					month = pad( date.getMonth() + 1, 2 );
					counterAttributes[ 'data-timer' ] = date.getFullYear() + '-' + month + '-' + date.getDate() + '-' + date.getHours() + '-' + date.getMinutes() + '-' + date.getSeconds();
				}

				counterAttributes[ 'data-omit-weeks' ] = ( 'yes' === values.show_weeks ) ? '0' : '1';

				return counterAttributes;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {Object}
			 */
			buildLinkAtts: function( values ) {
				var countdownShortcodeLink = {
					class: 'fusion-countdown-link',
					target: values.link_target,
					href: values.link_url
				};

				if ( '_blank' === values.link_target ) {
					countdownShortcodeLink.rel = 'noopener noreferrer';
				}

				return countdownShortcodeLink;
			},

			/**
			 * Builds the HTML.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @param {Object} extras - Extra args.
			 * @return {string}
			 */
			buildDashHtml: function( values, extras ) {
				var dashClass = '',
					dashhtml  = '',
					dashes    = [
						{
							show: values.show_weeks,
							class: 'weeks',
							shortname: extras.weeks_text,
							longname: extras.weeks_text
						},
						{
							show: 'yes',
							class: 'days',
							shortname: extras.days_text,
							longname: extras.days_text
						},
						{
							show: 'yes',
							class: 'hours',
							shortname: extras.hrs_text,
							longname: extras.hours_text
						},
						{
							show: 'yes',
							class: 'minutes',
							shortname: extras.min_text,
							longname: extras.minutes_text
						},
						{
							show: 'yes',
							class: 'seconds',
							shortname: extras.sec_text,
							longname: extras.seconds_text
						}
					];

				if ( ! values.counter_box_color || 'transparent' === values.counter_box_color ) {
					dashClass = ' fusion-no-bg';
				}

				jQuery.each( dashes, function( index, dash ) {
					if ( 'yes' === dash.show ) {
						dashhtml += '<div class="fusion-dash-wrapper ' + dashClass + '">';
						dashhtml += '<div class="fusion-dash fusion-dash-' + dash[ 'class' ] + '">';
						if ( 'days' === dash[ 'class' ] ) {
							dashhtml += '<div class="fusion-thousand-digit fusion-digit">0</div>';
						}
						if ( 'weeks' === dash[ 'class' ] || 'days' === dash[ 'class' ] ) {
							dashhtml += '<div class="fusion-hundred-digit fusion-digit">0</div>';
						}
						dashhtml += '<div class="fusion-digit">0</div><div class="fusion-digit">0</div>';
						dashhtml += '<div class="fusion-dash-title">' + dash[ values.dash_titles + 'name' ] + '</div>';
						dashhtml += '</div></div>';
					}
				} );

				return dashhtml;
			},

			/**
			 * Builds styles.
			 *
			 * @since 2.0
			 * @param {Object} values - The values.
			 * @return {string}
			 */
			buildStyles: function( values ) {
				var styles = '',
					cid = this.model.get( 'cid' );

				if ( values.background_image ) {
					styles += '.fusion-countdown-cid' + cid + ' {';
					styles += 'background:url(' + values.background_image + ') ' + values.background_position + ' ' + values.background_repeat + ' ' + values.background_color + ';';

					if ( 'no-repeat' === values.background_repeat ) {
						styles += '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
					}
					styles += '}';

				} else if ( values.background_color ) {
					styles += '.fusion-countdown-cid' + cid + ' {background-color:' + values.background_color + ';}';
				}

				if ( values.border_radius ) {
					styles += '.fusion-countdown-cid' + cid + ', .fusion-countdown-cid' + cid + ' .fusion-dash {border-radius:' + values.border_radius + ';}';
				}

				if ( values.heading_text_color ) {
					styles += '.fusion-countdown-cid' + cid + ' .fusion-countdown-heading {color:' + values.heading_text_color + ';}';
				}

				if ( values.subheading_text_color ) {
					styles += '.fusion-countdown-cid' + cid + ' .fusion-countdown-subheading {color:' + values.subheading_text_color + ';}';
				}

				if ( values.counter_text_color ) {
					styles += '.fusion-countdown-cid' + cid + ' .fusion-countdown-counter-wrapper {color:' + values.counter_text_color + ';}';
				}

				if ( values.counter_box_color ) {
					styles += '.fusion-countdown-cid' + cid + ' .fusion-dash {background-color:' + values.counter_box_color + ';}';
				}

				if ( values.link_text_color ) {
					styles += '.fusion-countdown-cid' + cid + ' .fusion-countdown-link {color:' + values.link_text_color + ';}';
				}

				return styles;
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @return {Object}
			 */
			buildHeadingAttr: function() {
				var self = this;

				return _.fusionInlineEditor( {
					cid: self.model.get( 'cid' ),
					param: 'heading_text',
					'disable-return': true,
					'disable-extra-spaces': true,
					toolbar: false
				}, { class: 'fusion-countdown-heading' } );
			},

			/**
			 * Builds attributes.
			 *
			 * @since 2.0
			 * @return {Object}
			 */
			buildSubHeadingAttr: function() {
				var self = this;

				return _.fusionInlineEditor( {
					cid: self.model.get( 'cid' ),
					param: 'subheading_text',
					'disable-return': true,
					'disable-extra-spaces': true,
					toolbar: false
				}, { class: 'fusion-countdown-subheading' } );
			}
		} );
	} );
}( jQuery ) );
