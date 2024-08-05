import Module from "./module";
import Gutenberg from "./gutenberg";

// Initialize the correct class based on the context.
document.addEventListener('DOMContentLoaded', function() {
    if (typeof wp !== 'undefined' && typeof acf !== 'undefined') {
        if (document.body.classList.contains('block-editor-page')) {
            new Gutenberg();
        } else {
            new Module();
        }
    }
});