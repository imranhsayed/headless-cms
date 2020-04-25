/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/settings.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/settings.js":
/*!****************************!*\
  !*** ./src/js/settings.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_settings_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/settings.scss */ "./src/scss/settings.scss");
/* harmony import */ var _scss_settings_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_settings_scss__WEBPACK_IMPORTED_MODULE_0__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 * Settings scripts, loaded plugin's settings page.
 *
 * @package headless-cms
 */

/**
 * Internal dependencies
 */

/**
 * Plugin Settings.
 *
 * @package hcms-app-features
 */



(function ($) {
  /**
   * Settings Class.
   */
  var Settings = /*#__PURE__*/function () {
    /**
     * Constructor.
     *
     * @return {void}
     */
    function Settings() {
      _classCallCheck(this, Settings);

      this.init();
    }
    /**
     * Init
     *
     * @return {void}
     */


    _createClass(Settings, [{
      key: "init",
      value: function init() {
        this.handleMediaUpload('#hcms-hero-img-section');
        this.handleMediaUpload('#hcms-srch-back-img-section');
      }
      /**
       * Handle Media Upload
       *
       * @param {string} sectionId Section Id.
       *
       * @return {void}
       */

    }, {
      key: "handleMediaUpload",
      value: function handleMediaUpload(sectionId) {
        /**
         * Upload media.
         */
        var mediaUploader; // When the Upload Button is clicked, open the WordPress Media Uploader to select/change the image.

        $(sectionId + ' .hcms-hero-upload-btn').click(function (event) {
          event.preventDefault();

          if (mediaUploader) {
            mediaUploader.open();
            return;
          }
          /* eslint-disable */


          mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
              text: 'Choose Image'
            },
            multiple: false
          });
          /* eslint-enable */

          mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            var inputEl = $(sectionId + ' .hcms-hero-input');
            var imgEl = $(sectionId + ' .hcms-hero-img');
            var uploadBtnEl = $(sectionId + ' .hcms-hero-upload-btn');
            imgEl.attr('src', attachment.url);
            inputEl.val(attachment.url);
            uploadBtnEl.val('Change Logo');
            $(sectionId).addClass('uploaded');
          });
          mediaUploader.open();
        });
        this.handleRemoveMedia(sectionId);
      }
      /**
       * Handles Remove Media.
       *
       * @param {string} sectionId Section Id.
       *
       * @return {void}
       */

    }, {
      key: "handleRemoveMedia",
      value: function handleRemoveMedia(sectionId) {
        // When the remove media button is clicked, remove the image url and the image.
        $(sectionId + ' .hcms-hero-remove-btn').on('click', function () {
          var inputEl = $(sectionId + ' .hcms-hero-input');
          var imgEl = $(sectionId + ' .hcms-hero-img');
          var uploadBtnEl = $(sectionId + ' .hcms-hero-upload-btn');
          imgEl.attr('src', '');
          inputEl.val('');
          uploadBtnEl.val('Select Logo');
          $(sectionId).removeClass('uploaded');
        });
      }
    }]);

    return Settings;
  }();

  new Settings();
})(jQuery);

/***/ }),

/***/ "./src/scss/settings.scss":
/*!********************************!*\
  !*** ./src/scss/settings.scss ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })

/******/ });
//# sourceMappingURL=settings.js.map