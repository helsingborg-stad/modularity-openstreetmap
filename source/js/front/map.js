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
        let tiles = this.getTilesStyle(this.container);
        this.setMapView(locations, startPosition, tiles);
    }

    setMapView(locations, startPosition, tiles) {
        let map = L.map('openstreetmap__map');
        let expand = this.container.querySelector('.openstreetmap__expand-icon');
        
        map.setView([startPosition.lat, startPosition.lng], startPosition.zoom);
        L.tileLayer(tiles?.url ? tiles.url : 'https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: tiles?.attribution ? tiles.attribution : '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
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

    getTilesStyle(container) {
        let tiles = container.hasAttribute('js-map-style') ? container.getAttribute('js-map-style') : 'default';

        switch (tiles) {
            case 'dark':
                return { 'attribution': '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>', 'url': 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png' };
            case 'pale':
                return { 'attribution': '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributor', 'url': 'https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png' };
            case 'default':
                return {
                    'attribution': '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>', 'url': 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png'
                };
            case 'color':
                return {
                    'attribution': 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ, TomTom, Intermap, iPC, USGS, FAO, NPS, NRCAN, GeoBase, Kadaster NL, Ordnance Survey, Esri Japan, METI, Esri China (Hong Kong), and the GIS User Community', 'url': 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}'
                };
            default:
                return {
                    'attribution': '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>', 'url': 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png'
                };
        }
    }
}

export default Map;