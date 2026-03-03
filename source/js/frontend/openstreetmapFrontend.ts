import L, { Layer, type Map as LeafletMap, type Marker } from 'leaflet';
import AccessibilityFeatures from './accessibility/accessibilityFeatures';
import FetchEndpointPosts from './api/fetchEndpointPosts';
import CreateMarker from './createMarker/createMarker';
import CreateTooltip from './createMarker/createTooltip';
import AddMarkersFromLocation from './map/addMarkersFromLocation';
import InitializeOsm from './map/initializeMap';
import { invalidateSize, setView } from './map/mapHelpers';
import SetMapTiles from './map/setMapTiles';
import ZoomMarker from './map/zoomMarker';
import AddEndpointPosts from './post/addEndpointPosts';
import PostAdded from './post/postAdded';
import PostMarkerPairs from './post/postMarkerPairs';
import ExpandOnClick from './sidebar/expandOnClick';
import HandlePostsLoadingSpinner from './sidebar/handlePostsLoadingSpinner';
import ObserveSize from './sidebar/observeSize';
import ShowIfNotEmpty from './sidebar/showIfNotEmpty';
import ShowPost from './sidebar/showPost';
import TooltipListener from './sidebar/tooltipListener';

class OpenStreetMap {
    settings: {
        endpoint: string;
        startposition: string;
    };

    private baseClass: string = 'modularity-openstreetmap';

    constructor(private container: HTMLElement) {
        this.settings = this.getSettings();
        const initializeMapInstance = new InitializeOsm(this.container);
        const [map, markers] = initializeMapInstance.create();
        if (map && markers) {
            this.setupMap(map);
            this.setupFeatures(map, markers);
        }
    }

    private setupMap(map: LeafletMap) {
        new SetMapTiles(this.container, map);
        setView(map, JSON.parse(this.settings.startposition));
        map.zoomControl.setPosition('bottomright');
        invalidateSize(map);
    }

    private setupFeatures(map: LeafletMap, markers: Marker[]) {
        const zoomMarkerInstance = new ZoomMarker(map, markers);
        const handlePostsLoadingSpinnerInstance = new HandlePostsLoadingSpinner(this.container);
        const createMarkerInstance = new CreateMarker(this.container);
        const createTooltipInstance = new CreateTooltip(this.container);
        const expandOnClickInstance = new ExpandOnClick(this.container, map, this.baseClass);
        const showPostInstance = new ShowPost(this.container, map, this.baseClass, zoomMarkerInstance);
        const showIfNotEmptyInstance = new ShowIfNotEmpty(this.container, this.baseClass);
        const observeSizeInstance = new ObserveSize(this.container, this.baseClass);
        const tooltipListenerInstance = new TooltipListener(this.container, map, markers);
        const postMarkerPairsInstance = new PostMarkerPairs(this.container);
        const accessibilityFeaturesInstance = new AccessibilityFeatures(
            this.container,
            map,
            markers,
            zoomMarkerInstance,
            this.baseClass,
        );

        const addMarkersFromLocationInstance = new AddMarkersFromLocation(
            this.container,
            map,
            markers,
            postMarkerPairsInstance,
            createMarkerInstance,
            createTooltipInstance,
        );
        const postAddedInstance = new PostAdded(this.container, addMarkersFromLocationInstance);
        const addEndpointPostsInstance = new AddEndpointPosts(this.container, map);
        const fetchEndpointPostsInstance = new FetchEndpointPosts(this.container, this.settings.endpoint);
    }

    private getSettings() {
        console.log(this.container.getAttribute('data-js-map-start-position'));
        return {
            endpoint: this.container.getAttribute('data-js-map-posts-endpoint') ?? '',
            startposition: this.container.getAttribute('data-js-map-start-position') ?? '',
        };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const componentElements = [...document.querySelectorAll('[data-js-modularity-openstreetmap]')];
    componentElements.forEach((element) => {
        new OpenStreetMap(element as HTMLElement);
    });
});