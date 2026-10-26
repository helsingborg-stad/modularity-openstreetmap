export function getTiles(container: HTMLElement): { attribution: string; url: string } {
    const tiles = container.hasAttribute('data-js-map-style')
        ? container.getAttribute('data-js-map-style')
        : 'default';

    switch (tiles) {
        case 'dark':
            return {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            };
        case 'pale':
            return {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles style by <a href="https://www.hotosm.org/" target="_blank">Humanitarian OpenStreetMap Team</a> hosted by <a href="https://openstreetmap.fr/" target="_blank">OpenStreetMap France</a>',
                url: 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png',
            };
        case 'color':
            return {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Tiles courtesy of <a href="https://www.openstreetmap.cat" target="_blank">Breton OpenStreetMap Team</a>',
                url: 'https://tile.openstreetmap.bzh/ca/{z}/{x}/{y}.png',
            };
        default:
            return {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
            };
    }
}