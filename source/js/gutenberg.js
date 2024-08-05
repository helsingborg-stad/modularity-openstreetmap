import TaxonomySelectUpdater from "./taxonomySelectUpdater";
import Storage from "./storage";
import Main from "./main";

class Gutenberg {
    initializedOsmBlocks =  [];

    constructor() {
        this.editor = wp.data.select('core/block-editor');

        wp.data.subscribe(() => {
            const osmBlockIds = this.editor.getBlocksByName('acf/open-street-map');
            if (osmBlockIds.length > 0) {
                osmBlockIds.forEach((osmBlockId) => {
                    if (!this.initializedOsmBlocks.includes(osmBlockId)) {
                        this.tryInitializeBlock(osmBlockId);
                    }
                });
            }
        });
    }

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
                    taxonomySelectUpdaterInstance
                );
                
                clearInterval(intervalId);
            }
        }, 1000);
    }

    tryInitializeBlock(osmBlockId) {
        const block = document.querySelector('#block-' + osmBlockId);
        if (block) {
            this.initializedOsmBlocks.push(osmBlockId);
            this.initializeBlock(block, osmBlockId);
        }
    }

    getOsm(osmBlockId) {
        let parsedData = {};

        try {
            parsedData = JSON.parse(osm);
        } catch (error) {
            console.error('Error parsing OSM data', error);
        }

        return [
            parsedData.hasOwnProperty('taxonomies') ? parsedData.taxonomies : {},
            this.getSelected(osmBlockId)
        ];
    }

    getSelected(osmBlockId) {
        const blockData = this.editor.getBlockAttributes(osmBlockId);
        if (!blockData?.data?.mod_osm_post_type) {
            return {};
        }

        let selected = {};
        selected[blockData.data.mod_osm_post_type] = [];
            
        if (blockData?.data?.mod_osm_filters > 0) {
            let i = 0;
            for (i; i < blockData.data.mod_osm_filters; i++) {
                if (blockData.data[`mod_osm_filters_${i}_mod_osm_filter_taxonomy`]) {
                    selected[blockData.data.mod_osm_post_type].push(blockData.data[`mod_osm_filters_${i}_mod_osm_filter_taxonomy`]);
                }
            }
        }

        return selected;
    }
}

export default Gutenberg;