import L from 'leaflet';
import 'leaflet.markercluster';
import Map from './front/map';
import ShowPost from './front/showPost';

let container = document.querySelector('#openstreetmap');
let map = false;
if (container) {
    map = L.map('openstreetmap__map');
} 

const MapInstance = new Map(openStreetMapComponents, map);
const ShowPostInstance = new ShowPost(map);