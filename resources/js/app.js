import './bootstrap';

import { create, registerPlugin } from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import 'filepond/dist/filepond.min.css';

registerPlugin(FilePondPluginFileValidateType, FilePondPluginFileValidateSize);

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('input.filepond').forEach((input) => {
		const acceptAttr = (input.getAttribute('accept') || '')
			.split(',')
			.map((s) => s.trim())
			.filter(Boolean);

		create(input, {
			credits: false,
			stylePanelLayout: 'compact',
			allowMultiple: false,
			storeAsFile: true, // ensure files submit with the form
			maxFileSize: input.dataset.maxSize || '10MB',
			acceptedFileTypes: acceptAttr,
			labelIdle: 'Drop or browse',
		});
	});
});
