import L from 'leaflet';
import 'leaflet.markercluster';
import Map from './front/map';
import ShowPost from './front/showPost';

let container = document.querySelector('#openstreetmap');
let map = false;
let markers = false;
if (container) {
    map = L.map('openstreetmap__map');
    markers = L.markerClusterGroup({
        maxClusterRadius: 50
    });
} 


const MapInstance = new Map(openStreetMapComponents, map, markers);
const ShowPostInstance = new ShowPost(map, markers);