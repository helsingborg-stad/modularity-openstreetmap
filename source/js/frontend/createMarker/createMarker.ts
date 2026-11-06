import L from 'leaflet';

class CreateMarker {
    constructor(private container: HTMLElement) { }

    public create(sourceElement?: HTMLElement) {
        const html = this.getTemplateHtml(sourceElement);

        if (!html) {
            return;
        }

        const marker = L.divIcon({
            className: 'modularity-openstreetmap__icon',
            html: html,
        });

        return marker;
    }

    private getTemplateHtml(sourceElement?: HTMLElement): string | null {
        const template = sourceElement?.querySelector('[data-js-map-item-template]') as HTMLTemplateElement | null;

        if (!template?.innerHTML) {
            return null;
        }

        const html = template.innerHTML.trim();
        return html.length > 0 ? html : null;
    }
}

export default CreateMarker;
