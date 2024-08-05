import { StorageInterface } from "./storageInterface";
import TaxonomySelectUpdater from "./taxonomySelectUpdater";

class Main {
    constructor(
        private container: HTMLElement,
        private acf: any, 
        private postTypeSelectFieldKey: string, 
        private filterSelectFieldKey: string, 
        private storageInstance: StorageInterface, 
        private taxonomySelectUpdaterInstance: TaxonomySelectUpdater
    ) {
        const postTypeSelect = this.container.querySelector(`[data-key="${this.postTypeSelectFieldKey}"] select`) as HTMLSelectElement|undefined;

        if (postTypeSelect) {
            if (postTypeSelect.value) {
                this.taxonomySelectUpdaterInstance.updateExistsingRows(postTypeSelect.value);
            }
            
            this.listenForPostTypeChanges(postTypeSelect);
            this.listenForFilterRowsAdded(postTypeSelect);
        }
    }

    private listenForPostTypeChanges(postTypeSelect: HTMLSelectElement): void {
        postTypeSelect.addEventListener('change', (event) => {
            const selectedValue = (event.target as HTMLSelectElement).value;
            this.taxonomySelectUpdaterInstance.updateExistsingRows(selectedValue);
        });
    }

    private listenForFilterRowsAdded(postTypeSelect: HTMLSelectElement): void
    {
        this.acf.addAction('append', ( jqueryEl: any ) => {
            if (!jqueryEl.hasOwnProperty(0)) {
                return;
            }

            const selectField = jqueryEl[0].querySelector(`[data-key="${this.filterSelectFieldKey}"] select`);
            
            if (selectField) {
                this.taxonomySelectUpdaterInstance.addSelectFieldOptions(selectField, 0, this.storageInstance.getTaxonomies(postTypeSelect.value), []);
            }
        });
    }
}

export default Main;