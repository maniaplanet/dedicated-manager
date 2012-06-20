(function($, undefined) {

	$.widget("mobile.huepicker", $.mobile.widget, {
		options: {
			hue        : 0,
			initSelector : ":jqmData(role='huepicker')"
		},

		_create: function() {
			var widgetOptionNames = {
				"hue"    : (this.element.is("input") ? "value" : "data-" + ($.mobile.ns || "") + "hue")
			},
			ui = {
				container: "#huepicker",
				selector:    "#huepicker-selector",
				hue:         "#huepicker-hue",
				eventSource: "[data-event-source='hue']"
			},
			proto = $(
				"<div>" +
				"	<div id='huepicker' class='ui-huepicker'>" +
				"		<div class='huepicker-arrow-btn-container'>" +
				"			<a href='#' class='huepicker-arrow-btn' data-target='hue' data-location='left' data-role='button' data-inline='true' data-iconpos='notext' data-icon='arrow-l'></a>" +
				"		</div>" +
				"		<div class='huepicker-masks-container'>" +
				"			<div id='huepicker-hue' class='hue-gradient' data-event-source='hue'></div>" +
				"			<div id='huepicker-selector' class='huepicker-selector ui-corner-all'></div>" +
				"		</div>" +
				"		<div class='huepicker-arrow-btn-container'>" +
				"			<a href='#' class='huepicker-arrow-btn' data-target='hue' data-location='right' data-role='button' data-inline='true' data-iconpos='notext' data-icon='arrow-r'></a>" +
				"		</div>" +
				"	</div>" +
				"</div>"
				),
			self = this;

			// Assign the relevant parts of the proto
			function assignElements(proto, obj) {
				for (var key in obj)
					if (typeof obj[key] === "string")
						obj[key] = proto.find(obj[key]).removeAttr("id");
					else
					if (typeof obj[key] === "object")
						obj[key] = assignElements(proto, obj[key]);
				return obj;
			}
			ui = assignElements(proto, ui);

			// Apply the proto
			if (this.element.is("input")){
				ui.container.insertBefore(this.element);
				this.element.hide();
			}
			else
				this.element.append(ui.container);
			$(".huepicker-arrow-btn[data-location=right]").css("left", "26px");

			// Define instance variables
			$.extend(this, {
				_ui: ui,
				_dragging_hue: 0,
				_selectorDraggingOffset: {
					x: -1,
					y: -1
				},
				_dragging: false
			});

			// Apply options - data-* options, if present, take precedence over this.options.*
			for (var key in this.options)
				this._setOption(key,
					(widgetOptionNames[key] === undefined || this.element.attr(widgetOptionNames[key]) === undefined)
					? this.options[key]
					: this.element.attr(widgetOptionNames[key]), true);

		
			ui.container.find(".huepicker-arrow-btn")
			.buttonMarkup()
			.bind("vclick", function(e) {
				var step = 0.05;

				self._dragging_hue = self._dragging_hue + step * ("left" === $(this).attr("data-location") ? -1 : 1);
				self._dragging_hue = Math.min(1, Math.max(0, self._dragging_hue));
				self._updateSelectors(self._dragging_hue);
			});

			if (this.element.is("input"))
			{
				this.element.bind("change", function() {
					self._setOption("hue", self.element.val());
				});
				this.element.closest("form").bind("reset", function() {
					self._setOption("hue", self.element[0].defaultValue);
				});
			}

			$( document )
			.bind( "vmousemove", function( event ) {
				if (self._dragging) {
					event.stopPropagation();
					event.preventDefault();
				}
			})
			.bind( "vmouseup", function( event ) {
				self._dragging = false;
			});

			this._bindElements();
		},

		_bindElements: function() {
			var self = this;
			this._ui.selector
			.bind("mousedown vmousedown", function(e) {self._handleMouseDown(e, true);})
			.bind("vmousemove touchmove", function(e) {self._handleMouseMove(e, true);})
			.bind("vmouseup",             function(e) {self._dragging = false;});
			this._ui.eventSource
			.bind("mousedown vmousedown", function(e) {self._handleMouseDown(e, false);})
			.bind("vmousemove touchmove", function(e) {self._handleMouseMove(e, false);})
			.bind("vmouseup",             function(e) {self._dragging = false;});
		},
	
		/**
	 * Get document-relative mouse coordinates from a given event
	 *
	 * From: http://www.quirksmode.org/js/events_properties.html#position
	 */
		_documentRelativeCoordsFromEvent: function(ev) {
			var e = ev ? ev : window.event,
		    client = {x: e.clientX, y: e.clientY},
		    page   = {x: e.pageX,   y: e.pageY},
			posx = 0,
			posy = 0;

			/* Grab useful coordinates from touch events */
			if (e.type.match(/^touch/)) {
				page = {
					x: e.originalEvent.targetTouches[0].pageX,
					y: e.originalEvent.targetTouches[0].pageY
				};
				client = {
					x: e.originalEvent.targetTouches[0].clientX,
					y: e.originalEvent.targetTouches[0].clientY
				};
			}

			if (page.x || page.y) {
				posx = page.x;
				posy = page.y;
			}
			else
			if (client.x || client.y) {
				posx = client.x + document.body.scrollLeft + document.documentElement.scrollLeft;
				posy = client.y + document.body.scrollTop  + document.documentElement.scrollTop;
			}

		return {x: posx, y: posy};
		},

		_targetRelativeCoordsFromEvent: function(e) {
		var coords = {x: e.offsetX, y: e.offsetY};

			if (coords.x === undefined || isNaN(coords.x) ||
				coords.y === undefined || isNaN(coords.y)) {
				var offset = $(e.target).offset();

				coords = this._documentRelativeCoordsFromEvent(e);
				coords.x -= offset.left;
				coords.y -= offset.top;
			}

			return coords;
		},

		_handleMouseDown: function(e, isSelector) {
			var coords = this._targetRelativeCoordsFromEvent(e),
			widgetStr = (isSelector ? "selector" : "eventSource");

			if (coords.x >= 0 && coords.x <= this._ui[widgetStr].outerWidth() &&
				coords.y >= 0 && coords.y <= this._ui[widgetStr].outerHeight()) {

				this._dragging = true;

				if (isSelector) {
					this._selectorDraggingOffset.x = coords.x;
					this._selectorDraggingOffset.y = coords.y;
				}

				this._handleMouseMove(e, isSelector, coords);
			}
		},

		_handleMouseMove: function(e, isSelector, coords) {
			if (this._dragging) {
				if (coords === undefined)
					coords = this._targetRelativeCoordsFromEvent(e);
				var potential = (isSelector
					? ((this._dragging_hue) +
						((coords.x - this._selectorDraggingOffset.x) / this._ui.eventSource.width()))
					: (coords.x / this._ui.eventSource.width()));

				this._dragging_hue = Math.min(1.0, Math.max(0.0, potential));

				if (!isSelector) {
					this._selectorDraggingOffset.x = Math.ceil(this._ui.selector.outerWidth()  / 2.0);
					this._selectorDraggingOffset.y = Math.ceil(this._ui.selector.outerHeight() / 2.0);
				}

				this._updateSelectors(this._dragging_hue);
				e.stopPropagation();
				e.preventDefault();
			}
		},

		_updateSelectors: function(hue) {
			var color = this._RGBToHTML(this._HueToRGB(hue));

			this._ui.selector.css("left", String(hue * 100) + "%");
			this._ui.selector.css("background", color);

			this._updateAttributes(hue);
		},

		/*
	 * Converts rgb array to html color string.
	 *
	 * Input: [ r, g, b ], where
	 * r is in [0, 1]
	 * g is in [0, 1]
	 * b is in [0, 1]
	 *
	 * Returns: string of the form "#aabbcc"
	 */
		_RGBToHTML: function(rgb) {
			return ("#" + 
				rgb.map(function(val) {
					var ret = val * 255,
					theFloor = Math.floor(ret);

					ret = ((ret - theFloor > 0.5) ? (theFloor + 1) : theFloor);
					ret = (((ret < 16) ? "0" : "") + (ret & 0xff).toString(16));
					return ret;
				})
				.join(""));
		},

		_HueToRGB: function(hue) {
			var h = hue * 6., x = 1 - Math.abs(h % 2 - 1);
			if(h < 1) return [1, x, 0];
			if(h < 2) return [x, 1, 0];
			if(h < 3) return [0, 1, x];
			if(h < 4) return [0, x, 1];
			if(h < 5) return [x, 0, 1];
			return [1, 0, x];
		},

		_updateAttributes: function(hue) {
			this.options.hue = hue;
			if (this.element.is("input")) {
				this.element.val(hue);
				this.element.triggerHandler("change");
			}
			else
				this.element.attr("data-" + ($.mobile.ns || "") + "hue", hue);
			this.element.triggerHandler("colorchanged");
		},

		_set_hue: function(hue, unconditional) {
			if (hue != this.options.hue || unconditional) {
				this._dragging_hue = hue;
				this._updateSelectors(hue);
			}
		},

		_set_disabled: function(value, unconditional) {
			if (this.options.disabled != value || unconditional) {
				this.options.disabled = value;
				this._ui.container[value ? "addClass" : "removeClass"]("ui-disabled");
			}
		},

		_setOption: function(key, value, unconditional) {
			if (unconditional === undefined)
				unconditional = false;
			if (this["_set_" + key] !== undefined)
				this["_set_" + key](value, unconditional);
		},

		enable: function() {
			this._setOption("disabled", false, true);
		},

		disable: function() {
			this._setOption("disabled", true, true);
		},

		refresh: function() {
			this._setOption("hue", (this.element.is("input") ? this.element.val() : this.element.attr("data-" + ($.mobile.ns || "") + "hue")), true);
		}
	});

	$(document).bind("pagecreate create", function(e) {
		$($.mobile.huepicker.prototype.options.initSelector, e.target)
		.not(":jqmData(role='none'), :jqmData(role='nojs')")
		.huepicker();
	});

})(jQuery);
