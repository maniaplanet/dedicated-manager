(function($, undefined) {
	$.widget( "mobile.huepicker", $.mobile.widget, {
		options: {
			disabled: false,
			initSelector: ":jqmData(role='huepicker')"
		},

		_create: function() {
			var self = this,
				control = this.element,
				val = function() { return parseFloat(control.val()) },
				min = 0,
				max = 1,
				step = 1. / 6,

				domHandle = document.createElement('a'),
				handle = $(domHandle),
				domSlider = document.createElement('div'),
				slider = $(domSlider);

			domHandle.setAttribute('href', '#');
			domSlider.setAttribute('role','application');
			domSlider.className = 'ui-huepicker';
			domHandle.className = 'ui-huepicker-handle ui-corner-all';
			domSlider.appendChild(domHandle);

			handle.buttonMarkup({ corners: false, shadow: true })
					.attr({
						'role': 'huepicker',
						'aria-valuemin': min,
						'aria-valuemax': max,
						'aria-valuenow': val(),
						'aria-valuetext': val(),
						'title': val()
					});

			$.extend( this, {
				slider: slider,
				handle: handle,
				dragging: false,
				beforeStart: null,
				userModified: false,
				mouseMoved: false
			});

			// monitor the input for updated values
			control.addClass('ui-huepicker-input')
				.change( function() {
					// if the user dragged the handle, the "change" event was triggered from inside refresh(); don't call refresh() again
					if (!self.mouseMoved) {
						self.refresh( val(), true );
					}
				})
				.keyup( function() { // necessary?
					self.refresh( val(), true, true );
				})
				.blur( function() {
					self.refresh( val(), true );
				});

			// prevent screen drag when slider activated
			$( document ).bind( 'vmousemove', function( event ) {
				if (self.dragging) {
					// self.mouseMoved must be updated before refresh() because it will be used in the control "change" event
					self.mouseMoved = true;
					self.refresh( event );

					return false;
				}
			});

			slider.bind('vmousedown', function( event ) {
				self.dragging = true;
				self.userModified = false;
				self.mouseMoved = false;

				self.refresh( event );
				return false;
			})
			.bind('vclick', false);

			slider.add(document)
				.bind('vmouseup', function() {
					if(self.dragging) {
						self.dragging = false;
						self.mouseMoved = false;

						return false;
					}
				});

			slider.insertAfter(control);

			this.handle.bind({
				// NOTE force focus on handle
				vmousedown: function() {
					$(this).focus();
				},

				vclick: false,

				keydown: function(event) {
					var index = val();

					if(self.options.disabled) {
						return;
					}

					// In all cases prevent the default and mark the handle as active
					switch(event.keyCode) {
						case $.mobile.keyCode.HOME:
						case $.mobile.keyCode.END:
						case $.mobile.keyCode.PAGE_UP:
						case $.mobile.keyCode.PAGE_DOWN:
						case $.mobile.keyCode.UP:
						case $.mobile.keyCode.RIGHT:
						case $.mobile.keyCode.DOWN:
						case $.mobile.keyCode.LEFT:
							event.preventDefault();

							if(!self._keySliding) {
								self._keySliding = true;
								$( this ).addClass('ui-state-active');
							}
							break;
					}

					// move the slider according to the keypress
					switch (event.keyCode) {
						case $.mobile.keyCode.HOME:
							self.refresh(min);
							break;
						case $.mobile.keyCode.END:
							self.refresh(max);
							break;
						case $.mobile.keyCode.PAGE_UP:
						case $.mobile.keyCode.UP:
						case $.mobile.keyCode.RIGHT:
							self.refresh(index + step);
							break;
						case $.mobile.keyCode.PAGE_DOWN:
						case $.mobile.keyCode.DOWN:
						case $.mobile.keyCode.LEFT:
							self.refresh(index - step);
							break;
					}
				}, // remove active mark

				keyup: function(event) {
					if(self._keySliding) {
						self._keySliding = false;
						$(this).removeClass('ui-state-active');
					}
				}
			});

			this.refresh(undefined, undefined, true);
		},

		_HueToHtml: function(hue) {
			var h = hue * 6.,
				x = 1 - Math.abs(h % 2 - 1);
			x = Math.round(x * 255);
			x = (x < 16 ? '0' : '') + (x & 0xFF).toString(16);
			if(h < 1) return '#ff'+x+'00';
			if(h < 2) return '#'+x+'ff00';
			if(h < 3) return '#00ff'+x;
			if(h < 4) return '#00'+x+'ff';
			if(h < 5) return '#'+x+'00ff';
			return '#ff00'+x;
		},

		refresh: function(val, isfromControl, preventInputUpdate) {

			if(this.options.disabled || this.element.attr('disabled')) {
				this.disable();
			}

			var control = this.element, newval,
				min = 0,
				max = 1;

			if(typeof val === 'object') {
				var data = val,
					// a slight tolerance helped get to the ends of the slider
					tol = 8;
				if ( !this.dragging ||
						data.pageX < this.slider.offset().left - tol ||
						data.pageX > this.slider.offset().left + this.slider.width() + tol ) {
					return;
				}
				newval = ( data.pageX - this.slider.offset().left ) / this.slider.width();
			} else {
				if ( val == null ) {
					val = parseFloat( control.val() || 0 );
				}
				newval = ( parseFloat( val ) - min ) / ( max - min );
			}

			if ( isNaN( newval ) ) {
				return;
			}

			if ( newval < min ) {
				newval = min;
			}

			if ( newval > max ) {
				newval = max;
			}
			
			newval = Math.round(newval * 1000) / 1000;

			this.handle.css({
				'left': (newval * 100) + '%',
				'background': this._HueToHtml(newval)
			});
			this.handle.attr( {
					'aria-valuenow': newval,
					'aria-valuetext': newval,
					title: newval
				});

			if ( !preventInputUpdate ) {
				var valueChanged = control.val() !== newval;
				control.val( newval );
				if ( !isfromControl && valueChanged ) {
					control.trigger('change');
				}
			}
		},

		enable: function() {
			this.element.attr('disabled', false );
			this.slider.removeClass('ui-disabled').attr('aria-disabled', false);
			return this._setOption('disabled', false);
		},

		disable: function() {
			this.element.attr('disabled', true);
			this.slider.addClass('ui-disabled').attr('aria-disabled', true);
			return this._setOption('disabled', true);
		}

	});

	//auto self-init widgets
	$( document ).bind('pagecreate create', function(e){
		$.mobile.huepicker.prototype.enhanceWithin(e.target, true);
	});

})(jQuery);


