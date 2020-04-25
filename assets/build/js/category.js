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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/js/category.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/js/category.js":
/*!****************************!*\
  !*** ./src/js/category.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_category_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/category.scss */ "./src/scss/category.scss");
/* harmony import */ var _scss_category_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_category_scss__WEBPACK_IMPORTED_MODULE_0__);
function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 * Category scripts, loaded plugin's settings page.
 *
 * @package headless-cms
 */

/**
 * Internal dependencies
 */


(function ($) {
  /**
   * Category Class.
   */
  var Category = /*#__PURE__*/function () {
    /**
     * Constructor.
     *
     * @return {void}
     */
    function Category() {
      _classCallCheck(this, Category);

      this.init();
    }
    /**
     * Init
     *
     * @return {void}
     */


    _createClass(Category, [{
      key: "init",
      value: function init() {
        this.mediaUpload('.hcms_tax_media_button.button');
        this.addEvents();
        this.ajaxRequest();
      }
    }, {
      key: "addEvents",
      value: function addEvents() {
        $('body').on('click', '.hcms_tax_media_remove', function () {
          $('#category-image-id').val('');
          $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
        });
      }
    }, {
      key: "mediaUpload",
      value: function mediaUpload(btnClass) {
        var customMedia = true;
        var origSendAttachment = wp.media.editor.send.attachment;
        $('body').on('click', btnClass, function (e) {
          var btnID = '#' + $(this).attr('id');
          var button = $(btnID);
          customMedia = true;

          wp.media.editor.send.attachment = function (props, attachment) {
            if (customMedia) {
              $('#category-image-id').val(attachment.id);
              $('#category-image-wrapper').html('<img class="custom_media_image" src=""/>');
              $('#category-image-wrapper .custom_media_image').attr('src', attachment.url).css('display', 'block');
            } else {
              return origSendAttachment.apply(btnID, [props, attachment]);
            }
          };

          wp.media.editor.open(button);
          return false;
        });
      }
    }, {
      key: "ajaxRequest",
      value: function ajaxRequest() {
        $(document).ajaxComplete(function (event, xhr, settings) {
          var queryStringArr = settings.data.split('&');

          if (-1 !== $.inArray('action=add-tag', queryStringArr)) {
            var xml = xhr.responseXML;
            var response = $(xml).find('term_id').text();

            if ('' != response) {
              // Clear the thumb image
              $('#category-image-wrapper').html('');
            }
          }
        });
      }
    }]);

    return Category;
  }();

  new Category();
})(jQuery);

/***/ }),

/***/ "./src/scss/category.scss":
/*!********************************!*\
  !*** ./src/scss/category.scss ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ })

/******/ });
//# sourceMappingURL=category.js.map