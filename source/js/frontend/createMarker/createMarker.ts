import L, { Map as LeafletMap, Marker } from 'leaflet';
import type { Icon } from '../interface/interface';

class CreateMarker {
    constructor(private container: HTMLElement) { }

    public create(customIcon: Icon | undefined) {
        const template = this.container?.querySelector('.modularity-openstreetmap__pin-icon');
        let html = template?.innerHTML;
        const icon = customIcon?.icon ? customIcon.icon : 'location_on';
        const color = customIcon?.backgroundColor ? customIcon.backgroundColor : this.getPrimaryColor();

        if (!html) return;
        html = html
            .replace('{icon-name}', icon as string)
            .replaceAll('{ICON_NAME}', icon as string)
            .replace('{ICON_BACKGROUND_COLOR}', color as string);
        const marker = L.divIcon({
            className: 'modularity-openstreetmap__icon',
            html: html,
        });

        return marker;
    }

    private getPrimaryColor() {
        const color = getComputedStyle(document.documentElement).getPropertyValue('--color-primary');
        return color ? color : '#ae0b05';
    }
}

export default CreateMarker;
