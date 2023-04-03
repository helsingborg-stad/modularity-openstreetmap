class Map {
    constructor(components) {
        this.components = components;
        this.container = document.querySelector('#openstreetmap');
        this.container && this.init();
    }

    init() {
        if (!this.container.hasAttribute('js-map-locations') || !this.container.hasAttribute('js-map-start-position')) {
            return;
        }

        let startPosition = JSON.parse(this.container.getAttribute('js-map-start-position'));
        let locations = JSON.parse(this.container.getAttribute('js-map-locations'));

        this.setMapView(locations, startPosition);
    }

    setMapView(locations, startPosition) {
        let map = L.map('openstreetmap__map');
        let expand = this.container.querySelector('.openstreetmap__expand-icon');

        map.setView([startPosition.lat, startPosition.lng], startPosition.zoom);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        locations.forEach(location => {
            if (location?.lat && location?.lng && location?.tooltip) {
                let customIcon = false;
                if (location?.icon) {
                    customIcon = location.icon;
                }
                this.setMarker(map, location.lat, location.lng, location.tooltip, customIcon);
            }
        });

        if (expand) {
            expand.addEventListener('click', () => {
                map.invalidateSize();
            });
        }
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

    createTooltip(tooltip) {
        let html = this.components.tooltip.html;
        html = html.replace('{TOOLTIP_HEADING}', tooltip.title).replace('{TOOLTIP_DIRECTIONS_URL}', tooltip.direction.url).replace('{TOOLTIP_DIRECTIONS_LABEL}', tooltip.direction.label);
        return html;
    }

    setMarker(map, lat, lng, tooltip, customIcon = false) {
        let marker = L.marker([lat, lng], { icon: this.createMarker(customIcon) }).addTo(map);
        marker.bindPopup(this.createTooltip(tooltip));
        
        marker.on('click', (e) => {
            map.setView(e.latlng, 15);
        });
    }
}

export default Map;