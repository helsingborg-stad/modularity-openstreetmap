import { StorageInterface } from "./storageInterface";
class TaxonomySelectUpdater {

    constructor(
        private selectFieldKey: string, 
        private storage: StorageInterface

    ) {}

    public updateExistsingRows(container: HTMLElement, postType: string): void {
        const taxonomies = this.storage.getTaxonomies(postType);
        const selectedValues = this.storage.getSelected(postType);

        [...container.querySelectorAll(`tr.acf-row:not(.acf-clone) [data-key="${this.selectFieldKey}"] select`)].forEach((selectField, index) => {
            selectField.innerHTML = "";
            this.addSelectFieldOptions(selectField, index, taxonomies, selectedValues);
        });
    }

    public addSelectFieldOptions(
        selectField: Element,
        index: number,
        taxonomies: string[],
        selectedValues: string[],
    ): void {
        for (const key in taxonomies) {
            const option = document.createElement('option');
            option.selected = selectedValues[index] && selectedValues[index] === key ? true : false;
            option.value = key;
            option.text = taxonomies[key];
            selectField.appendChild(option);
        }
    }
}

export default TaxonomySelectUpdater;