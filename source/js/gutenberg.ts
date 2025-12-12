import TaxonomySelectUpdater from './taxonomySelectUpdater';
import Storage from './storage';
import Main from './main';

class Gutenberg {
	initializedOsmBlocks = [];

	constructor() {
		this.editor = wp.data.select('core/block-editor');

		// Listens for blocks added to the editor.
		let handleOsmBlocksDebounced = null;

		wp.data.subscribe(() => {
			if (handleOsmBlocksDebounced) clearTimeout(handleOsmBlocksDebounced);

			handleOsmBlocksDebounced = setTimeout(() => {
				const osmBlockIds = this.editor.getBlocksByName('acf/open-street-map');
				if (osmBlockIds.length === 0) {
					return;
				}

				osmBlockIds.forEach((osmBlockId) => {
					if (!this.initializedOsmBlocks.includes(osmBlockId)) {
						this.tryInitializeBlock(osmBlockId);
					}
				});
			}, 300);
		});
	}

	// Initialize the block.
	// Interval is added since the field is not the block fields have not been loaded right away.
	initializeBlock(block, osmBlockId) {
		const filterSelectFieldKey = 'field_668d1cc80d853';
		const postTypeSelectFieldKey = 'field_642a8818a908c';

		const [taxonomies, selected] = this.getOsm(osmBlockId);
		const storageInstance = new Storage(taxonomies, selected);
		const taxonomySelectUpdaterInstance = new TaxonomySelectUpdater(filterSelectFieldKey, storageInstance);

		const intervalId = setInterval(() => {
			if (block.querySelector('[data-name="mod_osm_post_type"]')) {
				new Main(
					block,
					acf,
					postTypeSelectFieldKey,
					filterSelectFieldKey,
					storageInstance,
					taxonomySelectUpdaterInstance,
				);

				clearInterval(intervalId);
			}
		}, 1000);
	}

	// if the block is already initialized, skip it.
	tryInitializeBlock(osmBlockId) {
		const block = document.querySelector('#block-' + osmBlockId);
		if (block) {
			this.initializedOsmBlocks.push(osmBlockId);
			this.initializeBlock(block, osmBlockId);
		}
	}

	// Get the data (variable osm comes from PHP where all the taxonomies are already retrieved.)
	getOsm(osmBlockId) {
		let parsedData = {};

		try {
			parsedData = JSON.parse(osm);
		} catch (error) {
			console.error('Error parsing OSM data', error);
		}

		return [Object.hasOwn(parsedData, 'taxonomies') ? parsedData.taxonomies : {}, this.getSelected(osmBlockId)];
	}

	// Gets the selected values from the block attributes.
	getSelected(osmBlockId) {
		const blockData = this.editor.getBlockAttributes(osmBlockId);
		if (!blockData?.data?.mod_osm_post_type) {
			return {};
		}

		const selected = {};
		selected[blockData.data.mod_osm_post_type] = [];

		if (blockData?.data?.mod_osm_filters > 0) {
			let i = 0;
			for (i; i < blockData.data.mod_osm_filters; i++) {
				if (blockData.data[`mod_osm_filters_${i}_mod_osm_filter_taxonomy`]) {
					selected[blockData.data.mod_osm_post_type].push(
						blockData.data[`mod_osm_filters_${i}_mod_osm_filter_taxonomy`],
					);
				}
			}
		}

		return selected;
	}
}

export default Gutenberg;
