import TaxonomySelectUpdater from "./taxonomySelectUpdater";
import Storage from "./storage";
import Main from "./main";

document.addEventListener('DOMContentLoaded', function() {
    const filterSelectFieldKey = 'field_668d1cc80d853';
    const postTypeSelectFieldKey = 'field_642a8818a908c';

    if (typeof acf !== 'undefined') {
        const [taxonomies, selected] = getOsm();
        const storageInstance = new Storage(taxonomies, selected);
        const taxonomySelectUpdaterInstance = new TaxonomySelectUpdater(filterSelectFieldKey, storageInstance);
        new Main(acf, postTypeSelectFieldKey, filterSelectFieldKey, storageInstance, taxonomySelectUpdaterInstance);
    }
});

function getOsm() {
    let parsedData = {};
    try {
        parsedData = JSON.parse(osm);
    } catch (error) {
        console.error('Error parsing OSM data', error);
    }

    console.log(parsedData);

    return [
        parsedData.hasOwnProperty('taxonomies') ? parsedData.taxonomies : [], 
        parsedData.hasOwnProperty('selected') ? parsedData.selected : []
    ];
}