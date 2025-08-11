function Reminder(opts)
{
	var options = opts;

	var elements = {
		startDiv:$('#reminderOptionsStart'),
		endDiv:$('#reminderOptionsEnd')
	};

	Reminder.prototype.init = function ()
	{
		$('.reminderTime').forceNumeric();

		var enable = function (div)
		{
			div.find('.reminderEnabled').attr('checked', 'checked');
			div.find('.reminderTime, .reminderInterval').removeAttr('disabled', 'disabled');
		};

		var disable = function (div)
		{
			div.find('.reminderEnabled').removeAttr('checked');
			div.find('.reminderTime, .reminderInterval').attr('disabled', 'disabled');
		};

		if (opts.reminderTimeStart != '')
		{
			enable(elements.startDiv);
			elements.startDiv.find('.reminderTime').val(opts.reminderTimeStart);
			elements.startDiv.find('.reminderInterval').val(opts.reminderIntervalStart);
		}
		else
		{
			disable(elements.startDiv);
		}

		if (opts.reminderTimeEnd != '')
		{
			enable(elements.endDiv);
			elements.endDiv.find('.reminderTime').val(opts.reminderTimeEnd);
			elements.endDiv.find('.reminderInterval').val(opts.reminderIntervalEnd);
		}
		else
		{
			disable(elements.endDiv);
		}

		var wireUp = function (div)
		{
			div.find('.reminderEnabled').change(function ()
			{
				if ($(this).is(':checked'))
				{
					enable(div);
				}
				else
				{
					disable(div);
				}
			});
		};

		wireUp(elements.startDiv);
		wireUp(elements.endDiv);
	};
}
