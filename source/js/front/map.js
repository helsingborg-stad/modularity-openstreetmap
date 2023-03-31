class Map {
    constructor(components) {
        this.components = components;
        document.querySelector('#openstreetmap') && this.init(L);
    }

    init() {
        const container = document.querySelector('#openstreetmap');
        if (!container.hasAttribute('js-map-locations') || !container.hasAttribute('js-map-start-position')) {
            return;
        }

        let startPosition = JSON.parse(container.getAttribute('js-map-start-position'));
        let locations = JSON.parse(container.getAttribute('js-map-locations'));
        this.setMapView(locations, startPosition);
    }

    setMapView(locations, startPosition) {
        let map = L.map('openstreetmap_map');
        map.setView([startPosition.lat, startPosition.lng], startPosition.zoom);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        locations.forEach(location => {
            if (location?.lat && location?.lng && location?.title) {
                let customIcon = false;
                if (location?.icon) {
                    customIcon = location.icon;
                }
                this.setMarkers(map, location.lat, location.lng, location.title, customIcon);
            }
        });
    }

    getPrimaryColor() {
        let color = getComputedStyle(document.documentElement).getPropertyValue('--color-primary');
        return color ? color : '#ae0b05';
    }
    
    createMarker(customIcon) {
        let html = this.components.icon.html;
        console.log(html);
        let icon = customIcon?.icon?.src ? customIcon.icon.src : 'location_on';
        let color = customIcon?.color ? customIcon.color : this.getPrimaryColor();

        html = html.replace('{icon-name}', icon).replace('{ICON_NAME}', icon).replace('{ICON_BACKGROUND_COLOR}', color);
        let marker = L.divIcon({
            className: 'openstreetmap__icon',
            html: html
        });

        return marker;
    }

    setMarkers(map, lat, lng, tooltip, customIcon = false) {
        let marker = L.marker([lat, lng], { icon: this.createMarker(customIcon) }).addTo(map);
        marker.bindPopup(tooltip);
    }

    icon() {
        const icon = L.icon({
            iconUrl: 'https://upload.wikimedia.org/wikipedia/commons/e/ed/Map_pin_icon.svg',
            iconSize: [28, 39]
        });
        return icon;
    }
}

export default Map;