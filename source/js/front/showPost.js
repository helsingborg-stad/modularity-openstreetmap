class ShowPost {
    constructor(map) {
        this.container = document.querySelector('#openstreetmap');
        this.map = map;
        (this.container && this.map) && this.handleClick();
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
                console.log("hej");
                if (!gridClass) {
                    gridClass = paginationItem.className ? paginationItem.className : '';
                }
                paginationItem.className = "";
                paginationItem.classList.add('is-active');
                sidebar.classList.add('has-active');
                this.setMapZoom(collectionItem);
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
        console.log(collectionItem);
        if (collectionItem && collectionItem.hasAttribute('js-map-lat') && collectionItem.hasAttribute('js-map-lng')) {
            let lat = collectionItem.getAttribute('js-map-lat');
            let lng = collectionItem.getAttribute('js-map-lng');

            if (lat && lng) {
                let markerLatLng = L.latLng(lat, lng);

                let marker;
                this.map.eachLayer(function (layer) {
                    if (layer instanceof L.Marker && layer.getLatLng().equals(markerLatLng)) {
                        marker = layer;
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