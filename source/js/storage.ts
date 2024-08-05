import { StorageInterface } from "./storageInterface";
class Storage implements StorageInterface {
    constructor(private taxonomies: { [key: string]: string[] }, private selected: { [key: string]: string[] }) {
    }

    public getSelected(postType: string): string[] {
        if (this.selected.hasOwnProperty(postType)) {
            return this.selected[postType];
        }

        return [];
    }

    public getTaxonomies(postType: string): string[] {
        if (this.taxonomies.hasOwnProperty(postType)) {
            return this.taxonomies[postType];
        }

        return [];
    }
}

export default Storage;