import TaxonomySelectUpdater from './taxonomySelectUpdater';
import Storage from './storage';
import Main from './main';

class Module {
	constructor() {
		const filterSelectFieldKey = 'field_668d1cc80d853';
		const postTypeSelectFieldKey = 'field_642a8818a908c';
		const container = document.querySelector('#acf-group_64219abb0caec');

		if (container) {
			const [taxonomies, selected] = this.getOsm();
			const storageInstance = new Storage(taxonomies, selected);
			const taxonomySelectUpdaterInstance = new TaxonomySelectUpdater(filterSelectFieldKey, storageInstance);
			new Main(
				container,
				acf,
				postTypeSelectFieldKey,
				filterSelectFieldKey,
				storageInstance,
				taxonomySelectUpdaterInstance,
			);
		}
	}

	// Gets the taxonomies and selected taxonomies from osm (added in PHP).
	getOsm() {
		let parsedData = {};

		try {
			parsedData = JSON.parse(osm);
		} catch (error) {
			console.error('Error parsing OSM data', error);
		}

		return [
			Object.hasOwn(parsedData, 'taxonomies') ? parsedData.taxonomies : {},
			Object.hasOwn(parsedData, 'selected') ? parsedData.selected : {},
		];
	}
}

export default Module;
