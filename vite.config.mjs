import { createViteConfig } from "vite-config-factory";

const entries = {
	'js/modularity-open-street-map': './source/js/modularity-open-street-map.js',
	'css/modularity-open-street-map': './source/sass/modularity-open-street-map.scss',
};

export default createViteConfig(entries, {
	outDir: "assets/dist",
	manifestFile: "manifest.json",
});
