jQuery ->
	newEntriesChecker = new NewEntriesChecker((new Date()).getTime())
	newEntriesChecker.startChecking()

class NewEntriesChecker
	constructor: (@lastRefreshTime) ->

	startChecking: ->
		setInterval =>
				@checkForUpdates()
			, 15000

	checkForUpdates: ->
		jQuery.ajax '/ajax/newEntriesCheck',
			type: 'GET'
			cache: false
			dataType: 'html'
			data: { lastRefreshTime: @lastRefreshTime }
			error: (jqXHR, textStatus, errorThrown) ->
				console.log "AJAX error: #{textStatus}"
			success: (data, textStatus, jqXHR) =>
				@updateSidebar data

	updateSidebar: (numberOfUpdates) ->
		elementToRemove = jQuery '#sidebar-inbox a span.badge'
		elementToRemove.remove()

		if numberOfUpdates > 0
			elementToUpdate = jQuery '#sidebar-inbox a'
			elementToUpdate.append '<span class="badge">'+numberOfUpdates+'</span>'
