import type { StorageInterface } from './storageInterface';
import type TaxonomySelectUpdater from './taxonomySelectUpdater';

class Main {
	constructor(
		private container: HTMLElement,
		private acf: any,
		private postTypeSelectFieldKey: string,
		private filterSelectFieldKey: string,
		private storageInstance: StorageInterface,
		private taxonomySelectUpdaterInstance: TaxonomySelectUpdater,
	) {
		const postTypeSelect = this.container.querySelector(`[data-key="${this.postTypeSelectFieldKey}"] select`) as
			| HTMLSelectElement
			| undefined;

		if (postTypeSelect) {
			if (postTypeSelect.value) {
				this.taxonomySelectUpdaterInstance.updateExistsingRows(this.container, postTypeSelect.value);
			}

			this.listenForPostTypeChanges(postTypeSelect);
			this.listenForFilterRowsAdded(postTypeSelect);
		}
	}

	// Listens for changes in the post type select field.
	private listenForPostTypeChanges(postTypeSelect: HTMLSelectElement): void {
		postTypeSelect.addEventListener('change', (event) => {
			const selectedValue = (event.target as HTMLSelectElement).value;
			this.taxonomySelectUpdaterInstance.updateExistsingRows(this.container, selectedValue);
		});
	}

	// Listens for rows added in an ACF repeater field.
	// Updates the matching block/modules select field that was appended.
	private listenForFilterRowsAdded(postTypeSelect: HTMLSelectElement): void {
		this.acf.addAction('append', (jqueryEl: any) => {
			if (
				!Object.hasOwn(jqueryEl, 0) ||
				!jqueryEl[0].classList.contains('acf-row') ||
				!this.container.contains(jqueryEl[0])
			) {
				return;
			}

			const selectField = jqueryEl[0].querySelector(`[data-key="${this.filterSelectFieldKey}"] select`);

			if (selectField) {
				this.taxonomySelectUpdaterInstance.addSelectFieldOptions(
					selectField,
					0,
					this.storageInstance.getTaxonomies(postTypeSelect.value),
					[],
				);
			}
		});
	}
}

export default Main;
