jQuery ->
	feedEntryTools = new FeedEntryTools()
	feedEntryTools.init()

class FeedEntryTools

	# ------------------------------------------------------------------
	# General methods, useful for the entire tool

	init: =>
		jQuery('html.no-touch .tools').hide()
		jQuery('html.no-touch .entry').hover(@hoverIn, @hoverOut)
		jQuery('.tools .icon-tags').bind('click', @tagEntry)
		jQuery('.tools .icon-remove-circle').bind('click', @deleteEntry)

	hoverIn: ->
		entry = $(this)
		entry.find('.tools').fadeIn(100)

	hoverOut: ->
		entry = $(this)
		entry.find('.tools').fadeOut(100)
		entry.find('.tagForm').remove();

	findCurrentFeedEntry: (toolLocation) ->
		feedEntryId = toolLocation.parentsUntil('div.entry').parent()

	findCurrentFeedEntryId: (feedEntry) ->
		feedEntryId = feedEntry.attr('id')
		feedEntryId = feedEntryId.substring(feedEntryId.indexOf('-')+1, feedEntryId.length)

	# ------------------------------------------------------------------
	# Tagging methods

	findCurrentTags: (toolLocation) =>
		feedEntry = @findCurrentFeedEntry toolLocation
		feedEntryId = @findCurrentFeedEntryId feedEntry
		tags = feedEntryTags[feedEntryId]

	findTagByName: (feedEntryId, tagName) ->
		tags = feedEntryTags[feedEntryId]
		for tag in tags
			return tag if tag.tagName is tagName

		return undefined

	tagEntry: (event) =>
		tool = $(event.currentTarget)

		# Find the currently attached tags
		tags = this.findCurrentTags tool

		# We're creating a div which holds an input field and a button
		tagFormWrapper = $('<div></div>')
		tagFormWrapper.addClass('tagForm')

		tagInput = $('<input type="text" class="tags"></input')

		tagFormWrapper.append(tagInput)
		tool.parent().append(tagFormWrapper)

		tagInput.tagsInput(
			height: '15px',
			'minChars': 2,
			placeholderColor: '#005580',
			onAddTag: @addTag,
			onRemoveTag: @deleteTag,
		);

		importTags = []
		for tag in tags
			importTags[tag.tagId] = tag.tagName

		tagInput.importTags(importTags.join ',')

	addTag: (input, tagName) =>
		feedEntry = input.parent().parent().parent()
		feedEntryId = @findCurrentFeedEntryId feedEntry

		jQuery.ajax '/ajax/addTag',
			type: 'GET'
			dataType: 'json'
			data: { tagName: tagName, feedEntryId: feedEntryId }
			error: (jqXHR, textStatus, errorThrown) ->
				console.log "AJAX error: #{textStatus}"
			success: (data, textStatus, jqXHR) =>
				console.log('added tag ' + data)
				tag = {tagId: data.id, tagName: tagName}
				feedEntryTags[feedEntryId].push tag
				@refreshTags feedEntryId

	deleteTag: (input, tagName) =>
		feedEntry = input.parent().parent().parent()
		feedEntryId = @findCurrentFeedEntryId feedEntry
		tag = @findTagByName feedEntryId, tagName

		jQuery.ajax '/ajax/deleteTag',
			type: 'GET'
			dataType: 'html'
			data: { tagId: tag.tagId, feedEntryId: feedEntryId }
			error: (jqXHR, textStatus, errorThrown) ->
				console.log "AJAX error: #{textStatus}"
			success: (data, textStatus, jqXHR) =>
				tags = feedEntryTags[feedEntryId].splice(0, feedEntryTags[feedEntryId].length)
				for existingTag in tags
					feedEntryTags[feedEntryId].push(existingTag) unless existingTag.tagName is tagName

				@refreshTags feedEntryId

	refreshTags: (feedEntryId) ->
		feedEntry = $("div#entry-#{feedEntryId}")
		tagsLocation = feedEntry.find('p.tags')
		tagsLocation.empty()

		for tag in feedEntryTags[feedEntryId]
			tagWrapper = $('<span></span>')
			tagWrapper.addClass('label')
			tagName = $('<span></span>')
			tagName.html(tag.tagName)

			tagWrapper.append(tagName)
			tagsLocation.append(tagWrapper)

	# ------------------------------------------------------------------
	# Sharing methods

	shareEntry: ->
		tool = $(this)
		# TODO: Add a share functionality

	# ------------------------------------------------------------------
	# Delete methods

	deleteEntry: (event) =>
		tool = $(event.currentTarget)
		feedEntry = @findCurrentFeedEntry tool
		feedEntryId = @findCurrentFeedEntryId feedEntry

		jQuery.ajax '/ajax/deleteFeedEntry',
			type: 'GET'
			dataType: 'html'
			data: { feedEntryId: feedEntryId }
			error: (jqXHR, textStatus, errorThrown) ->
				console.log "AJAX error: #{textStatus}"
			success: (data, textStatus, jqXHR) =>
				feedEntry.remove()