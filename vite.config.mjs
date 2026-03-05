import { createViteConfig } from 'vite-config-factory';

const entries = {
	'js/modularity-open-street-map-backend': './source/js/backend/openstreetmapBackend.js',
	'js/modularity-open-street-map-frontend': './source/js/frontend/openstreetmapFrontend.ts',
	'css/modularity-open-street-map': './source/sass/modularity-open-street-map.scss',
};

export default createViteConfig(entries, {
	outDir: 'assets/dist',
	manifestFile: 'manifest.json',
});
