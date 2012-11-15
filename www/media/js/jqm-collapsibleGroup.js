(function($, undefined) {
	$.widget( "mobile.collapsiblegroup", $.mobile.collapsibleset, {
		options: {
			initSelector: ":jqmData(role='collapsible-group')"
		},
		_create: function() {
			var $el = this.element;
			if (!$el.jqmData('collapsiblebound')) {
				$.mobile.collapsibleset.prototype._create.call(this);
				$el.unbind('expand').bind('expand', $._data($el.get(0), 'events')['collapse'][0]);
			}
		}
	});

	//auto self-init widgets
	$( document ).bind( "pagecreate create", function( e ) {
		$.mobile.collapsiblegroup.prototype.enhanceWithin( e.target );
	});
	
})(jQuery);