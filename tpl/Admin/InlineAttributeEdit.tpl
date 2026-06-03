{if $attribute->AppliesToEntity($id)}
	{assign var=type value=$attribute->Type()}
	{assign var=attributeId value="inline{$attribute->Id()}{$id}"}
	{assign var=datatype value='text'}
	{assign var=inlineClass value='inlineAttribute'}
	{assign var=pickerValue value=$value}

	{if $type == CustomAttributeTypes::CHECKBOX}
		{assign var=datatype value='checklist'}
	{elseif $type == CustomAttributeTypes::MULTI_LINE_TEXTBOX}
		{assign var=datatype value='textarea'}
	{elseif $type == CustomAttributeTypes::SELECT_LIST}
		{assign var=datatype value='select'}
	{elseif $type == CustomAttributeTypes::DATETIME}
		{assign var=AltFormat value='short_datetime'}
		{assign var=pickerControlId value="inlinePickerInput{$attributeId}"}
		{assign var=inlineClass value='inlineAttributeDateTime'}
		{if $value != ''}
			{assign var=pickerValue value={formatdate date=$value format='Y-m-d H:i'}}
		{/if}
	{/if}

	<div class="updateCustomAttribute mb-0 d-inline-block">
		<label class="inline fw-bold">{$attribute->Label()}</label>

		<a class="update {if $type == CustomAttributeTypes::DATETIME}changeAttributeDateTime{else}changeAttribute{/if} link-primary"
			title="{translate key='Edit'}" href="#">
			<span class="bi bi-pencil-square"></span>
			<span class="visually-hidden">{translate key=Edit}</span>
		</a>

		<span class="{$inlineClass} update" id="{$attributeId}" data-type="{$datatype}" data-pk="{$id}"
			data-value="{$pickerValue|escape:'html'}" data-name="{FormKeys::ATTRIBUTE_PREFIX}{$attribute->Id()}"
			{if $type == CustomAttributeTypes::SELECT_LIST} data-source='[
					{if !$attribute->Required()}
						{ldelim}"value":"","text":""{rdelim},
					{/if}
					{foreach from=$attribute->PossibleValueList() item=v name=vals}
						{ldelim}"value":{$v|@json_encode|escape:'html'},"text":{$v|@json_encode|escape:'html'}{rdelim}{if not $smarty.foreach.vals.last},{/if}
					{/foreach}
				]' {/if} {if $type == CustomAttributeTypes::CHECKBOX}
		data-source='[{ldelim}value:"1",text:"{translate key=Yes}"{rdelim}]' {/if}>
		{if $type == CustomAttributeTypes::DATETIME}
			{if $value != ''}{formatdate date=$value key=$AltFormat}{else}-{/if}
		{/if}
	</span>

	{if $type == CustomAttributeTypes::DATETIME}
		<div class="d-none position-absolute border rounded bg-body shadow p-2 z-3 update" id="inlinePicker{$attributeId}">

			<input type="text" id="{$pickerControlId}" class="form-control form-control-sm" autocomplete="off">
		</div>

		{control type="DatePickerSetupControl" ControlId=$pickerControlId HasTimepicker=true Inline=true DefaultDate=$pickerValue AltFormat=$AltFormat}

		<script>
			(function() {
				const display = document.getElementById('{$attributeId}');
				const container = document.getElementById('inlinePicker{$attributeId}');
				const input = document.getElementById('{$pickerControlId}');
				const button = display.closest('.updateCustomAttribute').querySelector('.changeAttributeDateTime');
				const namespace = 'picker{$attributeId}';
				let pendingValue = display.dataset.value || '';
				let isSaving = false;

				// DatePickerSetupControl initializes flatpickr synchronously (whenFlatpickrReady
				// runs inline callbacks immediately when window.flatpickr is already loaded).
				// whenPickerReady is only a fallback for deferred script loading.
				function whenPickerReady(cb, retries = 60) {
					if (input._flatpickr) {
						cb(input._flatpickr);
						return;
					}

					let attempts = 0;
					const id = setInterval(() => {
						if (input._flatpickr) {
							clearInterval(id);
							cb(input._flatpickr);
							return;
						}

						attempts += 1;
						if (attempts >= retries) {
							clearInterval(id);
							console.warn('InlineAttributeEdit: flatpickr not initialized for {$pickerControlId}');
						}
					}, 50);
				}

				function show() { container.classList.remove('d-none'); }

				function hide() { container.classList.add('d-none'); }

				function extractErrorMessage(errors) {
					if (!errors) {
						return 'Error saving value';
					}

					if (typeof errors === 'string') {
						return errors;
					}

					if (Array.isArray(errors)) {
						return errors.join('\n');
					}

					if (typeof errors === 'object') {
						const messages = [];
						for (const key in errors) {
							if (!Object.prototype.hasOwnProperty.call(errors, key)) {
								continue;
							}

							const value = errors[key];
							if (Array.isArray(value)) {
								messages.push(value.join(', '));
							} else {
								messages.push(String(value));
							}
						}

						return messages.length ? messages.join('\n') : 'Error saving value';
					}

					return 'Error saving value';
				}

				function save(value) {
					const body = new URLSearchParams({
						pk: '{$id}',
						name: '{FormKeys::ATTRIBUTE_PREFIX}{$attribute->Id()}',
						value: value,
						CSRF_TOKEN: (document.getElementById('csrf_token') || {}).value || ''
					});

					fetch("{$url}", { method: 'POST', body })
					.then(function(response) {
							if (!response.ok) {
								throw new Error('HTTP ' + response.status);
							}

							return response.text();
						})
						.then(function(rawBody) {
							const trimmedBody = rawBody ? rawBody.trim() : '';
							if (trimmedBody !== '') {
								let payload;
								try {
									payload = JSON.parse(trimmedBody);
								} catch (e) {
									payload = null;
								}

								if (payload && typeof payload === 'object' && payload.errors) {
									throw new Error(extractErrorMessage(payload.errors));
								}
							}

							const text = value === '' ? '-' : (input._flatpickr?.altInput?.value || value);
							display.textContent = text;
							display.dataset.value = value;
							pendingValue = value;
							isSaving = false;
							hide();
						})
						.catch(function(error) {
							isSaving = false;
							alert((error && error.message) ? error.message : 'Error saving value');
						});
				}

				function saveIfChanged() {
					if (isSaving) {
						return;
					}

					const currentValue = display.dataset.value || '';
					if (pendingValue === currentValue) {
						hide();
						return;
					}

					isSaving = true;
					save(pendingValue);
				}

				whenPickerReady(function(picker) {
					picker.config.onChange.push(function(_, dateStr) {
						pendingValue = dateStr;
					});
				});

				button.addEventListener('click', function(e) {
					e.preventDefault();
					e.stopPropagation();
					pendingValue = display.dataset.value || '';
					show();
					if (input._flatpickr) {
						input._flatpickr.setDate(display.dataset.value || null, false);
					}
				});

				// Close on outside click / ESC — keyed registry avoids duplicate listeners
				// when multiple pickers are rendered on the same page
				if (!document._pickerHandlers) document._pickerHandlers = {};

				if (document._pickerHandlers[namespace + '_mousedown']) {
					document.removeEventListener('mousedown', document._pickerHandlers[namespace + '_mousedown']);
				}
				document._pickerHandlers[namespace + '_mousedown'] = function(e) {
					if (container.classList.contains('d-none')) return;
					if (container.contains(e.target) || button.contains(e.target)) return;
					saveIfChanged();
				};
				document.addEventListener('mousedown', document._pickerHandlers[namespace + '_mousedown']);

				if (document._pickerHandlers[namespace + '_keydown']) {
					document.removeEventListener('keydown', document._pickerHandlers[namespace + '_keydown']);
				}
				document._pickerHandlers[namespace + '_keydown'] = function(e) {
					if (e.key !== 'Escape') return;
					if (container.classList.contains('d-none')) return;
					pendingValue = display.dataset.value || '';
					if (input._flatpickr) {
						input._flatpickr.setDate(display.dataset.value || null, false);
					}
					hide();
				};
				document.addEventListener('keydown', document._pickerHandlers[namespace + '_keydown']);
			})();
		</script>
	{/if}
</div>
{/if}
