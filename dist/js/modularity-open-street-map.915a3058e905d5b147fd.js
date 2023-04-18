/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./source/js/front/showPost.js":
/*!*************************************!*\
  !*** ./source/js/front/showPost.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
class ShowPost {
    constructor() {
        this.container = document.querySelector('#openstreetmap');
        (window.leafletMap && this.container && window.leafletClusters) && this.handleClick();
    }
    handleClick() {
        
        let paginationContainer = this.container.querySelector('[js-pagination-container]');
        let sidebar = this.container.querySelector('.openstreetmap__sidebar');
        let gridClass = false;
        
        paginationContainer.addEventListener('click', (e) => {
            let collectionItem = e.target.closest('.openstreetmap__collection__item');
            let paginationItem = collectionItem?.parentElement;
            let backButton = e.target.closest('.openstreetmap__post-icon');
            if (paginationItem) {
                if (!gridClass) {
                    gridClass = paginationItem.className ? paginationItem.className : '';
                }
                paginationItem.className = "";
                paginationItem.classList.add('is-active');
                sidebar.classList.add('has-active');
                this.setMapZoom(collectionItem);
                paginationItem.scrollIntoView({block: "start"});
            }

            if (backButton) {
                sidebar.classList.remove('has-active');
                sidebar.querySelectorAll('[js-pagination-item]').forEach(item => {
                    if (gridClass) {
                        !item.classList.contains(gridClass) ? item.classList.add(gridClass) : '';
                    }
                    item.classList.remove('is-active');
                });
            }
        })
    }

    setMapZoom(collectionItem) {
        if (collectionItem && collectionItem.hasAttribute('js-map-lat') && collectionItem.hasAttribute('js-map-lng')) {
            let lat = collectionItem.getAttribute('js-map-lat');
            let lng = collectionItem.getAttribute('js-map-lng');
            if (lat && lng) {
                let markerLatLng = L.latLng(lat, lng);
                let markers = window.leafletClusters;
                let marker;
                markers.getLayers().forEach(function (layer) {
                    if (layer instanceof L.Marker && layer.getLatLng().equals(markerLatLng)) {
                        marker = layer;
                    } else if (layer instanceof L.MarkerCluster) {
                        layer.getAllChildMarkers().forEach(function (child) {
                            if (child.getLatLng().equals(markerLatLng)) {
                                marker = child;
                            }
                        });
                    }
                });
                if (marker) {
                    marker.fireEvent('click');
                }
            }
        }
    }
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ShowPost);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************************************!*\
  !*** ./source/js/modularity-open-street-map.js ***!
  \*************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _front_showPost__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./front/showPost */ "./source/js/front/showPost.js");
// import Map from './front/map';


// const MapInstance = new Map(openStreetMapComponents, map, markers);
const ShowPostInstance = new _front_showPost__WEBPACK_IMPORTED_MODULE_0__["default"](window.leafletClusters);
})();

/******/ })()
;
//# sourceMappingURL=modularity-open-street-map.915a3058e905d5b147fd.js.map