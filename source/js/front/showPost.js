class ShowPost {
    constructor(map, markers) {
        this.container = document.querySelector('#openstreetmap');
        this.map = map;
        this.markers = markers;
        (this.container && this.map && this.markers) && this.handleClick();
    }
    handleClick() {
        let paginationContainer = this.container.querySelector('[js-pagination-container]');
        let sidebar = this.container.querySelector('.openstreetmap__sidebar');
        let gridClass = false;
        
        paginationContainer.addEventListener('click', (e) => {
            let collectionItem = e.target.closest('.openstreetmap__collection__item');
            let paginationItem = collectionItem?.parentElement;
            let backButton = e.target.closest('.openstreetmap__post-icon');
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

                let marker;
                this.markers.getLayers().forEach(function (layer) {
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

export default ShowPost;