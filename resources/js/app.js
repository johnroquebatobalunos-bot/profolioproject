document.querySelectorAll('[data-copy]').forEach((button) => {
	button.addEventListener('click', async () => {
		const copyStatus = button.closest('.manage-main-panel')?.querySelector('.copy-status');

		try {
			await navigator.clipboard.writeText(button.dataset.copy);
			if (copyStatus) copyStatus.textContent = 'Public link copied';
		} catch {
			if (copyStatus) copyStatus.textContent = 'Copy unavailable in this browser';
		}
	});
});

document.querySelectorAll('[data-entry-editor]').forEach((editor) => {
	const source = editor.querySelector('[data-entry-source]');
	const list = editor.querySelector('[data-entry-list]');
	const addButton = editor.querySelector('[data-entry-add]');
	const labels = editor.dataset.entryFields.split('|');
	const placeholders = editor.dataset.entryPlaceholders.split('|');

	const updateTitles = () => {
		list.querySelectorAll('.entry-row-title').forEach((title, index) => {
			title.textContent = `${editor.dataset.entryAddLabel.replace(/^Add /, '')} ${index + 1}`;
		});
	};

	const addEntry = (values = []) => {
		const row = document.createElement('div');
		row.className = 'entry-row';

		const top = document.createElement('div');
		top.className = 'entry-row-top';
		const title = document.createElement('span');
		title.className = 'entry-row-title';
		const removeButton = document.createElement('button');
		removeButton.className = 'entry-remove';
		removeButton.type = 'button';
		removeButton.textContent = 'Remove';
		removeButton.setAttribute('aria-label', `Remove ${editor.dataset.entryAddLabel.replace(/^Add /, '').toLowerCase()}`);
		removeButton.addEventListener('click', () => {
			row.remove();
			updateTitles();
		});
		top.append(title, removeButton);

		const fields = document.createElement('div');
		fields.className = 'entry-row-fields';
		labels.forEach((label, index) => {
			const field = document.createElement('label');
			field.className = 'entry-field';
			const caption = document.createElement('span');
			caption.textContent = label;
			const input = document.createElement('input');
			input.type = 'text';
			input.value = values[index] ?? '';
			input.placeholder = placeholders[index] ?? '';
			input.dataset.entryValue = '';
			input.maxLength = label === 'URL' || label === 'Project link' ? 255 : 1000;
			field.append(caption, input);
			fields.append(field);
		});

		row.append(top, fields);
		list.append(row);
		updateTitles();
	};

	(source.value.match(/[^\r\n]+/g) ?? []).forEach((line) => {
		addEntry(line.split('|').map((value) => value.trim()));
	});
	editor.classList.add('has-entry-js');
	addButton.addEventListener('click', () => addEntry());
	editor.closest('form').addEventListener('submit', () => {
		source.value = [...list.querySelectorAll('.entry-row')]
			.map((row) => [...row.querySelectorAll('[data-entry-value]')].map((input) => input.value.trim()).join(' | '))
			.filter((line) => line.replaceAll('|', '').trim())
			.join('\n');
	});
});

document.querySelectorAll('[data-wizard]').forEach((form) => {
	const panels = [...form.querySelectorAll('[data-wizard-panel]')];
	const stepButtons = [...form.querySelectorAll('[data-wizard-go]')];
	const backButton = form.querySelector('[data-wizard-back]');
	const nextButton = form.querySelector('[data-wizard-next]');
	const submitButton = form.querySelector('[data-wizard-submit]');
	const status = form.querySelector('[data-wizard-status]');
	const percent = form.querySelector('[data-wizard-percent]');
	const progressBar = form.querySelector('[data-wizard-bar]');
	let activeStep = 0;

	const showStep = (step, focus = true) => {
		activeStep = Math.max(0, Math.min(step, panels.length - 1));
		panels.forEach((panel, index) => {
			panel.hidden = index !== activeStep;
		});
		stepButtons.forEach((button, index) => {
			button.disabled = index > activeStep;
			if (index === activeStep) button.setAttribute('aria-current', 'step');
			else button.removeAttribute('aria-current');
			button.classList.toggle('is-complete', index < activeStep);
		});

		const progress = Math.round(((activeStep + 1) / panels.length) * 100);
		status.textContent = `Step ${activeStep + 1} of ${panels.length} · ${panels[activeStep].dataset.wizardName}`;
		percent.textContent = `${progress}%`;
		progressBar.style.width = `${progress}%`;
		backButton.hidden = activeStep === 0;
		nextButton.hidden = activeStep === panels.length - 1;
		submitButton.hidden = activeStep !== panels.length - 1;

		if (focus) {
			panels[activeStep].querySelector('h2')?.focus({ preventScroll: true });
			form.scrollIntoView({ block: 'start' });
		}
	};

	const advance = () => {
		const invalidField = panels[activeStep].querySelector(':invalid');
		if (invalidField) {
			invalidField.reportValidity();
			return;
		}
		showStep(activeStep + 1);
	};

	nextButton.addEventListener('click', advance);
	backButton.addEventListener('click', () => showStep(activeStep - 1));
	stepButtons.forEach((button) => {
		button.addEventListener('click', () => {
			const requestedStep = Number(button.dataset.wizardGo);
			if (requestedStep <= activeStep) showStep(requestedStep);
		});
	});
	form.addEventListener('submit', (event) => {
		if (activeStep < panels.length - 1) {
			event.preventDefault();
			advance();
		}
	});

	const errorStep = panels.findIndex((panel) => panel.querySelector('.field-error'));
	showStep(errorStep >= 0 ? errorStep : 0, false);
});
