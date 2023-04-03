import L from 'leaflet';
import Map from './front/map';
const MapInstance = new Map(openStreetMapComponents);

acf.add_action('ready', function () {
   console.log(acf);
});