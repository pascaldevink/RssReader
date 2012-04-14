jQuery ->
	newEntriesChecker = new NewEntriesChecker
	newEntriesChecker.startChecking()

class NewEntriesChecker
	startChecking: ->
		setInterval =>
				@checkForUpdates()
			, 5000

	checkForUpdates: ->
		jQuery.ajax '/ajax/newEntriesCheck',
			type: 'GET'
			dataType: 'html'
			error: (jqXHR, textStatus, errorThrown) ->
				console.log "AJAX error: #{textStatus}"
			success: (data, textStatus, jqXHR) =>
				@updateSidebar data

	updateSidebar: (numberOfUpdates) ->
		elementToRemove = jQuery '#sidebar-inbox a span.badge'
		elementToRemove.remove()

		elementToUpdate = jQuery '#sidebar-inbox a'
		elementToUpdate.append '<span class="badge">'+numberOfUpdates+'</span>'
