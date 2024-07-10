export interface StorageInterface {
    getSelected(postType: string): string[];
    getTaxonomies(postType: string): string[];
}