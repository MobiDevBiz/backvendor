/*
* jQuery timepicker addon
* By: Trent Richardson [http://trentrichardson.com]
* Version 0.7.3
* Last Modified: 11/9/2010
* 
* Copyright 2010 Trent Richardson
* Dual licensed under the MIT and GPL licenses.
* http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
* http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
* 
* HERES THE CSS:
* .ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }
* .ui-timepicker-div dl{ text-align: left; }
* .ui-timepicker-div dl dt{ height: 25px; }
* .ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
* .ui-timepicker-div td { font-size: 90%; }
*/

(function($) {
	function Timepicker(singleton) {
		if(typeof(singleton) === 'boolean' && singleton === true) {
			this.regional = []; // Available regional settings, indexed by language code
			this.regional[''] = { // Default regional settings
				currentText: 'Now',
				ampm: false,
				timeFormat: 'hh:mm tt',
				timeOnlyTitle: 'Choose Time',
				timeText: 'Time',
				hourText: 'Hour',
				minuteText: 'Minute',
				secondText: 'Second'
			};
			this.defaults = { // Global defaults for all the datetime picker instances
				showButtonPanel: true,
				timeOnly: false,
				showHour: true,
				showMinute: true,
				showSecond: false,
				showTime: true,
				stepHour: 0.05,
				stepMinute: 0.05,
				stepSecond: 0.05,
				hour: 0,
				minute: 0,
				second: 0,
				hourMin: 0,
				minuteMin: 0,
				secondMin: 0,
				hourMax: 23,
				minuteMax: 59,
				secondMax: 59,
				hourGrid: 0,
				minuteGrid: 0,
				secondGrid: 0,
				alwaysSetTime: true
			};
			$.extend(this.defaults, this.regional['']);
		} else {
			this.defaults = $.extend({}, $.timepicker.defaults);
		}

	}

	Timepicker.prototype = {
		$input: null,
		$altInput: null,
		$timeObj: null,
		inst: null,
		hour_slider: null,
		minute_slider: null,
		second_slider: null,
		hour: 0,
		minute: 0,
		second: 0,
		ampm: '',
		formattedDate: '',
		formattedTime: '',
		formattedDateTime: '',

		//########################################################################
		// add our sliders to the calendar
		//########################################################################
		addTimePicker: function(dp_inst) {
			var tp_inst = this;
			var currDT;
			if ((this.$altInput) && this.$altInput !== null)
			{
				currDT = this.$input.val() + ' ' + this.$altInput.val();
			}
			else
			{
				currDT = this.$input.val();
			}
			var regstr = this.defaults.timeFormat.toString()
				.replace(/h{1,2}/ig, '(\\d?\\d)')
				.replace(/m{1,2}/ig, '(\\d?\\d)')
				.replace(/s{1,2}/ig, '(\\d?\\d)')
				.replace(/t{1,2}/ig, '(am|pm|a|p)?')
				.replace(/\s/g, '\\s?') + '$';

			if (!this.defaults.timeOnly) {
				//the time should come after x number of characters and a space.  x = at least the length of text specified by the date format
				var dp_dateFormat = $.datepicker._get(dp_inst, 'dateFormat');
				regstr = '.{' + dp_dateFormat.length + ',}\\s+' + regstr;
			}

			var order = this.getFormatPositions();
			var treg = currDT.match(new RegExp(regstr, 'i'));

			if (treg) {
				if (order.t !== -1) {
					this.ampm = ((treg[order.t] === undefined || treg[order.t].length === 0) ? '' : (treg[order.t].charAt(0).toUpperCase() == 'A') ? 'AM' : 'PM').toUpperCase();
				}

				if (order.h !== -1) {
					if (this.ampm == 'AM' && treg[order.h] == '12') {
						// 12am = 0 hour
						this.hour = 0;
					} else if (this.ampm == 'PM' && treg[order.h] != '12') {
						// 12pm = 12 hour, any other pm = hour + 12
						this.hour = (parseFloat(treg[order.h]) + 12).toFixed(0);
					} else {
						this.hour = treg[order.h];
					}
				}

				if (order.m !== -1) {
					this.minute = treg[order.m];
				}

				if (order.s !== -1) {
					this.second = treg[order.s];
				}
			}

			tp_inst.timeDefined = (treg) ? true : false;

			if (typeof(dp_inst.stay_open) !== 'boolean' || dp_inst.stay_open === false) {
				// wait for datepicker to create itself.. 60% of the time it works every time..
				setTimeout(function() {
					tp_inst.injectTimePicker(dp_inst, tp_inst);
				}, 10);
			} else {
				tp_inst.injectTimePicker(dp_inst, tp_inst);
			}

		},

		//########################################################################
		// figure out position of time elements.. cause js cant do named captures
		//########################################################################
		getFormatPositions: function() {
			var finds = this.defaults.timeFormat.toLowerCase().match(/(h{1,2}|m{1,2}|s{1,2}|t{1,2})/g);
			var orders = { h: -1, m: -1, s: -1, t: -1 };

			if (finds) {
				for (var i = 0; i < finds.length; i++) {
					if (orders[finds[i].toString().charAt(0)] == -1) {
						orders[finds[i].toString().charAt(0)] = i + 1;
					}
				}
			}

			return orders;
		},

		//########################################################################
		// generate and inject html for timepicker into ui datepicker
		//########################################################################
		injectTimePicker: function(dp_inst, tp_inst)
		{
			var $dp = dp_inst.dpDiv;
			var opts = tp_inst.defaults;

			// Added by Peter Medeiros:
			// - Figure out what the hour/minute/second max should be based on the step values.
			// - Example: if stepMinute is 15, then minMax is 45.
			var hourMax = opts.hourMax - (opts.hourMax % opts.stepHour);
			var minMax = opts.minuteMax - (opts.minuteMax % opts.stepMinute);
			var secMax = opts.secondMax - (opts.secondMax % opts.stepSecond);

			// Prevent displaying twice
			if ($dp.find("div#ui-timepicker-div-" + dp_inst.id).length === 0){
				var noDisplay = ' style="display:none;"';
				var html =
					'<div class="ui-timepicker-div" id="ui-timepicker-div-' + dp_inst.id + '"><dl>' +
						'<dt class="ui_tpicker_time_label" id="ui_tpicker_time_label_' + dp_inst.id + '"' + ((opts.showTime) ? '' : noDisplay) + '>' + opts.timeText + '</dt>' +
						'<dd class="ui_tpicker_time" id="ui_tpicker_time_' + dp_inst.id + '"' + ((opts.showTime) ? '' : noDisplay) + '></dd>' +
						'<dt class="ui_tpicker_hour_label" id="ui_tpicker_hour_label_' + dp_inst.id + '"' + ((opts.showHour) ? '' : noDisplay) + '>' + opts.hourText + '</dt>';
				var hourGridSize = 0;
				var minuteGridSize = 0;
				var secondGridSize = 0;
				var size = 0;

				if (opts.showHour && opts.hourGrid > 0)
				{
					html += '<dd class="ui_tpicker_hour">' +
							'<div id="ui_tpicker_hour_' + dp_inst.id + '"' + ((opts.showHour) ? '' : noDisplay) + '></div>' +
							'<div style="padding-left: 1px"><table><tr>';

					for (var h = 0; h <= hourMax; h += opts.hourGrid)
					{
						hourGridSize++;

						var tmph = h;
						if (opts.ampm && h > 12){
							tmph = h - 12;
						}
						else{
							tmph = h;
						}
						
						if (tmph < 10){
							tmph = '0' + tmph;
						}
						
						if (opts.ampm)
						{
							if (h === 0){
								tmph = 12 + 'a';
							}
							else if (h < 12){
								tmph += 'a';
							}
							else{
								tmph += 'p';
							}
						}
						html += '<td>' + tmph + '</td>';
					}

					html += '</tr></table></div>' +
							'</dd>';
				}
				else
				{
					html += '<dd class="ui_tpicker_hour" id="ui_tpicker_hour_' + dp_inst.id + '"' + ((opts.showHour) ? '' : noDisplay) + '></dd>';
				}

				html += '<dt class="ui_tpicker_minute_label" id="ui_tpicker_minute_label_' + dp_inst.id + '"' + ((opts.showMinute) ? '' : noDisplay) + '>' + opts.minuteText + '</dt>';

				if (opts.showMinute && opts.minuteGrid > 0)
				{
					html += '<dd class="ui_tpicker_minute">' +
							'<div id="ui_tpicker_minute_' + dp_inst.id + '"' + ((opts.showMinute) ? '' : noDisplay) + '></div>' +
							'<div style="padding-left: 1px"><table><tr>';

					for (var m = 0; m <= minMax; m += opts.minuteGrid)
					{
						minuteGridSize++;
						html += '<td>' + ((m < 10) ? '0' : '') + m + '</td>';
					}

					html += '</tr></table></div>' +
							'</dd>';
				}
				else
				{
					html += '<dd class="ui_tpicker_minute" id="ui_tpicker_minute_' + dp_inst.id + '"' + ((opts.showMinute) ? '' : noDisplay) + '></dd>';
				}

				html += '<dt class="ui_tpicker_second_label" id="ui_tpicker_second_label_' + dp_inst.id + '"' + ((opts.showSecond) ? '' : noDisplay) + '>' + opts.secondText + '</dt>';

				if (opts.showSecond && opts.secondGrid > 0)
				{
					html += '<dd class="ui_tpicker_second">' +
							'<div id="ui_tpicker_second_' + dp_inst.id + '"' + ((opts.showSecond) ? '' : noDisplay) + '></div>' +
							'<div style="padding-left: 1px"><table><tr>';

					for (var s = 0; s <= secMax; s += opts.secondGrid)
					{
						secondGridSize++;
						html += '<td>' + ((s < 10) ? '0' : '') + s + '</td>';
					}

					html += '</tr></table></div>' +
							'</dd>';
				}
				else
				{
					html += '<dd class="ui_tpicker_second" id="ui_tpicker_second_' + dp_inst.id + '"' + ((opts.showSecond) ? '' : noDisplay) + '></dd>';
				}

				html += '</dl></div>';
				$tp = $(html);

				// if we only want time picker...
				if (opts.timeOnly === true)
				{
					$tp.prepend(
						'<div class="ui-widget-header ui-helper-clearfix ui-corner-all">' +
							'<div class="ui-datepicker-title">' + opts.timeOnlyTitle + '</div>' +
						'</div>');
					$dp.find('.ui-datepicker-header, .ui-datepicker-calendar').hide();
				}

				tp_inst.hour_slider = $tp.find('#ui_tpicker_hour_' + dp_inst.id).slider({
					orientation: "horizontal",
					value: tp_inst.hour,
					min: opts.hourMin,
					max: hourMax,
					step: opts.stepHour,
					slide: function(event, ui)
					{
						tp_inst.hour_slider.slider("option", "value", ui.value);
						tp_inst.onTimeChange(dp_inst, tp_inst);
					}
				});

				// Updated by Peter Medeiros:
				// - Pass in Event and UI instance into slide function
				tp_inst.minute_slider = $tp.find('#ui_tpicker_minute_' + dp_inst.id).slider({
					orientation: "horizontal",
					value: tp_inst.minute,
					min: opts.minuteMin,
					max: minMax,
					step: opts.stepMinute,
					slide: function(event, ui)
					{
						// update the global minute slider instance value with the current slider value
						tp_inst.minute_slider.slider("option", "value", ui.value);
						tp_inst.onTimeChange(dp_inst, tp_inst);
					}
				});

				tp_inst.second_slider = $tp.find('#ui_tpicker_second_' + dp_inst.id).slider({
					orientation: "horizontal",
					value: tp_inst.second,
					min: opts.secondMin,
					max: secMax,
					step: opts.stepSecond,
					slide: function(event, ui)
					{
						tp_inst.second_slider.slider("option", "value", ui.value);
						tp_inst.onTimeChange(dp_inst, tp_inst);
					}
				});

				// Add grid functionality
				if (opts.showHour && opts.hourGrid > 0)
				{
					size = 100 * hourGridSize * opts.hourGrid / (hourMax - opts.hourMin);

					$tp.find(".ui_tpicker_hour table").css({
							'width': size + "%",
							'margin-left': (size / (-2 * hourGridSize)) + "%",
							'border-collapse': 'collapse'
						});
					$tp.find(".ui_tpicker_hour td").each(
							function(index)
							{
								$(this).click(
										function()
										{
											var h = $(this).html();
											if (opts.ampm)
											{
												var ap = h.substring(2).toLowerCase();
												var aph = parseInt(h.substring(0, 2), 10);

												if (ap == 'a')
												{
													if (aph == 12){
														h = 0;
													}
													else{ 
														h = aph;
													}
												} else
												{
													if (aph == 12){
														h = 12;
													}
													else{ 
														h = aph + 12;
													}
												}
											}
											tp_inst.hour_slider.slider("option", "value", h);
											tp_inst.onTimeChange(dp_inst, tp_inst);
										}
									);
								$(this).css({
										'cursor': "pointer",
										'width': (100 / hourGridSize) + '%',
										'text-align': 'center',
										'overflow': 'hidden'
									});
							}
						);
				}

				if (opts.showMinute && opts.minuteGrid > 0)
				{
					size = 100 * minuteGridSize * opts.minuteGrid / (minMax - opts.minuteMin);

					$tp.find(".ui_tpicker_minute table").css({
							'width': size + "%",
							'margin-left': (size / (-2 * minuteGridSize)) + "%",
							'border-collapse': 'collapse'
						});
					$tp.find(".ui_tpicker_minute td").each(
							function(index)
							{
								$(this).click(
										function()
										{
											tp_inst.minute_slider.slider("option", "value", $(this).html());
											tp_inst.onTimeChange(dp_inst, tp_inst);
										}
									);
								$(this).css({
										'cursor': "pointer",
										'width': (100 / minuteGridSize) + '%',
										'text-align': 'center',
										'overflow': 'hidden'
									});
							}
						);
				}

				if (opts.showSecond && opts.secondGrid > 0)
				{
					size = 100 * secondGridSize * opts.secondGrid / (secMax - opts.secondMin);

					$tp.find(".ui_tpicker_second table").css({
							'width': size + "%",
							'margin-left': (size / (-2 * secondGridSize)) + "%",
							'border-collapse': 'collapse'
						});
					$tp.find(".ui_tpicker_second td").each(
							function(index)
							{
								$(this).click(
										function()
										{
											tp_inst.second_slider.slider("option", "value", $(this).html());
											tp_inst.onTimeChange(dp_inst, tp_inst);
										}
									);
								$(this).css({
										'cursor': "pointer",
										'width': (100 / secondGridSize) + '%',
										'text-align': 'center',
										'overflow': 'hidden'
									});
							}
						);
				}

				$dp.find('.ui-datepicker-calendar').after($tp);

				tp_inst.$timeObj = $('#ui_tpicker_time_' + dp_inst.id);

				if (dp_inst !== null)
				{
					var timeDefined = tp_inst.timeDefined;
					tp_inst.onTimeChange(dp_inst, tp_inst, true);
					tp_inst.timeDefined = timeDefined;
				}
			}
		},

		//########################################################################
		// when a slider moves..
		// on time change is also called when the time is updated in the text field
		//########################################################################
		onTimeChange: function(dp_inst, tp_inst, force) {
			var hour   = (tp_inst.hour_slider)? tp_inst.hour_slider.slider('value') : tp_inst.hour;
			var minute = (tp_inst.minute_slider)? tp_inst.minute_slider.slider('value') : tp_inst.minute;
			var second = (tp_inst.second_slider)? tp_inst.second_slider.slider('value') : tp_inst.second;
			var ampm = (hour < 11.5) ? 'AM' : 'PM';
			hour = (hour >= 11.5 && hour < 12) ? 12 : hour;
			var hasChanged = false;

			// If the update was done in the input field, this field should not be updated.
			// If the update was done using the sliders, update the input field.
			if (tp_inst.hour != hour || tp_inst.minute != minute || tp_inst.second != second || (tp_inst.ampm.length > 0 && tp_inst.ampm != ampm) || (force !== undefined && force === true)) {
				hasChanged = true;
			}

			tp_inst.hour = parseFloat(hour).toFixed(0);
			tp_inst.minute = parseFloat(minute).toFixed(0);
			tp_inst.second = parseFloat(second).toFixed(0);
			tp_inst.ampm = ampm;

			tp_inst.formatTime(tp_inst);
			if(tp_inst.$timeObj){
				tp_inst.$timeObj.text(tp_inst.formattedTime);
			}
			
			if (hasChanged) {
				tp_inst.updateDateTime(dp_inst, tp_inst);
				tp_inst.timeDefined = true;
			}
		},

		//########################################################################
		// format the time all pretty...
		//########################################################################
		formatTime: function(tp_inst) {
			var tmptime = tp_inst.defaults.timeFormat.toString();
			var hour12 = ((tp_inst.ampm == 'AM') ? (tp_inst.hour) : (tp_inst.hour % 12));
			hour12 = (Number(hour12) === 0) ? 12 : hour12;

			if (tp_inst.defaults.ampm === true) {
				tmptime = tmptime.toString()
					.replace(/hh/g, ((hour12 < 10) ? '0' : '') + hour12)
					.replace(/h/g, hour12)
					.replace(/mm/g, ((tp_inst.minute < 10) ? '0' : '') + tp_inst.minute)
					.replace(/m/g, tp_inst.minute)
					.replace(/ss/g, ((tp_inst.second < 10) ? '0' : '') + tp_inst.second)
					.replace(/s/g, tp_inst.second)
					.replace(/TT/g, tp_inst.ampm.toUpperCase())
					.replace(/tt/g, tp_inst.ampm.toLowerCase())
					.replace(/T/g, tp_inst.ampm.charAt(0).toUpperCase())
					.replace(/t/g, tp_inst.ampm.charAt(0).toLowerCase());

			} else {
				tmptime = tmptime.toString()
					.replace(/hh/g, ((tp_inst.hour < 10) ? '0' : '') + tp_inst.hour)
					.replace(/h/g, tp_inst.hour)
					.replace(/mm/g, ((tp_inst.minute < 10) ? '0' : '') + tp_inst.minute)
					.replace(/m/g, tp_inst.minute)
					.replace(/ss/g, ((tp_inst.second < 10) ? '0' : '') + tp_inst.second)
					.replace(/s/g, tp_inst.second);
				tmptime = $.trim(tmptime.replace(/t/gi, ''));
			}

			tp_inst.formattedTime = tmptime;
			return tp_inst.formattedTime;
		},

		//########################################################################
		// update our input with the new date time..
		//########################################################################
		updateDateTime: function(dp_inst, tp_inst) {
			var dt = new Date(dp_inst.selectedYear, dp_inst.selectedMonth, dp_inst.selectedDay);
			var dateFmt = $.datepicker._get(dp_inst, 'dateFormat');
			var formatCfg = $.datepicker._getFormatConfig(dp_inst);
			this.formattedDate = $.datepicker.formatDate(dateFmt, (dt === null ? new Date() : dt), formatCfg);
			var formattedDateTime = this.formattedDate;
			var timeAvailable = ((dt !== null && tp_inst.timeDefined) !== true)? false : true;

			if(this.defaults.timeOnly === true){
				formattedDateTime = this.formattedTime;
			}
			else if (this.defaults.timeOnly !== true && (this.defaults.alwaysSetTime || timeAvailable)) {
				if ((this.$altInput) && this.$altInput !== null)
				{
					this.$altInput.val(this.formattedTime);
				}
				else{
					formattedDateTime += ' ' + this.formattedTime;
				}
			}
			
			this.formattedDateTime = formattedDateTime;
			if(!dp_inst.inline && this.$input){
				this.$input.val(formattedDateTime);
				this.$input.trigger("change");
			}
		},
		
		setDefaults: function(settings) {
			extendRemove(this.defaults, settings || {});
			return this;
		}
	};

	//########################################################################
	// extend timepicker to datepicker
	//########################################################################		
	jQuery.fn.datetimepicker = function(o) {
		var opts = (o === undefined ? {} : o);
		var input = $(this);
		
		if(typeof(o) == 'string')
		{
			if(o == 'setDate'){
				return input.datepicker(o, arguments[1]);
			}
			if(o == 'options' && typeof(arguments[1]) == 'string'){
				return input.datepicker(o, arguments[1], arguments[2]);
			}
			if(o == 'dialog'){
				return input.datepicker(o, arguments[1], arguments[2], arguments[3], arguments[4]);
			}
			return input.datepicker(o);
		}
		
		var tp = new Timepicker();
		var inlineSettings = {};

		for (var attrName in tp.defaults) {
			var attrValue = input.attr('time:' + attrName);
			if (attrValue) {
				try {
					inlineSettings[attrName] = eval(attrValue);
				} catch (err) {
					inlineSettings[attrName] = attrValue;
				}
			}
		}
		tp.defaults = $.extend(tp.defaults, inlineSettings);

		var beforeShowFunc = function(input, inst) {
			tp.hour = tp.defaults.hour;
			tp.minute = tp.defaults.minute;
			tp.second = tp.defaults.second;
			tp.ampm = '';
			tp.$input = $(input);
			if(opts.altField !== undefined && opts.altField != ''){
				tp.$altInput = $($.datepicker._get(inst, 'altField'));
			}
			tp.inst = inst;
			tp.addTimePicker(inst);
			if ($.isFunction(opts.beforeShow)) {
				opts.beforeShow(input, inst);
			}
		};

		var onChangeMonthYearFunc = function(year, month, inst) {
			// Update the time as well : this prevents the time from disappearing from the input field.
			tp.updateDateTime(inst, tp);
			if ($.isFunction(opts.onChangeMonthYear)) {
				opts.onChangeMonthYear(year, month, inst);
			}
		};

		var onCloseFunc = function(dateText, inst) {
			if(tp.timeDefined === true && input.val() != '') {
				tp.updateDateTime(inst, tp);
			}
			if ($.isFunction(opts.onClose)) {
				opts.onClose(dateText, inst);
			}
		};

		// make the alt field trigger the picker if its set
		if ((opts.altField) && opts.altField !== null){
			var me = $(opts.altField);

			me.css({ 'cursor': 'pointer' });
			me.focus(function(){
				input.trigger("focus");
			});
		}
		
		tp.defaults = $.extend({}, tp.defaults, opts, {
			beforeShow: beforeShowFunc,
			onChangeMonthYear: onChangeMonthYearFunc,
			onClose: onCloseFunc,
			timepicker: tp // add timepicker as a property of datepicker: $.datepicker._get(dp_inst, 'timepicker');
		});

		return input.datepicker(tp.defaults);

	};

	//########################################################################
	// shorthand just to use timepicker..
	//########################################################################
	jQuery.fn.timepicker = function(opts) {
		if(typeof opts == 'object'){
			opts = $.extend(opts, { timeOnly: true });
		}
			
		return $(this).datetimepicker(opts, arguments[1], arguments[2], arguments[3], arguments[4]);
	};

	//########################################################################
	// the bad hack :/ override datepicker so it doesnt close on select
	// inspired: http://stackoverflow.com/questions/1252512/jquery-datepicker-prevent-closing-picker-when-clicking-a-date/1762378#1762378
	//########################################################################
	$.datepicker._base_selectDate = $.datepicker._selectDate;
	$.datepicker._selectDate = function (id, dateStr) {
		var target = $(id);
		var inst = this._getInst(target[0]);
		var tp_inst = $.datepicker._get(inst, 'timepicker');
		
		if(tp_inst){
			inst.inline = true;
			inst.stay_open = true;
			$.datepicker._base_selectDate(id, dateStr);
			inst.stay_open = false;
			inst.inline = false;
			this._notifyChange(inst);
			this._updateDatepicker(inst);
		}
		else{
			$.datepicker._base_selectDate(id, dateStr);
		}
	};

	//#############################################################################################
	// second bad hack :/ override datepicker so it triggers an event when changing the input field
	// and does not redraw the datepicker on every selectDate event
	//#############################################################################################
	$.datepicker._base_updateDatepicker = $.datepicker._updateDatepicker;
	$.datepicker._updateDatepicker = function(inst) {
		if (typeof(inst.stay_open) !== 'boolean' || inst.stay_open === false) {
			this._base_updateDatepicker(inst);
			// Reload the time control when changing something in the input text field.
			this._beforeShow(inst.input, inst);
		}
	};

	$.datepicker._beforeShow = function(input, inst) {
		var beforeShow = this._get(inst, 'beforeShow');
		if (beforeShow) {
			inst.stay_open = true;
			beforeShow.apply((inst.input ? inst.input[0] : null), [inst.input, inst]);
			inst.stay_open = false;
		}
	};

	//#######################################################################################
	// third bad hack :/ override datepicker so it allows spaces and colan in the input field
	//#######################################################################################
	$.datepicker._base_doKeyPress = $.datepicker._doKeyPress;
	$.datepicker._doKeyPress = function(event) {
		var inst = $.datepicker._getInst(event.target);
		var tp_inst = $.datepicker._get(inst, 'timepicker');

		if(tp_inst){
			if ($.datepicker._get(inst, 'constrainInput')) {
				var dateChars = $.datepicker._possibleChars($.datepicker._get(inst, 'dateFormat'));
				var chr = String.fromCharCode(event.charCode === undefined ? event.keyCode : event.charCode);
				var chrl = chr.toLowerCase();
				// keyCode == 58 => ":"
				// keyCode == 32 => " "
				return event.ctrlKey || (chr < ' ' || !dateChars || dateChars.indexOf(chr) > -1 || event.keyCode == 58 || event.keyCode == 32 || chr == ':' || chr == ' ' || chrl == 'a' || chrl == 'p' || chrl == 'm');
			}
		}
		else{
			return $.datepicker._base_doKeyPress(event);
		}

	};
	
	//#######################################################################################
	// Override key up event to sync manual input changes.
	//#######################################################################################
	$.datepicker._base_doKeyUp = $.datepicker._doKeyUp;
	$.datepicker._doKeyUp = function (event) {
	
		var inst = $.datepicker._getInst(event.target);
		var tp_inst = $.datepicker._get(inst, 'timepicker');
		
		if (tp_inst !== null) {
			if (tp_inst.defaults.timeOnly && (inst.input.val() != inst.lastVal)) {
				try {
					$.datepicker._updateDatepicker(inst);
				}
				catch (err) {
					$.datepicker.log(err);
				}
			}
		}
		
		return $.datepicker._base_doKeyUp(event);
	};
	
	//#######################################################################################
	// override "Today" button to also grab the time.
	//#######################################################################################
	$.datepicker._base_gotoToday = $.datepicker._gotoToday;
	$.datepicker._gotoToday = function(id) {
		$.datepicker._base_gotoToday(id);
		
		var target = $(id);
		var dp_inst = this._getInst(target[0]);

		this._setTime(dp_inst, new Date());

	};

	//#######################################################################################
	// Create our on set time function
	//#######################################################################################
	$.datepicker._setTime = function(inst, date) {
	
		var tp_inst = $.datepicker._get(inst, 'timepicker');
		
		if(tp_inst){
			var hour = date.getHours();
			var minute = date.getMinutes();
			var second = date.getSeconds();

			//check if within min/max times..
			if( (hour < tp_inst.defaults.hourMin || hour > tp_inst.defaults.hourMax) || (minute < tp_inst.defaults.minuteMin || minute > tp_inst.defaults.minuteMax) || (second < tp_inst.defaults.secondMin || second > tp_inst.defaults.secondMax) ){					
				hour = tp_inst.defaults.hourMin;
				minute = tp_inst.defaults.minuteMin;
				second = tp_inst.defaults.secondMin;	
			}

			if(tp_inst.hour_slider && tp_inst.minute_slider && tp_inst.second_slider){
				tp_inst.hour_slider.slider('value', hour );
				tp_inst.minute_slider.slider('value', minute );
				tp_inst.second_slider.slider('value', second );
			}
			else{
				tp_inst.hour = hour;
				tp_inst.minute = minute;
				tp_inst.second = second;
			}
			
			tp_inst.onTimeChange(inst, tp_inst, true);
		}
	};

	//#######################################################################################
	// override getDate() to allow getting time too within date object
	//#######################################################################################
		$.datepicker._base_setDate = $.datepicker._setDate;
		$.datepicker._setDate = function(inst, date, noChange) {
			var tp_inst = $.datepicker._get(inst, 'timepicker');
			var tp_date = new Date(date.getYear(), date.getMonth(), date.getDay(), date.getHours(), date.getMinutes(), date.getSeconds());
			
			$.datepicker._updateDatepicker(inst);
			
			$.datepicker._base_setDate(inst, date, noChange);
			
			if(tp_inst){
				this._setTime(inst, tp_date);
			}
	
		};
	
	//#######################################################################################
	// override getDate() to allow getting time too within date object
	//#######################################################################################
	$.datepicker._base_getDate = $.datepicker._getDate;
	$.datepicker._getDate = function(inst) {
	
		var tp_inst = $.datepicker._get(inst, 'timepicker');
		
		if(tp_inst){
			var startDate = (!inst.currentYear || (inst.input && inst.input.val() == '') ? null :
				this._daylightSavingAdjust(new Date(
				inst.currentYear, inst.currentMonth, inst.currentDay, tp_inst.hour, tp_inst.minute, tp_inst.second)));
				return startDate;
		}
		
		return $.datepicker._base_getDate(inst);
	};
	
	//#######################################################################################
	// jQuery extend now ignores nulls!
	//#######################################################################################
	function extendRemove(target, props) {
		$.extend(target, props);
		for (var name in props){
			if (props[name] === null || props[name] === undefined){
				target[name] = props[name];
			}
		}
		return target;
	}

	$.timepicker = new Timepicker(true); // singleton instance
})(jQuery);


