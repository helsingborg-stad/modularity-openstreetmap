class ShowPost {
    constructor(container, id) {
        this.container = container;
        this.clusters = window.leafletClusters[`${id}`] ?? false;

        (window.leafletMap && this.container && window.leafletClusters) && this.handleClick();
    }
    handleClick() {
        let paginationContainer = this.container.querySelector('[js-pagination-container]');
        let sidebar = this.container.querySelector('.c-openstreetmap__sidebar');
        let gridClass = false;
        
        paginationContainer && paginationContainer.addEventListener('click', (e) => {
            if (e.target.closest('.c-collection__item__floating')) { return; };
            let collectionItem = e.target.closest('.c-openstreetmap__collection__item');
            let paginationItem = collectionItem?.parentElement;
            let backButton = e.target.closest('.c-openstreetmap__post-icon');
            if (paginationItem) {
                if (!gridClass) {
                    gridClass = paginationItem.className ? paginationItem.className : '';
                }
                paginationItem.className = "";
                paginationItem.classList.add('is-active');
                sidebar.classList.add('has-active');
                this.setMapZoom(collectionItem);
                paginationItem.scrollIntoView({block: "start"});
            }

            if (backButton) {
                sidebar.classList.remove('has-active');
                sidebar.querySelectorAll('[js-pagination-item]').forEach(item => {
                    if (gridClass) {
                        !item.classList.contains(gridClass) ? item.classList.add(gridClass) : '';
                    }
                    item.classList.remove('is-active');
                });
            }
        })
    }

    setMapZoom(collectionItem) {
        if (collectionItem && collectionItem.hasAttribute('js-map-lat') && collectionItem.hasAttribute('js-map-lng')) {
            let lat = collectionItem.getAttribute('js-map-lat');
            let lng = collectionItem.getAttribute('js-map-lng');
            if (lat && lng) {
                let markerLatLng = L.latLng(lat, lng);
                let markers = this.clusters;
                let marker;
                markers.getLayers().forEach(function (layer) {
                    if (layer instanceof L.Marker && layer.getLatLng().equals(markerLatLng)) {
                        marker = layer;
                    } else if (layer instanceof L.MarkerCluster) {
                        layer.getAllChildMarkers().forEach(function (child) {
                            if (child.getLatLng().equals(markerLatLng)) {
                                marker = child;
                            }
                        });
                    }
                });
                if (marker) {
                    marker.fireEvent('click');
                }
            }
        }
    }
}

export function initializeMapClick() {
    [...document.querySelectorAll('.c-openstreetmap')].forEach(container => {
        let id = container.getAttribute('id') ?? false;
        id && new ShowPost(container, id);
    });
}

export default ShowPost;