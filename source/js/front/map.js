class Map {
    constructor(components, map, markers) {
        this.components = components;
        this.container = document.querySelector('#openstreetmap');
        this.map = map;
        this.markers = markers;
        (this.container && this.map && this.markers) && this.init();
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
        let expand = this.container.querySelector('.openstreetmap__expand-icon');

        this.map.setView([startPosition.lat, startPosition.lng], startPosition.zoom);
        L.tileLayer(tiles?.url ? tiles.url : 'https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: tiles?.attribution ? tiles.attribution : '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.map);

        locations.forEach(location => {
            if (location?.lat && location?.lng && location?.tooltip) {
                let customIcon = false;
                if (location?.icon) {
                    customIcon = location.icon;
                }
                let marker = L.marker([location.lat, location.lng], { icon: this.createMarker(customIcon) });
                marker.bindPopup(this.createTooltip(location.tooltip));
                marker.on('click', (e) => {
                    let latlng = e.latlng ? e.latlng : (e.sourceTarget?._latlng ? e.sourceTarget?._latlng : false);
                    let zoomLevel = this.map.getZoom();
                    if (latlng) {
                        if (zoomLevel >= 15) {
                            this.map.setView(latlng);
                        } else {
                            this.map.setView(latlng, 15);
                        }
                    }
                });

                this.markers.addLayer(marker);

            }
        });

        this.markers.addTo(this.map);

        if (expand) {
            expand.addEventListener('click', () => {
                setTimeout(function () {
                    this.map.invalidateSize();
                }, 200);

            });
        }
    }
    
    getPrimaryColor() {
        let color = getComputedStyle(document.documentElement).getPropertyValue('--color-primary');
        return color ? color : '#ae0b05';
    }
    
    createMarker(customIcon) {
        let html = this.components.icon.html;
        let icon = customIcon?.icon ? customIcon.icon : 'location_on';
        let color = customIcon.backgroundColor ? customIcon.backgroundColor : this.getPrimaryColor();

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