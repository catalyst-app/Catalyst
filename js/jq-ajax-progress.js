!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){var n=e.ajax.bind(e);e.ajax=function(r,t){"object"==typeof r&&(t=r,r=void 0),t=t||{};var o;o=t.xhr?t.xhr():e.ajaxSettings.xhr(),t.xhr=function(){return o};var s=t.chunking||e.ajaxSettings.chunking;o.upload.onprogress=null;var i=n(r,t);return i.progress=function(e){var n=0;return o.addEventListener("progress",function(r){var t=[r],o="";3==this.readyState&&s&&(o=this.responseText.substr(n),n=this.responseText.length,t.push(o)),e.apply(this,t)},!1),this},i.uploadProgress=function(e){return o.upload&&o.upload.addEventListener("progress",function(n){e.apply(this,[n])},!1),this},i}});