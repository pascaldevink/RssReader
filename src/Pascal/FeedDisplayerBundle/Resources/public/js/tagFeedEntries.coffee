# FIXME: This is propably not relevant anymore

jQuery ->
	tagFeedEntries = new TagFeedEntries()
	tagFeedEntries.init()

class TagFeedEntries
	init: =>
		# Add a delete event on all tags
		jQuery('p.tags button.icon-remove').bind('click', @deleteTagFromFeedEntry)

	deleteTagFromFeedEntry: (event) ->
		button = $(_this);
		values = button.attr('value');

		dashLocation = values.indexOf('-');
		feedEntryId = values.substring(0, dashLocation);
		tagId = values.substring(dashLocation+1, values.length);

		jQuery.ajax '/ajax/deleteTag',
			type: 'GET'
			dataType: 'html'
			data: { tagId: tagId, feedEntryId: feedEntryId }
			error: (jqXHR, textStatus, errorThrown) ->
				console.log "AJAX error: #{textStatus}"
			success: (data, textStatus, jqXHR) =>
				console.log('removed tag ' + data);

		button.parent().remove();
