/*!
 * Vue.js v2.5.17
 * (c) 2014-2018 Evan You
 * Released under the MIT License.
 */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):e.Vue=t()}(this,function(){"use strict";var y=Object.freeze({});function M(e){return null==e}function D(e){return null!=e}function S(e){return!0===e}function T(e){return"string"==typeof e||"number"==typeof e||"symbol"==typeof e||"boolean"==typeof e}function P(e){return null!==e&&"object"==typeof e}var r=Object.prototype.toString;function l(e){return"[object Object]"===r.call(e)}function i(e){var t=parseFloat(String(e));return 0<=t&&Math.floor(t)===t&&isFinite(e)}function t(e){return null==e?"":"object"==typeof e?JSON.stringify(e,null,2):String(e)}function F(e){var t=parseFloat(e);return isNaN(t)?e:t}function s(e,t){for(var n=Object.create(null),r=e.split(","),i=0;i<r.length;i++)n[r[i]]=!0;return t?function(e){return n[e.toLowerCase()]}:function(e){return n[e]}}var c=s("slot,component",!0),u=s("key,ref,slot,slot-scope,is");function f(e,t){if(e.length){var n=e.indexOf(t);if(-1<n)return e.splice(n,1)}}var n=Object.prototype.hasOwnProperty;function p(e,t){return n.call(e,t)}function e(t){var n=Object.create(null);return function(e){return n[e]||(n[e]=t(e))}}var o=/-(\w)/g,g=e(function(e){return e.replace(o,function(e,t){return t?t.toUpperCase():""})}),d=e(function(e){return e.charAt(0).toUpperCase()+e.slice(1)}),a=/\B([A-Z])/g,_=e(function(e){return e.replace(a,"-$1").toLowerCase()});var v=Function.prototype.bind?function(e,t){return e.bind(t)}:function(n,r){function e(e){var t=arguments.length;return t?1<t?n.apply(r,arguments):n.call(r,e):n.call(r)}return e._length=n.length,e};function h(e,t){t=t||0;for(var n=e.length-t,r=new Array(n);n--;)r[n]=e[n+t];return r}function m(e,t){for(var n in t)e[n]=t[n];return e}function b(e){for(var t={},n=0;n<e.length;n++)e[n]&&m(t,e[n]);return t}function $(e,t,n){}var O=function(e,t,n){return!1},w=function(e){return e};function C(t,n){if(t===n)return!0;var e=P(t),r=P(n);if(!e||!r)return!e&&!r&&String(t)===String(n);try{var i=Array.isArray(t),o=Array.isArray(n);if(i&&o)return t.length===n.length&&t.every(function(e,t){return C(e,n[t])});if(i||o)return!1;var a=Object.keys(t),s=Object.keys(n);return a.length===s.length&&a.every(function(e){return C(t[e],n[e])})}catch(e){return!1}}function x(e,t){for(var n=0;n<e.length;n++)if(C(e[n],t))return n;return-1}function R(e){var t=!1;return function(){t||(t=!0,e.apply(this,arguments))}}var E="data-server-rendered",k=["component","directive","filter"],A=["beforeCreate","created","beforeMount","mounted","beforeUpdate","updated","beforeDestroy","destroyed","activated","deactivated","errorCaptured"],j={optionMergeStrategies:Object.create(null),silent:!1,productionTip:!1,devtools:!1,performance:!1,errorHandler:null,warnHandler:null,ignoredElements:[],keyCodes:Object.create(null),isReservedTag:O,isReservedAttr:O,isUnknownElement:O,getTagNamespace:$,parsePlatformTagName:w,mustUseProp:O,_lifecycleHooks:A};function N(e,t,n,r){Object.defineProperty(e,t,{value:n,enumerable:!!r,writable:!0,configurable:!0})}var L=/[^\w.$]/;var I,H="__proto__"in{},B="undefined"!=typeof window,U="undefined"!=typeof WXEnvironment&&!!WXEnvironment.platform,V=U&&WXEnvironment.platform.toLowerCase(),z=B&&window.navigator.userAgent.toLowerCase(),K=z&&/msie|trident/.test(z),J=z&&0<z.indexOf("msie 9.0"),q=z&&0<z.indexOf("edge/"),W=(z&&z.indexOf("android"),z&&/iphone|ipad|ipod|ios/.test(z)||"ios"===V),G=(z&&/chrome\/\d+/.test(z),{}.watch),Z=!1;if(B)try{var X={};Object.defineProperty(X,"passive",{get:function(){Z=!0}}),window.addEventListener("test-passive",null,X)}catch(e){}var Y=function(){return void 0===I&&(I=!B&&!U&&"undefined"!=typeof global&&"server"===global.process.env.VUE_ENV),I},Q=B&&window.__VUE_DEVTOOLS_GLOBAL_HOOK__;function ee(e){return"function"==typeof e&&/native code/.test(e.toString())}var te,ne="undefined"!=typeof Symbol&&ee(Symbol)&&"undefined"!=typeof Reflect&&ee(Reflect.ownKeys);te="undefined"!=typeof Set&&ee(Set)?Set:function(){function e(){this.set=Object.create(null)}return e.prototype.has=function(e){return!0===this.set[e]},e.prototype.add=function(e){this.set[e]=!0},e.prototype.clear=function(){this.set=Object.create(null)},e}();var re=$,ie=0,oe=function(){this.id=ie++,this.subs=[]};oe.prototype.addSub=function(e){this.subs.push(e)},oe.prototype.removeSub=function(e){f(this.subs,e)},oe.prototype.depend=function(){oe.target&&oe.target.addDep(this)},oe.prototype.notify=function(){for(var e=this.subs.slice(),t=0,n=e.length;t<n;t++)e[t].update()},oe.target=null;var ae=[];function se(e){oe.target&&ae.push(oe.target),oe.target=e}function ce(){oe.target=ae.pop()}var le=function(e,t,n,r,i,o,a,s){this.tag=e,this.data=t,this.children=n,this.text=r,this.elm=i,this.ns=void 0,this.context=o,this.fnContext=void 0,this.fnOptions=void 0,this.fnScopeId=void 0,this.key=t&&t.key,this.componentOptions=a,this.componentInstance=void 0,this.parent=void 0,this.raw=!1,this.isStatic=!1,this.isRootInsert=!0,this.isComment=!1,this.isCloned=!1,this.isOnce=!1,this.asyncFactory=s,this.asyncMeta=void 0,this.isAsyncPlaceholder=!1},ue={child:{configurable:!0}};ue.child.get=function(){return this.componentInstance},Object.defineProperties(le.prototype,ue);var fe=function(e){void 0===e&&(e="");var t=new le;return t.text=e,t.isComment=!0,t};function pe(e){return new le(void 0,void 0,void 0,String(e))}function de(e){var t=new le(e.tag,e.data,e.children,e.text,e.elm,e.context,e.componentOptions,e.asyncFactory);return t.ns=e.ns,t.isStatic=e.isStatic,t.key=e.key,t.isComment=e.isComment,t.fnContext=e.fnContext,t.fnOptions=e.fnOptions,t.fnScopeId=e.fnScopeId,t.isCloned=!0,t}var ve=Array.prototype,he=Object.create(ve);["push","pop","shift","unshift","splice","sort","reverse"].forEach(function(o){var a=ve[o];N(he,o,function(){for(var e=[],t=arguments.length;t--;)e[t]=arguments[t];var n,r=a.apply(this,e),i=this.__ob__;switch(o){case"push":case"unshift":n=e;break;case"splice":n=e.slice(2)}return n&&i.observeArray(n),i.dep.notify(),r})});var me=Object.getOwnPropertyNames(he),ye=!0;function ge(e){ye=e}var _e=function(e){(this.value=e,this.dep=new oe,this.vmCount=0,N(e,"__ob__",this),Array.isArray(e))?((H?be:$e)(e,he,me),this.observeArray(e)):this.walk(e)};function be(e,t,n){e.__proto__=t}function $e(e,t,n){for(var r=0,i=n.length;r<i;r++){var o=n[r];N(e,o,t[o])}}function we(e,t){var n;if(P(e)&&!(e instanceof le))return p(e,"__ob__")&&e.__ob__ instanceof _e?n=e.__ob__:ye&&!Y()&&(Array.isArray(e)||l(e))&&Object.isExtensible(e)&&!e._isVue&&(n=new _e(e)),t&&n&&n.vmCount++,n}function Ce(n,e,r,t,i){var o=new oe,a=Object.getOwnPropertyDescriptor(n,e);if(!a||!1!==a.configurable){var s=a&&a.get;s||2!==arguments.length||(r=n[e]);var c=a&&a.set,l=!i&&we(r);Object.defineProperty(n,e,{enumerable:!0,configurable:!0,get:function(){var e=s?s.call(n):r;return oe.target&&(o.depend(),l&&(l.dep.depend(),Array.isArray(e)&&function e(t){for(var n=void 0,r=0,i=t.length;r<i;r++)(n=t[r])&&n.__ob__&&n.__ob__.dep.depend(),Array.isArray(n)&&e(n)}(e))),e},set:function(e){var t=s?s.call(n):r;e===t||e!=e&&t!=t||(c?c.call(n,e):r=e,l=!i&&we(e),o.notify())}})}}function xe(e,t,n){if(Array.isArray(e)&&i(t))return e.length=Math.max(e.length,t),e.splice(t,1,n),n;if(t in e&&!(t in Object.prototype))return e[t]=n;var r=e.__ob__;return e._isVue||r&&r.vmCount?n:r?(Ce(r.value,t,n),r.dep.notify(),n):e[t]=n}function ke(e,t){if(Array.isArray(e)&&i(t))e.splice(t,1);else{var n=e.__ob__;e._isVue||n&&n.vmCount||p(e,t)&&(delete e[t],n&&n.dep.notify())}}_e.prototype.walk=function(e){for(var t=Object.keys(e),n=0;n<t.length;n++)Ce(e,t[n])},_e.prototype.observeArray=function(e){for(var t=0,n=e.length;t<n;t++)we(e[t])};var Ae=j.optionMergeStrategies;function Oe(e,t){if(!t)return e;for(var n,r,i,o=Object.keys(t),a=0;a<o.length;a++)r=e[n=o[a]],i=t[n],p(e,n)?l(r)&&l(i)&&Oe(r,i):xe(e,n,i);return e}function Se(n,r,i){return i?function(){var e="function"==typeof r?r.call(i,i):r,t="function"==typeof n?n.call(i,i):n;return e?Oe(e,t):t}:r?n?function(){return Oe("function"==typeof r?r.call(this,this):r,"function"==typeof n?n.call(this,this):n)}:r:n}function Te(e,t){return t?e?e.concat(t):Array.isArray(t)?t:[t]:e}function Ee(e,t,n,r){var i=Object.create(e||null);return t?m(i,t):i}Ae.data=function(e,t,n){return n?Se(e,t,n):t&&"function"!=typeof t?e:Se(e,t)},A.forEach(function(e){Ae[e]=Te}),k.forEach(function(e){Ae[e+"s"]=Ee}),Ae.watch=function(e,t,n,r){if(e===G&&(e=void 0),t===G&&(t=void 0),!t)return Object.create(e||null);if(!e)return t;var i={};for(var o in m(i,e),t){var a=i[o],s=t[o];a&&!Array.isArray(a)&&(a=[a]),i[o]=a?a.concat(s):Array.isArray(s)?s:[s]}return i},Ae.props=Ae.methods=Ae.inject=Ae.computed=function(e,t,n,r){if(!e)return t;var i=Object.create(null);return m(i,e),t&&m(i,t),i},Ae.provide=Se;var je=function(e,t){return void 0===t?e:t};function Ne(n,r,i){"function"==typeof r&&(r=r.options),function(e,t){var n=e.props;if(n){var r,i,o={};if(Array.isArray(n))for(r=n.length;r--;)"string"==typeof(i=n[r])&&(o[g(i)]={type:null});else if(l(n))for(var a in n)i=n[a],o[g(a)]=l(i)?i:{type:i};e.props=o}}(r),function(e,t){var n=e.inject;if(n){var r=e.inject={};if(Array.isArray(n))for(var i=0;i<n.length;i++)r[n[i]]={from:n[i]};else if(l(n))for(var o in n){var a=n[o];r[o]=l(a)?m({from:o},a):{from:a}}}}(r),function(e){var t=e.directives;if(t)for(var n in t){var r=t[n];"function"==typeof r&&(t[n]={bind:r,update:r})}}(r);var e=r.extends;if(e&&(n=Ne(n,e,i)),r.mixins)for(var t=0,o=r.mixins.length;t<o;t++)n=Ne(n,r.mixins[t],i);var a,s={};for(a in n)c(a);for(a in r)p(n,a)||c(a);function c(e){var t=Ae[e]||je;s[e]=t(n[e],r[e],i,e)}return s}function Le(e,t,n,r){if("string"==typeof n){var i=e[t];if(p(i,n))return i[n];var o=g(n);if(p(i,o))return i[o];var a=d(o);return p(i,a)?i[a]:i[n]||i[o]||i[a]}}function Ie(e,t,n,r){var i=t[e],o=!p(n,e),a=n[e],s=Pe(Boolean,i.type);if(-1<s)if(o&&!p(i,"default"))a=!1;else if(""===a||a===_(e)){var c=Pe(String,i.type);(c<0||s<c)&&(a=!0)}if(void 0===a){a=function(e,t,n){if(!p(t,"default"))return;var r=t.default;if(e&&e.$options.propsData&&void 0===e.$options.propsData[n]&&void 0!==e._props[n])return e._props[n];return"function"==typeof r&&"Function"!==Me(t.type)?r.call(e):r}(r,i,e);var l=ye;ge(!0),we(a),ge(l)}return a}function Me(e){var t=e&&e.toString().match(/^\s*function (\w+)/);return t?t[1]:""}function De(e,t){return Me(e)===Me(t)}function Pe(e,t){if(!Array.isArray(t))return De(t,e)?0:-1;for(var n=0,r=t.length;n<r;n++)if(De(t[n],e))return n;return-1}function Fe(e,t,n){if(t)for(var r=t;r=r.$parent;){var i=r.$options.errorCaptured;if(i)for(var o=0;o<i.length;o++)try{if(!1===i[o].call(r,e,t,n))return}catch(e){Re(e,r,"errorCaptured hook")}}Re(e,t,n)}function Re(e,t,n){if(j.errorHandler)try{return j.errorHandler.call(null,e,t,n)}catch(e){He(e,null,"config.errorHandler")}He(e,t,n)}function He(e,t,n){if(!B&&!U||"undefined"==typeof console)throw e;console.error(e)}var Be,Ue,Ve=[],ze=!1;function Ke(){ze=!1;for(var e=Ve.slice(0),t=Ve.length=0;t<e.length;t++)e[t]()}var Je=!1;if("undefined"!=typeof setImmediate&&ee(setImmediate))Ue=function(){setImmediate(Ke)};else if("undefined"==typeof MessageChannel||!ee(MessageChannel)&&"[object MessageChannelConstructor]"!==MessageChannel.toString())Ue=function(){setTimeout(Ke,0)};else{var qe=new MessageChannel,We=qe.port2;qe.port1.onmessage=Ke,Ue=function(){We.postMessage(1)}}if("undefined"!=typeof Promise&&ee(Promise)){var Ge=Promise.resolve();Be=function(){Ge.then(Ke),W&&setTimeout($)}}else Be=Ue;function Ze(e,t){var n;if(Ve.push(function(){if(e)try{e.call(t)}catch(e){Fe(e,t,"nextTick")}else n&&n(t)}),ze||(ze=!0,Je?Ue():Be()),!e&&"undefined"!=typeof Promise)return new Promise(function(e){n=e})}var Xe=new te;function Ye(e){!function e(t,n){var r,i;var o=Array.isArray(t);if(!o&&!P(t)||Object.isFrozen(t)||t instanceof le)return;if(t.__ob__){var a=t.__ob__.dep.id;if(n.has(a))return;n.add(a)}if(o)for(r=t.length;r--;)e(t[r],n);else for(i=Object.keys(t),r=i.length;r--;)e(t[i[r]],n)}(e,Xe),Xe.clear()}var Qe,et=e(function(e){var t="&"===e.charAt(0),n="~"===(e=t?e.slice(1):e).charAt(0),r="!"===(e=n?e.slice(1):e).charAt(0);return{name:e=r?e.slice(1):e,once:n,capture:r,passive:t}});function tt(e){function i(){var e=arguments,t=i.fns;if(!Array.isArray(t))return t.apply(null,arguments);for(var n=t.slice(),r=0;r<n.length;r++)n[r].apply(null,e)}return i.fns=e,i}function nt(e,t,n,r,i){var o,a,s,c;for(o in e)a=e[o],s=t[o],c=et(o),M(a)||(M(s)?(M(a.fns)&&(a=e[o]=tt(a)),n(c.name,a,c.once,c.capture,c.passive,c.params)):a!==s&&(s.fns=a,e[o]=s));for(o in t)M(e[o])&&r((c=et(o)).name,t[o],c.capture)}function rt(e,t,n){var r;e instanceof le&&(e=e.data.hook||(e.data.hook={}));var i=e[t];function o(){n.apply(this,arguments),f(r.fns,o)}M(i)?r=tt([o]):D(i.fns)&&S(i.merged)?(r=i).fns.push(o):r=tt([i,o]),r.merged=!0,e[t]=r}function it(e,t,n,r,i){if(D(t)){if(p(t,n))return e[n]=t[n],i||delete t[n],!0;if(p(t,r))return e[n]=t[r],i||delete t[r],!0}return!1}function ot(e){return T(e)?[pe(e)]:Array.isArray(e)?function e(t,n){var r=[];var i,o,a,s;for(i=0;i<t.length;i++)M(o=t[i])||"boolean"==typeof o||(a=r.length-1,s=r[a],Array.isArray(o)?0<o.length&&(at((o=e(o,(n||"")+"_"+i))[0])&&at(s)&&(r[a]=pe(s.text+o[0].text),o.shift()),r.push.apply(r,o)):T(o)?at(s)?r[a]=pe(s.text+o):""!==o&&r.push(pe(o)):at(o)&&at(s)?r[a]=pe(s.text+o.text):(S(t._isVList)&&D(o.tag)&&M(o.key)&&D(n)&&(o.key="__vlist"+n+"_"+i+"__"),r.push(o)));return r}(e):void 0}function at(e){return D(e)&&D(e.text)&&!1===e.isComment}function st(e,t){return(e.__esModule||ne&&"Module"===e[Symbol.toStringTag])&&(e=e.default),P(e)?t.extend(e):e}function ct(e){return e.isComment&&e.asyncFactory}function lt(e){if(Array.isArray(e))for(var t=0;t<e.length;t++){var n=e[t];if(D(n)&&(D(n.componentOptions)||ct(n)))return n}}function ut(e,t,n){n?Qe.$once(e,t):Qe.$on(e,t)}function ft(e,t){Qe.$off(e,t)}function pt(e,t,n){Qe=e,nt(t,n||{},ut,ft),Qe=void 0}function dt(e,t){var n={};if(!e)return n;for(var r=0,i=e.length;r<i;r++){var o=e[r],a=o.data;if(a&&a.attrs&&a.attrs.slot&&delete a.attrs.slot,o.context!==t&&o.fnContext!==t||!a||null==a.slot)(n.default||(n.default=[])).push(o);else{var s=a.slot,c=n[s]||(n[s]=[]);"template"===o.tag?c.push.apply(c,o.children||[]):c.push(o)}}for(var l in n)n[l].every(vt)&&delete n[l];return n}function vt(e){return e.isComment&&!e.asyncFactory||" "===e.text}function ht(e,t){t=t||{};for(var n=0;n<e.length;n++)Array.isArray(e[n])?ht(e[n],t):t[e[n].key]=e[n].fn;return t}var mt=null;function yt(e){for(;e&&(e=e.$parent);)if(e._inactive)return!0;return!1}function gt(e,t){if(t){if(e._directInactive=!1,yt(e))return}else if(e._directInactive)return;if(e._inactive||null===e._inactive){e._inactive=!1;for(var n=0;n<e.$children.length;n++)gt(e.$children[n]);_t(e,"activated")}}function _t(t,n){se();var e=t.$options[n];if(e)for(var r=0,i=e.length;r<i;r++)try{e[r].call(t)}catch(e){Fe(e,t,n+" hook")}t._hasHookEvent&&t.$emit("hook:"+n),ce()}var bt=[],$t=[],wt={},Ct=!1,xt=!1,kt=0;function At(){var e,t;for(xt=!0,bt.sort(function(e,t){return e.id-t.id}),kt=0;kt<bt.length;kt++)t=(e=bt[kt]).id,wt[t]=null,e.run();var n=$t.slice(),r=bt.slice();kt=bt.length=$t.length=0,wt={},Ct=xt=!1,function(e){for(var t=0;t<e.length;t++)e[t]._inactive=!0,gt(e[t],!0)}(n),function(e){var t=e.length;for(;t--;){var n=e[t],r=n.vm;r._watcher===n&&r._isMounted&&_t(r,"updated")}}(r),Q&&j.devtools&&Q.emit("flush")}var Ot=0,St=function(e,t,n,r,i){this.vm=e,i&&(e._watcher=this),e._watchers.push(this),r?(this.deep=!!r.deep,this.user=!!r.user,this.lazy=!!r.lazy,this.sync=!!r.sync):this.deep=this.user=this.lazy=this.sync=!1,this.cb=n,this.id=++Ot,this.active=!0,this.dirty=this.lazy,this.deps=[],this.newDeps=[],this.depIds=new te,this.newDepIds=new te,this.expression="","function"==typeof t?this.getter=t:(this.getter=function(e){if(!L.test(e)){var n=e.split(".");return function(e){for(var t=0;t<n.length;t++){if(!e)return;e=e[n[t]]}return e}}}(t),this.getter||(this.getter=function(){})),this.value=this.lazy?void 0:this.get()};St.prototype.get=function(){var e;se(this);var t=this.vm;try{e=this.getter.call(t,t)}catch(e){if(!this.user)throw e;Fe(e,t,'getter for watcher "'+this.expression+'"')}finally{this.deep&&Ye(e),ce(),this.cleanupDeps()}return e},St.prototype.addDep=function(e){var t=e.id;this.newDepIds.has(t)||(this.newDepIds.add(t),this.newDeps.push(e),this.depIds.has(t)||e.addSub(this))},St.prototype.cleanupDeps=function(){for(var e=this.deps.length;e--;){var t=this.deps[e];this.newDepIds.has(t.id)||t.removeSub(this)}var n=this.depIds;this.depIds=this.newDepIds,this.newDepIds=n,this.newDepIds.clear(),n=this.deps,this.deps=this.newDeps,this.newDeps=n,this.newDeps.length=0},St.prototype.update=function(){this.lazy?this.dirty=!0:this.sync?this.run():function(e){var t=e.id;if(null==wt[t]){if(wt[t]=!0,xt){for(var n=bt.length-1;kt<n&&bt[n].id>e.id;)n--;bt.splice(n+1,0,e)}else bt.push(e);Ct||(Ct=!0,Ze(At))}}(this)},St.prototype.run=function(){if(this.active){var e=this.get();if(e!==this.value||P(e)||this.deep){var t=this.value;if(this.value=e,this.user)try{this.cb.call(this.vm,e,t)}catch(e){Fe(e,this.vm,'callback for watcher "'+this.expression+'"')}else this.cb.call(this.vm,e,t)}}},St.prototype.evaluate=function(){this.value=this.get(),this.dirty=!1},St.prototype.depend=function(){for(var e=this.deps.length;e--;)this.deps[e].depend()},St.prototype.teardown=function(){if(this.active){this.vm._isBeingDestroyed||f(this.vm._watchers,this);for(var e=this.deps.length;e--;)this.deps[e].removeSub(this);this.active=!1}};var Tt={enumerable:!0,configurable:!0,get:$,set:$};function Et(e,t,n){Tt.get=function(){return this[t][n]},Tt.set=function(e){this[t][n]=e},Object.defineProperty(e,n,Tt)}function jt(e){e._watchers=[];var t=e.$options;t.props&&function(n,r){var i=n.$options.propsData||{},o=n._props={},a=n.$options._propKeys=[];n.$parent&&ge(!1);var e=function(e){a.push(e);var t=Ie(e,r,i,n);Ce(o,e,t),e in n||Et(n,"_props",e)};for(var t in r)e(t);ge(!0)}(e,t.props),t.methods&&function(e,t){e.$options.props;for(var n in t)e[n]=null==t[n]?$:v(t[n],e)}(e,t.methods),t.data?function(e){var t=e.$options.data;l(t=e._data="function"==typeof t?function(e,t){se();try{return e.call(t,t)}catch(e){return Fe(e,t,"data()"),{}}finally{ce()}}(t,e):t||{})||(t={});var n=Object.keys(t),r=e.$options.props,i=(e.$options.methods,n.length);for(;i--;){var o=n[i];r&&p(r,o)||(void 0,36!==(a=(o+"").charCodeAt(0))&&95!==a&&Et(e,"_data",o))}var a;we(t,!0)}(e):we(e._data={},!0),t.computed&&function(e,t){var n=e._computedWatchers=Object.create(null),r=Y();for(var i in t){var o=t[i],a="function"==typeof o?o:o.get;r||(n[i]=new St(e,a||$,$,Nt)),i in e||Lt(e,i,o)}}(e,t.computed),t.watch&&t.watch!==G&&function(e,t){for(var n in t){var r=t[n];if(Array.isArray(r))for(var i=0;i<r.length;i++)Mt(e,n,r[i]);else Mt(e,n,r)}}(e,t.watch)}var Nt={lazy:!0};function Lt(e,t,n){var r=!Y();"function"==typeof n?(Tt.get=r?It(t):n,Tt.set=$):(Tt.get=n.get?r&&!1!==n.cache?It(t):n.get:$,Tt.set=n.set?n.set:$),Object.defineProperty(e,t,Tt)}function It(t){return function(){var e=this._computedWatchers&&this._computedWatchers[t];if(e)return e.dirty&&e.evaluate(),oe.target&&e.depend(),e.value}}function Mt(e,t,n,r){return l(n)&&(n=(r=n).handler),"string"==typeof n&&(n=e[n]),e.$watch(t,n,r)}function Dt(t,e){if(t){for(var n=Object.create(null),r=ne?Reflect.ownKeys(t).filter(function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}):Object.keys(t),i=0;i<r.length;i++){for(var o=r[i],a=t[o].from,s=e;s;){if(s._provided&&p(s._provided,a)){n[o]=s._provided[a];break}s=s.$parent}if(!s&&"default"in t[o]){var c=t[o].default;n[o]="function"==typeof c?c.call(e):c}}return n}}function Pt(e,t){var n,r,i,o,a;if(Array.isArray(e)||"string"==typeof e)for(n=new Array(e.length),r=0,i=e.length;r<i;r++)n[r]=t(e[r],r);else if("number"==typeof e)for(n=new Array(e),r=0;r<e;r++)n[r]=t(r+1,r);else if(P(e))for(o=Object.keys(e),n=new Array(o.length),r=0,i=o.length;r<i;r++)a=o[r],n[r]=t(e[a],a,r);return D(n)&&(n._isVList=!0),n}function Ft(e,t,n,r){var i,o=this.$scopedSlots[e];if(o)n=n||{},r&&(n=m(m({},r),n)),i=o(n)||t;else{var a=this.$slots[e];a&&(a._rendered=!0),i=a||t}var s=n&&n.slot;return s?this.$createElement("template",{slot:s},i):i}function Rt(e){return Le(this.$options,"filters",e)||w}function Ht(e,t){return Array.isArray(e)?-1===e.indexOf(t):e!==t}function Bt(e,t,n,r,i){var o=j.keyCodes[t]||n;return i&&r&&!j.keyCodes[t]?Ht(i,r):o?Ht(o,e):r?_(r)!==t:void 0}function Ut(n,r,i,o,a){if(i)if(P(i)){var s;Array.isArray(i)&&(i=b(i));var e=function(t){if("class"===t||"style"===t||u(t))s=n;else{var e=n.attrs&&n.attrs.type;s=o||j.mustUseProp(r,e,t)?n.domProps||(n.domProps={}):n.attrs||(n.attrs={})}t in s||(s[t]=i[t],a&&((n.on||(n.on={}))["update:"+t]=function(e){i[t]=e}))};for(var t in i)e(t)}else;return n}function Vt(e,t){var n=this._staticTrees||(this._staticTrees=[]),r=n[e];return r&&!t||Kt(r=n[e]=this.$options.staticRenderFns[e].call(this._renderProxy,null,this),"__static__"+e,!1),r}function zt(e,t,n){return Kt(e,"__once__"+t+(n?"_"+n:""),!0),e}function Kt(e,t,n){if(Array.isArray(e))for(var r=0;r<e.length;r++)e[r]&&"string"!=typeof e[r]&&Jt(e[r],t+"_"+r,n);else Jt(e,t,n)}function Jt(e,t,n){e.isStatic=!0,e.key=t,e.isOnce=n}function qt(e,t){if(t)if(l(t)){var n=e.on=e.on?m({},e.on):{};for(var r in t){var i=n[r],o=t[r];n[r]=i?[].concat(i,o):o}}else;return e}function Wt(e){e._o=zt,e._n=F,e._s=t,e._l=Pt,e._t=Ft,e._q=C,e._i=x,e._m=Vt,e._f=Rt,e._k=Bt,e._b=Ut,e._v=pe,e._e=fe,e._u=ht,e._g=qt}function Gt(e,t,n,o,r){var a,s=r.options;p(o,"_uid")?(a=Object.create(o))._original=o:o=(a=o)._original;var i=S(s._compiled),c=!i;this.data=e,this.props=t,this.children=n,this.parent=o,this.listeners=e.on||y,this.injections=Dt(s.inject,o),this.slots=function(){return dt(n,o)},i&&(this.$options=s,this.$slots=this.slots(),this.$scopedSlots=e.scopedSlots||y),s._scopeId?this._c=function(e,t,n,r){var i=rn(a,e,t,n,r,c);return i&&!Array.isArray(i)&&(i.fnScopeId=s._scopeId,i.fnContext=o),i}:this._c=function(e,t,n,r){return rn(a,e,t,n,r,c)}}function Zt(e,t,n,r){var i=de(e);return i.fnContext=n,i.fnOptions=r,t.slot&&((i.data||(i.data={})).slot=t.slot),i}function Xt(e,t){for(var n in t)e[g(n)]=t[n]}Wt(Gt.prototype);var Yt={init:function(e,t,n,r){if(e.componentInstance&&!e.componentInstance._isDestroyed&&e.data.keepAlive){var i=e;Yt.prepatch(i,i)}else{(e.componentInstance=function(e,t,n,r){var i={_isComponent:!0,parent:t,_parentVnode:e,_parentElm:n||null,_refElm:r||null},o=e.data.inlineTemplate;D(o)&&(i.render=o.render,i.staticRenderFns=o.staticRenderFns);return new e.componentOptions.Ctor(i)}(e,mt,n,r)).$mount(t?e.elm:void 0,t)}},prepatch:function(e,t){var n=t.componentOptions;!function(e,t,n,r,i){var o=!!(i||e.$options._renderChildren||r.data.scopedSlots||e.$scopedSlots!==y);if(e.$options._parentVnode=r,e.$vnode=r,e._vnode&&(e._vnode.parent=r),e.$options._renderChildren=i,e.$attrs=r.data.attrs||y,e.$listeners=n||y,t&&e.$options.props){ge(!1);for(var a=e._props,s=e.$options._propKeys||[],c=0;c<s.length;c++){var l=s[c],u=e.$options.props;a[l]=Ie(l,u,t,e)}ge(!0),e.$options.propsData=t}n=n||y;var f=e.$options._parentListeners;e.$options._parentListeners=n,pt(e,n,f),o&&(e.$slots=dt(i,r.context),e.$forceUpdate())}(t.componentInstance=e.componentInstance,n.propsData,n.listeners,t,n.children)},insert:function(e){var t,n=e.context,r=e.componentInstance;r._isMounted||(r._isMounted=!0,_t(r,"mounted")),e.data.keepAlive&&(n._isMounted?((t=r)._inactive=!1,$t.push(t)):gt(r,!0))},destroy:function(e){var t=e.componentInstance;t._isDestroyed||(e.data.keepAlive?function e(t,n){if(!(n&&(t._directInactive=!0,yt(t))||t._inactive)){t._inactive=!0;for(var r=0;r<t.$children.length;r++)e(t.$children[r]);_t(t,"deactivated")}}(t,!0):t.$destroy())}},Qt=Object.keys(Yt);function en(e,t,n,r,i){if(!M(e)){var o=n.$options._base;if(P(e)&&(e=o.extend(e)),"function"==typeof e){var a,s,c,l,u,f,p;if(M(e.cid)&&void 0===(e=function(t,n,e){if(S(t.error)&&D(t.errorComp))return t.errorComp;if(D(t.resolved))return t.resolved;if(S(t.loading)&&D(t.loadingComp))return t.loadingComp;if(!D(t.contexts)){var r=t.contexts=[e],i=!0,o=function(){for(var e=0,t=r.length;e<t;e++)r[e].$forceUpdate()},a=R(function(e){t.resolved=st(e,n),i||o()}),s=R(function(e){D(t.errorComp)&&(t.error=!0,o())}),c=t(a,s);return P(c)&&("function"==typeof c.then?M(t.resolved)&&c.then(a,s):D(c.component)&&"function"==typeof c.component.then&&(c.component.then(a,s),D(c.error)&&(t.errorComp=st(c.error,n)),D(c.loading)&&(t.loadingComp=st(c.loading,n),0===c.delay?t.loading=!0:setTimeout(function(){M(t.resolved)&&M(t.error)&&(t.loading=!0,o())},c.delay||200)),D(c.timeout)&&setTimeout(function(){M(t.resolved)&&s(null)},c.timeout))),i=!1,t.loading?t.loadingComp:t.resolved}t.contexts.push(e)}(a=e,o,n)))return s=a,c=t,l=n,u=r,f=i,(p=fe()).asyncFactory=s,p.asyncMeta={data:c,context:l,children:u,tag:f},p;t=t||{},dn(e),D(t.model)&&function(e,t){var n=e.model&&e.model.prop||"value",r=e.model&&e.model.event||"input";(t.props||(t.props={}))[n]=t.model.value;var i=t.on||(t.on={});D(i[r])?i[r]=[t.model.callback].concat(i[r]):i[r]=t.model.callback}(e.options,t);var d=function(e,t,n){var r=t.options.props;if(!M(r)){var i={},o=e.attrs,a=e.props;if(D(o)||D(a))for(var s in r){var c=_(s);it(i,a,s,c,!0)||it(i,o,s,c,!1)}return i}}(t,e);if(S(e.options.functional))return function(e,t,n,r,i){var o=e.options,a={},s=o.props;if(D(s))for(var c in s)a[c]=Ie(c,s,t||y);else D(n.attrs)&&Xt(a,n.attrs),D(n.props)&&Xt(a,n.props);var l=new Gt(n,a,i,r,e),u=o.render.call(null,l._c,l);if(u instanceof le)return Zt(u,n,l.parent,o);if(Array.isArray(u)){for(var f=ot(u)||[],p=new Array(f.length),d=0;d<f.length;d++)p[d]=Zt(f[d],n,l.parent,o);return p}}(e,d,t,n,r);var v=t.on;if(t.on=t.nativeOn,S(e.options.abstract)){var h=t.slot;t={},h&&(t.slot=h)}!function(e){for(var t=e.hook||(e.hook={}),n=0;n<Qt.length;n++){var r=Qt[n];t[r]=Yt[r]}}(t);var m=e.options.name||i;return new le("vue-component-"+e.cid+(m?"-"+m:""),t,void 0,void 0,void 0,n,{Ctor:e,propsData:d,listeners:v,tag:i,children:r},a)}}}var tn=1,nn=2;function rn(e,t,n,r,i,o){return(Array.isArray(n)||T(n))&&(i=r,r=n,n=void 0),S(o)&&(i=nn),function(e,t,n,r,i){if(D(n)&&D(n.__ob__))return fe();D(n)&&D(n.is)&&(t=n.is);if(!t)return fe();Array.isArray(r)&&"function"==typeof r[0]&&((n=n||{}).scopedSlots={default:r[0]},r.length=0);i===nn?r=ot(r):i===tn&&(r=function(e){for(var t=0;t<e.length;t++)if(Array.isArray(e[t]))return Array.prototype.concat.apply([],e);return e}(r));var o,a;if("string"==typeof t){var s;a=e.$vnode&&e.$vnode.ns||j.getTagNamespace(t),o=j.isReservedTag(t)?new le(j.parsePlatformTagName(t),n,r,void 0,void 0,e):D(s=Le(e.$options,"components",t))?en(s,n,e,r,t):new le(t,n,r,void 0,void 0,e)}else o=en(t,n,e,r);return Array.isArray(o)?o:D(o)?(D(a)&&function e(t,n,r){t.ns=n;"foreignObject"===t.tag&&(n=void 0,r=!0);if(D(t.children))for(var i=0,o=t.children.length;i<o;i++){var a=t.children[i];D(a.tag)&&(M(a.ns)||S(r)&&"svg"!==a.tag)&&e(a,n,r)}}(o,a),D(n)&&function(e){P(e.style)&&Ye(e.style);P(e.class)&&Ye(e.class)}(n),o):fe()}(e,t,n,r,i)}var on,an,sn,cn,ln,un,fn,pn=0;function dn(e){var t=e.options;if(e.super){var n=dn(e.super);if(n!==e.superOptions){e.superOptions=n;var r=function(e){var t,n=e.options,r=e.extendOptions,i=e.sealedOptions;for(var o in n)n[o]!==i[o]&&(t||(t={}),t[o]=vn(n[o],r[o],i[o]));return t}(e);r&&m(e.extendOptions,r),(t=e.options=Ne(n,e.extendOptions)).name&&(t.components[t.name]=e)}}return t}function vn(e,t,n){if(Array.isArray(e)){var r=[];n=Array.isArray(n)?n:[n],t=Array.isArray(t)?t:[t];for(var i=0;i<e.length;i++)(0<=t.indexOf(e[i])||n.indexOf(e[i])<0)&&r.push(e[i]);return r}return e}function hn(e){this._init(e)}function mn(e){e.cid=0;var a=1;e.extend=function(e){e=e||{};var t=this,n=t.cid,r=e._Ctor||(e._Ctor={});if(r[n])return r[n];var i=e.name||t.options.name,o=function(e){this._init(e)};return((o.prototype=Object.create(t.prototype)).constructor=o).cid=a++,o.options=Ne(t.options,e),o.super=t,o.options.props&&function(e){var t=e.options.props;for(var n in t)Et(e.prototype,"_props",n)}(o),o.options.computed&&function(e){var t=e.options.computed;for(var n in t)Lt(e.prototype,n,t[n])}(o),o.extend=t.extend,o.mixin=t.mixin,o.use=t.use,k.forEach(function(e){o[e]=t[e]}),i&&(o.options.components[i]=o),o.superOptions=t.options,o.extendOptions=e,o.sealedOptions=m({},o.options),r[n]=o}}function yn(e){return e&&(e.Ctor.options.name||e.tag)}function gn(e,t){return Array.isArray(e)?-1<e.indexOf(t):"string"==typeof e?-1<e.split(",").indexOf(t):(n=e,"[object RegExp]"===r.call(n)&&e.test(t));var n}function _n(e,t){var n=e.cache,r=e.keys,i=e._vnode;for(var o in n){var a=n[o];if(a){var s=yn(a.componentOptions);s&&!t(s)&&bn(n,o,r,i)}}}function bn(e,t,n,r){var i=e[t];!i||r&&i.tag===r.tag||i.componentInstance.$destroy(),e[t]=null,f(n,t)}hn.prototype._init=function(e){var t,n,r,i,o=this;o._uid=pn++,o._isVue=!0,e&&e._isComponent?function(e,t){var n=e.$options=Object.create(e.constructor.options),r=t._parentVnode;n.parent=t.parent,n._parentVnode=r,n._parentElm=t._parentElm,n._refElm=t._refElm;var i=r.componentOptions;n.propsData=i.propsData,n._parentListeners=i.listeners,n._renderChildren=i.children,n._componentTag=i.tag,t.render&&(n.render=t.render,n.staticRenderFns=t.staticRenderFns)}(o,e):o.$options=Ne(dn(o.constructor),e||{},o),function(e){var t=e.$options,n=t.parent;if(n&&!t.abstract){for(;n.$options.abstract&&n.$parent;)n=n.$parent;n.$children.push(e)}e.$parent=n,e.$root=n?n.$root:e,e.$children=[],e.$refs={},e._watcher=null,e._inactive=null,e._directInactive=!1,e._isMounted=!1,e._isDestroyed=!1,e._isBeingDestroyed=!1}((o._renderProxy=o)._self=o),function(e){e._events=Object.create(null),e._hasHookEvent=!1;var t=e.$options._parentListeners;t&&pt(e,t)}(o),function(i){i._vnode=null,i._staticTrees=null;var e=i.$options,t=i.$vnode=e._parentVnode,n=t&&t.context;i.$slots=dt(e._renderChildren,n),i.$scopedSlots=y,i._c=function(e,t,n,r){return rn(i,e,t,n,r,!1)},i.$createElement=function(e,t,n,r){return rn(i,e,t,n,r,!0)};var r=t&&t.data;Ce(i,"$attrs",r&&r.attrs||y,null,!0),Ce(i,"$listeners",e._parentListeners||y,null,!0)}(o),_t(o,"beforeCreate"),(n=Dt((t=o).$options.inject,t))&&(ge(!1),Object.keys(n).forEach(function(e){Ce(t,e,n[e])}),ge(!0)),jt(o),(i=(r=o).$options.provide)&&(r._provided="function"==typeof i?i.call(r):i),_t(o,"created"),o.$options.el&&o.$mount(o.$options.el)},on=hn,an={get:function(){return this._data}},sn={get:function(){return this._props}},Object.defineProperty(on.prototype,"$data",an),Object.defineProperty(on.prototype,"$props",sn),on.prototype.$set=xe,on.prototype.$delete=ke,on.prototype.$watch=function(e,t,n){if(l(t))return Mt(this,e,t,n);(n=n||{}).user=!0;var r=new St(this,e,t,n);return n.immediate&&t.call(this,r.value),function(){r.teardown()}},ln=/^hook:/,(cn=hn).prototype.$on=function(e,t){if(Array.isArray(e))for(var n=0,r=e.length;n<r;n++)this.$on(e[n],t);else(this._events[e]||(this._events[e]=[])).push(t),ln.test(e)&&(this._hasHookEvent=!0);return this},cn.prototype.$once=function(e,t){var n=this;function r(){n.$off(e,r),t.apply(n,arguments)}return r.fn=t,n.$on(e,r),n},cn.prototype.$off=function(e,t){var n=this;if(!arguments.length)return n._events=Object.create(null),n;if(Array.isArray(e)){for(var r=0,i=e.length;r<i;r++)this.$off(e[r],t);return n}var o=n._events[e];if(!o)return n;if(!t)return n._events[e]=null,n;if(t)for(var a,s=o.length;s--;)if((a=o[s])===t||a.fn===t){o.splice(s,1);break}return n},cn.prototype.$emit=function(t){var n=this,e=n._events[t];if(e){e=1<e.length?h(e):e;for(var r=h(arguments,1),i=0,o=e.length;i<o;i++)try{e[i].apply(n,r)}catch(e){Fe(e,n,'event handler for "'+t+'"')}}return n},(un=hn).prototype._update=function(e,t){var n=this;n._isMounted&&_t(n,"beforeUpdate");var r=n.$el,i=n._vnode,o=mt;(mt=n)._vnode=e,i?n.$el=n.__patch__(i,e):(n.$el=n.__patch__(n.$el,e,t,!1,n.$options._parentElm,n.$options._refElm),n.$options._parentElm=n.$options._refElm=null),mt=o,r&&(r.__vue__=null),n.$el&&(n.$el.__vue__=n),n.$vnode&&n.$parent&&n.$vnode===n.$parent._vnode&&(n.$parent.$el=n.$el)},un.prototype.$forceUpdate=function(){this._watcher&&this._watcher.update()},un.prototype.$destroy=function(){var e=this;if(!e._isBeingDestroyed){_t(e,"beforeDestroy"),e._isBeingDestroyed=!0;var t=e.$parent;!t||t._isBeingDestroyed||e.$options.abstract||f(t.$children,e),e._watcher&&e._watcher.teardown();for(var n=e._watchers.length;n--;)e._watchers[n].teardown();e._data.__ob__&&e._data.__ob__.vmCount--,e._isDestroyed=!0,e.__patch__(e._vnode,null),_t(e,"destroyed"),e.$off(),e.$el&&(e.$el.__vue__=null),e.$vnode&&(e.$vnode.parent=null)}},Wt((fn=hn).prototype),fn.prototype.$nextTick=function(e){return Ze(e,this)},fn.prototype._render=function(){var t,n=this,e=n.$options,r=e.render,i=e._parentVnode;i&&(n.$scopedSlots=i.data.scopedSlots||y),n.$vnode=i;try{t=r.call(n._renderProxy,n.$createElement)}catch(e){Fe(e,n,"render"),t=n._vnode}return t instanceof le||(t=fe()),t.parent=i,t};var $n,wn,Cn,xn=[String,RegExp,Array],kn={KeepAlive:{name:"keep-alive",abstract:!0,props:{include:xn,exclude:xn,max:[String,Number]},created:function(){this.cache=Object.create(null),this.keys=[]},destroyed:function(){for(var e in this.cache)bn(this.cache,e,this.keys)},mounted:function(){var e=this;this.$watch("include",function(t){_n(e,function(e){return gn(t,e)})}),this.$watch("exclude",function(t){_n(e,function(e){return!gn(t,e)})})},render:function(){var e=this.$slots.default,t=lt(e),n=t&&t.componentOptions;if(n){var r=yn(n),i=this.include,o=this.exclude;if(i&&(!r||!gn(i,r))||o&&r&&gn(o,r))return t;var a=this.cache,s=this.keys,c=null==t.key?n.Ctor.cid+(n.tag?"::"+n.tag:""):t.key;a[c]?(t.componentInstance=a[c].componentInstance,f(s,c),s.push(c)):(a[c]=t,s.push(c),this.max&&s.length>parseInt(this.max)&&bn(a,s[0],s,this._vnode)),t.data.keepAlive=!0}return t||e&&e[0]}}};$n=hn,Cn={get:function(){return j}},Object.defineProperty($n,"config",Cn),$n.util={warn:re,extend:m,mergeOptions:Ne,defineReactive:Ce},$n.set=xe,$n.delete=ke,$n.nextTick=Ze,$n.options=Object.create(null),k.forEach(function(e){$n.options[e+"s"]=Object.create(null)}),m(($n.options._base=$n).options.components,kn),$n.use=function(e){var t=this._installedPlugins||(this._installedPlugins=[]);if(-1<t.indexOf(e))return this;var n=h(arguments,1);return n.unshift(this),"function"==typeof e.install?e.install.apply(e,n):"function"==typeof e&&e.apply(null,n),t.push(e),this},$n.mixin=function(e){return this.options=Ne(this.options,e),this},mn($n),wn=$n,k.forEach(function(n){wn[n]=function(e,t){return t?("component"===n&&l(t)&&(t.name=t.name||e,t=this.options._base.extend(t)),"directive"===n&&"function"==typeof t&&(t={bind:t,update:t}),this.options[n+"s"][e]=t):this.options[n+"s"][e]}}),Object.defineProperty(hn.prototype,"$isServer",{get:Y}),Object.defineProperty(hn.prototype,"$ssrContext",{get:function(){return this.$vnode&&this.$vnode.ssrContext}}),Object.defineProperty(hn,"FunctionalRenderContext",{value:Gt}),hn.version="2.5.17";var An=s("style,class"),On=s("input,textarea,option,select,progress"),Sn=function(e,t,n){return"value"===n&&On(e)&&"button"!==t||"selected"===n&&"option"===e||"checked"===n&&"input"===e||"muted"===n&&"video"===e},Tn=s("contenteditable,draggable,spellcheck"),En=s("allowfullscreen,async,autofocus,autoplay,checked,compact,controls,declare,default,defaultchecked,defaultmuted,defaultselected,defer,disabled,enabled,formnovalidate,hidden,indeterminate,inert,ismap,itemscope,loop,multiple,muted,nohref,noresize,noshade,novalidate,nowrap,open,pauseonexit,readonly,required,reversed,scoped,seamless,selected,sortable,translate,truespeed,typemustmatch,visible"),jn="http://www.w3.org/1999/xlink",Nn=function(e){return":"===e.charAt(5)&&"xlink"===e.slice(0,5)},Ln=function(e){return Nn(e)?e.slice(6,e.length):""},In=function(e){return null==e||!1===e};function Mn(e){for(var t=e.data,n=e,r=e;D(r.componentInstance);)(r=r.componentInstance._vnode)&&r.data&&(t=Dn(r.data,t));for(;D(n=n.parent);)n&&n.data&&(t=Dn(t,n.data));return function(e,t){if(D(e)||D(t))return Pn(e,Fn(t));return""}(t.staticClass,t.class)}function Dn(e,t){return{staticClass:Pn(e.staticClass,t.staticClass),class:D(e.class)?[e.class,t.class]:t.class}}function Pn(e,t){return e?t?e+" "+t:e:t||""}function Fn(e){return Array.isArray(e)?function(e){for(var t,n="",r=0,i=e.length;r<i;r++)D(t=Fn(e[r]))&&""!==t&&(n&&(n+=" "),n+=t);return n}(e):P(e)?function(e){var t="";for(var n in e)e[n]&&(t&&(t+=" "),t+=n);return t}(e):"string"==typeof e?e:""}var Rn={svg:"http://www.w3.org/2000/svg",math:"http://www.w3.org/1998/Math/MathML"},Hn=s("html,body,base,head,link,meta,style,title,address,article,aside,footer,header,h1,h2,h3,h4,h5,h6,hgroup,nav,section,div,dd,dl,dt,figcaption,figure,picture,hr,img,li,main,ol,p,pre,ul,a,b,abbr,bdi,bdo,br,cite,code,data,dfn,em,i,kbd,mark,q,rp,rt,rtc,ruby,s,samp,small,span,strong,sub,sup,time,u,var,wbr,area,audio,map,track,video,embed,object,param,source,canvas,script,noscript,del,ins,caption,col,colgroup,table,thead,tbody,td,th,tr,button,datalist,fieldset,form,input,label,legend,meter,optgroup,option,output,progress,select,textarea,details,dialog,menu,menuitem,summary,content,element,shadow,template,blockquote,iframe,tfoot"),Bn=s("svg,animate,circle,clippath,cursor,defs,desc,ellipse,filter,font-face,foreignObject,g,glyph,image,line,marker,mask,missing-glyph,path,pattern,polygon,polyline,rect,switch,symbol,text,textpath,tspan,use,view",!0),Un=function(e){return Hn(e)||Bn(e)};function Vn(e){return Bn(e)?"svg":"math"===e?"math":void 0}var zn=Object.create(null);var Kn=s("text,number,password,search,email,tel,url");function Jn(e){if("string"==typeof e){var t=document.querySelector(e);return t||document.createElement("div")}return e}var qn=Object.freeze({createElement:function(e,t){var n=document.createElement(e);return"select"!==e||t.data&&t.data.attrs&&void 0!==t.data.attrs.multiple&&n.setAttribute("multiple","multiple"),n},createElementNS:function(e,t){return document.createElementNS(Rn[e],t)},createTextNode:function(e){return document.createTextNode(e)},createComment:function(e){return document.createComment(e)},insertBefore:function(e,t,n){e.insertBefore(t,n)},removeChild:function(e,t){e.removeChild(t)},appendChild:function(e,t){e.appendChild(t)},parentNode:function(e){return e.parentNode},nextSibling:function(e){return e.nextSibling},tagName:function(e){return e.tagName},setTextContent:function(e,t){e.textContent=t},setStyleScope:function(e,t){e.setAttribute(t,"")}}),Wn={create:function(e,t){Gn(t)},update:function(e,t){e.data.ref!==t.data.ref&&(Gn(e,!0),Gn(t))},destroy:function(e){Gn(e,!0)}};function Gn(e,t){var n=e.data.ref;if(D(n)){var r=e.context,i=e.componentInstance||e.elm,o=r.$refs;t?Array.isArray(o[n])?f(o[n],i):o[n]===i&&(o[n]=void 0):e.data.refInFor?Array.isArray(o[n])?o[n].indexOf(i)<0&&o[n].push(i):o[n]=[i]:o[n]=i}}var Zn=new le("",{},[]),Xn=["create","activate","update","remove","destroy"];function Yn(e,t){return e.key===t.key&&(e.tag===t.tag&&e.isComment===t.isComment&&D(e.data)===D(t.data)&&function(e,t){if("input"!==e.tag)return!0;var n,r=D(n=e.data)&&D(n=n.attrs)&&n.type,i=D(n=t.data)&&D(n=n.attrs)&&n.type;return r===i||Kn(r)&&Kn(i)}(e,t)||S(e.isAsyncPlaceholder)&&e.asyncFactory===t.asyncFactory&&M(t.asyncFactory.error))}function Qn(e,t,n){var r,i,o={};for(r=t;r<=n;++r)D(i=e[r].key)&&(o[i]=r);return o}var er={create:tr,update:tr,destroy:function(e){tr(e,Zn)}};function tr(e,t){(e.data.directives||t.data.directives)&&function(t,n){var e,r,i,o=t===Zn,a=n===Zn,s=rr(t.data.directives,t.context),c=rr(n.data.directives,n.context),l=[],u=[];for(e in c)r=s[e],i=c[e],r?(i.oldValue=r.value,ir(i,"update",n,t),i.def&&i.def.componentUpdated&&u.push(i)):(ir(i,"bind",n,t),i.def&&i.def.inserted&&l.push(i));if(l.length){var f=function(){for(var e=0;e<l.length;e++)ir(l[e],"inserted",n,t)};o?rt(n,"insert",f):f()}u.length&&rt(n,"postpatch",function(){for(var e=0;e<u.length;e++)ir(u[e],"componentUpdated",n,t)});if(!o)for(e in s)c[e]||ir(s[e],"unbind",t,t,a)}(e,t)}var nr=Object.create(null);function rr(e,t){var n,r,i,o=Object.create(null);if(!e)return o;for(n=0;n<e.length;n++)(r=e[n]).modifiers||(r.modifiers=nr),(o[(i=r,i.rawName||i.name+"."+Object.keys(i.modifiers||{}).join("."))]=r).def=Le(t.$options,"directives",r.name);return o}function ir(t,n,r,e,i){var o=t.def&&t.def[n];if(o)try{o(r.elm,t,r,e,i)}catch(e){Fe(e,r.context,"directive "+t.name+" "+n+" hook")}}var or=[Wn,er];function ar(e,t){var n=t.componentOptions;if(!(D(n)&&!1===n.Ctor.options.inheritAttrs||M(e.data.attrs)&&M(t.data.attrs))){var r,i,o=t.elm,a=e.data.attrs||{},s=t.data.attrs||{};for(r in D(s.__ob__)&&(s=t.data.attrs=m({},s)),s)i=s[r],a[r]!==i&&sr(o,r,i);for(r in(K||q)&&s.value!==a.value&&sr(o,"value",s.value),a)M(s[r])&&(Nn(r)?o.removeAttributeNS(jn,Ln(r)):Tn(r)||o.removeAttribute(r))}}function sr(e,t,n){-1<e.tagName.indexOf("-")?cr(e,t,n):En(t)?In(n)?e.removeAttribute(t):(n="allowfullscreen"===t&&"EMBED"===e.tagName?"true":t,e.setAttribute(t,n)):Tn(t)?e.setAttribute(t,In(n)||"false"===n?"false":"true"):Nn(t)?In(n)?e.removeAttributeNS(jn,Ln(t)):e.setAttributeNS(jn,t,n):cr(e,t,n)}function cr(t,e,n){if(In(n))t.removeAttribute(e);else{if(K&&!J&&"TEXTAREA"===t.tagName&&"placeholder"===e&&!t.__ieph){var r=function(e){e.stopImmediatePropagation(),t.removeEventListener("input",r)};t.addEventListener("input",r),t.__ieph=!0}t.setAttribute(e,n)}}var lr={create:ar,update:ar};function ur(e,t){var n=t.elm,r=t.data,i=e.data;if(!(M(r.staticClass)&&M(r.class)&&(M(i)||M(i.staticClass)&&M(i.class)))){var o=Mn(t),a=n._transitionClasses;D(a)&&(o=Pn(o,Fn(a))),o!==n._prevClass&&(n.setAttribute("class",o),n._prevClass=o)}}var fr,pr,dr,vr,hr,mr,yr={create:ur,update:ur},gr=/[\w).+\-_$\]]/;function _r(e){var t,n,r,i,o,a=!1,s=!1,c=!1,l=!1,u=0,f=0,p=0,d=0;for(r=0;r<e.length;r++)if(n=t,t=e.charCodeAt(r),a)39===t&&92!==n&&(a=!1);else if(s)34===t&&92!==n&&(s=!1);else if(c)96===t&&92!==n&&(c=!1);else if(l)47===t&&92!==n&&(l=!1);else if(124!==t||124===e.charCodeAt(r+1)||124===e.charCodeAt(r-1)||u||f||p){switch(t){case 34:s=!0;break;case 39:a=!0;break;case 96:c=!0;break;case 40:p++;break;case 41:p--;break;case 91:f++;break;case 93:f--;break;case 123:u++;break;case 125:u--}if(47===t){for(var v=r-1,h=void 0;0<=v&&" "===(h=e.charAt(v));v--);h&&gr.test(h)||(l=!0)}}else void 0===i?(d=r+1,i=e.slice(0,r).trim()):m();function m(){(o||(o=[])).push(e.slice(d,r).trim()),d=r+1}if(void 0===i?i=e.slice(0,r).trim():0!==d&&m(),o)for(r=0;r<o.length;r++)i=br(i,o[r]);return i}function br(e,t){var n=t.indexOf("(");if(n<0)return'_f("'+t+'")('+e+")";var r=t.slice(0,n),i=t.slice(n+1);return'_f("'+r+'")('+e+(")"!==i?","+i:i)}function $r(e){console.error("[Vue compiler]: "+e)}function wr(e,t){return e?e.map(function(e){return e[t]}).filter(function(e){return e}):[]}function Cr(e,t,n){(e.props||(e.props=[])).push({name:t,value:n}),e.plain=!1}function xr(e,t,n){(e.attrs||(e.attrs=[])).push({name:t,value:n}),e.plain=!1}function kr(e,t,n){e.attrsMap[t]=n,e.attrsList.push({name:t,value:n})}function Ar(e,t,n,r,i,o){var a;(r=r||y).capture&&(delete r.capture,t="!"+t),r.once&&(delete r.once,t="~"+t),r.passive&&(delete r.passive,t="&"+t),"click"===t&&(r.right?(t="contextmenu",delete r.right):r.middle&&(t="mouseup")),r.native?(delete r.native,a=e.nativeEvents||(e.nativeEvents={})):a=e.events||(e.events={});var s={value:n.trim()};r!==y&&(s.modifiers=r);var c=a[t];Array.isArray(c)?i?c.unshift(s):c.push(s):a[t]=c?i?[s,c]:[c,s]:s,e.plain=!1}function Or(e,t,n){var r=Sr(e,":"+t)||Sr(e,"v-bind:"+t);if(null!=r)return _r(r);if(!1!==n){var i=Sr(e,t);if(null!=i)return JSON.stringify(i)}}function Sr(e,t,n){var r;if(null!=(r=e.attrsMap[t]))for(var i=e.attrsList,o=0,a=i.length;o<a;o++)if(i[o].name===t){i.splice(o,1);break}return n&&delete e.attrsMap[t],r}function Tr(e,t,n){var r=n||{},i=r.number,o="$$v",a=o;r.trim&&(a="(typeof $$v === 'string'? $$v.trim(): $$v)"),i&&(a="_n("+a+")");var s=Er(t,a);e.model={value:"("+t+")",expression:'"'+t+'"',callback:"function ($$v) {"+s+"}"}}function Er(e,t){var n=function(e){if(e=e.trim(),fr=e.length,e.indexOf("[")<0||e.lastIndexOf("]")<fr-1)return-1<(vr=e.lastIndexOf("."))?{exp:e.slice(0,vr),key:'"'+e.slice(vr+1)+'"'}:{exp:e,key:null};pr=e,vr=hr=mr=0;for(;!Nr();)Lr(dr=jr())?Mr(dr):91===dr&&Ir(dr);return{exp:e.slice(0,hr),key:e.slice(hr+1,mr)}}(e);return null===n.key?e+"="+t:"$set("+n.exp+", "+n.key+", "+t+")"}function jr(){return pr.charCodeAt(++vr)}function Nr(){return fr<=vr}function Lr(e){return 34===e||39===e}function Ir(e){var t=1;for(hr=vr;!Nr();)if(Lr(e=jr()))Mr(e);else if(91===e&&t++,93===e&&t--,0===t){mr=vr;break}}function Mr(e){for(var t=e;!Nr()&&(e=jr())!==t;);}var Dr,Pr="__r",Fr="__c";function Rr(e,t,n,r,i){var o,a,s,c,l;t=(o=t)._withTask||(o._withTask=function(){Je=!0;var e=o.apply(null,arguments);return Je=!1,e}),n&&(a=t,s=e,c=r,l=Dr,t=function e(){null!==a.apply(null,arguments)&&Hr(s,e,c,l)}),Dr.addEventListener(e,t,Z?{capture:r,passive:i}:r)}function Hr(e,t,n,r){(r||Dr).removeEventListener(e,t._withTask||t,n)}function Br(e,t){if(!M(e.data.on)||!M(t.data.on)){var n=t.data.on||{},r=e.data.on||{};Dr=t.elm,function(e){if(D(e[Pr])){var t=K?"change":"input";e[t]=[].concat(e[Pr],e[t]||[]),delete e[Pr]}D(e[Fr])&&(e.change=[].concat(e[Fr],e.change||[]),delete e[Fr])}(n),nt(n,r,Rr,Hr,t.context),Dr=void 0}}var Ur={create:Br,update:Br};function Vr(e,t){if(!M(e.data.domProps)||!M(t.data.domProps)){var n,r,i,o,a=t.elm,s=e.data.domProps||{},c=t.data.domProps||{};for(n in D(c.__ob__)&&(c=t.data.domProps=m({},c)),s)M(c[n])&&(a[n]="");for(n in c){if(r=c[n],"textContent"===n||"innerHTML"===n){if(t.children&&(t.children.length=0),r===s[n])continue;1===a.childNodes.length&&a.removeChild(a.childNodes[0])}if("value"===n){var l=M(a._value=r)?"":String(r);o=l,(i=a).composing||"OPTION"!==i.tagName&&!function(e,t){var n=!0;try{n=document.activeElement!==e}catch(e){}return n&&e.value!==t}(i,o)&&!function(e,t){var n=e.value,r=e._vModifiers;if(D(r)){if(r.lazy)return!1;if(r.number)return F(n)!==F(t);if(r.trim)return n.trim()!==t.trim()}return n!==t}(i,o)||(a.value=l)}else a[n]=r}}}var zr={create:Vr,update:Vr},Kr=e(function(e){var n={},r=/:(.+)/;return e.split(/;(?![^(]*\))/g).forEach(function(e){if(e){var t=e.split(r);1<t.length&&(n[t[0].trim()]=t[1].trim())}}),n});function Jr(e){var t=qr(e.style);return e.staticStyle?m(e.staticStyle,t):t}function qr(e){return Array.isArray(e)?b(e):"string"==typeof e?Kr(e):e}var Wr,Gr=/^--/,Zr=/\s*!important$/,Xr=function(e,t,n){if(Gr.test(t))e.style.setProperty(t,n);else if(Zr.test(n))e.style.setProperty(t,n.replace(Zr,""),"important");else{var r=Qr(t);if(Array.isArray(n))for(var i=0,o=n.length;i<o;i++)e.style[r]=n[i];else e.style[r]=n}},Yr=["Webkit","Moz","ms"],Qr=e(function(e){if(Wr=Wr||document.createElement("div").style,"filter"!==(e=g(e))&&e in Wr)return e;for(var t=e.charAt(0).toUpperCase()+e.slice(1),n=0;n<Yr.length;n++){var r=Yr[n]+t;if(r in Wr)return r}});function ei(e,t){var n=t.data,r=e.data;if(!(M(n.staticStyle)&&M(n.style)&&M(r.staticStyle)&&M(r.style))){var i,o,a=t.elm,s=r.staticStyle,c=r.normalizedStyle||r.style||{},l=s||c,u=qr(t.data.style)||{};t.data.normalizedStyle=D(u.__ob__)?m({},u):u;var f=function(e,t){var n,r={};if(t)for(var i=e;i.componentInstance;)(i=i.componentInstance._vnode)&&i.data&&(n=Jr(i.data))&&m(r,n);(n=Jr(e.data))&&m(r,n);for(var o=e;o=o.parent;)o.data&&(n=Jr(o.data))&&m(r,n);return r}(t,!0);for(o in l)M(f[o])&&Xr(a,o,"");for(o in f)(i=f[o])!==l[o]&&Xr(a,o,null==i?"":i)}}var ti={create:ei,update:ei};function ni(t,e){if(e&&(e=e.trim()))if(t.classList)-1<e.indexOf(" ")?e.split(/\s+/).forEach(function(e){return t.classList.add(e)}):t.classList.add(e);else{var n=" "+(t.getAttribute("class")||"")+" ";n.indexOf(" "+e+" ")<0&&t.setAttribute("class",(n+e).trim())}}function ri(t,e){if(e&&(e=e.trim()))if(t.classList)-1<e.indexOf(" ")?e.split(/\s+/).forEach(function(e){return t.classList.remove(e)}):t.classList.remove(e),t.classList.length||t.removeAttribute("class");else{for(var n=" "+(t.getAttribute("class")||"")+" ",r=" "+e+" ";0<=n.indexOf(r);)n=n.replace(r," ");(n=n.trim())?t.setAttribute("class",n):t.removeAttribute("class")}}function ii(e){if(e){if("object"==typeof e){var t={};return!1!==e.css&&m(t,oi(e.name||"v")),m(t,e),t}return"string"==typeof e?oi(e):void 0}}var oi=e(function(e){return{enterClass:e+"-enter",enterToClass:e+"-enter-to",enterActiveClass:e+"-enter-active",leaveClass:e+"-leave",leaveToClass:e+"-leave-to",leaveActiveClass:e+"-leave-active"}}),ai=B&&!J,si="transition",ci="animation",li="transition",ui="transitionend",fi="animation",pi="animationend";ai&&(void 0===window.ontransitionend&&void 0!==window.onwebkittransitionend&&(li="WebkitTransition",ui="webkitTransitionEnd"),void 0===window.onanimationend&&void 0!==window.onwebkitanimationend&&(fi="WebkitAnimation",pi="webkitAnimationEnd"));var di=B?window.requestAnimationFrame?window.requestAnimationFrame.bind(window):setTimeout:function(e){return e()};function vi(e){di(function(){di(e)})}function hi(e,t){var n=e._transitionClasses||(e._transitionClasses=[]);n.indexOf(t)<0&&(n.push(t),ni(e,t))}function mi(e,t){e._transitionClasses&&f(e._transitionClasses,t),ri(e,t)}function yi(t,e,n){var r=_i(t,e),i=r.type,o=r.timeout,a=r.propCount;if(!i)return n();var s=i===si?ui:pi,c=0,l=function(){t.removeEventListener(s,u),n()},u=function(e){e.target===t&&++c>=a&&l()};setTimeout(function(){c<a&&l()},o+1),t.addEventListener(s,u)}var gi=/\b(transform|all)(,|$)/;function _i(e,t){var n,r=window.getComputedStyle(e),i=r[li+"Delay"].split(", "),o=r[li+"Duration"].split(", "),a=bi(i,o),s=r[fi+"Delay"].split(", "),c=r[fi+"Duration"].split(", "),l=bi(s,c),u=0,f=0;return t===si?0<a&&(n=si,u=a,f=o.length):t===ci?0<l&&(n=ci,u=l,f=c.length):f=(n=0<(u=Math.max(a,l))?l<a?si:ci:null)?n===si?o.length:c.length:0,{type:n,timeout:u,propCount:f,hasTransform:n===si&&gi.test(r[li+"Property"])}}function bi(n,e){for(;n.length<e.length;)n=n.concat(n);return Math.max.apply(null,e.map(function(e,t){return $i(e)+$i(n[t])}))}function $i(e){return 1e3*Number(e.slice(0,-1))}function wi(n,e){var r=n.elm;D(r._leaveCb)&&(r._leaveCb.cancelled=!0,r._leaveCb());var t=ii(n.data.transition);if(!M(t)&&!D(r._enterCb)&&1===r.nodeType){for(var i=t.css,o=t.type,a=t.enterClass,s=t.enterToClass,c=t.enterActiveClass,l=t.appearClass,u=t.appearToClass,f=t.appearActiveClass,p=t.beforeEnter,d=t.enter,v=t.afterEnter,h=t.enterCancelled,m=t.beforeAppear,y=t.appear,g=t.afterAppear,_=t.appearCancelled,b=t.duration,$=mt,w=mt.$vnode;w&&w.parent;)$=(w=w.parent).context;var C=!$._isMounted||!n.isRootInsert;if(!C||y||""===y){var x=C&&l?l:a,k=C&&f?f:c,A=C&&u?u:s,O=C&&m||p,S=C&&"function"==typeof y?y:d,T=C&&g||v,E=C&&_||h,j=F(P(b)?b.enter:b),N=!1!==i&&!J,L=ki(S),I=r._enterCb=R(function(){N&&(mi(r,A),mi(r,k)),I.cancelled?(N&&mi(r,x),E&&E(r)):T&&T(r),r._enterCb=null});n.data.show||rt(n,"insert",function(){var e=r.parentNode,t=e&&e._pending&&e._pending[n.key];t&&t.tag===n.tag&&t.elm._leaveCb&&t.elm._leaveCb(),S&&S(r,I)}),O&&O(r),N&&(hi(r,x),hi(r,k),vi(function(){mi(r,x),I.cancelled||(hi(r,A),L||(xi(j)?setTimeout(I,j):yi(r,o,I)))})),n.data.show&&(e&&e(),S&&S(r,I)),N||L||I()}}}function Ci(e,t){var n=e.elm;D(n._enterCb)&&(n._enterCb.cancelled=!0,n._enterCb());var r=ii(e.data.transition);if(M(r)||1!==n.nodeType)return t();if(!D(n._leaveCb)){var i=r.css,o=r.type,a=r.leaveClass,s=r.leaveToClass,c=r.leaveActiveClass,l=r.beforeLeave,u=r.leave,f=r.afterLeave,p=r.leaveCancelled,d=r.delayLeave,v=r.duration,h=!1!==i&&!J,m=ki(u),y=F(P(v)?v.leave:v),g=n._leaveCb=R(function(){n.parentNode&&n.parentNode._pending&&(n.parentNode._pending[e.key]=null),h&&(mi(n,s),mi(n,c)),g.cancelled?(h&&mi(n,a),p&&p(n)):(t(),f&&f(n)),n._leaveCb=null});d?d(_):_()}function _(){g.cancelled||(e.data.show||((n.parentNode._pending||(n.parentNode._pending={}))[e.key]=e),l&&l(n),h&&(hi(n,a),hi(n,c),vi(function(){mi(n,a),g.cancelled||(hi(n,s),m||(xi(y)?setTimeout(g,y):yi(n,o,g)))})),u&&u(n,g),h||m||g())}}function xi(e){return"number"==typeof e&&!isNaN(e)}function ki(e){if(M(e))return!1;var t=e.fns;return D(t)?ki(Array.isArray(t)?t[0]:t):1<(e._length||e.length)}function Ai(e,t){!0!==t.data.show&&wi(t)}var Oi=function(e){var r,t,g={},n=e.modules,_=e.nodeOps;for(r=0;r<Xn.length;++r)for(g[Xn[r]]=[],t=0;t<n.length;++t)D(n[t][Xn[r]])&&g[Xn[r]].push(n[t][Xn[r]]);function o(e){var t=_.parentNode(e);D(t)&&_.removeChild(t,e)}function b(e,t,n,r,i,o,a){if(D(e.elm)&&D(o)&&(e=o[a]=de(e)),e.isRootInsert=!i,!function(e,t,n,r){var i=e.data;if(D(i)){var o=D(e.componentInstance)&&i.keepAlive;if(D(i=i.hook)&&D(i=i.init)&&i(e,!1,n,r),D(e.componentInstance))return d(e,t),S(o)&&function(e,t,n,r){for(var i,o=e;o.componentInstance;)if(o=o.componentInstance._vnode,D(i=o.data)&&D(i=i.transition)){for(i=0;i<g.activate.length;++i)g.activate[i](Zn,o);t.push(o);break}u(n,e.elm,r)}(e,t,n,r),!0}}(e,t,n,r)){var s=e.data,c=e.children,l=e.tag;D(l)?(e.elm=e.ns?_.createElementNS(e.ns,l):_.createElement(l,e),f(e),v(e,c,t),D(s)&&h(e,t)):S(e.isComment)?e.elm=_.createComment(e.text):e.elm=_.createTextNode(e.text),u(n,e.elm,r)}}function d(e,t){D(e.data.pendingInsert)&&(t.push.apply(t,e.data.pendingInsert),e.data.pendingInsert=null),e.elm=e.componentInstance.$el,$(e)?(h(e,t),f(e)):(Gn(e),t.push(e))}function u(e,t,n){D(e)&&(D(n)?n.parentNode===e&&_.insertBefore(e,t,n):_.appendChild(e,t))}function v(e,t,n){if(Array.isArray(t))for(var r=0;r<t.length;++r)b(t[r],n,e.elm,null,!0,t,r);else T(e.text)&&_.appendChild(e.elm,_.createTextNode(String(e.text)))}function $(e){for(;e.componentInstance;)e=e.componentInstance._vnode;return D(e.tag)}function h(e,t){for(var n=0;n<g.create.length;++n)g.create[n](Zn,e);D(r=e.data.hook)&&(D(r.create)&&r.create(Zn,e),D(r.insert)&&t.push(e))}function f(e){var t;if(D(t=e.fnScopeId))_.setStyleScope(e.elm,t);else for(var n=e;n;)D(t=n.context)&&D(t=t.$options._scopeId)&&_.setStyleScope(e.elm,t),n=n.parent;D(t=mt)&&t!==e.context&&t!==e.fnContext&&D(t=t.$options._scopeId)&&_.setStyleScope(e.elm,t)}function y(e,t,n,r,i,o){for(;r<=i;++r)b(n[r],o,e,t,!1,n,r)}function w(e){var t,n,r=e.data;if(D(r))for(D(t=r.hook)&&D(t=t.destroy)&&t(e),t=0;t<g.destroy.length;++t)g.destroy[t](e);if(D(t=e.children))for(n=0;n<e.children.length;++n)w(e.children[n])}function C(e,t,n,r){for(;n<=r;++n){var i=t[n];D(i)&&(D(i.tag)?(a(i),w(i)):o(i.elm))}}function a(e,t){if(D(t)||D(e.data)){var n,r=g.remove.length+1;for(D(t)?t.listeners+=r:t=function(e,t){function n(){0==--n.listeners&&o(e)}return n.listeners=t,n}(e.elm,r),D(n=e.componentInstance)&&D(n=n._vnode)&&D(n.data)&&a(n,t),n=0;n<g.remove.length;++n)g.remove[n](e,t);D(n=e.data.hook)&&D(n=n.remove)?n(e,t):t()}else o(e.elm)}function x(e,t,n,r){for(var i=n;i<r;i++){var o=t[i];if(D(o)&&Yn(e,o))return i}}function k(e,t,n,r){if(e!==t){var i=t.elm=e.elm;if(S(e.isAsyncPlaceholder))D(t.asyncFactory.resolved)?O(e.elm,t,n):t.isAsyncPlaceholder=!0;else if(S(t.isStatic)&&S(e.isStatic)&&t.key===e.key&&(S(t.isCloned)||S(t.isOnce)))t.componentInstance=e.componentInstance;else{var o,a=t.data;D(a)&&D(o=a.hook)&&D(o=o.prepatch)&&o(e,t);var s=e.children,c=t.children;if(D(a)&&$(t)){for(o=0;o<g.update.length;++o)g.update[o](e,t);D(o=a.hook)&&D(o=o.update)&&o(e,t)}M(t.text)?D(s)&&D(c)?s!==c&&function(e,t,n,r,i){for(var o,a,s,c=0,l=0,u=t.length-1,f=t[0],p=t[u],d=n.length-1,v=n[0],h=n[d],m=!i;c<=u&&l<=d;)M(f)?f=t[++c]:M(p)?p=t[--u]:Yn(f,v)?(k(f,v,r),f=t[++c],v=n[++l]):Yn(p,h)?(k(p,h,r),p=t[--u],h=n[--d]):Yn(f,h)?(k(f,h,r),m&&_.insertBefore(e,f.elm,_.nextSibling(p.elm)),f=t[++c],h=n[--d]):(Yn(p,v)?(k(p,v,r),m&&_.insertBefore(e,p.elm,f.elm),p=t[--u]):(M(o)&&(o=Qn(t,c,u)),M(a=D(v.key)?o[v.key]:x(v,t,c,u))?b(v,r,e,f.elm,!1,n,l):Yn(s=t[a],v)?(k(s,v,r),t[a]=void 0,m&&_.insertBefore(e,s.elm,f.elm)):b(v,r,e,f.elm,!1,n,l)),v=n[++l]);u<c?y(e,M(n[d+1])?null:n[d+1].elm,n,l,d,r):d<l&&C(0,t,c,u)}(i,s,c,n,r):D(c)?(D(e.text)&&_.setTextContent(i,""),y(i,null,c,0,c.length-1,n)):D(s)?C(0,s,0,s.length-1):D(e.text)&&_.setTextContent(i,""):e.text!==t.text&&_.setTextContent(i,t.text),D(a)&&D(o=a.hook)&&D(o=o.postpatch)&&o(e,t)}}}function A(e,t,n){if(S(n)&&D(e.parent))e.parent.data.pendingInsert=t;else for(var r=0;r<t.length;++r)t[r].data.hook.insert(t[r])}var m=s("attrs,class,staticClass,staticStyle,key");function O(e,t,n,r){var i,o=t.tag,a=t.data,s=t.children;if(r=r||a&&a.pre,t.elm=e,S(t.isComment)&&D(t.asyncFactory))return t.isAsyncPlaceholder=!0;if(D(a)&&(D(i=a.hook)&&D(i=i.init)&&i(t,!0),D(i=t.componentInstance)))return d(t,n),!0;if(D(o)){if(D(s))if(e.hasChildNodes())if(D(i=a)&&D(i=i.domProps)&&D(i=i.innerHTML)){if(i!==e.innerHTML)return!1}else{for(var c=!0,l=e.firstChild,u=0;u<s.length;u++){if(!l||!O(l,s[u],n,r)){c=!1;break}l=l.nextSibling}if(!c||l)return!1}else v(t,s,n);if(D(a)){var f=!1;for(var p in a)if(!m(p)){f=!0,h(t,n);break}!f&&a.class&&Ye(a.class)}}else e.data!==t.text&&(e.data=t.text);return!0}return function(e,t,n,r,i,o){if(!M(t)){var a,s=!1,c=[];if(M(e))s=!0,b(t,c,i,o);else{var l=D(e.nodeType);if(!l&&Yn(e,t))k(e,t,c,r);else{if(l){if(1===e.nodeType&&e.hasAttribute(E)&&(e.removeAttribute(E),n=!0),S(n)&&O(e,t,c))return A(t,c,!0),e;a=e,e=new le(_.tagName(a).toLowerCase(),{},[],void 0,a)}var u=e.elm,f=_.parentNode(u);if(b(t,c,u._leaveCb?null:f,_.nextSibling(u)),D(t.parent))for(var p=t.parent,d=$(t);p;){for(var v=0;v<g.destroy.length;++v)g.destroy[v](p);if(p.elm=t.elm,d){for(var h=0;h<g.create.length;++h)g.create[h](Zn,p);var m=p.data.hook.insert;if(m.merged)for(var y=1;y<m.fns.length;y++)m.fns[y]()}else Gn(p);p=p.parent}D(f)?C(0,[e],0,0):D(e.tag)&&w(e)}}return A(t,c,s),t.elm}D(e)&&w(e)}}({nodeOps:qn,modules:[lr,yr,Ur,zr,ti,B?{create:Ai,activate:Ai,remove:function(e,t){!0!==e.data.show?Ci(e,t):t()}}:{}].concat(or)});J&&document.addEventListener("selectionchange",function(){var e=document.activeElement;e&&e.vmodel&&Mi(e,"input")});var Si={inserted:function(e,t,n,r){"select"===n.tag?(r.elm&&!r.elm._vOptions?rt(n,"postpatch",function(){Si.componentUpdated(e,t,n)}):Ti(e,t,n.context),e._vOptions=[].map.call(e.options,Ni)):("textarea"===n.tag||Kn(e.type))&&(e._vModifiers=t.modifiers,t.modifiers.lazy||(e.addEventListener("compositionstart",Li),e.addEventListener("compositionend",Ii),e.addEventListener("change",Ii),J&&(e.vmodel=!0)))},componentUpdated:function(e,t,n){if("select"===n.tag){Ti(e,t,n.context);var r=e._vOptions,i=e._vOptions=[].map.call(e.options,Ni);if(i.some(function(e,t){return!C(e,r[t])}))(e.multiple?t.value.some(function(e){return ji(e,i)}):t.value!==t.oldValue&&ji(t.value,i))&&Mi(e,"change")}}};function Ti(e,t,n){Ei(e,t,n),(K||q)&&setTimeout(function(){Ei(e,t,n)},0)}function Ei(e,t,n){var r=t.value,i=e.multiple;if(!i||Array.isArray(r)){for(var o,a,s=0,c=e.options.length;s<c;s++)if(a=e.options[s],i)o=-1<x(r,Ni(a)),a.selected!==o&&(a.selected=o);else if(C(Ni(a),r))return void(e.selectedIndex!==s&&(e.selectedIndex=s));i||(e.selectedIndex=-1)}}function ji(t,e){return e.every(function(e){return!C(e,t)})}function Ni(e){return"_value"in e?e._value:e.value}function Li(e){e.target.composing=!0}function Ii(e){e.target.composing&&(e.target.composing=!1,Mi(e.target,"input"))}function Mi(e,t){var n=document.createEvent("HTMLEvents");n.initEvent(t,!0,!0),e.dispatchEvent(n)}function Di(e){return!e.componentInstance||e.data&&e.data.transition?e:Di(e.componentInstance._vnode)}var Pi={model:Si,show:{bind:function(e,t,n){var r=t.value,i=(n=Di(n)).data&&n.data.transition,o=e.__vOriginalDisplay="none"===e.style.display?"":e.style.display;r&&i?(n.data.show=!0,wi(n,function(){e.style.display=o})):e.style.display=r?o:"none"},update:function(e,t,n){var r=t.value;!r!=!t.oldValue&&((n=Di(n)).data&&n.data.transition?(n.data.show=!0,r?wi(n,function(){e.style.display=e.__vOriginalDisplay}):Ci(n,function(){e.style.display="none"})):e.style.display=r?e.__vOriginalDisplay:"none")},unbind:function(e,t,n,r,i){i||(e.style.display=e.__vOriginalDisplay)}}},Fi={name:String,appear:Boolean,css:Boolean,mode:String,type:String,enterClass:String,leaveClass:String,enterToClass:String,leaveToClass:String,enterActiveClass:String,leaveActiveClass:String,appearClass:String,appearActiveClass:String,appearToClass:String,duration:[Number,String,Object]};function Ri(e){var t=e&&e.componentOptions;return t&&t.Ctor.options.abstract?Ri(lt(t.children)):e}function Hi(e){var t={},n=e.$options;for(var r in n.propsData)t[r]=e[r];var i=n._parentListeners;for(var o in i)t[g(o)]=i[o];return t}function Bi(e,t){if(/\d-keep-alive$/.test(t.tag))return e("keep-alive",{props:t.componentOptions.propsData})}var Ui={name:"transition",props:Fi,abstract:!0,render:function(e){var t=this,n=this.$slots.default;if(n&&(n=n.filter(function(e){return e.tag||ct(e)})).length){var r=this.mode,i=n[0];if(function(e){for(;e=e.parent;)if(e.data.transition)return!0}(this.$vnode))return i;var o=Ri(i);if(!o)return i;if(this._leaving)return Bi(e,i);var a="__transition-"+this._uid+"-";o.key=null==o.key?o.isComment?a+"comment":a+o.tag:T(o.key)?0===String(o.key).indexOf(a)?o.key:a+o.key:o.key;var s,c,l=(o.data||(o.data={})).transition=Hi(this),u=this._vnode,f=Ri(u);if(o.data.directives&&o.data.directives.some(function(e){return"show"===e.name})&&(o.data.show=!0),f&&f.data&&(s=o,(c=f).key!==s.key||c.tag!==s.tag)&&!ct(f)&&(!f.componentInstance||!f.componentInstance._vnode.isComment)){var p=f.data.transition=m({},l);if("out-in"===r)return this._leaving=!0,rt(p,"afterLeave",function(){t._leaving=!1,t.$forceUpdate()}),Bi(e,i);if("in-out"===r){if(ct(o))return u;var d,v=function(){d()};rt(l,"afterEnter",v),rt(l,"enterCancelled",v),rt(p,"delayLeave",function(e){d=e})}}return i}}},Vi=m({tag:String,moveClass:String},Fi);function zi(e){e.elm._moveCb&&e.elm._moveCb(),e.elm._enterCb&&e.elm._enterCb()}function Ki(e){e.data.newPos=e.elm.getBoundingClientRect()}function Ji(e){var t=e.data.pos,n=e.data.newPos,r=t.left-n.left,i=t.top-n.top;if(r||i){e.data.moved=!0;var o=e.elm.style;o.transform=o.WebkitTransform="translate("+r+"px,"+i+"px)",o.transitionDuration="0s"}}delete Vi.mode;var qi={Transition:Ui,TransitionGroup:{props:Vi,render:function(e){for(var t=this.tag||this.$vnode.data.tag||"span",n=Object.create(null),r=this.prevChildren=this.children,i=this.$slots.default||[],o=this.children=[],a=Hi(this),s=0;s<i.length;s++){var c=i[s];c.tag&&null!=c.key&&0!==String(c.key).indexOf("__vlist")&&(o.push(c),((n[c.key]=c).data||(c.data={})).transition=a)}if(r){for(var l=[],u=[],f=0;f<r.length;f++){var p=r[f];p.data.transition=a,p.data.pos=p.elm.getBoundingClientRect(),n[p.key]?l.push(p):u.push(p)}this.kept=e(t,null,l),this.removed=u}return e(t,null,o)},beforeUpdate:function(){this.__patch__(this._vnode,this.kept,!1,!0),this._vnode=this.kept},updated:function(){var e=this.prevChildren,r=this.moveClass||(this.name||"v")+"-move";e.length&&this.hasMove(e[0].elm,r)&&(e.forEach(zi),e.forEach(Ki),e.forEach(Ji),this._reflow=document.body.offsetHeight,e.forEach(function(e){if(e.data.moved){var n=e.elm,t=n.style;hi(n,r),t.transform=t.WebkitTransform=t.transitionDuration="",n.addEventListener(ui,n._moveCb=function e(t){t&&!/transform$/.test(t.propertyName)||(n.removeEventListener(ui,e),n._moveCb=null,mi(n,r))})}}))},methods:{hasMove:function(e,t){if(!ai)return!1;if(this._hasMove)return this._hasMove;var n=e.cloneNode();e._transitionClasses&&e._transitionClasses.forEach(function(e){ri(n,e)}),ni(n,t),n.style.display="none",this.$el.appendChild(n);var r=_i(n);return this.$el.removeChild(n),this._hasMove=r.hasTransform}}}};hn.config.mustUseProp=Sn,hn.config.isReservedTag=Un,hn.config.isReservedAttr=An,hn.config.getTagNamespace=Vn,hn.config.isUnknownElement=function(e){if(!B)return!0;if(Un(e))return!1;if(e=e.toLowerCase(),null!=zn[e])return zn[e];var t=document.createElement(e);return-1<e.indexOf("-")?zn[e]=t.constructor===window.HTMLUnknownElement||t.constructor===window.HTMLElement:zn[e]=/HTMLUnknownElement/.test(t.toString())},m(hn.options.directives,Pi),m(hn.options.components,qi),hn.prototype.__patch__=B?Oi:$,hn.prototype.$mount=function(e,t){return e=e&&B?Jn(e):void 0,r=e,i=t,(n=this).$el=r,n.$options.render||(n.$options.render=fe),_t(n,"beforeMount"),new St(n,function(){n._update(n._render(),i)},$,null,!0),i=!1,null==n.$vnode&&(n._isMounted=!0,_t(n,"mounted")),n;var n,r,i},B&&setTimeout(function(){j.devtools&&Q&&Q.emit("init",hn)},0);var Wi=/\{\{((?:.|\n)+?)\}\}/g,Gi=/[-.*+?^${}()|[\]\/\\]/g,Zi=e(function(e){var t=e[0].replace(Gi,"\\$&"),n=e[1].replace(Gi,"\\$&");return new RegExp(t+"((?:.|\\n)+?)"+n,"g")});var Xi={staticKeys:["staticClass"],transformNode:function(e,t){t.warn;var n=Sr(e,"class");n&&(e.staticClass=JSON.stringify(n));var r=Or(e,"class",!1);r&&(e.classBinding=r)},genData:function(e){var t="";return e.staticClass&&(t+="staticClass:"+e.staticClass+","),e.classBinding&&(t+="class:"+e.classBinding+","),t}};var Yi,Qi={staticKeys:["staticStyle"],transformNode:function(e,t){t.warn;var n=Sr(e,"style");n&&(e.staticStyle=JSON.stringify(Kr(n)));var r=Or(e,"style",!1);r&&(e.styleBinding=r)},genData:function(e){var t="";return e.staticStyle&&(t+="staticStyle:"+e.staticStyle+","),e.styleBinding&&(t+="style:("+e.styleBinding+"),"),t}},eo=function(e){return(Yi=Yi||document.createElement("div")).innerHTML=e,Yi.textContent},to=s("area,base,br,col,embed,frame,hr,img,input,isindex,keygen,link,meta,param,source,track,wbr"),no=s("colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr,source"),ro=s("address,article,aside,base,blockquote,body,caption,col,colgroup,dd,details,dialog,div,dl,dt,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,head,header,hgroup,hr,html,legend,li,menuitem,meta,optgroup,option,param,rp,rt,source,style,summary,tbody,td,tfoot,th,thead,title,tr,track"),io=/^\s*([^\s"'<>\/=]+)(?:\s*(=)\s*(?:"([^"]*)"+|'([^']*)'+|([^\s"'=<>`]+)))?/,oo="[a-zA-Z_][\\w\\-\\.]*",ao="((?:"+oo+"\\:)?"+oo+")",so=new RegExp("^<"+ao),co=/^\s*(\/?)>/,lo=new RegExp("^<\\/"+ao+"[^>]*>"),uo=/^<!DOCTYPE [^>]+>/i,fo=/^<!\--/,po=/^<!\[/,vo=!1;"x".replace(/x(.)?/g,function(e,t){vo=""===t});var ho=s("script,style,textarea",!0),mo={},yo={"&lt;":"<","&gt;":">","&quot;":'"',"&amp;":"&","&#10;":"\n","&#9;":"\t"},go=/&(?:lt|gt|quot|amp);/g,_o=/&(?:lt|gt|quot|amp|#10|#9);/g,bo=s("pre,textarea",!0),$o=function(e,t){return e&&bo(e)&&"\n"===t[0]};var wo,Co,xo,ko,Ao,Oo,So,To,Eo=/^@|^v-on:/,jo=/^v-|^@|^:/,No=/([^]*?)\s+(?:in|of)\s+([^]*)/,Lo=/,([^,\}\]]*)(?:,([^,\}\]]*))?$/,Io=/^\(|\)$/g,Mo=/:(.*)$/,Do=/^:|^v-bind:/,Po=/\.[^.]+/g,Fo=e(eo);function Ro(e,t,n){return{type:1,tag:e,attrsList:t,attrsMap:function(e){for(var t={},n=0,r=e.length;n<r;n++)t[e[n].name]=e[n].value;return t}(t),parent:n,children:[]}}function Ho(e,p){wo=p.warn||$r,Oo=p.isPreTag||O,So=p.mustUseProp||O,To=p.getTagNamespace||O,xo=wr(p.modules,"transformNode"),ko=wr(p.modules,"preTransformNode"),Ao=wr(p.modules,"postTransformNode"),Co=p.delimiters;var d,v,h=[],i=!1!==p.preserveWhitespace,m=!1,y=!1;function g(e){e.pre&&(m=!1),Oo(e.tag)&&(y=!1);for(var t=0;t<Ao.length;t++)Ao[t](e,p)}return function(i,d){for(var e,v,h=[],m=d.expectHTML,y=d.isUnaryTag||O,g=d.canBeLeftOpenTag||O,a=0;i;){if(e=i,v&&ho(v)){var r=0,o=v.toLowerCase(),t=mo[o]||(mo[o]=new RegExp("([\\s\\S]*?)(</"+o+"[^>]*>)","i")),n=i.replace(t,function(e,t,n){return r=n.length,ho(o)||"noscript"===o||(t=t.replace(/<!\--([\s\S]*?)-->/g,"$1").replace(/<!\[CDATA\[([\s\S]*?)]]>/g,"$1")),$o(o,t)&&(t=t.slice(1)),d.chars&&d.chars(t),""});a+=i.length-n.length,i=n,A(o,a-r,a)}else{var s=i.indexOf("<");if(0===s){if(fo.test(i)){var c=i.indexOf("--\x3e");if(0<=c){d.shouldKeepComment&&d.comment(i.substring(4,c)),C(c+3);continue}}if(po.test(i)){var l=i.indexOf("]>");if(0<=l){C(l+2);continue}}var u=i.match(uo);if(u){C(u[0].length);continue}var f=i.match(lo);if(f){var p=a;C(f[0].length),A(f[1],p,a);continue}var _=x();if(_){k(_),$o(v,i)&&C(1);continue}}var b=void 0,$=void 0,w=void 0;if(0<=s){for($=i.slice(s);!(lo.test($)||so.test($)||fo.test($)||po.test($)||(w=$.indexOf("<",1))<0);)s+=w,$=i.slice(s);b=i.substring(0,s),C(s)}s<0&&(b=i,i=""),d.chars&&b&&d.chars(b)}if(i===e){d.chars&&d.chars(i);break}}function C(e){a+=e,i=i.substring(e)}function x(){var e=i.match(so);if(e){var t,n,r={tagName:e[1],attrs:[],start:a};for(C(e[0].length);!(t=i.match(co))&&(n=i.match(io));)C(n[0].length),r.attrs.push(n);if(t)return r.unarySlash=t[1],C(t[0].length),r.end=a,r}}function k(e){var t=e.tagName,n=e.unarySlash;m&&("p"===v&&ro(t)&&A(v),g(t)&&v===t&&A(t));for(var r,i,o,a=y(t)||!!n,s=e.attrs.length,c=new Array(s),l=0;l<s;l++){var u=e.attrs[l];vo&&-1===u[0].indexOf('""')&&(""===u[3]&&delete u[3],""===u[4]&&delete u[4],""===u[5]&&delete u[5]);var f=u[3]||u[4]||u[5]||"",p="a"===t&&"href"===u[1]?d.shouldDecodeNewlinesForHref:d.shouldDecodeNewlines;c[l]={name:u[1],value:(r=f,i=p,o=i?_o:go,r.replace(o,function(e){return yo[e]}))}}a||(h.push({tag:t,lowerCasedTag:t.toLowerCase(),attrs:c}),v=t),d.start&&d.start(t,c,a,e.start,e.end)}function A(e,t,n){var r,i;if(null==t&&(t=a),null==n&&(n=a),e&&(i=e.toLowerCase()),e)for(r=h.length-1;0<=r&&h[r].lowerCasedTag!==i;r--);else r=0;if(0<=r){for(var o=h.length-1;r<=o;o--)d.end&&d.end(h[o].tag,t,n);h.length=r,v=r&&h[r-1].tag}else"br"===i?d.start&&d.start(e,[],!0,t,n):"p"===i&&(d.start&&d.start(e,[],!1,t,n),d.end&&d.end(e,t,n))}A()}(e,{warn:wo,expectHTML:p.expectHTML,isUnaryTag:p.isUnaryTag,canBeLeftOpenTag:p.canBeLeftOpenTag,shouldDecodeNewlines:p.shouldDecodeNewlines,shouldDecodeNewlinesForHref:p.shouldDecodeNewlinesForHref,shouldKeepComment:p.comments,start:function(e,t,n){var r=v&&v.ns||To(e);K&&"svg"===r&&(t=function(e){for(var t=[],n=0;n<e.length;n++){var r=e[n];Ko.test(r.name)||(r.name=r.name.replace(Jo,""),t.push(r))}return t}(t));var i,o,a,s,c,l=Ro(e,t,v);r&&(l.ns=r),"style"!==(i=l).tag&&("script"!==i.tag||i.attrsMap.type&&"text/javascript"!==i.attrsMap.type)||Y()||(l.forbidden=!0);for(var u=0;u<ko.length;u++)l=ko[u](l,p)||l;if(m||(null!=Sr(o=l,"v-pre")&&(o.pre=!0),l.pre&&(m=!0)),Oo(l.tag)&&(y=!0),m?function(e){var t=e.attrsList.length;if(t)for(var n=e.attrs=new Array(t),r=0;r<t;r++)n[r]={name:e.attrsList[r].name,value:JSON.stringify(e.attrsList[r].value)};else e.pre||(e.plain=!0)}(l):l.processed||(Uo(l),function(e){var t=Sr(e,"v-if");if(t)e.if=t,Vo(e,{exp:t,block:e});else{null!=Sr(e,"v-else")&&(e.else=!0);var n=Sr(e,"v-else-if");n&&(e.elseif=n)}}(l),null!=Sr(a=l,"v-once")&&(a.once=!0),Bo(l,p)),d?h.length||d.if&&(l.elseif||l.else)&&Vo(d,{exp:l.elseif,block:l}):d=l,v&&!l.forbidden)if(l.elseif||l.else)s=l,(c=function(e){var t=e.length;for(;t--;){if(1===e[t].type)return e[t];e.pop()}}(v.children))&&c.if&&Vo(c,{exp:s.elseif,block:s});else if(l.slotScope){v.plain=!1;var f=l.slotTarget||'"default"';(v.scopedSlots||(v.scopedSlots={}))[f]=l}else v.children.push(l),l.parent=v;n?g(l):(v=l,h.push(l))},end:function(){var e=h[h.length-1],t=e.children[e.children.length-1];t&&3===t.type&&" "===t.text&&!y&&e.children.pop(),h.length-=1,v=h[h.length-1],g(e)},chars:function(e){if(v&&(!K||"textarea"!==v.tag||v.attrsMap.placeholder!==e)){var t,n,r=v.children;if(e=y||e.trim()?"script"===(t=v).tag||"style"===t.tag?e:Fo(e):i&&r.length?" ":"")!m&&" "!==e&&(n=function(e,t){var n=t?Zi(t):Wi;if(n.test(e)){for(var r,i,o,a=[],s=[],c=n.lastIndex=0;r=n.exec(e);){c<(i=r.index)&&(s.push(o=e.slice(c,i)),a.push(JSON.stringify(o)));var l=_r(r[1].trim());a.push("_s("+l+")"),s.push({"@binding":l}),c=i+r[0].length}return c<e.length&&(s.push(o=e.slice(c)),a.push(JSON.stringify(o))),{expression:a.join("+"),tokens:s}}}(e,Co))?r.push({type:2,expression:n.expression,tokens:n.tokens,text:e}):" "===e&&r.length&&" "===r[r.length-1].text||r.push({type:3,text:e})}},comment:function(e){v.children.push({type:3,text:e,isComment:!0})}}),d}function Bo(e,t){var n,r,i,o;(r=Or(n=e,"key"))&&(n.key=r),e.plain=!e.key&&!e.attrsList.length,(o=Or(i=e,"ref"))&&(i.ref=o,i.refInFor=function(e){for(var t=e;t;){if(void 0!==t.for)return!0;t=t.parent}return!1}(i)),function(e){if("slot"===e.tag)e.slotName=Or(e,"name");else{var t;"template"===e.tag?(t=Sr(e,"scope"),e.slotScope=t||Sr(e,"slot-scope")):(t=Sr(e,"slot-scope"))&&(e.slotScope=t);var n=Or(e,"slot");n&&(e.slotTarget='""'===n?'"default"':n,"template"===e.tag||e.slotScope||xr(e,"slot",n))}}(e),function(e){var t;(t=Or(e,"is"))&&(e.component=t);null!=Sr(e,"inline-template")&&(e.inlineTemplate=!0)}(e);for(var a=0;a<xo.length;a++)e=xo[a](e,t)||e;!function(e){var t,n,r,i,o,a,s,c=e.attrsList;for(t=0,n=c.length;t<n;t++)if(r=i=c[t].name,o=c[t].value,jo.test(r))if(e.hasBindings=!0,(a=zo(r))&&(r=r.replace(Po,"")),Do.test(r))r=r.replace(Do,""),o=_r(o),s=!1,a&&(a.prop&&(s=!0,"innerHtml"===(r=g(r))&&(r="innerHTML")),a.camel&&(r=g(r)),a.sync&&Ar(e,"update:"+g(r),Er(o,"$event"))),s||!e.component&&So(e.tag,e.attrsMap.type,r)?Cr(e,r,o):xr(e,r,o);else if(Eo.test(r))r=r.replace(Eo,""),Ar(e,r,o,a,!1);else{var l=(r=r.replace(jo,"")).match(Mo),u=l&&l[1];u&&(r=r.slice(0,-(u.length+1))),p=r,d=i,v=o,h=u,m=a,((f=e).directives||(f.directives=[])).push({name:p,rawName:d,value:v,arg:h,modifiers:m}),f.plain=!1}else xr(e,r,JSON.stringify(o)),!e.component&&"muted"===r&&So(e.tag,e.attrsMap.type,r)&&Cr(e,r,"true");var f,p,d,v,h,m}(e)}function Uo(e){var t;if(t=Sr(e,"v-for")){var n=function(e){var t=e.match(No);if(!t)return;var n={};n.for=t[2].trim();var r=t[1].trim().replace(Io,""),i=r.match(Lo);i?(n.alias=r.replace(Lo,""),n.iterator1=i[1].trim(),i[2]&&(n.iterator2=i[2].trim())):n.alias=r;return n}(t);n&&m(e,n)}}function Vo(e,t){e.ifConditions||(e.ifConditions=[]),e.ifConditions.push(t)}function zo(e){var t=e.match(Po);if(t){var n={};return t.forEach(function(e){n[e.slice(1)]=!0}),n}}var Ko=/^xmlns:NS\d+/,Jo=/^NS\d+:/;function qo(e){return Ro(e.tag,e.attrsList.slice(),e.parent)}var Wo=[Xi,Qi,{preTransformNode:function(e,t){if("input"===e.tag){var n,r=e.attrsMap;if(!r["v-model"])return;if((r[":type"]||r["v-bind:type"])&&(n=Or(e,"type")),r.type||n||!r["v-bind"]||(n="("+r["v-bind"]+").type"),n){var i=Sr(e,"v-if",!0),o=i?"&&("+i+")":"",a=null!=Sr(e,"v-else",!0),s=Sr(e,"v-else-if",!0),c=qo(e);Uo(c),kr(c,"type","checkbox"),Bo(c,t),c.processed=!0,c.if="("+n+")==='checkbox'"+o,Vo(c,{exp:c.if,block:c});var l=qo(e);Sr(l,"v-for",!0),kr(l,"type","radio"),Bo(l,t),Vo(c,{exp:"("+n+")==='radio'"+o,block:l});var u=qo(e);return Sr(u,"v-for",!0),kr(u,":type",n),Bo(u,t),Vo(c,{exp:i,block:u}),a?c.else=!0:s&&(c.elseif=s),c}}}}];var Go,Zo,Xo,Yo={expectHTML:!0,modules:Wo,directives:{model:function(e,t,n){var r,i,o,a,s,c,l,u,f,p,d,v,h,m,y,g,_=t.value,b=t.modifiers,$=e.tag,w=e.attrsMap.type;if(e.component)return Tr(e,_,b),!1;if("select"===$)h=e,m=_,g=(g='var $$selectedVal = Array.prototype.filter.call($event.target.options,function(o){return o.selected}).map(function(o){var val = "_value" in o ? o._value : o.value;return '+((y=b)&&y.number?"_n(val)":"val")+"});")+" "+Er(m,"$event.target.multiple ? $$selectedVal : $$selectedVal[0]"),Ar(h,"change",g,null,!0);else if("input"===$&&"checkbox"===w)c=e,l=_,f=(u=b)&&u.number,p=Or(c,"value")||"null",d=Or(c,"true-value")||"true",v=Or(c,"false-value")||"false",Cr(c,"checked","Array.isArray("+l+")?_i("+l+","+p+")>-1"+("true"===d?":("+l+")":":_q("+l+","+d+")")),Ar(c,"change","var $$a="+l+",$$el=$event.target,$$c=$$el.checked?("+d+"):("+v+");if(Array.isArray($$a)){var $$v="+(f?"_n("+p+")":p)+",$$i=_i($$a,$$v);if($$el.checked){$$i<0&&("+Er(l,"$$a.concat([$$v])")+")}else{$$i>-1&&("+Er(l,"$$a.slice(0,$$i).concat($$a.slice($$i+1))")+")}}else{"+Er(l,"$$c")+"}",null,!0);else if("input"===$&&"radio"===w)r=e,i=_,a=(o=b)&&o.number,s=Or(r,"value")||"null",Cr(r,"checked","_q("+i+","+(s=a?"_n("+s+")":s)+")"),Ar(r,"change",Er(i,s),null,!0);else if("input"===$||"textarea"===$)!function(e,t,n){var r=e.attrsMap.type,i=n||{},o=i.lazy,a=i.number,s=i.trim,c=!o&&"range"!==r,l=o?"change":"range"===r?Pr:"input",u="$event.target.value";s&&(u="$event.target.value.trim()"),a&&(u="_n("+u+")");var f=Er(t,u);c&&(f="if($event.target.composing)return;"+f),Cr(e,"value","("+t+")"),Ar(e,l,f,null,!0),(s||a)&&Ar(e,"blur","$forceUpdate()")}(e,_,b);else if(!j.isReservedTag($))return Tr(e,_,b),!1;return!0},text:function(e,t){t.value&&Cr(e,"textContent","_s("+t.value+")")},html:function(e,t){t.value&&Cr(e,"innerHTML","_s("+t.value+")")}},isPreTag:function(e){return"pre"===e},isUnaryTag:to,mustUseProp:Sn,canBeLeftOpenTag:no,isReservedTag:Un,getTagNamespace:Vn,staticKeys:(Go=Wo,Go.reduce(function(e,t){return e.concat(t.staticKeys||[])},[]).join(","))},Qo=e(function(e){return s("type,tag,attrsList,attrsMap,plain,parent,children,attrs"+(e?","+e:""))});function ea(e,t){e&&(Zo=Qo(t.staticKeys||""),Xo=t.isReservedTag||O,function e(t){t.static=function(e){if(2===e.type)return!1;if(3===e.type)return!0;return!(!e.pre&&(e.hasBindings||e.if||e.for||c(e.tag)||!Xo(e.tag)||function(e){for(;e.parent;){if("template"!==(e=e.parent).tag)return!1;if(e.for)return!0}return!1}(e)||!Object.keys(e).every(Zo)))}(t);if(1===t.type){if(!Xo(t.tag)&&"slot"!==t.tag&&null==t.attrsMap["inline-template"])return;for(var n=0,r=t.children.length;n<r;n++){var i=t.children[n];e(i),i.static||(t.static=!1)}if(t.ifConditions)for(var o=1,a=t.ifConditions.length;o<a;o++){var s=t.ifConditions[o].block;e(s),s.static||(t.static=!1)}}}(e),function e(t,n){if(1===t.type){if((t.static||t.once)&&(t.staticInFor=n),t.static&&t.children.length&&(1!==t.children.length||3!==t.children[0].type))return void(t.staticRoot=!0);if(t.staticRoot=!1,t.children)for(var r=0,i=t.children.length;r<i;r++)e(t.children[r],n||!!t.for);if(t.ifConditions)for(var o=1,a=t.ifConditions.length;o<a;o++)e(t.ifConditions[o].block,n)}}(e,!1))}var ta=/^([\w$_]+|\([^)]*?\))\s*=>|^function\s*\(/,na=/^[A-Za-z_$][\w$]*(?:\.[A-Za-z_$][\w$]*|\['[^']*?']|\["[^"]*?"]|\[\d+]|\[[A-Za-z_$][\w$]*])*$/,ra={esc:27,tab:9,enter:13,space:32,up:38,left:37,right:39,down:40,delete:[8,46]},ia={esc:"Escape",tab:"Tab",enter:"Enter",space:" ",up:["Up","ArrowUp"],left:["Left","ArrowLeft"],right:["Right","ArrowRight"],down:["Down","ArrowDown"],delete:["Backspace","Delete"]},oa=function(e){return"if("+e+")return null;"},aa={stop:"$event.stopPropagation();",prevent:"$event.preventDefault();",self:oa("$event.target !== $event.currentTarget"),ctrl:oa("!$event.ctrlKey"),shift:oa("!$event.shiftKey"),alt:oa("!$event.altKey"),meta:oa("!$event.metaKey"),left:oa("'button' in $event && $event.button !== 0"),middle:oa("'button' in $event && $event.button !== 1"),right:oa("'button' in $event && $event.button !== 2")};function sa(e,t,n){var r=t?"nativeOn:{":"on:{";for(var i in e)r+='"'+i+'":'+ca(i,e[i])+",";return r.slice(0,-1)+"}"}function ca(t,e){if(!e)return"function(){}";if(Array.isArray(e))return"["+e.map(function(e){return ca(t,e)}).join(",")+"]";var n=na.test(e.value),r=ta.test(e.value);if(e.modifiers){var i="",o="",a=[];for(var s in e.modifiers)if(aa[s])o+=aa[s],ra[s]&&a.push(s);else if("exact"===s){var c=e.modifiers;o+=oa(["ctrl","shift","alt","meta"].filter(function(e){return!c[e]}).map(function(e){return"$event."+e+"Key"}).join("||"))}else a.push(s);return a.length&&(i+="if(!('button' in $event)&&"+a.map(la).join("&&")+")return null;"),o&&(i+=o),"function($event){"+i+(n?"return "+e.value+"($event)":r?"return ("+e.value+")($event)":e.value)+"}"}return n||r?e.value:"function($event){"+e.value+"}"}function la(e){var t=parseInt(e,10);if(t)return"$event.keyCode!=="+t;var n=ra[e],r=ia[e];return"_k($event.keyCode,"+JSON.stringify(e)+","+JSON.stringify(n)+",$event.key,"+JSON.stringify(r)+")"}var ua={on:function(e,t){e.wrapListeners=function(e){return"_g("+e+","+t.value+")"}},bind:function(t,n){t.wrapData=function(e){return"_b("+e+",'"+t.tag+"',"+n.value+","+(n.modifiers&&n.modifiers.prop?"true":"false")+(n.modifiers&&n.modifiers.sync?",true":"")+")"}},cloak:$},fa=function(e){this.options=e,this.warn=e.warn||$r,this.transforms=wr(e.modules,"transformCode"),this.dataGenFns=wr(e.modules,"genData"),this.directives=m(m({},ua),e.directives);var t=e.isReservedTag||O;this.maybeComponent=function(e){return!t(e.tag)},this.onceId=0,this.staticRenderFns=[]};function pa(e,t){var n=new fa(t);return{render:"with(this){return "+(e?da(e,n):'_c("div")')+"}",staticRenderFns:n.staticRenderFns}}function da(e,t){if(e.staticRoot&&!e.staticProcessed)return va(e,t);if(e.once&&!e.onceProcessed)return ha(e,t);if(e.for&&!e.forProcessed)return f=t,v=(u=e).for,h=u.alias,m=u.iterator1?","+u.iterator1:"",y=u.iterator2?","+u.iterator2:"",u.forProcessed=!0,(d||"_l")+"(("+v+"),function("+h+m+y+"){return "+(p||da)(u,f)+"})";if(e.if&&!e.ifProcessed)return ma(e,t);if("template"!==e.tag||e.slotTarget){if("slot"===e.tag)return function(e,t){var n=e.slotName||'"default"',r=_a(e,t),i="_t("+n+(r?","+r:""),o=e.attrs&&"{"+e.attrs.map(function(e){return g(e.name)+":"+e.value}).join(",")+"}",a=e.attrsMap["v-bind"];!o&&!a||r||(i+=",null");o&&(i+=","+o);a&&(i+=(o?"":",null")+","+a);return i+")"}(e,t);var n;if(e.component)a=e.component,c=t,l=(s=e).inlineTemplate?null:_a(s,c,!0),n="_c("+a+","+ya(s,c)+(l?","+l:"")+")";else{var r=e.plain?void 0:ya(e,t),i=e.inlineTemplate?null:_a(e,t,!0);n="_c('"+e.tag+"'"+(r?","+r:"")+(i?","+i:"")+")"}for(var o=0;o<t.transforms.length;o++)n=t.transforms[o](e,n);return n}return _a(e,t)||"void 0";var a,s,c,l,u,f,p,d,v,h,m,y}function va(e,t){return e.staticProcessed=!0,t.staticRenderFns.push("with(this){return "+da(e,t)+"}"),"_m("+(t.staticRenderFns.length-1)+(e.staticInFor?",true":"")+")"}function ha(e,t){if(e.onceProcessed=!0,e.if&&!e.ifProcessed)return ma(e,t);if(e.staticInFor){for(var n="",r=e.parent;r;){if(r.for){n=r.key;break}r=r.parent}return n?"_o("+da(e,t)+","+t.onceId+++","+n+")":da(e,t)}return va(e,t)}function ma(e,t,n,r){return e.ifProcessed=!0,function e(t,n,r,i){if(!t.length)return i||"_e()";var o=t.shift();return o.exp?"("+o.exp+")?"+a(o.block)+":"+e(t,n,r,i):""+a(o.block);function a(e){return r?r(e,n):e.once?ha(e,n):da(e,n)}}(e.ifConditions.slice(),t,n,r)}function ya(e,t){var n,r,i="{",o=function(e,t){var n=e.directives;if(!n)return;var r,i,o,a,s="directives:[",c=!1;for(r=0,i=n.length;r<i;r++){o=n[r],a=!0;var l=t.directives[o.name];l&&(a=!!l(e,o,t.warn)),a&&(c=!0,s+='{name:"'+o.name+'",rawName:"'+o.rawName+'"'+(o.value?",value:("+o.value+"),expression:"+JSON.stringify(o.value):"")+(o.arg?',arg:"'+o.arg+'"':"")+(o.modifiers?",modifiers:"+JSON.stringify(o.modifiers):"")+"},")}if(c)return s.slice(0,-1)+"]"}(e,t);o&&(i+=o+","),e.key&&(i+="key:"+e.key+","),e.ref&&(i+="ref:"+e.ref+","),e.refInFor&&(i+="refInFor:true,"),e.pre&&(i+="pre:true,"),e.component&&(i+='tag:"'+e.tag+'",');for(var a=0;a<t.dataGenFns.length;a++)i+=t.dataGenFns[a](e);if(e.attrs&&(i+="attrs:{"+wa(e.attrs)+"},"),e.props&&(i+="domProps:{"+wa(e.props)+"},"),e.events&&(i+=sa(e.events,!1,t.warn)+","),e.nativeEvents&&(i+=sa(e.nativeEvents,!0,t.warn)+","),e.slotTarget&&!e.slotScope&&(i+="slot:"+e.slotTarget+","),e.scopedSlots&&(i+=(n=e.scopedSlots,r=t,"scopedSlots:_u(["+Object.keys(n).map(function(e){return ga(e,n[e],r)}).join(",")+"]),")),e.model&&(i+="model:{value:"+e.model.value+",callback:"+e.model.callback+",expression:"+e.model.expression+"},"),e.inlineTemplate){var s=function(e,t){var n=e.children[0];if(1===n.type){var r=pa(n,t.options);return"inlineTemplate:{render:function(){"+r.render+"},staticRenderFns:["+r.staticRenderFns.map(function(e){return"function(){"+e+"}"}).join(",")+"]}"}}(e,t);s&&(i+=s+",")}return i=i.replace(/,$/,"")+"}",e.wrapData&&(i=e.wrapData(i)),e.wrapListeners&&(i=e.wrapListeners(i)),i}function ga(e,t,n){return t.for&&!t.forProcessed?(r=e,o=n,a=(i=t).for,s=i.alias,c=i.iterator1?","+i.iterator1:"",l=i.iterator2?","+i.iterator2:"",i.forProcessed=!0,"_l(("+a+"),function("+s+c+l+"){return "+ga(r,i,o)+"})"):"{key:"+e+",fn:"+("function("+String(t.slotScope)+"){return "+("template"===t.tag?t.if?t.if+"?"+(_a(t,n)||"undefined")+":undefined":_a(t,n)||"undefined":da(t,n))+"}")+"}";var r,i,o,a,s,c,l}function _a(e,t,n,r,i){var o=e.children;if(o.length){var a=o[0];if(1===o.length&&a.for&&"template"!==a.tag&&"slot"!==a.tag)return(r||da)(a,t);var s=n?function(e,t){for(var n=0,r=0;r<e.length;r++){var i=e[r];if(1===i.type){if(ba(i)||i.ifConditions&&i.ifConditions.some(function(e){return ba(e.block)})){n=2;break}(t(i)||i.ifConditions&&i.ifConditions.some(function(e){return t(e.block)}))&&(n=1)}}return n}(o,t.maybeComponent):0,c=i||$a;return"["+o.map(function(e){return c(e,t)}).join(",")+"]"+(s?","+s:"")}}function ba(e){return void 0!==e.for||"template"===e.tag||"slot"===e.tag}function $a(e,t){return 1===e.type?da(e,t):3===e.type&&e.isComment?(r=e,"_e("+JSON.stringify(r.text)+")"):"_v("+(2===(n=e).type?n.expression:Ca(JSON.stringify(n.text)))+")";var n,r}function wa(e){for(var t="",n=0;n<e.length;n++){var r=e[n];t+='"'+r.name+'":'+Ca(r.value)+","}return t.slice(0,-1)}function Ca(e){return e.replace(/\u2028/g,"\\u2028").replace(/\u2029/g,"\\u2029")}new RegExp("\\b"+"do,if,for,let,new,try,var,case,else,with,await,break,catch,class,const,super,throw,while,yield,delete,export,import,return,switch,default,extends,finally,continue,debugger,function,arguments".split(",").join("\\b|\\b")+"\\b"),new RegExp("\\b"+"delete,typeof,void".split(",").join("\\s*\\([^\\)]*\\)|\\b")+"\\s*\\([^\\)]*\\)");function xa(t,n){try{return new Function(t)}catch(e){return n.push({err:e,code:t}),$}}var ka,Aa,Oa=(ka=function(e,t){var n=Ho(e.trim(),t);!1!==t.optimize&&ea(n,t);var r=pa(n,t);return{ast:n,render:r.render,staticRenderFns:r.staticRenderFns}},function(s){function e(e,t){var n=Object.create(s),r=[],i=[];if(n.warn=function(e,t){(t?i:r).push(e)},t)for(var o in t.modules&&(n.modules=(s.modules||[]).concat(t.modules)),t.directives&&(n.directives=m(Object.create(s.directives||null),t.directives)),t)"modules"!==o&&"directives"!==o&&(n[o]=t[o]);var a=ka(e,n);return a.errors=r,a.tips=i,a}return{compile:e,compileToFunctions:(c=e,l=Object.create(null),function(e,t,n){(t=m({},t)).warn,delete t.warn;var r=t.delimiters?String(t.delimiters)+e:e;if(l[r])return l[r];var i=c(e,t),o={},a=[];return o.render=xa(i.render,a),o.staticRenderFns=i.staticRenderFns.map(function(e){return xa(e,a)}),l[r]=o})};var c,l})(Yo).compileToFunctions;function Sa(e){return(Aa=Aa||document.createElement("div")).innerHTML=e?'<a href="\n"/>':'<div a="\n"/>',0<Aa.innerHTML.indexOf("&#10;")}var Ta=!!B&&Sa(!1),Ea=!!B&&Sa(!0),ja=e(function(e){var t=Jn(e);return t&&t.innerHTML}),Na=hn.prototype.$mount;return hn.prototype.$mount=function(e,t){if((e=e&&Jn(e))===document.body||e===document.documentElement)return this;var n=this.$options;if(!n.render){var r=n.template;if(r)if("string"==typeof r)"#"===r.charAt(0)&&(r=ja(r));else{if(!r.nodeType)return this;r=r.innerHTML}else e&&(r=function(e){{if(e.outerHTML)return e.outerHTML;var t=document.createElement("div");return t.appendChild(e.cloneNode(!0)),t.innerHTML}}(e));if(r){var i=Oa(r,{shouldDecodeNewlines:Ta,shouldDecodeNewlinesForHref:Ea,delimiters:n.delimiters,comments:n.comments},this),o=i.render,a=i.staticRenderFns;n.render=o,n.staticRenderFns=a}}return Na.call(this,e,t)},hn.compile=Oa,hn});
/* interact.js v1.3.4 | https://raw.github.com/taye/interact.js/master/LICENSE */
!function(t){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=t();else if("function"==typeof define&&define.amd)define([],t);else{var e;e="undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this,e.interact=t()}}(function(){return function t(e,n,r){function i(s,a){if(!n[s]){if(!e[s]){var c="function"==typeof require&&require;if(!a&&c)return c(s,!0);if(o)return o(s,!0);var l=new Error("Cannot find module '"+s+"'");throw l.code="MODULE_NOT_FOUND",l}var p=n[s]={exports:{}};e[s][0].call(p.exports,function(t){var n=e[s][1][t];return i(n||t)},p,p.exports,t,e,n,r)}return n[s].exports}for(var o="function"==typeof require&&require,s=0;s<r.length;s++)i(r[s]);return i}({1:[function(t,e,n){"use strict";"undefined"==typeof window?e.exports=function(e){return t("./src/utils/window").init(e),t("./src/index")}:e.exports=t("./src/index")},{"./src/index":19,"./src/utils/window":52}],2:[function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function i(t,e){for(var n=0;n<e.length;n++){var r;r=e[n];var i=r;if(t.immediatePropagationStopped)break;i(t)}}var o=t("./utils/extend.js"),s=function(){function t(e){r(this,t),this.options=o({},e||{})}return t.prototype.fire=function(t){var e=void 0,n="on"+t.type,r=this.global;(e=this[t.type])&&i(t,e),this[n]&&this[n](t),!t.propagationStopped&&r&&(e=r[t.type])&&i(t,e)},t.prototype.on=function(t,e){this[t]?this[t].push(e):this[t]=[e]},t.prototype.off=function(t,e){var n=this[t],r=n?n.indexOf(e):-1;-1!==r&&n.splice(r,1),(n&&0===n.length||!e)&&(this[t]=void 0)},t}();e.exports=s},{"./utils/extend.js":41}],3:[function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var i=t("./utils/extend"),o=t("./utils/getOriginXY"),s=t("./defaultOptions"),a=t("./utils/Signals").new(),c=function(){function t(e,n,c,l,p,u){var d=arguments.length>6&&void 0!==arguments[6]&&arguments[6];r(this,t);var f=e.target,v=(f&&f.options||s).deltaSource,g=o(f,p,c),h="start"===l,m="end"===l,y=h?e.startCoords:e.curCoords,x=e.prevEvent;p=p||e.element;var b=i({},y.page),w=i({},y.client);b.x-=g.x,b.y-=g.y,w.x-=g.x,w.y-=g.y,this.ctrlKey=n.ctrlKey,this.altKey=n.altKey,this.shiftKey=n.shiftKey,this.metaKey=n.metaKey,this.button=n.button,this.buttons=n.buttons,this.target=p,this.currentTarget=p,this.relatedTarget=u||null,this.preEnd=d,this.type=c+(l||""),this.interaction=e,this.interactable=f,this.t0=h?e.downTimes[e.downTimes.length-1]:x.t0;var E={interaction:e,event:n,action:c,phase:l,element:p,related:u,page:b,client:w,coords:y,starting:h,ending:m,deltaSource:v,iEvent:this};a.fire("set-xy",E),m?(this.pageX=x.pageX,this.pageY=x.pageY,this.clientX=x.clientX,this.clientY=x.clientY):(this.pageX=b.x,this.pageY=b.y,this.clientX=w.x,this.clientY=w.y),this.x0=e.startCoords.page.x-g.x,this.y0=e.startCoords.page.y-g.y,this.clientX0=e.startCoords.client.x-g.x,this.clientY0=e.startCoords.client.y-g.y,a.fire("set-delta",E),this.timeStamp=y.timeStamp,this.dt=e.pointerDelta.timeStamp,this.duration=this.timeStamp-this.t0,this.speed=e.pointerDelta[v].speed,this.velocityX=e.pointerDelta[v].vx,this.velocityY=e.pointerDelta[v].vy,this.swipe=m||"inertiastart"===l?this.getSwipe():null,a.fire("new",E)}return t.prototype.getSwipe=function(){var t=this.interaction;if(t.prevEvent.speed<600||this.timeStamp-t.prevEvent.timeStamp>150)return null;var e=180*Math.atan2(t.prevEvent.velocityY,t.prevEvent.velocityX)/Math.PI;e<0&&(e+=360);var n=112.5<=e&&e<247.5,r=202.5<=e&&e<337.5,i=!n&&(292.5<=e||e<67.5);return{up:r,down:!r&&22.5<=e&&e<157.5,left:n,right:i,angle:e,speed:t.prevEvent.speed,velocity:{x:t.prevEvent.velocityX,y:t.prevEvent.velocityY}}},t.prototype.preventDefault=function(){},t.prototype.stopImmediatePropagation=function(){this.immediatePropagationStopped=this.propagationStopped=!0},t.prototype.stopPropagation=function(){this.propagationStopped=!0},t}();a.on("set-delta",function(t){var e=t.iEvent,n=t.interaction,r=t.starting,i=t.deltaSource,o=r?e:n.prevEvent;"client"===i?(e.dx=e.clientX-o.clientX,e.dy=e.clientY-o.clientY):(e.dx=e.pageX-o.pageX,e.dy=e.pageY-o.pageY)}),c.signals=a,e.exports=c},{"./defaultOptions":18,"./utils/Signals":34,"./utils/extend":41,"./utils/getOriginXY":42}],4:[function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var i=t("./utils/clone"),o=t("./utils/is"),s=t("./utils/events"),a=t("./utils/extend"),c=t("./actions/base"),l=t("./scope"),p=t("./Eventable"),u=t("./defaultOptions"),d=t("./utils/Signals").new(),f=t("./utils/domUtils"),v=f.getElementRect,g=f.nodeContains,h=f.trySelector,m=f.matchesSelector,y=t("./utils/window"),x=y.getWindow,b=t("./utils/arr"),w=b.contains,E=t("./utils/browser"),T=E.wheelEvent;l.interactables=[];var S=function(){function t(e,n){r(this,t),n=n||{},this.target=e,this.events=new p,this._context=n.context||l.document,this._win=x(h(e)?this._context:e),this._doc=this._win.document,d.fire("new",{target:e,options:n,interactable:this,win:this._win}),l.addDocument(this._doc,this._win),l.interactables.push(this),this.set(n)}return t.prototype.setOnEvents=function(t,e){var n="on"+t;return o.function(e.onstart)&&(this.events[n+"start"]=e.onstart),o.function(e.onmove)&&(this.events[n+"move"]=e.onmove),o.function(e.onend)&&(this.events[n+"end"]=e.onend),o.function(e.oninertiastart)&&(this.events[n+"inertiastart"]=e.oninertiastart),this},t.prototype.setPerAction=function(t,e){for(var n in e)n in u[t]&&(o.object(e[n])?(this.options[t][n]=i(this.options[t][n]||{}),a(this.options[t][n],e[n]),o.object(u.perAction[n])&&"enabled"in u.perAction[n]&&(this.options[t][n].enabled=!1!==e[n].enabled)):o.bool(e[n])&&o.object(u.perAction[n])?this.options[t][n].enabled=e[n]:void 0!==e[n]&&(this.options[t][n]=e[n]))},t.prototype.getRect=function(t){return t=t||this.target,o.string(this.target)&&!o.element(t)&&(t=this._context.querySelector(this.target)),v(t)},t.prototype.rectChecker=function(t){return o.function(t)?(this.getRect=t,this):null===t?(delete this.options.getRect,this):this.getRect},t.prototype._backCompatOption=function(t,e){if(h(e)||o.object(e)){this.options[t]=e;for(var n=0;n<c.names.length;n++){var r;r=c.names[n];var i=r;this.options[i][t]=e}return this}return this.options[t]},t.prototype.origin=function(t){return this._backCompatOption("origin",t)},t.prototype.deltaSource=function(t){return"page"===t||"client"===t?(this.options.deltaSource=t,this):this.options.deltaSource},t.prototype.context=function(){return this._context},t.prototype.inContext=function(t){return this._context===t.ownerDocument||g(this._context,t)},t.prototype.fire=function(t){return this.events.fire(t),this},t.prototype._onOffMultiple=function(t,e,n,r){if(o.string(e)&&-1!==e.search(" ")&&(e=e.trim().split(/ +/)),o.array(e)){for(var i=0;i<e.length;i++){var s;s=e[i];var a=s;this[t](a,n,r)}return!0}if(o.object(e)){for(var c in e)this[t](c,e[c],n);return!0}},t.prototype.on=function(e,n,r){return this._onOffMultiple("on",e,n,r)?this:("wheel"===e&&(e=T),w(t.eventTypes,e)?this.events.on(e,n):o.string(this.target)?s.addDelegate(this.target,this._context,e,n,r):s.add(this.target,e,n,r),this)},t.prototype.off=function(e,n,r){return this._onOffMultiple("off",e,n,r)?this:("wheel"===e&&(e=T),w(t.eventTypes,e)?this.events.off(e,n):o.string(this.target)?s.removeDelegate(this.target,this._context,e,n,r):s.remove(this.target,e,n,r),this)},t.prototype.set=function(e){o.object(e)||(e={}),this.options=i(u.base);var n=i(u.perAction);for(var r in c.methodDict){var s=c.methodDict[r];this.options[r]=i(u[r]),this.setPerAction(r,n),this[s](e[r])}for(var a=0;a<t.settingsMethods.length;a++){var l;l=t.settingsMethods[a];var p=l;this.options[p]=u.base[p],p in e&&this[p](e[p])}return d.fire("set",{options:e,interactable:this}),this},t.prototype.unset=function(){if(s.remove(this.target,"all"),o.string(this.target))for(var t in s.delegatedEvents){var e=s.delegatedEvents[t];e.selectors[0]===this.target&&e.contexts[0]===this._context&&(e.selectors.splice(0,1),e.contexts.splice(0,1),e.listeners.splice(0,1),e.selectors.length||(e[t]=null)),s.remove(this._context,t,s.delegateListener),s.remove(this._context,t,s.delegateUseCapture,!0)}else s.remove(this,"all");d.fire("unset",{interactable:this}),l.interactables.splice(l.interactables.indexOf(this),1);for(var n=0;n<(l.interactions||[]).length;n++){var r;r=(l.interactions||[])[n];var i=r;i.target===this&&i.interacting()&&!i._ending&&i.stop()}return l.interact},t}();l.interactables.indexOfElement=function(t,e){e=e||l.document;for(var n=0;n<this.length;n++){var r=this[n];if(r.target===t&&r._context===e)return n}return-1},l.interactables.get=function(t,e,n){var r=this[this.indexOfElement(t,e&&e.context)];return r&&(o.string(t)||n||r.inContext(t))?r:null},l.interactables.forEachMatch=function(t,e){for(var n=0;n<this.length;n++){var r;r=this[n];var i=r,s=void 0;if((o.string(i.target)?o.element(t)&&m(t,i.target):t===i.target)&&i.inContext(t)&&(s=e(i)),void 0!==s)return s}},S.eventTypes=l.eventTypes=[],S.signals=d,S.settingsMethods=["deltaSource","origin","preventDefault","rectChecker"],e.exports=S},{"./Eventable":2,"./actions/base":6,"./defaultOptions":18,"./scope":33,"./utils/Signals":34,"./utils/arr":35,"./utils/browser":36,"./utils/clone":37,"./utils/domUtils":39,"./utils/events":40,"./utils/extend":41,"./utils/is":46,"./utils/window":52}],5:[function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function i(t){return function(e){var n=c.getPointerType(e),r=c.getEventTargets(e),i=r[0],o=r[1],s=[];if(p.supportsTouch&&/touch/.test(e.type)){h=(new Date).getTime();for(var l=0;l<e.changedTouches.length;l++){var u;u=e.changedTouches[l];var f=u,v=f,g=d.search(v,e.type,i);s.push([v,g||new m({pointerType:n})])}}else{var y=!1;if(!p.supportsPointerEvent&&/mouse/.test(e.type)){for(var x=0;x<a.interactions.length&&!y;x++)y="mouse"!==a.interactions[x].pointerType&&a.interactions[x].pointerIsDown;y=y||(new Date).getTime()-h<500||0===e.timeStamp}if(!y){var b=d.search(e,e.type,i);b||(b=new m({pointerType:n})),s.push([e,b])}}for(var w=0;w<s.length;w++){var E=s[w],T=E[0],S=E[1];S._updateEventTargets(i,o),S[t](T,e,i,o)}}}function o(t){for(var e=0;e<a.interactions.length;e++){var n;n=a.interactions[e];var r=n;r.end(t),f.fire("endall",{event:t,interaction:r})}}function s(t,e){var n=t.doc,r=0===e.indexOf("add")?l.add:l.remove;for(var i in a.delegatedEvents)r(n,i,l.delegateListener),r(n,i,l.delegateUseCapture,!0);for(var o in b)r(n,o,b[o],p.isIOS?{passive:!1}:void 0)}var a=t("./scope"),c=t("./utils"),l=t("./utils/events"),p=t("./utils/browser"),u=t("./utils/domObjects"),d=t("./utils/interactionFinder"),f=t("./utils/Signals").new(),v={},g=["pointerDown","pointerMove","pointerUp","updatePointer","removePointer"],h=0;a.interactions=[];for(var m=function(){function t(e){var n=e.pointerType;r(this,t),this.target=null,this.element=null,this.prepared={name:null,axis:null,edges:null},this.pointers=[],this.pointerIds=[],this.downTargets=[],this.downTimes=[],this.prevCoords={page:{x:0,y:0},client:{x:0,y:0},timeStamp:0},this.curCoords={page:{x:0,y:0},client:{x:0,y:0},timeStamp:0},this.startCoords={page:{x:0,y:0},client:{x:0,y:0},timeStamp:0},this.pointerDelta={page:{x:0,y:0,vx:0,vy:0,speed:0},client:{x:0,y:0,vx:0,vy:0,speed:0},timeStamp:0},this.downEvent=null,this.downPointer={},this._eventTarget=null,this._curEventTarget=null,this.prevEvent=null,this.pointerIsDown=!1,this.pointerWasMoved=!1,this._interacting=!1,this._ending=!1,this.pointerType=n,f.fire("new",this),a.interactions.push(this)}return t.prototype.pointerDown=function(t,e,n){var r=this.updatePointer(t,e,!0);f.fire("down",{pointer:t,event:e,eventTarget:n,pointerIndex:r,interaction:this})},t.prototype.start=function(t,e,n){this.interacting()||!this.pointerIsDown||this.pointerIds.length<("gesture"===t.name?2:1)||(-1===a.interactions.indexOf(this)&&a.interactions.push(this),c.copyAction(this.prepared,t),this.target=e,this.element=n,f.fire("action-start",{interaction:this,event:this.downEvent}))},t.prototype.pointerMove=function(e,n,r){this.simulation||(this.updatePointer(e),c.setCoords(this.curCoords,this.pointers));var i=this.curCoords.page.x===this.prevCoords.page.x&&this.curCoords.page.y===this.prevCoords.page.y&&this.curCoords.client.x===this.prevCoords.client.x&&this.curCoords.client.y===this.prevCoords.client.y,o=void 0,s=void 0;this.pointerIsDown&&!this.pointerWasMoved&&(o=this.curCoords.client.x-this.startCoords.client.x,s=this.curCoords.client.y-this.startCoords.client.y,this.pointerWasMoved=c.hypot(o,s)>t.pointerMoveTolerance);var a={pointer:e,pointerIndex:this.getPointerIndex(e),event:n,eventTarget:r,dx:o,dy:s,duplicate:i,interaction:this,interactingBeforeMove:this.interacting()};i||c.setCoordDeltas(this.pointerDelta,this.prevCoords,this.curCoords),f.fire("move",a),i||(this.interacting()&&this.doMove(a),this.pointerWasMoved&&c.copyCoords(this.prevCoords,this.curCoords))},t.prototype.doMove=function(t){t=c.extend({pointer:this.pointers[0],event:this.prevEvent,eventTarget:this._eventTarget,interaction:this},t||{}),f.fire("before-action-move",t),this._dontFireMove||f.fire("action-move",t),this._dontFireMove=!1},t.prototype.pointerUp=function(t,e,n,r){var i=this.getPointerIndex(t);f.fire(/cancel$/i.test(e.type)?"cancel":"up",{pointer:t,pointerIndex:i,event:e,eventTarget:n,curEventTarget:r,interaction:this}),this.simulation||this.end(e),this.pointerIsDown=!1,this.removePointer(t,e)},t.prototype.end=function(t){this._ending=!0,t=t||this.prevEvent,this.interacting()&&f.fire("action-end",{event:t,interaction:this}),this.stop(),this._ending=!1},t.prototype.currentAction=function(){return this._interacting?this.prepared.name:null},t.prototype.interacting=function(){return this._interacting},t.prototype.stop=function(){f.fire("stop",{interaction:this}),this._interacting&&(f.fire("stop-active",{interaction:this}),f.fire("stop-"+this.prepared.name,{interaction:this})),this.target=this.element=null,this._interacting=!1,this.prepared.name=this.prevEvent=null},t.prototype.getPointerIndex=function(t){return"mouse"===this.pointerType||"pen"===this.pointerType?0:this.pointerIds.indexOf(c.getPointerId(t))},t.prototype.updatePointer=function(t,e){var n=arguments.length>2&&void 0!==arguments[2]?arguments[2]:e&&/(down|start)$/i.test(e.type),r=c.getPointerId(t),i=this.getPointerIndex(t);return-1===i&&(i=this.pointerIds.length,this.pointerIds[i]=r),n&&f.fire("update-pointer-down",{pointer:t,event:e,down:n,pointerId:r,pointerIndex:i,interaction:this}),this.pointers[i]=t,i},t.prototype.removePointer=function(t,e){var n=this.getPointerIndex(t);-1!==n&&(f.fire("remove-pointer",{pointer:t,event:e,pointerIndex:n,interaction:this}),this.pointers.splice(n,1),this.pointerIds.splice(n,1),this.downTargets.splice(n,1),this.downTimes.splice(n,1))},t.prototype._updateEventTargets=function(t,e){this._eventTarget=t,this._curEventTarget=e},t}(),y=0;y<g.length;y++){var x=g[y];v[x]=i(x)}var b={},w=p.pEventTypes;u.PointerEvent?(b[w.down]=v.pointerDown,b[w.move]=v.pointerMove,b[w.up]=v.pointerUp,b[w.cancel]=v.pointerUp):(b.mousedown=v.pointerDown,b.mousemove=v.pointerMove,b.mouseup=v.pointerUp,b.touchstart=v.pointerDown,b.touchmove=v.pointerMove,b.touchend=v.pointerUp,b.touchcancel=v.pointerUp),b.blur=o,f.on("update-pointer-down",function(t){var e=t.interaction,n=t.pointer,r=t.pointerId,i=t.pointerIndex,o=t.event,s=t.eventTarget,a=t.down;e.pointerIds[i]=r,e.pointers[i]=n,a&&(e.pointerIsDown=!0),e.interacting()||(c.setCoords(e.startCoords,e.pointers),c.copyCoords(e.curCoords,e.startCoords),c.copyCoords(e.prevCoords,e.startCoords),e.downEvent=o,e.downTimes[i]=e.curCoords.timeStamp,e.downTargets[i]=s||o&&c.getEventTargets(o)[0],e.pointerWasMoved=!1,c.pointerExtend(e.downPointer,n))}),a.signals.on("add-document",s),a.signals.on("remove-document",s),m.pointerMoveTolerance=1,m.doOnInteractions=i,m.endAll=o,m.signals=f,m.docEvents=b,a.endAllInteractions=o,e.exports=m},{"./scope":33,"./utils":44,"./utils/Signals":34,"./utils/browser":36,"./utils/domObjects":38,"./utils/events":40,"./utils/interactionFinder":45}],6:[function(t,e,n){"use strict";function r(t,e,n,r){var i=t.prepared.name,s=new o(t,e,i,n,t.element,null,r);t.target.fire(s),t.prevEvent=s}var i=t("../Interaction"),o=t("../InteractEvent"),s={firePrepared:r,names:[],methodDict:{}};i.signals.on("action-start",function(t){var e=t.interaction,n=t.event;e._interacting=!0,r(e,n,"start")}),i.signals.on("action-move",function(t){var e=t.interaction;if(r(e,t.event,"move",t.preEnd),!e.interacting())return!1}),i.signals.on("action-end",function(t){r(t.interaction,t.event,"end")}),e.exports=s},{"../InteractEvent":3,"../Interaction":5}],7:[function(t,e,n){"use strict";var r=t("./base"),i=t("../utils"),o=t("../InteractEvent"),s=t("../Interactable"),a=t("../Interaction"),c=t("../defaultOptions"),l={defaults:{enabled:!1,mouseButtons:null,origin:null,snap:null,restrict:null,inertia:null,autoScroll:null,startAxis:"xy",lockAxis:"xy"},checker:function(t,e,n){var r=n.options.drag;return r.enabled?{name:"drag",axis:"start"===r.lockAxis?r.startAxis:r.lockAxis}:null},getCursor:function(){return"move"}};a.signals.on("before-action-move",function(t){var e=t.interaction;if("drag"===e.prepared.name){var n=e.prepared.axis;"x"===n?(e.curCoords.page.y=e.startCoords.page.y,e.curCoords.client.y=e.startCoords.client.y,e.pointerDelta.page.speed=Math.abs(e.pointerDelta.page.vx),e.pointerDelta.client.speed=Math.abs(e.pointerDelta.client.vx),e.pointerDelta.client.vy=0,e.pointerDelta.page.vy=0):"y"===n&&(e.curCoords.page.x=e.startCoords.page.x,e.curCoords.client.x=e.startCoords.client.x,e.pointerDelta.page.speed=Math.abs(e.pointerDelta.page.vy),e.pointerDelta.client.speed=Math.abs(e.pointerDelta.client.vy),e.pointerDelta.client.vx=0,e.pointerDelta.page.vx=0)}}),o.signals.on("new",function(t){var e=t.iEvent,n=t.interaction;if("dragmove"===e.type){var r=n.prepared.axis;"x"===r?(e.pageY=n.startCoords.page.y,e.clientY=n.startCoords.client.y,e.dy=0):"y"===r&&(e.pageX=n.startCoords.page.x,e.clientX=n.startCoords.client.x,e.dx=0)}}),s.prototype.draggable=function(t){return i.is.object(t)?(this.options.drag.enabled=!1!==t.enabled,this.setPerAction("drag",t),this.setOnEvents("drag",t),/^(xy|x|y|start)$/.test(t.lockAxis)&&(this.options.drag.lockAxis=t.lockAxis),/^(xy|x|y)$/.test(t.startAxis)&&(this.options.drag.startAxis=t.startAxis),this):i.is.bool(t)?(this.options.drag.enabled=t,t||(this.ondragstart=this.ondragstart=this.ondragend=null),this):this.options.drag},r.drag=l,r.names.push("drag"),i.merge(s.eventTypes,["dragstart","dragmove","draginertiastart","draginertiaresume","dragend"]),r.methodDict.drag="draggable",c.drag=l.defaults,e.exports=l},{"../InteractEvent":3,"../Interactable":4,"../Interaction":5,"../defaultOptions":18,"../utils":44,"./base":6}],8:[function(t,e,n){"use strict";function r(t,e){for(var n=[],r=[],i=0;i<u.interactables.length;i++){var o;o=u.interactables[i];var s=o;if(s.options.drop.enabled){var a=s.options.drop.accept;if(!(p.is.element(a)&&a!==e||p.is.string(a)&&!p.matchesSelector(e,a)))for(var c=p.is.string(s.target)?s._context.querySelectorAll(s.target):[s.target],l=0;l<c.length;l++){var d;d=c[l];var f=d;f!==e&&(n.push(s),r.push(f))}}}return{elements:r,dropzones:n}}function i(t,e){for(var n=void 0,r=0;r<t.dropzones.length;r++){var i=t.dropzones[r],o=t.elements[r];o!==n&&(e.target=o,i.fire(e)),n=o}}function o(t,e){var n=r(t,e);t.dropzones=n.dropzones,t.elements=n.elements,t.rects=[];for(var i=0;i<t.dropzones.length;i++)t.rects[i]=t.dropzones[i].getRect(t.elements[i])}function s(t,e,n){var r=t.interaction,i=[];y&&o(r.activeDrops,n);for(var s=0;s<r.activeDrops.dropzones.length;s++){var a=r.activeDrops.dropzones[s],c=r.activeDrops.elements[s],l=r.activeDrops.rects[s];i.push(a.dropCheck(t,e,r.target,n,c,l)?c:null)}var u=p.indexOfDeepestElement(i);return{dropzone:r.activeDrops.dropzones[u]||null,element:r.activeDrops.elements[u]||null}}function a(t,e,n){var r={enter:null,leave:null,activate:null,deactivate:null,move:null,drop:null},i={dragEvent:n,interaction:t,target:t.dropElement,dropzone:t.dropTarget,relatedTarget:n.target,draggable:n.interactable,timeStamp:n.timeStamp};return t.dropElement!==t.prevDropElement&&(t.prevDropTarget&&(r.leave=p.extend({type:"dragleave"},i),n.dragLeave=r.leave.target=t.prevDropElement,n.prevDropzone=r.leave.dropzone=t.prevDropTarget),t.dropTarget&&(r.enter={dragEvent:n,interaction:t,target:t.dropElement,dropzone:t.dropTarget,relatedTarget:n.target,draggable:n.interactable,timeStamp:n.timeStamp,type:"dragenter"},n.dragEnter=t.dropElement,n.dropzone=t.dropTarget)),"dragend"===n.type&&t.dropTarget&&(r.drop=p.extend({type:"drop"},i),n.dropzone=t.dropTarget,n.relatedTarget=t.dropElement),"dragstart"===n.type&&(r.activate=p.extend({type:"dropactivate"},i),r.activate.target=null,r.activate.dropzone=null),"dragend"===n.type&&(r.deactivate=p.extend({type:"dropdeactivate"},i),r.deactivate.target=null,r.deactivate.dropzone=null),"dragmove"===n.type&&t.dropTarget&&(r.move=p.extend({dragmove:n,type:"dropmove"},i),n.dropzone=t.dropTarget),r}function c(t,e){var n=t.activeDrops,r=t.prevDropTarget,o=t.dropTarget,s=t.dropElement;e.leave&&r.fire(e.leave),e.move&&o.fire(e.move),e.enter&&o.fire(e.enter),e.drop&&o.fire(e.drop),e.deactivate&&i(n,e.deactivate),t.prevDropTarget=o,t.prevDropElement=s}var l=t("./base"),p=t("../utils"),u=t("../scope"),d=t("../interact"),f=t("../InteractEvent"),v=t("../Interactable"),g=t("../Interaction"),h=t("../defaultOptions"),m={defaults:{enabled:!1,accept:null,overlap:"pointer"}},y=!1;g.signals.on("action-start",function(t){var e=t.interaction,n=t.event;if("drag"===e.prepared.name){e.activeDrops.dropzones=[],e.activeDrops.elements=[],e.activeDrops.rects=[],e.dropEvents=null,e.dynamicDrop||o(e.activeDrops,e.element);var r=e.prevEvent,s=a(e,n,r);s.activate&&i(e.activeDrops,s.activate)}}),f.signals.on("new",function(t){var e=t.interaction,n=t.iEvent,r=t.event;if("dragmove"===n.type||"dragend"===n.type){var i=e.element,o=n,c=s(o,r,i);e.dropTarget=c.dropzone,e.dropElement=c.element,e.dropEvents=a(e,r,o)}}),g.signals.on("action-move",function(t){var e=t.interaction;"drag"===e.prepared.name&&c(e,e.dropEvents)}),g.signals.on("action-end",function(t){var e=t.interaction;"drag"===e.prepared.name&&c(e,e.dropEvents)}),g.signals.on("stop-drag",function(t){var e=t.interaction;e.activeDrops={dropzones:null,elements:null,rects:null},e.dropEvents=null}),v.prototype.dropzone=function(t){return p.is.object(t)?(this.options.drop.enabled=!1!==t.enabled,p.is.function(t.ondrop)&&(this.events.ondrop=t.ondrop),p.is.function(t.ondropactivate)&&(this.events.ondropactivate=t.ondropactivate),p.is.function(t.ondropdeactivate)&&(this.events.ondropdeactivate=t.ondropdeactivate),p.is.function(t.ondragenter)&&(this.events.ondragenter=t.ondragenter),p.is.function(t.ondragleave)&&(this.events.ondragleave=t.ondragleave),p.is.function(t.ondropmove)&&(this.events.ondropmove=t.ondropmove),/^(pointer|center)$/.test(t.overlap)?this.options.drop.overlap=t.overlap:p.is.number(t.overlap)&&(this.options.drop.overlap=Math.max(Math.min(1,t.overlap),0)),"accept"in t&&(this.options.drop.accept=t.accept),"checker"in t&&(this.options.drop.checker=t.checker),this):p.is.bool(t)?(this.options.drop.enabled=t,t||(this.ondragenter=this.ondragleave=this.ondrop=this.ondropactivate=this.ondropdeactivate=null),this):this.options.drop},v.prototype.dropCheck=function(t,e,n,r,i,o){var s=!1;if(!(o=o||this.getRect(i)))return!!this.options.drop.checker&&this.options.drop.checker(t,e,s,this,i,n,r);var a=this.options.drop.overlap;if("pointer"===a){var c=p.getOriginXY(n,r,"drag"),l=p.getPageXY(t);l.x+=c.x,l.y+=c.y;var u=l.x>o.left&&l.x<o.right,d=l.y>o.top&&l.y<o.bottom;s=u&&d}var f=n.getRect(r);if(f&&"center"===a){var v=f.left+f.width/2,g=f.top+f.height/2;s=v>=o.left&&v<=o.right&&g>=o.top&&g<=o.bottom}if(f&&p.is.number(a)){s=Math.max(0,Math.min(o.right,f.right)-Math.max(o.left,f.left))*Math.max(0,Math.min(o.bottom,f.bottom)-Math.max(o.top,f.top))/(f.width*f.height)>=a}return this.options.drop.checker&&(s=this.options.drop.checker(t,e,s,this,i,n,r)),s},v.signals.on("unset",function(t){t.interactable.dropzone(!1)}),v.settingsMethods.push("dropChecker"),g.signals.on("new",function(t){t.dropTarget=null,t.dropElement=null,t.prevDropTarget=null,t.prevDropElement=null,t.dropEvents=null,t.activeDrops={dropzones:[],elements:[],rects:[]}}),g.signals.on("stop",function(t){var e=t.interaction;e.dropTarget=e.dropElement=e.prevDropTarget=e.prevDropElement=null}),d.dynamicDrop=function(t){return p.is.bool(t)?(y=t,d):y},p.merge(v.eventTypes,["dragenter","dragleave","dropactivate","dropdeactivate","dropmove","drop"]),l.methodDict.drop="dropzone",h.drop=m.defaults,e.exports=m},{"../InteractEvent":3,"../Interactable":4,"../Interaction":5,"../defaultOptions":18,"../interact":21,"../scope":33,"../utils":44,"./base":6}],9:[function(t,e,n){"use strict";var r=t("./base"),i=t("../utils"),o=t("../InteractEvent"),s=t("../Interactable"),a=t("../Interaction"),c=t("../defaultOptions"),l={defaults:{enabled:!1,origin:null,restrict:null},checker:function(t,e,n,r,i){return i.pointerIds.length>=2?{name:"gesture"}:null},getCursor:function(){return""}};o.signals.on("new",function(t){var e=t.iEvent,n=t.interaction;"gesturestart"===e.type&&(e.ds=0,n.gesture.startDistance=n.gesture.prevDistance=e.distance,n.gesture.startAngle=n.gesture.prevAngle=e.angle,n.gesture.scale=1)}),o.signals.on("new",function(t){var e=t.iEvent,n=t.interaction;"gesturemove"===e.type&&(e.ds=e.scale-n.gesture.scale,n.target.fire(e),n.gesture.prevAngle=e.angle,n.gesture.prevDistance=e.distance,e.scale===1/0||null===e.scale||void 0===e.scale||isNaN(e.scale)||(n.gesture.scale=e.scale))}),s.prototype.gesturable=function(t){return i.is.object(t)?(this.options.gesture.enabled=!1!==t.enabled,this.setPerAction("gesture",t),this.setOnEvents("gesture",t),this):i.is.bool(t)?(this.options.gesture.enabled=t,t||(this.ongesturestart=this.ongesturestart=this.ongestureend=null),this):this.options.gesture},o.signals.on("set-delta",function(t){var e=t.interaction,n=t.iEvent,r=t.action,s=t.event,a=t.starting,c=t.ending,l=t.deltaSource;if("gesture"===r){var p=e.pointers;n.touches=[p[0],p[1]],a?(n.distance=i.touchDistance(p,l),n.box=i.touchBBox(p),n.scale=1,n.ds=0,n.angle=i.touchAngle(p,void 0,l),n.da=0):c||s instanceof o?(n.distance=e.prevEvent.distance,n.box=e.prevEvent.box,n.scale=e.prevEvent.scale,n.ds=n.scale-1,n.angle=e.prevEvent.angle,n.da=n.angle-e.gesture.startAngle):(n.distance=i.touchDistance(p,l),n.box=i.touchBBox(p),n.scale=n.distance/e.gesture.startDistance,n.angle=i.touchAngle(p,e.gesture.prevAngle,l),n.ds=n.scale-e.gesture.prevScale,n.da=n.angle-e.gesture.prevAngle)}}),a.signals.on("new",function(t){t.gesture={start:{x:0,y:0},startDistance:0,prevDistance:0,distance:0,scale:1,startAngle:0,prevAngle:0}}),r.gesture=l,r.names.push("gesture"),i.merge(s.eventTypes,["gesturestart","gesturemove","gestureend"]),r.methodDict.gesture="gesturable",c.gesture=l.defaults,e.exports=l},{"../InteractEvent":3,"../Interactable":4,"../Interaction":5,"../defaultOptions":18,"../utils":44,"./base":6}],10:[function(t,e,n){"use strict";function r(t,e,n,r,i,s,a){if(!e)return!1;if(!0===e){var c=o.is.number(s.width)?s.width:s.right-s.left,l=o.is.number(s.height)?s.height:s.bottom-s.top;if(c<0&&("left"===t?t="right":"right"===t&&(t="left")),l<0&&("top"===t?t="bottom":"bottom"===t&&(t="top")),"left"===t)return n.x<(c>=0?s.left:s.right)+a;if("top"===t)return n.y<(l>=0?s.top:s.bottom)+a;if("right"===t)return n.x>(c>=0?s.right:s.left)-a;if("bottom"===t)return n.y>(l>=0?s.bottom:s.top)-a}return!!o.is.element(r)&&(o.is.element(e)?e===r:o.matchesUpTo(r,e,i))}var i=t("./base"),o=t("../utils"),s=t("../utils/browser"),a=t("../InteractEvent"),c=t("../Interactable"),l=t("../Interaction"),p=t("../defaultOptions"),u=s.supportsTouch||s.supportsPointerEvent?20:10,d={defaults:{enabled:!1,mouseButtons:null,origin:null,snap:null,restrict:null,inertia:null,autoScroll:null,square:!1,preserveAspectRatio:!1,axis:"xy",margin:NaN,edges:null,invert:"none"},checker:function(t,e,n,i,s,a){if(!a)return null;var c=o.extend({},s.curCoords.page),l=n.options;if(l.resize.enabled){var p=l.resize,d={left:!1,right:!1,top:!1,bottom:!1};if(o.is.object(p.edges)){for(var f in d)d[f]=r(f,p.edges[f],c,s._eventTarget,i,a,p.margin||u);if(d.left=d.left&&!d.right,d.top=d.top&&!d.bottom,d.left||d.right||d.top||d.bottom)return{name:"resize",edges:d}}else{var v="y"!==l.resize.axis&&c.x>a.right-u,g="x"!==l.resize.axis&&c.y>a.bottom-u;if(v||g)return{name:"resize",axes:(v?"x":"")+(g?"y":"")}}}return null},cursors:s.isIe9?{x:"e-resize",y:"s-resize",xy:"se-resize",top:"n-resize",left:"w-resize",bottom:"s-resize",right:"e-resize",topleft:"se-resize",bottomright:"se-resize",topright:"ne-resize",bottomleft:"ne-resize"}:{x:"ew-resize",y:"ns-resize",xy:"nwse-resize",top:"ns-resize",left:"ew-resize",bottom:"ns-resize",right:"ew-resize",topleft:"nwse-resize",bottomright:"nwse-resize",topright:"nesw-resize",bottomleft:"nesw-resize"},getCursor:function(t){if(t.axis)return d.cursors[t.name+t.axis];if(t.edges){for(var e="",n=["top","bottom","left","right"],r=0;r<4;r++)t.edges[n[r]]&&(e+=n[r]);return d.cursors[e]}}};a.signals.on("new",function(t){var e=t.iEvent,n=t.interaction;if("resizestart"===e.type&&n.prepared.edges){var r=n.target.getRect(n.element),i=n.target.options.resize;if(i.square||i.preserveAspectRatio){var s=o.extend({},n.prepared.edges);s.top=s.top||s.left&&!s.bottom,s.left=s.left||s.top&&!s.right,s.bottom=s.bottom||s.right&&!s.top,s.right=s.right||s.bottom&&!s.left,n.prepared._linkedEdges=s}else n.prepared._linkedEdges=null;i.preserveAspectRatio&&(n.resizeStartAspectRatio=r.width/r.height),n.resizeRects={start:r,current:o.extend({},r),inverted:o.extend({},r),previous:o.extend({},r),delta:{left:0,right:0,width:0,top:0,bottom:0,height:0}},e.rect=n.resizeRects.inverted,e.deltaRect=n.resizeRects.delta}}),a.signals.on("new",function(t){var e=t.iEvent,n=t.phase,r=t.interaction;if("move"===n&&r.prepared.edges){var i=r.target.options.resize,s=i.invert,a="reposition"===s||"negate"===s,c=r.prepared.edges,l=r.resizeRects.start,p=r.resizeRects.current,u=r.resizeRects.inverted,d=r.resizeRects.delta,f=o.extend(r.resizeRects.previous,u),v=c,g=e.dx,h=e.dy;if(i.preserveAspectRatio||i.square){var m=i.preserveAspectRatio?r.resizeStartAspectRatio:1;c=r.prepared._linkedEdges,v.left&&v.bottom||v.right&&v.top?h=-g/m:v.left||v.right?h=g/m:(v.top||v.bottom)&&(g=h*m)}if(c.top&&(p.top+=h),c.bottom&&(p.bottom+=h),c.left&&(p.left+=g),c.right&&(p.right+=g),a){if(o.extend(u,p),"reposition"===s){var y=void 0;u.top>u.bottom&&(y=u.top,u.top=u.bottom,u.bottom=y),u.left>u.right&&(y=u.left,u.left=u.right,u.right=y)}}else u.top=Math.min(p.top,l.bottom),u.bottom=Math.max(p.bottom,l.top),u.left=Math.min(p.left,l.right),u.right=Math.max(p.right,l.left);u.width=u.right-u.left,u.height=u.bottom-u.top;for(var x in u)d[x]=u[x]-f[x];e.edges=r.prepared.edges,e.rect=u,e.deltaRect=d}}),c.prototype.resizable=function(t){return o.is.object(t)?(this.options.resize.enabled=!1!==t.enabled,this.setPerAction("resize",t),this.setOnEvents("resize",t),/^x$|^y$|^xy$/.test(t.axis)?this.options.resize.axis=t.axis:null===t.axis&&(this.options.resize.axis=p.resize.axis),o.is.bool(t.preserveAspectRatio)?this.options.resize.preserveAspectRatio=t.preserveAspectRatio:o.is.bool(t.square)&&(this.options.resize.square=t.square),this):o.is.bool(t)?(this.options.resize.enabled=t,t||(this.onresizestart=this.onresizestart=this.onresizeend=null),this):this.options.resize},l.signals.on("new",function(t){t.resizeAxes="xy"}),a.signals.on("set-delta",function(t){var e=t.interaction,n=t.iEvent;"resize"===t.action&&e.resizeAxes&&(e.target.options.resize.square?("y"===e.resizeAxes?n.dx=n.dy:n.dy=n.dx,n.axes="xy"):(n.axes=e.resizeAxes,"x"===e.resizeAxes?n.dy=0:"y"===e.resizeAxes&&(n.dx=0)))}),i.resize=d,i.names.push("resize"),
o.merge(c.eventTypes,["resizestart","resizemove","resizeinertiastart","resizeinertiaresume","resizeend"]),i.methodDict.resize="resizable",p.resize=d.defaults,e.exports=d},{"../InteractEvent":3,"../Interactable":4,"../Interaction":5,"../defaultOptions":18,"../utils":44,"../utils/browser":36,"./base":6}],11:[function(t,e,n){"use strict";var r=t("./utils/raf"),i=t("./utils/window").getWindow,o=t("./utils/is"),s=t("./utils/domUtils"),a=t("./Interaction"),c=t("./defaultOptions"),l={defaults:{enabled:!1,container:null,margin:60,speed:300},interaction:null,i:null,x:0,y:0,isScrolling:!1,prevTime:0,start:function(t){l.isScrolling=!0,r.cancel(l.i),l.interaction=t,l.prevTime=(new Date).getTime(),l.i=r.request(l.scroll)},stop:function(){l.isScrolling=!1,r.cancel(l.i)},scroll:function(){var t=l.interaction.target.options[l.interaction.prepared.name].autoScroll,e=t.container||i(l.interaction.element),n=(new Date).getTime(),s=(n-l.prevTime)/1e3,a=t.speed*s;a>=1&&(o.window(e)?e.scrollBy(l.x*a,l.y*a):e&&(e.scrollLeft+=l.x*a,e.scrollTop+=l.y*a),l.prevTime=n),l.isScrolling&&(r.cancel(l.i),l.i=r.request(l.scroll))},check:function(t,e){var n=t.options;return n[e].autoScroll&&n[e].autoScroll.enabled},onInteractionMove:function(t){var e=t.interaction,n=t.pointer;if(e.interacting()&&l.check(e.target,e.prepared.name)){if(e.simulation)return void(l.x=l.y=0);var r=void 0,a=void 0,c=void 0,p=void 0,u=e.target.options[e.prepared.name].autoScroll,d=u.container||i(e.element);if(o.window(d))p=n.clientX<l.margin,r=n.clientY<l.margin,a=n.clientX>d.innerWidth-l.margin,c=n.clientY>d.innerHeight-l.margin;else{var f=s.getElementClientRect(d);p=n.clientX<f.left+l.margin,r=n.clientY<f.top+l.margin,a=n.clientX>f.right-l.margin,c=n.clientY>f.bottom-l.margin}l.x=a?1:p?-1:0,l.y=c?1:r?-1:0,l.isScrolling||(l.margin=u.margin,l.speed=u.speed,l.start(e))}}};a.signals.on("stop-active",function(){l.stop()}),a.signals.on("action-move",l.onInteractionMove),c.perAction.autoScroll=l.defaults,e.exports=l},{"./Interaction":5,"./defaultOptions":18,"./utils/domUtils":39,"./utils/is":46,"./utils/raf":50,"./utils/window":52}],12:[function(t,e,n){"use strict";var r=t("../Interactable"),i=t("../actions/base"),o=t("../utils/is"),s=t("../utils/domUtils"),a=t("../utils"),c=a.warnOnce;r.prototype.getAction=function(t,e,n,r){var i=this.defaultActionChecker(t,e,n,r);return this.options.actionChecker?this.options.actionChecker(t,e,i,this,r,n):i},r.prototype.ignoreFrom=c(function(t){return this._backCompatOption("ignoreFrom",t)},"Interactable.ignoreForm() has been deprecated. Use Interactble.draggable({ignoreFrom: newValue})."),r.prototype.allowFrom=c(function(t){return this._backCompatOption("allowFrom",t)},"Interactable.allowForm() has been deprecated. Use Interactble.draggable({allowFrom: newValue})."),r.prototype.testIgnore=function(t,e,n){return!(!t||!o.element(n))&&(o.string(t)?s.matchesUpTo(n,t,e):!!o.element(t)&&s.nodeContains(t,n))},r.prototype.testAllow=function(t,e,n){return!t||!!o.element(n)&&(o.string(t)?s.matchesUpTo(n,t,e):!!o.element(t)&&s.nodeContains(t,n))},r.prototype.testIgnoreAllow=function(t,e,n){return!this.testIgnore(t.ignoreFrom,e,n)&&this.testAllow(t.allowFrom,e,n)},r.prototype.actionChecker=function(t){return o.function(t)?(this.options.actionChecker=t,this):null===t?(delete this.options.actionChecker,this):this.options.actionChecker},r.prototype.styleCursor=function(t){return o.bool(t)?(this.options.styleCursor=t,this):null===t?(delete this.options.styleCursor,this):this.options.styleCursor},r.prototype.defaultActionChecker=function(t,e,n,r){for(var o=this.getRect(r),s=e.buttons||{0:1,1:4,3:8,4:16}[e.button],a=null,c=0;c<i.names.length;c++){var l;l=i.names[c];var p=l;if((!n.pointerIsDown||!/mouse|pointer/.test(n.pointerType)||0!=(s&this.options[p].mouseButtons))&&(a=i[p].checker(t,e,this,r,n,o)))return a}}},{"../Interactable":4,"../actions/base":6,"../utils":44,"../utils/domUtils":39,"../utils/is":46}],13:[function(t,e,n){"use strict";function r(t,e,n,r){return v.is.object(t)&&e.testIgnoreAllow(e.options[t.name],n,r)&&e.options[t.name].enabled&&a(e,n,t)?t:null}function i(t,e,n,i,o,s){for(var a=0,c=i.length;a<c;a++){var l=i[a],p=o[a],u=r(l.getAction(e,n,t,p),l,p,s);if(u)return{action:u,target:l,element:p}}return{}}function o(t,e,n,r){function o(t){s.push(t),a.push(c)}for(var s=[],a=[],c=r;v.is.element(c);){s=[],a=[],f.interactables.forEachMatch(c,o);var l=i(t,e,n,s,a,r);if(l.action&&!l.target.options[l.action.name].manualStart)return l;c=v.parentNode(c)}return{}}function s(t,e){var n=e.action,r=e.target,i=e.element;if(n=n||{},t.target&&t.target.options.styleCursor&&(t.target._doc.documentElement.style.cursor=""),t.target=r,t.element=i,v.copyAction(t.prepared,n),r&&r.options.styleCursor){var o=n?u[n.name].getCursor(n):"";t.target._doc.documentElement.style.cursor=o}g.fire("prepared",{interaction:t})}function a(t,e,n){var r=t.options,i=r[n.name].max,o=r[n.name].maxPerElement,s=0,a=0,c=0;if(i&&o&&h.maxInteractions){for(var l=0;l<f.interactions.length;l++){var p;p=f.interactions[l];var u=p,d=u.prepared.name;if(u.interacting()){if(++s>=h.maxInteractions)return!1;if(u.target===t){if((a+=d===n.name|0)>=i)return!1;if(u.element===e&&(c++,d!==n.name||c>=o))return!1}}}return h.maxInteractions>0}}var c=t("../interact"),l=t("../Interactable"),p=t("../Interaction"),u=t("../actions/base"),d=t("../defaultOptions"),f=t("../scope"),v=t("../utils"),g=t("../utils/Signals").new();t("./InteractableMethods");var h={signals:g,withinInteractionLimit:a,maxInteractions:1/0,defaults:{perAction:{manualStart:!1,max:1/0,maxPerElement:1,allowFrom:null,ignoreFrom:null,mouseButtons:1}},setActionDefaults:function(t){v.extend(t.defaults,h.defaults.perAction)},validateAction:r};p.signals.on("down",function(t){var e=t.interaction,n=t.pointer,r=t.event,i=t.eventTarget;if(!e.interacting()){s(e,o(e,n,r,i))}}),p.signals.on("move",function(t){var e=t.interaction,n=t.pointer,r=t.event,i=t.eventTarget;if("mouse"===e.pointerType&&!e.pointerIsDown&&!e.interacting()){s(e,o(e,n,r,i))}}),p.signals.on("move",function(t){var e=t.interaction,n=t.event;if(e.pointerIsDown&&!e.interacting()&&e.pointerWasMoved&&e.prepared.name){g.fire("before-start",t);var r=e.target;e.prepared.name&&r&&(r.options[e.prepared.name].manualStart||!a(r,e.element,e.prepared)?e.stop(n):e.start(e.prepared,r,e.element))}}),p.signals.on("stop",function(t){var e=t.interaction,n=e.target;n&&n.options.styleCursor&&(n._doc.documentElement.style.cursor="")}),c.maxInteractions=function(t){return v.is.number(t)?(h.maxInteractions=t,c):h.maxInteractions},l.settingsMethods.push("styleCursor"),l.settingsMethods.push("actionChecker"),l.settingsMethods.push("ignoreFrom"),l.settingsMethods.push("allowFrom"),d.base.actionChecker=null,d.base.styleCursor=!0,v.extend(d.perAction,h.defaults.perAction),e.exports=h},{"../Interactable":4,"../Interaction":5,"../actions/base":6,"../defaultOptions":18,"../interact":21,"../scope":33,"../utils":44,"../utils/Signals":34,"./InteractableMethods":12}],14:[function(t,e,n){"use strict";function r(t,e){if(!e)return!1;var n=e.options.drag.startAxis;return"xy"===t||"xy"===n||n===t}var i=t("./base"),o=t("../scope"),s=t("../utils/is"),a=t("../utils/domUtils"),c=a.parentNode;i.setActionDefaults(t("../actions/drag")),i.signals.on("before-start",function(t){var e=t.interaction,n=t.eventTarget,a=t.dx,l=t.dy;if("drag"===e.prepared.name){var p=Math.abs(a),u=Math.abs(l),d=e.target.options.drag,f=d.startAxis,v=p>u?"x":p<u?"y":"xy";if(e.prepared.axis="start"===d.lockAxis?v[0]:d.lockAxis,"xy"!==v&&"xy"!==f&&f!==v){e.prepared.name=null;for(var g=n,h=function(t){if(t!==e.target){var o=e.target.options.drag;if(!o.manualStart&&t.testIgnoreAllow(o,g,n)){var s=t.getAction(e.downPointer,e.downEvent,e,g);if(s&&"drag"===s.name&&r(v,t)&&i.validateAction(s,t,g,n))return t}}};s.element(g);){var m=o.interactables.forEachMatch(g,h);if(m){e.prepared.name="drag",e.target=m,e.element=g;break}g=c(g)}}}})},{"../actions/drag":7,"../scope":33,"../utils/domUtils":39,"../utils/is":46,"./base":13}],15:[function(t,e,n){"use strict";t("./base").setActionDefaults(t("../actions/gesture"))},{"../actions/gesture":9,"./base":13}],16:[function(t,e,n){"use strict";function r(t){var e=t.prepared&&t.prepared.name;if(!e)return null;var n=t.target.options;return n[e].hold||n[e].delay}var i=t("./base"),o=t("../Interaction");i.defaults.perAction.hold=0,i.defaults.perAction.delay=0,o.signals.on("new",function(t){t.autoStartHoldTimer=null}),i.signals.on("prepared",function(t){var e=t.interaction,n=r(e);n>0&&(e.autoStartHoldTimer=setTimeout(function(){e.start(e.prepared,e.target,e.element)},n))}),o.signals.on("move",function(t){var e=t.interaction,n=t.duplicate;e.pointerWasMoved&&!n&&clearTimeout(e.autoStartHoldTimer)}),i.signals.on("before-start",function(t){var e=t.interaction;r(e)>0&&(e.prepared.name=null)}),e.exports={getHoldDuration:r}},{"../Interaction":5,"./base":13}],17:[function(t,e,n){"use strict";t("./base").setActionDefaults(t("../actions/resize"))},{"../actions/resize":10,"./base":13}],18:[function(t,e,n){"use strict";e.exports={base:{accept:null,preventDefault:"auto",deltaSource:"page"},perAction:{origin:{x:0,y:0},inertia:{enabled:!1,resistance:10,minSpeed:100,endSpeed:10,allowResume:!0,smoothEndDuration:300}}}},{}],19:[function(t,e,n){"use strict";t("./inertia"),t("./modifiers/snap"),t("./modifiers/restrict"),t("./pointerEvents/base"),t("./pointerEvents/holdRepeat"),t("./pointerEvents/interactableTargets"),t("./autoStart/hold"),t("./actions/gesture"),t("./actions/resize"),t("./actions/drag"),t("./actions/drop"),t("./modifiers/snapSize"),t("./modifiers/restrictEdges"),t("./modifiers/restrictSize"),t("./autoStart/gesture"),t("./autoStart/resize"),t("./autoStart/drag"),t("./interactablePreventDefault.js"),t("./autoScroll"),e.exports=t("./interact")},{"./actions/drag":7,"./actions/drop":8,"./actions/gesture":9,"./actions/resize":10,"./autoScroll":11,"./autoStart/drag":14,"./autoStart/gesture":15,"./autoStart/hold":16,"./autoStart/resize":17,"./inertia":20,"./interact":21,"./interactablePreventDefault.js":22,"./modifiers/restrict":24,"./modifiers/restrictEdges":25,"./modifiers/restrictSize":26,"./modifiers/snap":27,"./modifiers/snapSize":28,"./pointerEvents/base":30,"./pointerEvents/holdRepeat":31,"./pointerEvents/interactableTargets":32}],20:[function(t,e,n){"use strict";function r(t,e){var n=t.target.options[t.prepared.name].inertia,r=n.resistance,i=-Math.log(n.endSpeed/e.v0)/r;e.x0=t.prevEvent.pageX,e.y0=t.prevEvent.pageY,e.t0=e.startEvent.timeStamp/1e3,e.sx=e.sy=0,e.modifiedXe=e.xe=(e.vx0-i)/r,e.modifiedYe=e.ye=(e.vy0-i)/r,e.te=i,e.lambda_v0=r/e.v0,e.one_ve_v0=1-n.endSpeed/e.v0}function i(){s(this),p.setCoordDeltas(this.pointerDelta,this.prevCoords,this.curCoords);var t=this.inertiaStatus,e=this.target.options[this.prepared.name].inertia,n=e.resistance,r=(new Date).getTime()/1e3-t.t0;if(r<t.te){var i=1-(Math.exp(-n*r)-t.lambda_v0)/t.one_ve_v0;if(t.modifiedXe===t.xe&&t.modifiedYe===t.ye)t.sx=t.xe*i,t.sy=t.ye*i;else{var o=p.getQuadraticCurvePoint(0,0,t.xe,t.ye,t.modifiedXe,t.modifiedYe,i);t.sx=o.x,t.sy=o.y}this.doMove(),t.i=u.request(this.boundInertiaFrame)}else t.sx=t.modifiedXe,t.sy=t.modifiedYe,this.doMove(),this.end(t.startEvent),t.active=!1,this.simulation=null;p.copyCoords(this.prevCoords,this.curCoords)}function o(){s(this);var t=this.inertiaStatus,e=(new Date).getTime()-t.t0,n=this.target.options[this.prepared.name].inertia.smoothEndDuration;e<n?(t.sx=p.easeOutQuad(e,0,t.xe,n),t.sy=p.easeOutQuad(e,0,t.ye,n),this.pointerMove(t.startEvent,t.startEvent),t.i=u.request(this.boundSmoothEndFrame)):(t.sx=t.xe,t.sy=t.ye,this.pointerMove(t.startEvent,t.startEvent),this.end(t.startEvent),t.smoothEnd=t.active=!1,this.simulation=null)}function s(t){var e=t.inertiaStatus;if(e.active){var n=e.upCoords.page,r=e.upCoords.client;p.setCoords(t.curCoords,[{pageX:n.x+e.sx,pageY:n.y+e.sy,clientX:r.x+e.sx,clientY:r.y+e.sy}])}}var a=t("./InteractEvent"),c=t("./Interaction"),l=t("./modifiers/base"),p=t("./utils"),u=t("./utils/raf");c.signals.on("new",function(t){t.inertiaStatus={active:!1,smoothEnd:!1,allowResume:!1,startEvent:null,upCoords:{},xe:0,ye:0,sx:0,sy:0,t0:0,vx0:0,vys:0,duration:0,lambda_v0:0,one_ve_v0:0,i:null},t.boundInertiaFrame=function(){return i.apply(t)},t.boundSmoothEndFrame=function(){return o.apply(t)}}),c.signals.on("down",function(t){var e=t.interaction,n=t.event,r=t.pointer,i=t.eventTarget,o=e.inertiaStatus;if(o.active)for(var s=i;p.is.element(s);){if(s===e.element){u.cancel(o.i),o.active=!1,e.simulation=null,e.updatePointer(r),p.setCoords(e.curCoords,e.pointers);var d={interaction:e};c.signals.fire("before-action-move",d),c.signals.fire("action-resume",d);var f=new a(e,n,e.prepared.name,"inertiaresume",e.element);e.target.fire(f),e.prevEvent=f,l.resetStatuses(e.modifierStatuses),p.copyCoords(e.prevCoords,e.curCoords);break}s=p.parentNode(s)}}),c.signals.on("up",function(t){var e=t.interaction,n=t.event,i=e.inertiaStatus;if(e.interacting()&&!i.active){var o=e.target,s=o&&o.options,c=s&&e.prepared.name&&s[e.prepared.name].inertia,d=(new Date).getTime(),f={},v=p.extend({},e.curCoords.page),g=e.pointerDelta.client.speed,h=!1,m=void 0,y=c&&c.enabled&&"gesture"!==e.prepared.name&&n!==i.startEvent,x=y&&d-e.curCoords.timeStamp<50&&g>c.minSpeed&&g>c.endSpeed,b={interaction:e,pageCoords:v,statuses:f,preEnd:!0,requireEndOnly:!0};y&&!x&&(l.resetStatuses(f),m=l.setAll(b),m.shouldMove&&m.locked&&(h=!0)),(x||h)&&(p.copyCoords(i.upCoords,e.curCoords),e.pointers[0]=i.startEvent=new a(e,n,e.prepared.name,"inertiastart",e.element),i.t0=d,i.active=!0,i.allowResume=c.allowResume,e.simulation=i,o.fire(i.startEvent),x?(i.vx0=e.pointerDelta.client.vx,i.vy0=e.pointerDelta.client.vy,i.v0=g,r(e,i),p.extend(v,e.curCoords.page),v.x+=i.xe,v.y+=i.ye,l.resetStatuses(f),m=l.setAll(b),i.modifiedXe+=m.dx,i.modifiedYe+=m.dy,i.i=u.request(e.boundInertiaFrame)):(i.smoothEnd=!0,i.xe=m.dx,i.ye=m.dy,i.sx=i.sy=0,i.i=u.request(e.boundSmoothEndFrame)))}}),c.signals.on("stop-active",function(t){var e=t.interaction,n=e.inertiaStatus;n.active&&(u.cancel(n.i),n.active=!1,e.simulation=null)})},{"./InteractEvent":3,"./Interaction":5,"./modifiers/base":23,"./utils":44,"./utils/raf":50}],21:[function(t,e,n){"use strict";function r(t,e){var n=a.interactables.get(t,e);return n||(n=new c(t,e),n.events.global=p),n}var i=t("./utils/browser"),o=t("./utils/events"),s=t("./utils"),a=t("./scope"),c=t("./Interactable"),l=t("./Interaction"),p={};r.isSet=function(t,e){return-1!==a.interactables.indexOfElement(t,e&&e.context)},r.on=function(t,e,n){if(s.is.string(t)&&-1!==t.search(" ")&&(t=t.trim().split(/ +/)),s.is.array(t)){for(var i=0;i<t.length;i++){var l;l=t[i];var u=l;r.on(u,e,n)}return r}if(s.is.object(t)){for(var d in t)r.on(d,t[d],e);return r}return s.contains(c.eventTypes,t)?p[t]?p[t].push(e):p[t]=[e]:o.add(a.document,t,e,{options:n}),r},r.off=function(t,e,n){if(s.is.string(t)&&-1!==t.search(" ")&&(t=t.trim().split(/ +/)),s.is.array(t)){for(var i=0;i<t.length;i++){var l;l=t[i];var u=l;r.off(u,e,n)}return r}if(s.is.object(t)){for(var d in t)r.off(d,t[d],e);return r}if(s.contains(c.eventTypes,t)){var f=void 0;t in p&&-1!==(f=p[t].indexOf(e))&&p[t].splice(f,1)}else o.remove(a.document,t,e,n);return r},r.debug=function(){return a},r.getPointerAverage=s.pointerAverage,r.getTouchBBox=s.touchBBox,r.getTouchDistance=s.touchDistance,r.getTouchAngle=s.touchAngle,r.getElementRect=s.getElementRect,r.getElementClientRect=s.getElementClientRect,r.matchesSelector=s.matchesSelector,r.closest=s.closest,r.supportsTouch=function(){return i.supportsTouch},r.supportsPointerEvent=function(){return i.supportsPointerEvent},r.stop=function(t){for(var e=a.interactions.length-1;e>=0;e--)a.interactions[e].stop(t);return r},r.pointerMoveTolerance=function(t){return s.is.number(t)?(l.pointerMoveTolerance=t,r):l.pointerMoveTolerance},r.addDocument=a.addDocument,r.removeDocument=a.removeDocument,a.interact=r,e.exports=r},{"./Interactable":4,"./Interaction":5,"./scope":33,"./utils":44,"./utils/browser":36,"./utils/events":40}],22:[function(t,e,n){"use strict";function r(t){var e=t.interaction,n=t.event;e.target&&e.target.checkAndPreventDefault(n)}var i=t("./Interactable"),o=t("./Interaction"),s=t("./scope"),a=t("./utils/is"),c=t("./utils/events"),l=t("./utils/browser"),p=t("./utils/domUtils"),u=p.nodeContains,d=p.matchesSelector;i.prototype.preventDefault=function(t){return/^(always|never|auto)$/.test(t)?(this.options.preventDefault=t,this):a.bool(t)?(this.options.preventDefault=t?"always":"never",this):this.options.preventDefault},i.prototype.checkAndPreventDefault=function(t){var e=this.options.preventDefault;if("never"!==e)return"always"===e?void t.preventDefault():void(c.supportsPassive&&/^touch(start|move)$/.test(t.type)&&!l.isIOS||/^(mouse|pointer|touch)*(down|start)/i.test(t.type)||a.element(t.target)&&d(t.target,"input,select,textarea,[contenteditable=true],[contenteditable=true] *")||t.preventDefault())};for(var f=["down","move","up","cancel"],v=0;v<f.length;v++){var g=f[v];o.signals.on(g,r)}o.docEvents.dragstart=function(t){for(var e=0;e<s.interactions.length;e++){var n;n=s.interactions[e];var r=n;if(r.element&&(r.element===t.target||u(r.element,t.target)))return void r.target.checkAndPreventDefault(t)}}},{"./Interactable":4,"./Interaction":5,"./scope":33,"./utils/browser":36,"./utils/domUtils":39,"./utils/events":40,"./utils/is":46}],23:[function(t,e,n){"use strict";function r(t,e,n){return t&&t.enabled&&(e||!t.endOnly)&&(!n||t.endOnly)}var i=t("../InteractEvent"),o=t("../Interaction"),s=t("../utils/extend"),a={names:[],setOffsets:function(t){var e=t.interaction,n=t.pageCoords,r=e.target,i=e.element,o=e.startOffset,s=r.getRect(i);s?(o.left=n.x-s.left,o.top=n.y-s.top,o.right=s.right-n.x,o.bottom=s.bottom-n.y,"width"in s||(s.width=s.right-s.left),"height"in s||(s.height=s.bottom-s.top)):o.left=o.top=o.right=o.bottom=0,t.rect=s,t.interactable=r,t.element=i;for(var c=0;c<a.names.length;c++){var l;l=a.names[c];var p=l;t.options=r.options[e.prepared.name][p],t.options&&(e.modifierOffsets[p]=a[p].setOffset(t))}},setAll:function(t){var e=t.interaction,n=t.statuses,i=t.preEnd,o=t.requireEndOnly,c={dx:0,dy:0,changed:!1,locked:!1,shouldMove:!0};t.modifiedCoords=s({},t.pageCoords);for(var l=0;l<a.names.length;l++){var p;p=a.names[l];var u=p,d=a[u],f=e.target.options[e.prepared.name][u];r(f,i,o)&&(t.status=t.status=n[u],t.options=f,t.offset=t.interaction.modifierOffsets[u],d.set(t),t.status.locked&&(t.modifiedCoords.x+=t.status.dx,t.modifiedCoords.y+=t.status.dy,c.dx+=t.status.dx,c.dy+=t.status.dy,c.locked=!0))}return c.shouldMove=!t.status||!c.locked||t.status.changed,c},resetStatuses:function(t){for(var e=0;e<a.names.length;e++){var n;n=a.names[e];var r=n,i=t[r]||{};i.dx=i.dy=0,i.modifiedX=i.modifiedY=NaN,i.locked=!1,i.changed=!0,t[r]=i}return t},start:function(t,e){var n=t.interaction,r={interaction:n,pageCoords:("action-resume"===e?n.curCoords:n.startCoords).page,startOffset:n.startOffset,statuses:n.modifierStatuses,preEnd:!1,requireEndOnly:!1};a.setOffsets(r),a.resetStatuses(r.statuses),r.pageCoords=s({},n.startCoords.page),n.modifierResult=a.setAll(r)},beforeMove:function(t){var e=t.interaction,n=t.preEnd,r=t.interactingBeforeMove,i=a.setAll({interaction:e,preEnd:n,pageCoords:e.curCoords.page,statuses:e.modifierStatuses,requireEndOnly:!1});!i.shouldMove&&r&&(e._dontFireMove=!0),e.modifierResult=i},end:function(t){for(var e=t.interaction,n=t.event,i=0;i<a.names.length;i++){var o;o=a.names[i];var s=o;if(r(e.target.options[e.prepared.name][s],!0,!0)){e.doMove({event:n,preEnd:!0});break}}},setXY:function(t){for(var e=t.iEvent,n=t.interaction,r=s({},t),i=0;i<a.names.length;i++){var o=a.names[i];if(r.options=n.target.options[n.prepared.name][o],r.options){var c=a[o];r.status=n.modifierStatuses[o],e[o]=c.modifyCoords(r)}}}};o.signals.on("new",function(t){t.startOffset={left:0,right:0,top:0,bottom:0},t.modifierOffsets={},t.modifierStatuses=a.resetStatuses({}),t.modifierResult=null}),o.signals.on("action-start",a.start),o.signals.on("action-resume",a.start),o.signals.on("before-action-move",a.beforeMove),o.signals.on("action-end",a.end),i.signals.on("set-xy",a.setXY),e.exports=a},{"../InteractEvent":3,"../Interaction":5,"../utils/extend":41}],24:[function(t,e,n){"use strict";function r(t,e,n){return o.is.function(t)?o.resolveRectLike(t,e.target,e.element,[n.x,n.y,e]):o.resolveRectLike(t,e.target,e.element)}var i=t("./base"),o=t("../utils"),s=t("../defaultOptions"),a={defaults:{enabled:!1,endOnly:!1,restriction:null,elementRect:null},setOffset:function(t){var e=t.rect,n=t.startOffset,r=t.options,i=r&&r.elementRect,o={};return e&&i?(o.left=n.left-e.width*i.left,o.top=n.top-e.height*i.top,o.right=n.right-e.width*(1-i.right),o.bottom=n.bottom-e.height*(1-i.bottom)):o.left=o.top=o.right=o.bottom=0,o},set:function(t){var e=t.modifiedCoords,n=t.interaction,i=t.status,s=t.options;if(!s)return i;var a=i.useStatusXY?{x:i.x,y:i.y}:o.extend({},e),c=r(s.restriction,n,a);if(!c)return i;i.dx=0,i.dy=0,i.locked=!1;var l=c,p=a.x,u=a.y,d=n.modifierOffsets.restrict;"x"in c&&"y"in c?(p=Math.max(Math.min(l.x+l.width-d.right,a.x),l.x+d.left),u=Math.max(Math.min(l.y+l.height-d.bottom,a.y),l.y+d.top)):(p=Math.max(Math.min(l.right-d.right,a.x),l.left+d.left),u=Math.max(Math.min(l.bottom-d.bottom,a.y),l.top+d.top)),i.dx=p-a.x,i.dy=u-a.y,i.changed=i.modifiedX!==p||i.modifiedY!==u,i.locked=!(!i.dx&&!i.dy),i.modifiedX=p,i.modifiedY=u},modifyCoords:function(t){var e=t.page,n=t.client,r=t.status,i=t.phase,o=t.options,s=o&&o.elementRect;if(o&&o.enabled&&("start"!==i||!s||!r.locked)&&r.locked)return e.x+=r.dx,e.y+=r.dy,n.x+=r.dx,n.y+=r.dy,{dx:r.dx,dy:r.dy}},getRestrictionRect:r};i.restrict=a,i.names.push("restrict"),s.perAction.restrict=a.defaults,e.exports=a},{"../defaultOptions":18,"../utils":44,"./base":23}],25:[function(t,e,n){"use strict";var r=t("./base"),i=t("../utils"),o=t("../utils/rect"),s=t("../defaultOptions"),a=t("../actions/resize"),c=t("./restrict"),l=c.getRestrictionRect,p={top:1/0,left:1/0,bottom:-1/0,right:-1/0},u={top:-1/0,left:-1/0,bottom:1/0,right:1/0},d={defaults:{enabled:!1,endOnly:!1,min:null,max:null,offset:null},setOffset:function(t){var e=t.interaction,n=t.startOffset,r=t.options;if(!r)return i.extend({},n);var o=l(r.offset,e,e.startCoords.page);return o?{top:n.top+o.y,left:n.left+o.x,bottom:n.bottom+o.y,right:n.right+o.x}:n},set:function(t){var e=t.modifiedCoords,n=t.interaction,r=t.status,s=t.offset,a=t.options,c=n.prepared.linkedEdges||n.prepared.edges;if(n.interacting()&&c){var d=r.useStatusXY?{x:r.x,y:r.y}:i.extend({},e),f=o.xywhToTlbr(l(a.inner,n,d))||p,v=o.xywhToTlbr(l(a.outer,n,d))||u,g=d.x,h=d.y;r.dx=0,r.dy=0,r.locked=!1,c.top?h=Math.min(Math.max(v.top+s.top,d.y),f.top+s.top):c.bottom&&(h=Math.max(Math.min(v.bottom-s.bottom,d.y),f.bottom-s.bottom)),c.left?g=Math.min(Math.max(v.left+s.left,d.x),f.left+s.left):c.right&&(g=Math.max(Math.min(v.right-s.right,d.x),f.right-s.right)),r.dx=g-d.x,r.dy=h-d.y,r.changed=r.modifiedX!==g||r.modifiedY!==h,r.locked=!(!r.dx&&!r.dy),r.modifiedX=g,r.modifiedY=h}},modifyCoords:function(t){var e=t.page,n=t.client,r=t.status,i=t.phase,o=t.options;if(o&&o.enabled&&("start"!==i||!r.locked)&&r.locked)return e.x+=r.dx,e.y+=r.dy,n.x+=r.dx,n.y+=r.dy,{dx:r.dx,dy:r.dy}},noInner:p,noOuter:u,getRestrictionRect:l};r.restrictEdges=d,r.names.push("restrictEdges"),s.perAction.restrictEdges=d.defaults,a.defaults.restrictEdges=d.defaults,e.exports=d},{"../actions/resize":10,"../defaultOptions":18,"../utils":44,"../utils/rect":51,"./base":23,"./restrict":24}],26:[function(t,e,n){"use strict";var r=t("./base"),i=t("./restrictEdges"),o=t("../utils"),s=t("../utils/rect"),a=t("../defaultOptions"),c=t("../actions/resize"),l={width:-1/0,height:-1/0},p={width:1/0,height:1/0},u={defaults:{enabled:!1,endOnly:!1,min:null,max:null},setOffset:function(t){return t.interaction.startOffset},set:function(t){var e=t.interaction,n=t.options,r=e.prepared.linkedEdges||e.prepared.edges;if(e.interacting()&&r){var a=s.xywhToTlbr(e.resizeRects.inverted),c=s.tlbrToXywh(i.getRestrictionRect(n.min,e))||l,u=s.tlbrToXywh(i.getRestrictionRect(n.max,e))||p;t.options={enabled:n.enabled,endOnly:n.endOnly,inner:o.extend({},i.noInner),outer:o.extend({},i.noOuter)},r.top?(t.options.inner.top=a.bottom-c.height,t.options.outer.top=a.bottom-u.height):r.bottom&&(t.options.inner.bottom=a.top+c.height,t.options.outer.bottom=a.top+u.height),r.left?(t.options.inner.left=a.right-c.width,t.options.outer.left=a.right-u.width):r.right&&(t.options.inner.right=a.left+c.width,t.options.outer.right=a.left+u.width),i.set(t)}},modifyCoords:i.modifyCoords};r.restrictSize=u,r.names.push("restrictSize"),a.perAction.restrictSize=u.defaults,c.defaults.restrictSize=u.defaults,e.exports=u},{"../actions/resize":10,"../defaultOptions":18,"../utils":44,"../utils/rect":51,"./base":23,"./restrictEdges":25}],27:[function(t,e,n){"use strict";var r=t("./base"),i=t("../interact"),o=t("../utils"),s=t("../defaultOptions"),a={defaults:{enabled:!1,endOnly:!1,range:1/0,targets:null,offsets:null,relativePoints:null},setOffset:function(t){var e=t.interaction,n=t.interactable,r=t.element,i=t.rect,s=t.startOffset,a=t.options,c=[],l=o.rectToXY(o.resolveRectLike(a.origin)),p=l||o.getOriginXY(n,r,e.prepared.name);a=a||n.options[e.prepared.name].snap||{};var u=void 0;if("startCoords"===a.offset)u={x:e.startCoords.page.x-p.x,y:e.startCoords.page.y-p.y};else{var d=o.resolveRectLike(a.offset,n,r,[e]);u=o.rectToXY(d)||{x:0,y:0}}if(i&&a.relativePoints&&a.relativePoints.length)for(var f=0;f<a.relativePoints.length;f++){var v;v=a.relativePoints[f];var g=v,h=g.x,m=g.y;c.push({x:s.left-i.width*h+u.x,y:s.top-i.height*m+u.y})}else c.push(u);return c},set:function(t){var e=t.interaction,n=t.modifiedCoords,r=t.status,i=t.options,s=t.offset,a=[],c=void 0,l=void 0,p=void 0;if(r.useStatusXY)l={x:r.x,y:r.y};else{var u=o.getOriginXY(e.target,e.element,e.prepared.name);l=o.extend({},n),l.x-=u.x,l.y-=u.y}r.realX=l.x,r.realY=l.y;for(var d=i.targets?i.targets.length:0,f=0;f<s.length;f++){var v;v=s[f];for(var g=v,h=g.x,m=g.y,y=l.x-h,x=l.y-m,b=0;b<(i.targets||[]).length;b++){var w;w=(i.targets||[])[b];var E=w;c=o.is.function(E)?E(y,x,e):E,c&&a.push({x:o.is.number(c.x)?c.x+h:y,y:o.is.number(c.y)?c.y+m:x,range:o.is.number(c.range)?c.range:i.range})}}var T={target:null,inRange:!1,distance:0,range:0,dx:0,dy:0};for(p=0,d=a.length;p<d;p++){c=a[p];var S=c.range,C=c.x-l.x,I=c.y-l.y,D=o.hypot(C,I),O=D<=S;S===1/0&&T.inRange&&T.range!==1/0&&(O=!1),T.target&&!(O?T.inRange&&S!==1/0?D/S<T.distance/T.range:S===1/0&&T.range!==1/0||D<T.distance:!T.inRange&&D<T.distance)||(T.target=c,T.distance=D,T.range=S,T.inRange=O,T.dx=C,T.dy=I,r.range=S)}var M=void 0;T.target?(M=r.modifiedX!==T.target.x||r.modifiedY!==T.target.y,r.modifiedX=T.target.x,r.modifiedY=T.target.y):(M=!0,r.modifiedX=NaN,r.modifiedY=NaN),r.dx=T.dx,r.dy=T.dy,r.changed=M||T.inRange&&!r.locked,r.locked=T.inRange},modifyCoords:function(t){var e=t.page,n=t.client,r=t.status,i=t.phase,o=t.options,s=o&&o.relativePoints;if(o&&o.enabled&&("start"!==i||!s||!s.length))return r.locked&&(e.x+=r.dx,e.y+=r.dy,n.x+=r.dx,n.y+=r.dy),{range:r.range,locked:r.locked,x:r.modifiedX,y:r.modifiedY,realX:r.realX,realY:r.realY,dx:r.dx,dy:r.dy}}};i.createSnapGrid=function(t){return function(e,n){var r=t.limits||{left:-1/0,right:1/0,top:-1/0,bottom:1/0},i=0,s=0;o.is.object(t.offset)&&(i=t.offset.x,s=t.offset.y);var a=Math.round((e-i)/t.x),c=Math.round((n-s)/t.y);return{x:Math.max(r.left,Math.min(r.right,a*t.x+i)),y:Math.max(r.top,Math.min(r.bottom,c*t.y+s)),range:t.range}}},r.snap=a,r.names.push("snap"),s.perAction.snap=a.defaults,e.exports=a},{"../defaultOptions":18,"../interact":21,"../utils":44,"./base":23}],28:[function(t,e,n){"use strict";var r=t("./base"),i=t("./snap"),o=t("../defaultOptions"),s=t("../actions/resize"),a=t("../utils/"),c={defaults:{enabled:!1,endOnly:!1,range:1/0,targets:null,offsets:null},setOffset:function(t){var e=t.interaction,n=t.options,r=e.prepared.edges;if(r){t.options={relativePoints:[{x:r.left?0:1,y:r.top?0:1}],origin:{x:0,y:0},offset:"self",range:n.range};var o=i.setOffset(t);return t.options=n,o}},set:function(t){var e=t.interaction,n=t.options,r=t.offset,o=t.modifiedCoords,s=a.extend({},o),c=s.x-r[0].x,l=s.y-r[0].y;t.options=a.extend({},n),t.options.targets=[];for(var p=0;p<(n.targets||[]).length;p++){var u;u=(n.targets||[])[p];var d=u,f=void 0;f=a.is.function(d)?d(c,l,e):d,f&&("width"in f&&"height"in f&&(f.x=f.width,f.y=f.height),t.options.targets.push(f))}i.set(t)},modifyCoords:function(t){var e=t.options;t.options=a.extend({},e),t.options.enabled=e.enabled,t.options.relativePoints=[null],i.modifyCoords(t)}};r.snapSize=c,r.names.push("snapSize"),o.perAction.snapSize=c.defaults,s.defaults.snapSize=c.defaults,e.exports=c},{"../actions/resize":10,"../defaultOptions":18,"../utils/":44,"./base":23,"./snap":27}],29:[function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var i=t("../utils/pointerUtils");e.exports=function(){function t(e,n,o,s,a){if(r(this,t),i.pointerExtend(this,o),o!==n&&i.pointerExtend(this,n),this.interaction=a,this.timeStamp=(new Date).getTime(),this.originalEvent=o,this.type=e,this.pointerId=i.getPointerId(n),this.pointerType=i.getPointerType(n),this.target=s,this.currentTarget=null,"tap"===e){var c=a.getPointerIndex(n);this.dt=this.timeStamp-a.downTimes[c];var l=this.timeStamp-a.tapTime;this.double=!!(a.prevTap&&"doubletap"!==a.prevTap.type&&a.prevTap.target===this.target&&l<500)}else"doubletap"===e&&(this.dt=n.timeStamp-a.tapTime)}return t.prototype.subtractOrigin=function(t){var e=t.x,n=t.y;return this.pageX-=e,this.pageY-=n,this.clientX-=e,this.clientY-=n,this},t.prototype.addOrigin=function(t){var e=t.x,n=t.y;return this.pageX+=e,this.pageY+=n,this.clientX+=e,this.clientY+=n,this},t.prototype.preventDefault=function(){this.originalEvent.preventDefault()},t.prototype.stopPropagation=function(){this.propagationStopped=!0},t.prototype.stopImmediatePropagation=function(){this.immediatePropagationStopped=this.propagationStopped=!0},t}()},{"../utils/pointerUtils":49}],30:[function(t,e,n){"use strict";function r(t){for(var e=t.interaction,n=t.pointer,s=t.event,c=t.eventTarget,p=t.type,u=void 0===p?t.pointerEvent.type:p,d=t.targets,f=void 0===d?i(t):d,v=t.pointerEvent,g=void 0===v?new o(u,n,s,c,e):v,h={interaction:e,pointer:n,event:s,eventTarget:c,targets:f,type:u,pointerEvent:g},m=0;m<f.length;m++){var y=f[m];for(var x in y.props||{})g[x]=y.props[x];var b=a.getOriginXY(y.eventable,y.element);if(g.subtractOrigin(b),g.eventable=y.eventable,g.currentTarget=y.element,y.eventable.fire(g),g.addOrigin(b),g.immediatePropagationStopped||g.propagationStopped&&m+1<f.length&&f[m+1].element!==g.currentTarget)break}if(l.fire("fired",h),"tap"===u){var w=g.double?r({interaction:e,pointer:n,event:s,eventTarget:c,type:"doubletap"}):g;e.prevTap=w,e.tapTime=w.timeStamp}return g}function i(t){var e=t.interaction,n=t.pointer,r=t.event,i=t.eventTarget,o=t.type,s=e.getPointerIndex(n);if("tap"===o&&(e.pointerWasMoved||!e.downTargets[s]||e.downTargets[s]!==i))return[];for(var c=a.getPath(i),p={interaction:e,pointer:n,event:r,eventTarget:i,type:o,path:c,targets:[],element:null},u=0;u<c.length;u++){var d;d=c[u];var f=d;p.element=f,l.fire("collect-targets",p)}return"hold"===o&&(p.targets=p.targets.filter(function(t){return t.eventable.options.holdDuration===e.holdTimers[s].duration})),p.targets}var o=t("./PointerEvent"),s=t("../Interaction"),a=t("../utils"),c=t("../defaultOptions"),l=t("../utils/Signals").new(),p=["down","up","cancel"],u=["down","up","cancel"],d={PointerEvent:o,fire:r,collectEventTargets:i,signals:l,defaults:{holdDuration:600,ignoreFrom:null,allowFrom:null,origin:{x:0,y:0}},types:["down","move","up","cancel","tap","doubletap","hold"]};s.signals.on("update-pointer-down",function(t){var e=t.interaction,n=t.pointerIndex;e.holdTimers[n]={duration:1/0,timeout:null}}),s.signals.on("remove-pointer",function(t){var e=t.interaction,n=t.pointerIndex;e.holdTimers.splice(n,1)}),
s.signals.on("move",function(t){var e=t.interaction,n=t.pointer,i=t.event,o=t.eventTarget,s=t.duplicateMove,a=e.getPointerIndex(n);s||e.pointerIsDown&&!e.pointerWasMoved||(e.pointerIsDown&&clearTimeout(e.holdTimers[a].timeout),r({interaction:e,pointer:n,event:i,eventTarget:o,type:"move"}))}),s.signals.on("down",function(t){for(var e=t.interaction,n=t.pointer,i=t.event,o=t.eventTarget,s=t.pointerIndex,c=e.holdTimers[s],p=a.getPath(o),u={interaction:e,pointer:n,event:i,eventTarget:o,type:"hold",targets:[],path:p,element:null},d=0;d<p.length;d++){var f;f=p[d];var v=f;u.element=v,l.fire("collect-targets",u)}if(u.targets.length){for(var g=1/0,h=0;h<u.targets.length;h++){var m;m=u.targets[h];var y=m,x=y.eventable.options.holdDuration;x<g&&(g=x)}c.duration=g,c.timeout=setTimeout(function(){r({interaction:e,eventTarget:o,pointer:n,event:i,type:"hold"})},g)}}),s.signals.on("up",function(t){var e=t.interaction,n=t.pointer,i=t.event,o=t.eventTarget;e.pointerWasMoved||r({interaction:e,eventTarget:o,pointer:n,event:i,type:"tap"})});for(var f=["up","cancel"],v=0;v<f.length;v++){var g=f[v];s.signals.on(g,function(t){var e=t.interaction,n=t.pointerIndex;e.holdTimers[n]&&clearTimeout(e.holdTimers[n].timeout)})}for(var h=0;h<p.length;h++)s.signals.on(p[h],function(t){return function(e){var n=e.interaction,i=e.pointer,o=e.event;r({interaction:n,eventTarget:e.eventTarget,pointer:i,event:o,type:t})}}(u[h]));s.signals.on("new",function(t){t.prevTap=null,t.tapTime=0,t.holdTimers=[]}),c.pointerEvents=d.defaults,e.exports=d},{"../Interaction":5,"../defaultOptions":18,"../utils":44,"../utils/Signals":34,"./PointerEvent":29}],31:[function(t,e,n){"use strict";function r(t){var e=t.pointerEvent;"hold"===e.type&&(e.count=(e.count||0)+1)}function i(t){var e=t.interaction,n=t.pointerEvent,r=t.eventTarget,i=t.targets;if("hold"===n.type&&i.length){var o=i[0].eventable.options.holdRepeatInterval;o<=0||(e.holdIntervalHandle=setTimeout(function(){s.fire({interaction:e,eventTarget:r,type:"hold",pointer:n,event:n})},o))}}function o(t){var e=t.interaction;e.holdIntervalHandle&&(clearInterval(e.holdIntervalHandle),e.holdIntervalHandle=null)}var s=t("./base"),a=t("../Interaction");s.signals.on("new",r),s.signals.on("fired",i);for(var c=["move","up","cancel","endall"],l=0;l<c.length;l++){var p=c[l];a.signals.on(p,o)}s.defaults.holdRepeatInterval=0,s.types.push("holdrepeat"),e.exports={onNew:r,onFired:i,endHoldRepeat:o}},{"../Interaction":5,"./base":30}],32:[function(t,e,n){"use strict";var r=t("./base"),i=t("../Interactable"),o=t("../utils/is"),s=t("../scope"),a=t("../utils/extend"),c=t("../utils/arr"),l=c.merge;r.signals.on("collect-targets",function(t){var e=t.targets,n=t.element,r=t.type,i=t.eventTarget;s.interactables.forEachMatch(n,function(t){var s=t.events,a=s.options;s[r]&&o.element(n)&&t.testIgnoreAllow(a,n,i)&&e.push({element:n,eventable:s,props:{interactable:t}})})}),i.signals.on("new",function(t){var e=t.interactable;e.events.getRect=function(t){return e.getRect(t)}}),i.signals.on("set",function(t){var e=t.interactable,n=t.options;a(e.events.options,r.defaults),a(e.events.options,n)}),l(i.eventTypes,r.types),i.prototype.pointerEvents=function(t){return a(this.events.options,t),this};var p=i.prototype._backCompatOption;i.prototype._backCompatOption=function(t,e){var n=p.call(this,t,e);return n===this&&(this.events.options[t]=e),n},i.settingsMethods.push("pointerEvents")},{"../Interactable":4,"../scope":33,"../utils/arr":35,"../utils/extend":41,"../utils/is":46,"./base":30}],33:[function(t,e,n){"use strict";var r=t("./utils"),i=t("./utils/events"),o=t("./utils/Signals").new(),s=t("./utils/window"),a=s.getWindow,c={signals:o,events:i,utils:r,document:t("./utils/domObjects").document,documents:[],addDocument:function(t,e){if(r.contains(c.documents,t))return!1;e=e||a(t),c.documents.push(t),i.documents.push(t),t!==c.document&&i.add(e,"unload",c.onWindowUnload),o.fire("add-document",{doc:t,win:e})},removeDocument:function(t,e){var n=c.documents.indexOf(t);e=e||a(t),i.remove(e,"unload",c.onWindowUnload),c.documents.splice(n,1),i.documents.splice(n,1),o.fire("remove-document",{win:e,doc:t})},onWindowUnload:function(){c.removeDocument(this.document,this)}};e.exports=c},{"./utils":44,"./utils/Signals":34,"./utils/domObjects":38,"./utils/events":40,"./utils/window":52}],34:[function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}var i=function(){function t(){r(this,t),this.listeners={}}return t.prototype.on=function(t,e){if(!this.listeners[t])return void(this.listeners[t]=[e]);this.listeners[t].push(e)},t.prototype.off=function(t,e){if(this.listeners[t]){var n=this.listeners[t].indexOf(e);-1!==n&&this.listeners[t].splice(n,1)}},t.prototype.fire=function(t,e){var n=this.listeners[t];if(n)for(var r=0;r<n.length;r++){var i;i=n[r];var o=i;if(!1===o(e,t))return}},t}();i.new=function(){return new i},e.exports=i},{}],35:[function(t,e,n){"use strict";function r(t,e){return-1!==t.indexOf(e)}function i(t,e){for(var n=0;n<e.length;n++){var r;r=e[n];var i=r;t.push(i)}return t}e.exports={contains:r,merge:i}},{}],36:[function(t,e,n){"use strict";var r=t("./window"),i=r.window,o=t("./is"),s=t("./domObjects"),a=s.Element,c=i.navigator,l={supportsTouch:!!("ontouchstart"in i||o.function(i.DocumentTouch)&&s.document instanceof i.DocumentTouch),supportsPointerEvent:!!s.PointerEvent,isIOS:/iP(hone|od|ad)/.test(c.platform),isIOS7:/iP(hone|od|ad)/.test(c.platform)&&/OS 7[^\d]/.test(c.appVersion),isIe9:/MSIE 9/.test(c.userAgent),prefixedMatchesSelector:"matches"in a.prototype?"matches":"webkitMatchesSelector"in a.prototype?"webkitMatchesSelector":"mozMatchesSelector"in a.prototype?"mozMatchesSelector":"oMatchesSelector"in a.prototype?"oMatchesSelector":"msMatchesSelector",pEventTypes:s.PointerEvent?s.PointerEvent===i.MSPointerEvent?{up:"MSPointerUp",down:"MSPointerDown",over:"mouseover",out:"mouseout",move:"MSPointerMove",cancel:"MSPointerCancel"}:{up:"pointerup",down:"pointerdown",over:"pointerover",out:"pointerout",move:"pointermove",cancel:"pointercancel"}:null,wheelEvent:"onmousewheel"in s.document?"mousewheel":"wheel"};l.isOperaMobile="Opera"===c.appName&&l.supportsTouch&&c.userAgent.match("Presto"),e.exports=l},{"./domObjects":38,"./is":46,"./window":52}],37:[function(t,e,n){"use strict";var r=t("./is");e.exports=function t(e){var n={};for(var i in e)r.plainObject(e[i])?n[i]=t(e[i]):n[i]=e[i];return n}},{"./is":46}],38:[function(t,e,n){"use strict";function r(){}var i={},o=t("./window").window;i.document=o.document,i.DocumentFragment=o.DocumentFragment||r,i.SVGElement=o.SVGElement||r,i.SVGSVGElement=o.SVGSVGElement||r,i.SVGElementInstance=o.SVGElementInstance||r,i.Element=o.Element||r,i.HTMLElement=o.HTMLElement||i.Element,i.Event=o.Event,i.Touch=o.Touch||r,i.PointerEvent=o.PointerEvent||o.MSPointerEvent,e.exports=i},{"./window":52}],39:[function(t,e,n){"use strict";var r=t("./window"),i=t("./browser"),o=t("./is"),s=t("./domObjects"),a={nodeContains:function(t,e){for(;e;){if(e===t)return!0;e=e.parentNode}return!1},closest:function(t,e){for(;o.element(t);){if(a.matchesSelector(t,e))return t;t=a.parentNode(t)}return null},parentNode:function(t){var e=t.parentNode;if(o.docFrag(e)){for(;(e=e.host)&&o.docFrag(e););return e}return e},matchesSelector:function(t,e){return r.window!==r.realWindow&&(e=e.replace(/\/deep\//g," ")),t[i.prefixedMatchesSelector](e)},indexOfDeepestElement:function(t){var e=[],n=[],r=void 0,i=t[0],o=i?0:-1,a=void 0,c=void 0,l=void 0,p=void 0;for(l=1;l<t.length;l++)if((r=t[l])&&r!==i)if(i){if(r.parentNode!==r.ownerDocument)if(i.parentNode!==r.ownerDocument){if(!e.length)for(a=i;a.parentNode&&a.parentNode!==a.ownerDocument;)e.unshift(a),a=a.parentNode;if(i instanceof s.HTMLElement&&r instanceof s.SVGElement&&!(r instanceof s.SVGSVGElement)){if(r===i.parentNode)continue;a=r.ownerSVGElement}else a=r;for(n=[];a.parentNode!==a.ownerDocument;)n.unshift(a),a=a.parentNode;for(p=0;n[p]&&n[p]===e[p];)p++;var u=[n[p-1],n[p],e[p]];for(c=u[0].lastChild;c;){if(c===u[1]){i=r,o=l,e=[];break}if(c===u[2])break;c=c.previousSibling}}else i=r,o=l}else i=r,o=l;return o},matchesUpTo:function(t,e,n){for(;o.element(t);){if(a.matchesSelector(t,e))return!0;if((t=a.parentNode(t))===n)return a.matchesSelector(t,e)}return!1},getActualElement:function(t){return t instanceof s.SVGElementInstance?t.correspondingUseElement:t},getScrollXY:function(t){return t=t||r.window,{x:t.scrollX||t.document.documentElement.scrollLeft,y:t.scrollY||t.document.documentElement.scrollTop}},getElementClientRect:function(t){var e=t instanceof s.SVGElement?t.getBoundingClientRect():t.getClientRects()[0];return e&&{left:e.left,right:e.right,top:e.top,bottom:e.bottom,width:e.width||e.right-e.left,height:e.height||e.bottom-e.top}},getElementRect:function(t){var e=a.getElementClientRect(t);if(!i.isIOS7&&e){var n=a.getScrollXY(r.getWindow(t));e.left+=n.x,e.right+=n.x,e.top+=n.y,e.bottom+=n.y}return e},getPath:function(t){for(var e=[];t;)e.push(t),t=a.parentNode(t);return e},trySelector:function(t){return!!o.string(t)&&(s.document.querySelector(t),!0)}};e.exports=a},{"./browser":36,"./domObjects":38,"./is":46,"./window":52}],40:[function(t,e,n){"use strict";function r(t,e,n,r){var i=p(r),o=x.indexOf(t),s=b[o];s||(s={events:{},typeCount:0},o=x.push(t)-1,b.push(s)),s.events[e]||(s.events[e]=[],s.typeCount++),y(s.events[e],n)||(t.addEventListener(e,n,T?i:!!i.capture),s.events[e].push(n))}function i(t,e,n,r){var o=p(r),s=x.indexOf(t),a=b[s];if(a&&a.events)if("all"!==e){if(a.events[e]){var c=a.events[e].length;if("all"===n){for(var l=0;l<c;l++)i(t,e,a.events[e][l],o);return}for(var u=0;u<c;u++)if(a.events[e][u]===n){t.removeEventListener("on"+e,n,T?o:!!o.capture),a.events[e].splice(u,1);break}a.events[e]&&0===a.events[e].length&&(a.events[e]=null,a.typeCount--)}a.typeCount||(b.splice(s,1),x.splice(s,1))}else for(e in a.events)a.events.hasOwnProperty(e)&&i(t,e,"all")}function o(t,e,n,i,o){var s=p(o);if(!w[n]){w[n]={selectors:[],contexts:[],listeners:[]};for(var l=0;l<E.length;l++){var u=E[l];r(u,n,a),r(u,n,c,!0)}}var d=w[n],f=void 0;for(f=d.selectors.length-1;f>=0&&(d.selectors[f]!==t||d.contexts[f]!==e);f--);-1===f&&(f=d.selectors.length,d.selectors.push(t),d.contexts.push(e),d.listeners.push([])),d.listeners[f].push([i,!!s.capture,s.passive])}function s(t,e,n,r,o){var s=p(o),l=w[n],u=!1,d=void 0;if(l)for(d=l.selectors.length-1;d>=0;d--)if(l.selectors[d]===t&&l.contexts[d]===e){for(var f=l.listeners[d],v=f.length-1;v>=0;v--){var g=f[v],h=g[0],m=g[1],y=g[2];if(h===r&&m===!!s.capture&&y===s.passive){f.splice(v,1),f.length||(l.selectors.splice(d,1),l.contexts.splice(d,1),l.listeners.splice(d,1),i(e,n,a),i(e,n,c,!0),l.selectors.length||(w[n]=null)),u=!0;break}}if(u)break}}function a(t,e){var n=p(e),r={},i=w[t.type],o=f.getEventTargets(t),s=o[0],a=s;for(v(r,t),r.originalEvent=t,r.preventDefault=l;u.element(a);){for(var c=0;c<i.selectors.length;c++){var g=i.selectors[c],h=i.contexts[c];if(d.matchesSelector(a,g)&&d.nodeContains(h,s)&&d.nodeContains(h,a)){var m=i.listeners[c];r.currentTarget=a;for(var y=0;y<m.length;y++){var x=m[y],b=x[0],E=x[1],T=x[2];E===!!n.capture&&T===n.passive&&b(r)}}}a=d.parentNode(a)}}function c(t){return a.call(this,t,!0)}function l(){this.originalEvent.preventDefault()}function p(t){return u.object(t)?t:{capture:t}}var u=t("./is"),d=t("./domUtils"),f=t("./pointerUtils"),v=t("./pointerExtend"),g=t("./window"),h=g.window,m=t("./arr"),y=m.contains,x=[],b=[],w={},E=[],T=function(){var t=!1;return h.document.createElement("div").addEventListener("test",null,{get capture(){t=!0}}),t}();e.exports={add:r,remove:i,addDelegate:o,removeDelegate:s,delegateListener:a,delegateUseCapture:c,delegatedEvents:w,documents:E,supportsOptions:T,_elements:x,_targets:b}},{"./arr":35,"./domUtils":39,"./is":46,"./pointerExtend":48,"./pointerUtils":49,"./window":52}],41:[function(t,e,n){"use strict";e.exports=function(t,e){for(var n in e)t[n]=e[n];return t}},{}],42:[function(t,e,n){"use strict";var r=t("./rect"),i=r.resolveRectLike,o=r.rectToXY;e.exports=function(t,e,n){var r=t.options[n],s=r&&r.origin,a=s||t.options.origin,c=i(a,t,e,[t&&e]);return o(c)||{x:0,y:0}}},{"./rect":51}],43:[function(t,e,n){"use strict";e.exports=function(t,e){return Math.sqrt(t*t+e*e)}},{}],44:[function(t,e,n){"use strict";var r=t("./extend"),i=t("./window"),o={warnOnce:function(t,e){var n=!1;return function(){return n||(i.window.console.warn(e),n=!0),t.apply(this,arguments)}},_getQBezierValue:function(t,e,n,r){var i=1-t;return i*i*e+2*i*t*n+t*t*r},getQuadraticCurvePoint:function(t,e,n,r,i,s,a){return{x:o._getQBezierValue(a,t,n,i),y:o._getQBezierValue(a,e,r,s)}},easeOutQuad:function(t,e,n,r){return t/=r,-n*t*(t-2)+e},copyAction:function(t,e){return t.name=e.name,t.axis=e.axis,t.edges=e.edges,t},is:t("./is"),extend:r,hypot:t("./hypot"),getOriginXY:t("./getOriginXY")};r(o,t("./arr")),r(o,t("./domUtils")),r(o,t("./pointerUtils")),r(o,t("./rect")),e.exports=o},{"./arr":35,"./domUtils":39,"./extend":41,"./getOriginXY":42,"./hypot":43,"./is":46,"./pointerUtils":49,"./rect":51,"./window":52}],45:[function(t,e,n){"use strict";var r=t("../scope"),i=t("./index"),o={methodOrder:["simulationResume","mouseOrPen","hasPointer","idle"],search:function(t,e,n){for(var r=i.getPointerType(t),s=i.getPointerId(t),a={pointer:t,pointerId:s,pointerType:r,eventType:e,eventTarget:n},c=0;c<o.methodOrder.length;c++){var l;l=o.methodOrder[c];var p=l,u=o[p](a);if(u)return u}},simulationResume:function(t){var e=t.pointerType,n=t.eventType,o=t.eventTarget;if(!/down|start/i.test(n))return null;for(var s=0;s<r.interactions.length;s++){var a;a=r.interactions[s];var c=a,l=o;if(c.simulation&&c.simulation.allowResume&&c.pointerType===e)for(;l;){if(l===c.element)return c;l=i.parentNode(l)}}return null},mouseOrPen:function(t){var e=t.pointerId,n=t.pointerType,o=t.eventType;if("mouse"!==n&&"pen"!==n)return null;for(var s=void 0,a=0;a<r.interactions.length;a++){var c;c=r.interactions[a];var l=c;if(l.pointerType===n){if(l.simulation&&!i.contains(l.pointerIds,e))continue;if(l.interacting())return l;s||(s=l)}}if(s)return s;for(var p=0;p<r.interactions.length;p++){var u;u=r.interactions[p];var d=u;if(!(d.pointerType!==n||/down/i.test(o)&&d.simulation))return d}return null},hasPointer:function(t){for(var e=t.pointerId,n=0;n<r.interactions.length;n++){var o;o=r.interactions[n];var s=o;if(i.contains(s.pointerIds,e))return s}},idle:function(t){for(var e=t.pointerType,n=0;n<r.interactions.length;n++){var i;i=r.interactions[n];var o=i;if(1===o.pointerIds.length){var s=o.target;if(s&&!s.options.gesture.enabled)continue}else if(o.pointerIds.length>=2)continue;if(!o.interacting()&&e===o.pointerType)return o}return null}};e.exports=o},{"../scope":33,"./index":44}],46:[function(t,e,n){"use strict";var r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},i=t("./window"),o=t("./isWindow"),s={array:function(){},window:function(t){return t===i.window||o(t)},docFrag:function(t){return s.object(t)&&11===t.nodeType},object:function(t){return!!t&&"object"===(void 0===t?"undefined":r(t))},function:function(t){return"function"==typeof t},number:function(t){return"number"==typeof t},bool:function(t){return"boolean"==typeof t},string:function(t){return"string"==typeof t},element:function(t){if(!t||"object"!==(void 0===t?"undefined":r(t)))return!1;var e=i.getWindow(t)||i.window;return/object|function/.test(r(e.Element))?t instanceof e.Element:1===t.nodeType&&"string"==typeof t.nodeName},plainObject:function(t){return s.object(t)&&"Object"===t.constructor.name}};s.array=function(t){return s.object(t)&&void 0!==t.length&&s.function(t.splice)},e.exports=s},{"./isWindow":47,"./window":52}],47:[function(t,e,n){"use strict";e.exports=function(t){return!(!t||!t.Window)&&t instanceof t.Window}},{}],48:[function(t,e,n){"use strict";function r(t,n){for(var r in n){var i=e.exports.prefixedPropREs,o=!1;for(var s in i)if(0===r.indexOf(s)&&i[s].test(r)){o=!0;break}o||"function"==typeof n[r]||(t[r]=n[r])}return t}r.prefixedPropREs={webkit:/(Movement[XY]|Radius[XY]|RotationAngle|Force)$/},e.exports=r},{}],49:[function(t,e,n){"use strict";var r=t("./hypot"),i=t("./browser"),o=t("./domObjects"),s=t("./domUtils"),a=t("./domObjects"),c=t("./is"),l=t("./pointerExtend"),p={copyCoords:function(t,e){t.page=t.page||{},t.page.x=e.page.x,t.page.y=e.page.y,t.client=t.client||{},t.client.x=e.client.x,t.client.y=e.client.y,t.timeStamp=e.timeStamp},setCoordDeltas:function(t,e,n){t.page.x=n.page.x-e.page.x,t.page.y=n.page.y-e.page.y,t.client.x=n.client.x-e.client.x,t.client.y=n.client.y-e.client.y,t.timeStamp=n.timeStamp-e.timeStamp;var i=Math.max(t.timeStamp/1e3,.001);t.page.speed=r(t.page.x,t.page.y)/i,t.page.vx=t.page.x/i,t.page.vy=t.page.y/i,t.client.speed=r(t.client.x,t.page.y)/i,t.client.vx=t.client.x/i,t.client.vy=t.client.y/i},isNativePointer:function(t){return t instanceof o.Event||t instanceof o.Touch},getXY:function(t,e,n){return n=n||{},t=t||"page",n.x=e[t+"X"],n.y=e[t+"Y"],n},getPageXY:function(t,e){return e=e||{},i.isOperaMobile&&p.isNativePointer(t)?(p.getXY("screen",t,e),e.x+=window.scrollX,e.y+=window.scrollY):p.getXY("page",t,e),e},getClientXY:function(t,e){return e=e||{},i.isOperaMobile&&p.isNativePointer(t)?p.getXY("screen",t,e):p.getXY("client",t,e),e},getPointerId:function(t){return c.number(t.pointerId)?t.pointerId:t.identifier},setCoords:function(t,e,n){var r=e.length>1?p.pointerAverage(e):e[0],i={};p.getPageXY(r,i),t.page.x=i.x,t.page.y=i.y,p.getClientXY(r,i),t.client.x=i.x,t.client.y=i.y,t.timeStamp=c.number(n)?n:(new Date).getTime()},pointerExtend:l,getTouchPair:function(t){var e=[];return c.array(t)?(e[0]=t[0],e[1]=t[1]):"touchend"===t.type?1===t.touches.length?(e[0]=t.touches[0],e[1]=t.changedTouches[0]):0===t.touches.length&&(e[0]=t.changedTouches[0],e[1]=t.changedTouches[1]):(e[0]=t.touches[0],e[1]=t.touches[1]),e},pointerAverage:function(t){for(var e={pageX:0,pageY:0,clientX:0,clientY:0,screenX:0,screenY:0},n=0;n<t.length;n++){var r;r=t[n];var i=r;for(var o in e)e[o]+=i[o]}for(var s in e)e[s]/=t.length;return e},touchBBox:function(t){if(t.length||t.touches&&t.touches.length>1){var e=p.getTouchPair(t),n=Math.min(e[0].pageX,e[1].pageX),r=Math.min(e[0].pageY,e[1].pageY);return{x:n,y:r,left:n,top:r,width:Math.max(e[0].pageX,e[1].pageX)-n,height:Math.max(e[0].pageY,e[1].pageY)-r}}},touchDistance:function(t,e){var n=e+"X",i=e+"Y",o=p.getTouchPair(t),s=o[0][n]-o[1][n],a=o[0][i]-o[1][i];return r(s,a)},touchAngle:function(t,e,n){var r=n+"X",i=n+"Y",o=p.getTouchPair(t),s=o[1][r]-o[0][r],a=o[1][i]-o[0][i];return 180*Math.atan2(a,s)/Math.PI},getPointerType:function(t){return c.string(t.pointerType)?t.pointerType:c.number(t.pointerType)?[void 0,void 0,"touch","pen","mouse"][t.pointerType]:/touch/.test(t.type)||t instanceof a.Touch?"touch":"mouse"},getEventTargets:function(t){var e=c.function(t.composedPath)?t.composedPath():t.path;return[s.getActualElement(e?e[0]:t.target),s.getActualElement(t.currentTarget)]}};e.exports=p},{"./browser":36,"./domObjects":38,"./domUtils":39,"./hypot":43,"./is":46,"./pointerExtend":48}],50:[function(t,e,n){"use strict";for(var r=t("./window"),i=r.window,o=["ms","moz","webkit","o"],s=0,a=void 0,c=void 0,l=0;l<o.length&&!i.requestAnimationFrame;l++)a=i[o[l]+"RequestAnimationFrame"],c=i[o[l]+"CancelAnimationFrame"]||i[o[l]+"CancelRequestAnimationFrame"];a||(a=function(t){var e=(new Date).getTime(),n=Math.max(0,16-(e-s)),r=setTimeout(function(){t(e+n)},n);return s=e+n,r}),c||(c=function(t){clearTimeout(t)}),e.exports={request:a,cancel:c}},{"./window":52}],51:[function(t,e,n){"use strict";var r=t("./extend"),i=t("./is"),o=t("./domUtils"),s=o.closest,a=o.parentNode,c=o.getElementRect,l={getStringOptionResult:function(t,e,n){return i.string(t)?t="parent"===t?a(n):"self"===t?e.getRect(n):s(n,t):null},resolveRectLike:function(t,e,n,r){return t=l.getStringOptionResult(t,e,n)||t,i.function(t)&&(t=t.apply(null,r)),i.element(t)&&(t=c(t)),t},rectToXY:function(t){return t&&{x:"x"in t?t.x:t.left,y:"y"in t?t.y:t.top}},xywhToTlbr:function(t){return!t||"left"in t&&"top"in t||(t=r({},t),t.left=t.x||0,t.top=t.y||0,t.right=t.right||t.left+t.width,t.bottom=t.bottom||t.top+t.height),t},tlbrToXywh:function(t){return!t||"x"in t&&"y"in t||(t=r({},t),t.x=t.left||0,t.top=t.top||0,t.width=t.width||t.right-t.x,t.height=t.height||t.bottom-t.y),t}};e.exports=l},{"./domUtils":39,"./extend":41,"./is":46}],52:[function(t,e,n){"use strict";function r(t){i.realWindow=t;var e=t.document.createTextNode("");e.ownerDocument!==t.document&&"function"==typeof t.wrap&&t.wrap(e)===e&&(t=t.wrap(t)),i.window=t}var i=e.exports,o=t("./isWindow");"undefined"==typeof window?(i.window=void 0,i.realWindow=void 0):r(window),i.getWindow=function(t){if(o(t))return t;var e=t.ownerDocument||t;return e.defaultView||e.parentWindow||i.window},i.init=r},{"./isWindow":47}]},{},[1])(1)});

//# sourceMappingURL=interact.min.js.map

(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory(require("Vue"), require("interact"));
	else if(typeof define === 'function' && define.amd)
		define("Segel", ["Vue", "interact"], factory);
	else if(typeof exports === 'object')
		exports["Segel"] = factory(require("Vue"), require("interact"));
	else
		root["Segel"] = factory(root["Vue"], root["interact"]);
})(typeof self !== 'undefined' ? self : this, function(__WEBPACK_EXTERNAL_MODULE_10__, __WEBPACK_EXTERNAL_MODULE_4__) {
return /******/ (function(modules) { // webpackBootstrap
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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 21);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 1 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_forOwn__ = __webpack_require__(26);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_forOwn___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_lodash_forOwn__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__helpers_cast__ = __webpack_require__(18);





var Store = {
  config: {
    editable: true,
    set: function set(config) {
      // Iterate over all supplied properties.
      __WEBPACK_IMPORTED_MODULE_0_lodash_forOwn___default()(config, function (value, key) {
        // Set value if key is available in state.
        if (this[key] !== undefined) {
          this[key] = value;
        }
      });
    }
  },
  clock: {
    duration: function duration() {
      return this.end - this.start;
    },
    steps: 48,
    current: null,
    tick: function tick() {
      this.current = __WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].date(new Date());
    }
  },
  user: {
    id: null,
    admin: false,
    isAdmin: function isAdmin() {
      return this.admin === true;
    },
    isAuthenticated: function isAuthenticated() {
      return this.id !== null;
    },
    set: function set(user) {
      // Iterate over all supplied properties.
      __WEBPACK_IMPORTED_MODULE_0_lodash_forOwn___default()(user, function (value, key) {
        // Set value if key is available in state.
        if (this[key] !== undefined) {
          this[key] = value;
        }
      });
    }
  }
};

// Start timer that ticks every second.
(function timer() {
  Store.clock.tick();
  setTimeout(timer, 1000);
})();

// Return the instance.
/* harmony default export */ __webpack_exports__["a"] = (Store);

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var Symbol = __webpack_require__(12),
    getRawTag = __webpack_require__(35),
    objectToString = __webpack_require__(36);

/** `Object#toString` result references. */
var nullTag = '[object Null]',
    undefinedTag = '[object Undefined]';

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? getRawTag(value)
    : objectToString(value);
}

module.exports = baseGetTag;


/***/ }),
/* 3 */
/***/ (function(module, exports) {

/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

module.exports = isObjectLike;


/***/ }),
/* 4 */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_4__;

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var freeGlobal = __webpack_require__(13);

/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = freeGlobal || freeSelf || Function('return this')();

module.exports = root;


/***/ }),
/* 6 */
/***/ (function(module, exports) {

/**
 * Checks if `value` is classified as an `Array` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
 * @example
 *
 * _.isArray([1, 2, 3]);
 * // => true
 *
 * _.isArray(document.body.children);
 * // => false
 *
 * _.isArray('abc');
 * // => false
 *
 * _.isArray(_.noop);
 * // => false
 */
var isArray = Array.isArray;

module.exports = isArray;


/***/ }),
/* 7 */
/***/ (function(module, exports) {

/**
 * Checks if `value` is the
 * [language type](http://www.ecma-international.org/ecma-262/7.0/#sec-ecmascript-language-types)
 * of `Object`. (e.g. arrays, functions, objects, regexes, `new Number(0)`, and `new String('')`)
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an object, else `false`.
 * @example
 *
 * _.isObject({});
 * // => true
 *
 * _.isObject([1, 2, 3]);
 * // => true
 *
 * _.isObject(_.noop);
 * // => true
 *
 * _.isObject(null);
 * // => false
 */
function isObject(value) {
  var type = typeof value;
  return value != null && (type == 'object' || type == 'function');
}

module.exports = isObject;


/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(7),
    isSymbol = __webpack_require__(54);

/** Used as references for various `Number` constants. */
var NAN = 0 / 0;

/** Used to match leading and trailing whitespace. */
var reTrim = /^\s+|\s+$/g;

/** Used to detect bad signed hexadecimal string values. */
var reIsBadHex = /^[-+]0x[0-9a-f]+$/i;

/** Used to detect binary string values. */
var reIsBinary = /^0b[01]+$/i;

/** Used to detect octal string values. */
var reIsOctal = /^0o[0-7]+$/i;

/** Built-in method references without a dependency on `root`. */
var freeParseInt = parseInt;

/**
 * Converts `value` to a number.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to process.
 * @returns {number} Returns the number.
 * @example
 *
 * _.toNumber(3.2);
 * // => 3.2
 *
 * _.toNumber(Number.MIN_VALUE);
 * // => 5e-324
 *
 * _.toNumber(Infinity);
 * // => Infinity
 *
 * _.toNumber('3.2');
 * // => 3.2
 */
function toNumber(value) {
  if (typeof value == 'number') {
    return value;
  }
  if (isSymbol(value)) {
    return NAN;
  }
  if (isObject(value)) {
    var other = typeof value.valueOf == 'function' ? value.valueOf() : value;
    value = isObject(other) ? (other + '') : other;
  }
  if (typeof value != 'string') {
    return value === 0 ? value : +value;
  }
  value = value.replace(reTrim, '');
  var isBinary = reIsBinary.test(value);
  return (isBinary || reIsOctal.test(value))
    ? freeParseInt(value.slice(2), isBinary ? 2 : 8)
    : (reIsBadHex.test(value) ? NAN : +value);
}

module.exports = toNumber;


/***/ }),
/* 9 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_interactjs__);


// Dependencies.



/**
 * Create an interact.js snap grid.
 *
 * @param {Object} coordinates - Size of grid in pixels.
 * @returns {Array} - interact.js snap grid.
 */
function snapGrid(coordinates) {
  return [__WEBPACK_IMPORTED_MODULE_0_interactjs___default.a.createSnapGrid(coordinates)];
}

/**
 * Base grid object.
 *
 * @type {Object}
 */
var Grid = {};

/**
 * Create snap grid.
 *
 * @param {Number} width - Width of the available area in pixels.
 * @param {Number} height - Height of the grid in pixels.
 * @param {Number} steps - Number of steps to divide the horizontal space in.
 * @returns {Array} - interact.js snap grid.
 */
Grid.create = function (width, height, steps) {
  return snapGrid({
    x: width / steps,
    y: height
  });
};

/**
 * Round timestamp to closest step.
 *
 * @param {Number} timestamp - Timestamp to round.
 * @param {Number} duration - Duration of the calendar.
 * @param {Number} steps - Number of steps within the duration of the calendar.
 * @returns {Number} - Rounded timestamp.
 */
Grid.round = function (timestamp, duration, steps) {
  var size = duration / steps;
  var offset = timestamp % size;
  var rounded = timestamp - offset;

  return offset >= size / 2 ? rounded + size : rounded;
};

// Return the instance.
/* harmony default export */ __webpack_exports__["a"] = (Grid);

/***/ }),
/* 10 */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_10__;

/***/ }),
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var baseFor = __webpack_require__(27),
    keys = __webpack_require__(29);

/**
 * The base implementation of `_.forOwn` without support for iteratee shorthands.
 *
 * @private
 * @param {Object} object The object to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Object} Returns `object`.
 */
function baseForOwn(object, iteratee) {
  return object && baseFor(object, iteratee, keys);
}

module.exports = baseForOwn;


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

var root = __webpack_require__(5);

/** Built-in value references. */
var Symbol = root.Symbol;

module.exports = Symbol;


/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof global == 'object' && global && global.Object === Object && global;

module.exports = freeGlobal;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(34)))

/***/ }),
/* 14 */
/***/ (function(module, exports) {

module.exports = function(module) {
	if(!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if(!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),
/* 15 */
/***/ (function(module, exports) {

/** Used as references for various `Number` constants. */
var MAX_SAFE_INTEGER = 9007199254740991;

/**
 * Checks if `value` is a valid array-like length.
 *
 * **Note:** This method is loosely based on
 * [`ToLength`](http://ecma-international.org/ecma-262/7.0/#sec-tolength).
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a valid length, else `false`.
 * @example
 *
 * _.isLength(3);
 * // => true
 *
 * _.isLength(Number.MIN_VALUE);
 * // => false
 *
 * _.isLength(Infinity);
 * // => false
 *
 * _.isLength('3');
 * // => false
 */
function isLength(value) {
  return typeof value == 'number' &&
    value > -1 && value % 1 == 0 && value <= MAX_SAFE_INTEGER;
}

module.exports = isLength;


/***/ }),
/* 16 */
/***/ (function(module, exports, __webpack_require__) {

var isFunction = __webpack_require__(48),
    isLength = __webpack_require__(15);

/**
 * Checks if `value` is array-like. A value is considered array-like if it's
 * not a function and has a `value.length` that's an integer greater than or
 * equal to `0` and less than or equal to `Number.MAX_SAFE_INTEGER`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is array-like, else `false`.
 * @example
 *
 * _.isArrayLike([1, 2, 3]);
 * // => true
 *
 * _.isArrayLike(document.body.children);
 * // => true
 *
 * _.isArrayLike('abc');
 * // => true
 *
 * _.isArrayLike(_.noop);
 * // => false
 */
function isArrayLike(value) {
  return value != null && isLength(value.length) && !isFunction(value);
}

module.exports = isArrayLike;


/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

var identity = __webpack_require__(49);

/**
 * Casts `value` to `identity` if it's not a function.
 *
 * @private
 * @param {*} value The value to inspect.
 * @returns {Function} Returns cast function.
 */
function castFunction(value) {
  return typeof value == 'function' ? value : identity;
}

module.exports = castFunction;


/***/ }),
/* 18 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";


/**
 * Far future date to compare timestamps against.
 * Is used to detect if timestamp is defined in seconds or milliseconds.
 * This will make it impossible to use dates beyond year 10000.
 *
 * @type {Date}
 */

var futureDate = new Date(10000, 1, 1);

/**
 * Base cast object.
 *
 * @type {Object}
 */
var Cast = {};

/**
 * Cast properties of a booking to correct type.
 *
 * @param {Object} booking - Booking to work with.
 * @returns {Object} - Booking with properties cast to correct types.
 */
Cast.booking = function (booking) {
  booking.start = this.date(booking.start);
  booking.end = this.date(booking.end);

  return booking;
};

/**
 * Cast properties of a resource to correct type.
 *
 * @param {Object} resource - Resource to work with.
 * @returns {Object} - Resource with properties cast to correct types.
 */
Cast.resource = function (resource) {
  return resource;
};

/**
 * Convert date and time to an Unix Epoch timestamp.
 *
 * @param date - Date to be cast.
 * @returns {number} - Date in Unix Epoch timestamp (seconds).
 */
Cast.date = function (date) {
  var dateReturn = date;

  // Convert strings to integer if it's numeric.
  if (Object.prototype.toString.call(date) === "[object String]" && /^\d+$/.test(date)) {
    date = parseInt(date);
  }

  switch (Object.prototype.toString.call(date)) {
    case "[object Date]":
      // Get Unix timestamp (in milliseconds) and divide to seconds.
      dateReturn = date.getTime() / 1000;
      break;
    case "[object Number]":
      // If timestamp is in milliseconds it will be beyond year 10000 multiplied, otherwise assume it is in seconds.
      dateReturn = date * 1000 > futureDate.getTime() ? date / 1000 : date;
      break;
    default:
      // Leave it to Date constructor if not recognized.
      dateReturn = new Date(date).getTime();
      break;
  }

  return Math.floor(dateReturn);
};

// Return the instance.
/* harmony default export */ __webpack_exports__["a"] = (Cast);

/***/ }),
/* 19 */
/***/ (function(module, exports, __webpack_require__) {

var baseInRange = __webpack_require__(52),
    toFinite = __webpack_require__(53),
    toNumber = __webpack_require__(8);

/**
 * Checks if `n` is between `start` and up to, but not including, `end`. If
 * `end` is not specified, it's set to `start` with `start` then set to `0`.
 * If `start` is greater than `end` the params are swapped to support
 * negative ranges.
 *
 * @static
 * @memberOf _
 * @since 3.3.0
 * @category Number
 * @param {number} number The number to check.
 * @param {number} [start=0] The start of the range.
 * @param {number} end The end of the range.
 * @returns {boolean} Returns `true` if `number` is in the range, else `false`.
 * @see _.range, _.rangeRight
 * @example
 *
 * _.inRange(3, 2, 4);
 * // => true
 *
 * _.inRange(4, 8);
 * // => true
 *
 * _.inRange(4, 2);
 * // => false
 *
 * _.inRange(2, 2);
 * // => false
 *
 * _.inRange(1.2, 2);
 * // => true
 *
 * _.inRange(5.2, 4);
 * // => false
 *
 * _.inRange(-3, -2, -6);
 * // => true
 */
function inRange(number, start, end) {
  start = toFinite(start);
  if (end === undefined) {
    end = start;
    start = 0;
  } else {
    end = toFinite(end);
  }
  number = toNumber(number);
  return baseInRange(number, start, end);
}

module.exports = inRange;


/***/ }),
/* 20 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(7),
    now = __webpack_require__(66),
    toNumber = __webpack_require__(8);

/** Error message constants. */
var FUNC_ERROR_TEXT = 'Expected a function';

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeMax = Math.max,
    nativeMin = Math.min;

/**
 * Creates a debounced function that delays invoking `func` until after `wait`
 * milliseconds have elapsed since the last time the debounced function was
 * invoked. The debounced function comes with a `cancel` method to cancel
 * delayed `func` invocations and a `flush` method to immediately invoke them.
 * Provide `options` to indicate whether `func` should be invoked on the
 * leading and/or trailing edge of the `wait` timeout. The `func` is invoked
 * with the last arguments provided to the debounced function. Subsequent
 * calls to the debounced function return the result of the last `func`
 * invocation.
 *
 * **Note:** If `leading` and `trailing` options are `true`, `func` is
 * invoked on the trailing edge of the timeout only if the debounced function
 * is invoked more than once during the `wait` timeout.
 *
 * If `wait` is `0` and `leading` is `false`, `func` invocation is deferred
 * until to the next tick, similar to `setTimeout` with a timeout of `0`.
 *
 * See [David Corbacho's article](https://css-tricks.com/debouncing-throttling-explained-examples/)
 * for details over the differences between `_.debounce` and `_.throttle`.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Function
 * @param {Function} func The function to debounce.
 * @param {number} [wait=0] The number of milliseconds to delay.
 * @param {Object} [options={}] The options object.
 * @param {boolean} [options.leading=false]
 *  Specify invoking on the leading edge of the timeout.
 * @param {number} [options.maxWait]
 *  The maximum time `func` is allowed to be delayed before it's invoked.
 * @param {boolean} [options.trailing=true]
 *  Specify invoking on the trailing edge of the timeout.
 * @returns {Function} Returns the new debounced function.
 * @example
 *
 * // Avoid costly calculations while the window size is in flux.
 * jQuery(window).on('resize', _.debounce(calculateLayout, 150));
 *
 * // Invoke `sendMail` when clicked, debouncing subsequent calls.
 * jQuery(element).on('click', _.debounce(sendMail, 300, {
 *   'leading': true,
 *   'trailing': false
 * }));
 *
 * // Ensure `batchLog` is invoked once after 1 second of debounced calls.
 * var debounced = _.debounce(batchLog, 250, { 'maxWait': 1000 });
 * var source = new EventSource('/stream');
 * jQuery(source).on('message', debounced);
 *
 * // Cancel the trailing debounced invocation.
 * jQuery(window).on('popstate', debounced.cancel);
 */
function debounce(func, wait, options) {
  var lastArgs,
      lastThis,
      maxWait,
      result,
      timerId,
      lastCallTime,
      lastInvokeTime = 0,
      leading = false,
      maxing = false,
      trailing = true;

  if (typeof func != 'function') {
    throw new TypeError(FUNC_ERROR_TEXT);
  }
  wait = toNumber(wait) || 0;
  if (isObject(options)) {
    leading = !!options.leading;
    maxing = 'maxWait' in options;
    maxWait = maxing ? nativeMax(toNumber(options.maxWait) || 0, wait) : maxWait;
    trailing = 'trailing' in options ? !!options.trailing : trailing;
  }

  function invokeFunc(time) {
    var args = lastArgs,
        thisArg = lastThis;

    lastArgs = lastThis = undefined;
    lastInvokeTime = time;
    result = func.apply(thisArg, args);
    return result;
  }

  function leadingEdge(time) {
    // Reset any `maxWait` timer.
    lastInvokeTime = time;
    // Start the timer for the trailing edge.
    timerId = setTimeout(timerExpired, wait);
    // Invoke the leading edge.
    return leading ? invokeFunc(time) : result;
  }

  function remainingWait(time) {
    var timeSinceLastCall = time - lastCallTime,
        timeSinceLastInvoke = time - lastInvokeTime,
        timeWaiting = wait - timeSinceLastCall;

    return maxing
      ? nativeMin(timeWaiting, maxWait - timeSinceLastInvoke)
      : timeWaiting;
  }

  function shouldInvoke(time) {
    var timeSinceLastCall = time - lastCallTime,
        timeSinceLastInvoke = time - lastInvokeTime;

    // Either this is the first call, activity has stopped and we're at the
    // trailing edge, the system time has gone backwards and we're treating
    // it as the trailing edge, or we've hit the `maxWait` limit.
    return (lastCallTime === undefined || (timeSinceLastCall >= wait) ||
      (timeSinceLastCall < 0) || (maxing && timeSinceLastInvoke >= maxWait));
  }

  function timerExpired() {
    var time = now();
    if (shouldInvoke(time)) {
      return trailingEdge(time);
    }
    // Restart the timer.
    timerId = setTimeout(timerExpired, remainingWait(time));
  }

  function trailingEdge(time) {
    timerId = undefined;

    // Only invoke if we have `lastArgs` which means `func` has been
    // debounced at least once.
    if (trailing && lastArgs) {
      return invokeFunc(time);
    }
    lastArgs = lastThis = undefined;
    return result;
  }

  function cancel() {
    if (timerId !== undefined) {
      clearTimeout(timerId);
    }
    lastInvokeTime = 0;
    lastArgs = lastCallTime = lastThis = timerId = undefined;
  }

  function flush() {
    return timerId === undefined ? result : trailingEdge(now());
  }

  function debounced() {
    var time = now(),
        isInvoking = shouldInvoke(time);

    lastArgs = arguments;
    lastThis = this;
    lastCallTime = time;

    if (isInvoking) {
      if (timerId === undefined) {
        return leadingEdge(lastCallTime);
      }
      if (maxing) {
        // Handle invocations in a tight loop.
        timerId = setTimeout(timerExpired, wait);
        return invokeFunc(lastCallTime);
      }
    }
    if (timerId === undefined) {
      timerId = setTimeout(timerExpired, wait);
    }
    return result;
  }
  debounced.cancel = cancel;
  debounced.flush = flush;
  return debounced;
}

module.exports = debounce;


/***/ }),
/* 21 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(22);


/***/ }),
/* 22 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__vue__ = __webpack_require__(23);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__helpers_cast__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__helpers_events__ = __webpack_require__(73);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__helpers_process__ = __webpack_require__(74);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_4__store__ = __webpack_require__(1);


// Components.







/**
 * Constructor.
 *
 * @param {String} selector - CSS selector to which it will insert the schedule.
 * @constructor
 */
var Segel = function Segel(selector) {
  // Set required properties of the Vue instance.
  __WEBPACK_IMPORTED_MODULE_0__vue__["a" /* default */].bookings = [];
  __WEBPACK_IMPORTED_MODULE_0__vue__["a" /* default */].resources = [];
  __WEBPACK_IMPORTED_MODULE_0__vue__["a" /* default */].time = {
    start: __WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].date(new Date().setHours(0, 0, 0, 0)),
    end: __WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].date(new Date().setHours(24, 0, 0, 0))
  };

  // Mount the Vue instance to document.
  __WEBPACK_IMPORTED_MODULE_0__vue__["a" /* default */].$mount(selector);

  return Segel;
};

/**
 * Root instance of Vue.
 *
 * @type {Vue}
 */
Segel.instance = __WEBPACK_IMPORTED_MODULE_0__vue__["a" /* default */];

/**
 * Return traversal to main Segel object.
 *
 * @returns {Segel}
 */
function returnSegel() {
  return Segel;
}

/**
 * Base for methods manipulating bookings in store.
 *
 * @type {Object}
 */
Segel.bookings = {};

/**
 * Get all stored bookings.
 *
 * @returns {Array} - All stored bookings.
 */
Segel.bookings.all = function () {
  return __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].bookings.all;
};

/**
 * Get a store booking based on ID.
 *
 * @param id - ID of requested booking.
 * @returns {Object|null} - Booking if found, otherwise null.
 */
Segel.bookings.get = function (id) {
  return __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].bookings.get(id) || null;
};

/**
 * Add one or more bookings to the store.
 *
 * @param {Object|Object[]} bookings - Bookings to add.
 * @returns {Segel.bookings}
 */
Segel.bookings.add = function (bookings) {
  Object(__WEBPACK_IMPORTED_MODULE_3__helpers_process__["a" /* default */])(bookings, function (booking) {
    __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].bookings.add(__WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].booking(booking));
  });

  return this;
};

/**
 * Update one or more bookings in the store.
 *
 * @param {Object|Object[]} bookings - Bookings to update.
 * @returns {Segel.bookings}
 */
Segel.bookings.update = function (bookings) {
  Object(__WEBPACK_IMPORTED_MODULE_3__helpers_process__["a" /* default */])(bookings, function (booking) {
    __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].bookings.update(__WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].booking(booking));
  });

  return this;
};

/**
 * Remove one ore more bookings from the store.
 *
 * @param {Object|Object[]} bookings - Bookings to remove.
 * @returns {Segel.bookings}
 */
Segel.bookings.remove = function (bookings) {
  Object(__WEBPACK_IMPORTED_MODULE_3__helpers_process__["a" /* default */])(bookings, function (booking) {
    __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].bookings.remove(__WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].booking(booking));
  });

  return this;
};

/**
 * Return traversal to main Segel object.
 *
 * @returns {Segel}
 */
Segel.bookings.end = returnSegel;

/**
 * Base for methods manipulating resources in store.
 *
 * @type {Object}
 */
Segel.resources = {};

/**
 * Get all stored resources.
 *
 * @returns {Array} - All stored resources.
 */
Segel.resources.all = function () {
  return __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].resources.all;
};

/**
 * Get a store resource based on ID.
 *
 * @param id - ID of requested resource.
 * @returns {Object|null} - Resource if found, otherwise null.
 */
Segel.resources.get = function (id) {
  return __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].resources.get(id) || null;
};

/**
 * Add one or more resources to the store.
 *
 * @param {Object|Object[]} resources - Resources to add.
 * @returns {Segel.resources}
 */
Segel.resources.add = function (resources) {
  Object(__WEBPACK_IMPORTED_MODULE_3__helpers_process__["a" /* default */])(resources, function (resource) {
    __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].resources.add(__WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].resource(resource));
  });

  return this;
};

/**
 * Update one or more resources in the store.
 *
 * @param {Object|Object[]} resources - Resources to update.
 * @returns {Segel.resources}
 */
Segel.resources.update = function (resources) {
  Object(__WEBPACK_IMPORTED_MODULE_3__helpers_process__["a" /* default */])(resources, function (resource) {
    __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].resources.update(__WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].resource(resource));
  });

  return this;
};

/**
 * Remove one ore more resources from the store.
 *
 * @param {Object|Object[]} resources - Resources to remove.
 * @returns {Segel.resources}
 */
Segel.resources.remove = function (resources) {
  Object(__WEBPACK_IMPORTED_MODULE_3__helpers_process__["a" /* default */])(resources, function (resource) {
    __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].resources.remove(__WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].resource(resource));
  });

  return this;
};

/**
 * Return traversal to main Segel object.
 *
 * @returns {Segel}
 */
Segel.resources.end = returnSegel;

/**
 * Base for methods manipulating time in store.
 *
 * @type {Object}
 */
Segel.time = {};

/**
 * Set the current user.
 *
 * @param start
 * @param end
 * @returns {Segel.time}
 */
Segel.time.set = function (start, end) {
  __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].time.set(__WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].date(start), __WEBPACK_IMPORTED_MODULE_1__helpers_cast__["a" /* default */].date(end));

  return this;
};

/**
 * Return traversal to main Segel object.
 *
 * @returns {Segel}
 */
Segel.time.end = returnSegel;

/**
 * Base for methods manipulating user in store.
 *
 * @type {Object}
 */
Segel.user = {};

/**
 * Set the current user.
 *
 * @param user
 * @returns {Segel.user}
 */
Segel.user.set = function (user) {
  if (["[object String]", "[object Number]"].indexOf(Object.prototype.toString.call(user)) > -1) {
    user = { id: user };
  }

  __WEBPACK_IMPORTED_MODULE_4__store__["a" /* default */].user.set(user);

  return this;
};

/**
 * Return traversal to main Segel object.
 *
 * @returns {Segel}
 */
Segel.user.end = returnSegel;

/**
 * Assign callback handler for events.
 *
 * @param {String|String[]} event - Event to subscribe to.
 * @param {Function} callback - Function to run when event is broadcast.
 * @returns {Segel}
 */
Segel.on = function (event, callback) {
  __WEBPACK_IMPORTED_MODULE_2__helpers_events__["a" /* default */].$on(event, callback);

  return Segel;
};

/**
 * Remove an assigned callback event handler.
 *
 * @param {String|String[]} event - Event to unsubscribe to.
 * @param {Function} callback - Function to remove.
 * @returns {Segel}
 */
Segel.off = function (event, callback) {
  __WEBPACK_IMPORTED_MODULE_2__helpers_events__["a" /* default */].$off(event, callback);

  return Segel;
};

/* harmony default export */ __webpack_exports__["default"] = (Segel);

/***/ }),
/* 23 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue__ = __webpack_require__(10);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_main_vue__ = __webpack_require__(24);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__components_main_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__components_main_vue__);


// Dependencies.



// Components.


/**
 * Root element for Vue.
 *
 * @type {Vue}
 */
var Instance = new __WEBPACK_IMPORTED_MODULE_0_vue___default.a({
  template: "<segel-main v-bind:bookings='bookings' v-bind:resources='resources' v-bind:time='time'></segel-main>",

  components: {
    "segel-main": __WEBPACK_IMPORTED_MODULE_1__components_main_vue___default.a
  },

  data: function data() {
    return {
      bookings: [],
      resources: [],
      time: {}
    };
  }
});

/* harmony default export */ __webpack_exports__["a"] = (Instance);

/***/ }),
/* 24 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(25)
/* template */
var __vue_template__ = __webpack_require__(72)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/components/main.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-5c886d6e", Component.options)
  } else {
    hotAPI.reload("data-v-5c886d6e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 25 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__store__ = __webpack_require__(1);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__indicator_vue__ = __webpack_require__(50);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__indicator_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__indicator_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ruler_vue__ = __webpack_require__(56);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__ruler_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__ruler_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__resources_vue__ = __webpack_require__(59);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__resources_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__resources_vue__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//






/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    bookings: {
      type: Array,
      default: function _default() {
        return [];
      }
    },
    resources: {
      type: Array,
      default: function _default() {
        return [];
      }
    },
    time: {
      type: Object,
      default: function _default() {
        return {
          start: Cast.date(new Date().setHours(0, 0, 0, 0)),
          end: Cast.date(new Date().setHours(24, 0, 0, 0))
        };
      }
    }
  },

  data: function data() {
    return __WEBPACK_IMPORTED_MODULE_0__store__["a" /* default */];
  },

  provide: function provide() {
    var _this = this;

    var state = {};

    Object.defineProperty(state, "config", {
      enumerable: true,
      get: function get() {
        return __WEBPACK_IMPORTED_MODULE_0__store__["a" /* default */].config;
      }
    });

    Object.defineProperty(state, "time", {
      enumerable: true,
      get: function get() {
        return Object.assign(_this.time, __WEBPACK_IMPORTED_MODULE_0__store__["a" /* default */].clock);
      }
    });

    Object.defineProperty(state, "user", {
      enumerable: true,
      get: function get() {
        return __WEBPACK_IMPORTED_MODULE_0__store__["a" /* default */].user;
      }
    });

    return { state: state };
  },

  components: {
    "segel-indicator": __WEBPACK_IMPORTED_MODULE_1__indicator_vue___default.a,
    "segel-ruler": __WEBPACK_IMPORTED_MODULE_2__ruler_vue___default.a,
    "segel-resources": __WEBPACK_IMPORTED_MODULE_3__resources_vue___default.a
  }
});

/***/ }),
/* 26 */
/***/ (function(module, exports, __webpack_require__) {

var baseForOwn = __webpack_require__(11),
    castFunction = __webpack_require__(17);

/**
 * Iterates over own enumerable string keyed properties of an object and
 * invokes `iteratee` for each property. The iteratee is invoked with three
 * arguments: (value, key, object). Iteratee functions may exit iteration
 * early by explicitly returning `false`.
 *
 * @static
 * @memberOf _
 * @since 0.3.0
 * @category Object
 * @param {Object} object The object to iterate over.
 * @param {Function} [iteratee=_.identity] The function invoked per iteration.
 * @returns {Object} Returns `object`.
 * @see _.forOwnRight
 * @example
 *
 * function Foo() {
 *   this.a = 1;
 *   this.b = 2;
 * }
 *
 * Foo.prototype.c = 3;
 *
 * _.forOwn(new Foo, function(value, key) {
 *   console.log(key);
 * });
 * // => Logs 'a' then 'b' (iteration order is not guaranteed).
 */
function forOwn(object, iteratee) {
  return object && baseForOwn(object, castFunction(iteratee));
}

module.exports = forOwn;


/***/ }),
/* 27 */
/***/ (function(module, exports, __webpack_require__) {

var createBaseFor = __webpack_require__(28);

/**
 * The base implementation of `baseForOwn` which iterates over `object`
 * properties returned by `keysFunc` and invokes `iteratee` for each property.
 * Iteratee functions may exit iteration early by explicitly returning `false`.
 *
 * @private
 * @param {Object} object The object to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @param {Function} keysFunc The function to get the keys of `object`.
 * @returns {Object} Returns `object`.
 */
var baseFor = createBaseFor();

module.exports = baseFor;


/***/ }),
/* 28 */
/***/ (function(module, exports) {

/**
 * Creates a base function for methods like `_.forIn` and `_.forOwn`.
 *
 * @private
 * @param {boolean} [fromRight] Specify iterating from right to left.
 * @returns {Function} Returns the new base function.
 */
function createBaseFor(fromRight) {
  return function(object, iteratee, keysFunc) {
    var index = -1,
        iterable = Object(object),
        props = keysFunc(object),
        length = props.length;

    while (length--) {
      var key = props[fromRight ? length : ++index];
      if (iteratee(iterable[key], key, iterable) === false) {
        break;
      }
    }
    return object;
  };
}

module.exports = createBaseFor;


/***/ }),
/* 29 */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeKeys = __webpack_require__(30),
    baseKeys = __webpack_require__(44),
    isArrayLike = __webpack_require__(16);

/**
 * Creates an array of the own enumerable property names of `object`.
 *
 * **Note:** Non-object values are coerced to objects. See the
 * [ES spec](http://ecma-international.org/ecma-262/7.0/#sec-object.keys)
 * for more details.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Object
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 * @example
 *
 * function Foo() {
 *   this.a = 1;
 *   this.b = 2;
 * }
 *
 * Foo.prototype.c = 3;
 *
 * _.keys(new Foo);
 * // => ['a', 'b'] (iteration order is not guaranteed)
 *
 * _.keys('hi');
 * // => ['0', '1']
 */
function keys(object) {
  return isArrayLike(object) ? arrayLikeKeys(object) : baseKeys(object);
}

module.exports = keys;


/***/ }),
/* 30 */
/***/ (function(module, exports, __webpack_require__) {

var baseTimes = __webpack_require__(31),
    isArguments = __webpack_require__(32),
    isArray = __webpack_require__(6),
    isBuffer = __webpack_require__(37),
    isIndex = __webpack_require__(39),
    isTypedArray = __webpack_require__(40);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Creates an array of the enumerable property names of the array-like `value`.
 *
 * @private
 * @param {*} value The value to query.
 * @param {boolean} inherited Specify returning inherited property names.
 * @returns {Array} Returns the array of property names.
 */
function arrayLikeKeys(value, inherited) {
  var isArr = isArray(value),
      isArg = !isArr && isArguments(value),
      isBuff = !isArr && !isArg && isBuffer(value),
      isType = !isArr && !isArg && !isBuff && isTypedArray(value),
      skipIndexes = isArr || isArg || isBuff || isType,
      result = skipIndexes ? baseTimes(value.length, String) : [],
      length = result.length;

  for (var key in value) {
    if ((inherited || hasOwnProperty.call(value, key)) &&
        !(skipIndexes && (
           // Safari 9 has enumerable `arguments.length` in strict mode.
           key == 'length' ||
           // Node.js 0.10 has enumerable non-index properties on buffers.
           (isBuff && (key == 'offset' || key == 'parent')) ||
           // PhantomJS 2 has enumerable non-index properties on typed arrays.
           (isType && (key == 'buffer' || key == 'byteLength' || key == 'byteOffset')) ||
           // Skip index properties.
           isIndex(key, length)
        ))) {
      result.push(key);
    }
  }
  return result;
}

module.exports = arrayLikeKeys;


/***/ }),
/* 31 */
/***/ (function(module, exports) {

/**
 * The base implementation of `_.times` without support for iteratee shorthands
 * or max array length checks.
 *
 * @private
 * @param {number} n The number of times to invoke `iteratee`.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the array of results.
 */
function baseTimes(n, iteratee) {
  var index = -1,
      result = Array(n);

  while (++index < n) {
    result[index] = iteratee(index);
  }
  return result;
}

module.exports = baseTimes;


/***/ }),
/* 32 */
/***/ (function(module, exports, __webpack_require__) {

var baseIsArguments = __webpack_require__(33),
    isObjectLike = __webpack_require__(3);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/** Built-in value references. */
var propertyIsEnumerable = objectProto.propertyIsEnumerable;

/**
 * Checks if `value` is likely an `arguments` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
 *  else `false`.
 * @example
 *
 * _.isArguments(function() { return arguments; }());
 * // => true
 *
 * _.isArguments([1, 2, 3]);
 * // => false
 */
var isArguments = baseIsArguments(function() { return arguments; }()) ? baseIsArguments : function(value) {
  return isObjectLike(value) && hasOwnProperty.call(value, 'callee') &&
    !propertyIsEnumerable.call(value, 'callee');
};

module.exports = isArguments;


/***/ }),
/* 33 */
/***/ (function(module, exports, __webpack_require__) {

var baseGetTag = __webpack_require__(2),
    isObjectLike = __webpack_require__(3);

/** `Object#toString` result references. */
var argsTag = '[object Arguments]';

/**
 * The base implementation of `_.isArguments`.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
 */
function baseIsArguments(value) {
  return isObjectLike(value) && baseGetTag(value) == argsTag;
}

module.exports = baseIsArguments;


/***/ }),
/* 34 */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || Function("return this")() || (1,eval)("this");
} catch(e) {
	// This works if the window reference is available
	if(typeof window === "object")
		g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),
/* 35 */
/***/ (function(module, exports, __webpack_require__) {

var Symbol = __webpack_require__(12);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

module.exports = getRawTag;


/***/ }),
/* 36 */
/***/ (function(module, exports) {

/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

module.exports = objectToString;


/***/ }),
/* 37 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var root = __webpack_require__(5),
    stubFalse = __webpack_require__(38);

/** Detect free variable `exports`. */
var freeExports = typeof exports == 'object' && exports && !exports.nodeType && exports;

/** Detect free variable `module`. */
var freeModule = freeExports && typeof module == 'object' && module && !module.nodeType && module;

/** Detect the popular CommonJS extension `module.exports`. */
var moduleExports = freeModule && freeModule.exports === freeExports;

/** Built-in value references. */
var Buffer = moduleExports ? root.Buffer : undefined;

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeIsBuffer = Buffer ? Buffer.isBuffer : undefined;

/**
 * Checks if `value` is a buffer.
 *
 * @static
 * @memberOf _
 * @since 4.3.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a buffer, else `false`.
 * @example
 *
 * _.isBuffer(new Buffer(2));
 * // => true
 *
 * _.isBuffer(new Uint8Array(2));
 * // => false
 */
var isBuffer = nativeIsBuffer || stubFalse;

module.exports = isBuffer;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(14)(module)))

/***/ }),
/* 38 */
/***/ (function(module, exports) {

/**
 * This method returns `false`.
 *
 * @static
 * @memberOf _
 * @since 4.13.0
 * @category Util
 * @returns {boolean} Returns `false`.
 * @example
 *
 * _.times(2, _.stubFalse);
 * // => [false, false]
 */
function stubFalse() {
  return false;
}

module.exports = stubFalse;


/***/ }),
/* 39 */
/***/ (function(module, exports) {

/** Used as references for various `Number` constants. */
var MAX_SAFE_INTEGER = 9007199254740991;

/** Used to detect unsigned integer values. */
var reIsUint = /^(?:0|[1-9]\d*)$/;

/**
 * Checks if `value` is a valid array-like index.
 *
 * @private
 * @param {*} value The value to check.
 * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
 * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
 */
function isIndex(value, length) {
  var type = typeof value;
  length = length == null ? MAX_SAFE_INTEGER : length;

  return !!length &&
    (type == 'number' ||
      (type != 'symbol' && reIsUint.test(value))) &&
        (value > -1 && value % 1 == 0 && value < length);
}

module.exports = isIndex;


/***/ }),
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

var baseIsTypedArray = __webpack_require__(41),
    baseUnary = __webpack_require__(42),
    nodeUtil = __webpack_require__(43);

/* Node.js helper references. */
var nodeIsTypedArray = nodeUtil && nodeUtil.isTypedArray;

/**
 * Checks if `value` is classified as a typed array.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
 * @example
 *
 * _.isTypedArray(new Uint8Array);
 * // => true
 *
 * _.isTypedArray([]);
 * // => false
 */
var isTypedArray = nodeIsTypedArray ? baseUnary(nodeIsTypedArray) : baseIsTypedArray;

module.exports = isTypedArray;


/***/ }),
/* 41 */
/***/ (function(module, exports, __webpack_require__) {

var baseGetTag = __webpack_require__(2),
    isLength = __webpack_require__(15),
    isObjectLike = __webpack_require__(3);

/** `Object#toString` result references. */
var argsTag = '[object Arguments]',
    arrayTag = '[object Array]',
    boolTag = '[object Boolean]',
    dateTag = '[object Date]',
    errorTag = '[object Error]',
    funcTag = '[object Function]',
    mapTag = '[object Map]',
    numberTag = '[object Number]',
    objectTag = '[object Object]',
    regexpTag = '[object RegExp]',
    setTag = '[object Set]',
    stringTag = '[object String]',
    weakMapTag = '[object WeakMap]';

var arrayBufferTag = '[object ArrayBuffer]',
    dataViewTag = '[object DataView]',
    float32Tag = '[object Float32Array]',
    float64Tag = '[object Float64Array]',
    int8Tag = '[object Int8Array]',
    int16Tag = '[object Int16Array]',
    int32Tag = '[object Int32Array]',
    uint8Tag = '[object Uint8Array]',
    uint8ClampedTag = '[object Uint8ClampedArray]',
    uint16Tag = '[object Uint16Array]',
    uint32Tag = '[object Uint32Array]';

/** Used to identify `toStringTag` values of typed arrays. */
var typedArrayTags = {};
typedArrayTags[float32Tag] = typedArrayTags[float64Tag] =
typedArrayTags[int8Tag] = typedArrayTags[int16Tag] =
typedArrayTags[int32Tag] = typedArrayTags[uint8Tag] =
typedArrayTags[uint8ClampedTag] = typedArrayTags[uint16Tag] =
typedArrayTags[uint32Tag] = true;
typedArrayTags[argsTag] = typedArrayTags[arrayTag] =
typedArrayTags[arrayBufferTag] = typedArrayTags[boolTag] =
typedArrayTags[dataViewTag] = typedArrayTags[dateTag] =
typedArrayTags[errorTag] = typedArrayTags[funcTag] =
typedArrayTags[mapTag] = typedArrayTags[numberTag] =
typedArrayTags[objectTag] = typedArrayTags[regexpTag] =
typedArrayTags[setTag] = typedArrayTags[stringTag] =
typedArrayTags[weakMapTag] = false;

/**
 * The base implementation of `_.isTypedArray` without Node.js optimizations.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
 */
function baseIsTypedArray(value) {
  return isObjectLike(value) &&
    isLength(value.length) && !!typedArrayTags[baseGetTag(value)];
}

module.exports = baseIsTypedArray;


/***/ }),
/* 42 */
/***/ (function(module, exports) {

/**
 * The base implementation of `_.unary` without support for storing metadata.
 *
 * @private
 * @param {Function} func The function to cap arguments for.
 * @returns {Function} Returns the new capped function.
 */
function baseUnary(func) {
  return function(value) {
    return func(value);
  };
}

module.exports = baseUnary;


/***/ }),
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(module) {var freeGlobal = __webpack_require__(13);

/** Detect free variable `exports`. */
var freeExports = typeof exports == 'object' && exports && !exports.nodeType && exports;

/** Detect free variable `module`. */
var freeModule = freeExports && typeof module == 'object' && module && !module.nodeType && module;

/** Detect the popular CommonJS extension `module.exports`. */
var moduleExports = freeModule && freeModule.exports === freeExports;

/** Detect free variable `process` from Node.js. */
var freeProcess = moduleExports && freeGlobal.process;

/** Used to access faster Node.js helpers. */
var nodeUtil = (function() {
  try {
    // Use `util.types` for Node.js 10+.
    var types = freeModule && freeModule.require && freeModule.require('util').types;

    if (types) {
      return types;
    }

    // Legacy `process.binding('util')` for Node.js < 10.
    return freeProcess && freeProcess.binding && freeProcess.binding('util');
  } catch (e) {}
}());

module.exports = nodeUtil;

/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(14)(module)))

/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

var isPrototype = __webpack_require__(45),
    nativeKeys = __webpack_require__(46);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * The base implementation of `_.keys` which doesn't treat sparse arrays as dense.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 */
function baseKeys(object) {
  if (!isPrototype(object)) {
    return nativeKeys(object);
  }
  var result = [];
  for (var key in Object(object)) {
    if (hasOwnProperty.call(object, key) && key != 'constructor') {
      result.push(key);
    }
  }
  return result;
}

module.exports = baseKeys;


/***/ }),
/* 45 */
/***/ (function(module, exports) {

/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Checks if `value` is likely a prototype object.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a prototype, else `false`.
 */
function isPrototype(value) {
  var Ctor = value && value.constructor,
      proto = (typeof Ctor == 'function' && Ctor.prototype) || objectProto;

  return value === proto;
}

module.exports = isPrototype;


/***/ }),
/* 46 */
/***/ (function(module, exports, __webpack_require__) {

var overArg = __webpack_require__(47);

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeKeys = overArg(Object.keys, Object);

module.exports = nativeKeys;


/***/ }),
/* 47 */
/***/ (function(module, exports) {

/**
 * Creates a unary function that invokes `func` with its argument transformed.
 *
 * @private
 * @param {Function} func The function to wrap.
 * @param {Function} transform The argument transform.
 * @returns {Function} Returns the new function.
 */
function overArg(func, transform) {
  return function(arg) {
    return func(transform(arg));
  };
}

module.exports = overArg;


/***/ }),
/* 48 */
/***/ (function(module, exports, __webpack_require__) {

var baseGetTag = __webpack_require__(2),
    isObject = __webpack_require__(7);

/** `Object#toString` result references. */
var asyncTag = '[object AsyncFunction]',
    funcTag = '[object Function]',
    genTag = '[object GeneratorFunction]',
    proxyTag = '[object Proxy]';

/**
 * Checks if `value` is classified as a `Function` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a function, else `false`.
 * @example
 *
 * _.isFunction(_);
 * // => true
 *
 * _.isFunction(/abc/);
 * // => false
 */
function isFunction(value) {
  if (!isObject(value)) {
    return false;
  }
  // The use of `Object#toString` avoids issues with the `typeof` operator
  // in Safari 9 which returns 'object' for typed arrays and other constructors.
  var tag = baseGetTag(value);
  return tag == funcTag || tag == genTag || tag == asyncTag || tag == proxyTag;
}

module.exports = isFunction;


/***/ }),
/* 49 */
/***/ (function(module, exports) {

/**
 * This method returns the first argument it receives.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Util
 * @param {*} value Any value.
 * @returns {*} Returns `value`.
 * @example
 *
 * var object = { 'a': 1 };
 *
 * console.log(_.identity(object) === object);
 * // => true
 */
function identity(value) {
  return value;
}

module.exports = identity;


/***/ }),
/* 50 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(51)
/* template */
var __vue_template__ = __webpack_require__(55)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/components/indicator.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-ddd032a2", Component.options)
  } else {
    hotAPI.reload("data-v-ddd032a2", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 51 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_inRange__ = __webpack_require__(19);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_inRange___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_lodash_inRange__);
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {};
  },

  inject: ["state"],

  computed: {
    isInView: function isInView() {
      return __WEBPACK_IMPORTED_MODULE_0_lodash_inRange___default()(this.state.time.current, this.state.time.start, this.state.time.end);
    },
    left: function left() {
      return (this.state.time.current - this.state.time.start) / this.state.time.duration() * 100;
    }
  }
});

/***/ }),
/* 52 */
/***/ (function(module, exports) {

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeMax = Math.max,
    nativeMin = Math.min;

/**
 * The base implementation of `_.inRange` which doesn't coerce arguments.
 *
 * @private
 * @param {number} number The number to check.
 * @param {number} start The start of the range.
 * @param {number} end The end of the range.
 * @returns {boolean} Returns `true` if `number` is in the range, else `false`.
 */
function baseInRange(number, start, end) {
  return number >= nativeMin(start, end) && number < nativeMax(start, end);
}

module.exports = baseInRange;


/***/ }),
/* 53 */
/***/ (function(module, exports, __webpack_require__) {

var toNumber = __webpack_require__(8);

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0,
    MAX_INTEGER = 1.7976931348623157e+308;

/**
 * Converts `value` to a finite number.
 *
 * @static
 * @memberOf _
 * @since 4.12.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {number} Returns the converted number.
 * @example
 *
 * _.toFinite(3.2);
 * // => 3.2
 *
 * _.toFinite(Number.MIN_VALUE);
 * // => 5e-324
 *
 * _.toFinite(Infinity);
 * // => 1.7976931348623157e+308
 *
 * _.toFinite('3.2');
 * // => 3.2
 */
function toFinite(value) {
  if (!value) {
    return value === 0 ? value : 0;
  }
  value = toNumber(value);
  if (value === INFINITY || value === -INFINITY) {
    var sign = (value < 0 ? -1 : 1);
    return sign * MAX_INTEGER;
  }
  return value === value ? value : 0;
}

module.exports = toFinite;


/***/ }),
/* 54 */
/***/ (function(module, exports, __webpack_require__) {

var baseGetTag = __webpack_require__(2),
    isObjectLike = __webpack_require__(3);

/** `Object#toString` result references. */
var symbolTag = '[object Symbol]';

/**
 * Checks if `value` is classified as a `Symbol` primitive or object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
 * @example
 *
 * _.isSymbol(Symbol.iterator);
 * // => true
 *
 * _.isSymbol('abc');
 * // => false
 */
function isSymbol(value) {
  return typeof value == 'symbol' ||
    (isObjectLike(value) && baseGetTag(value) == symbolTag);
}

module.exports = isSymbol;


/***/ }),
/* 55 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.isInView
    ? _c("div", {
        class: { "segel-indicator": true },
        style: { left: _vm.left + "%" }
      })
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-ddd032a2", module.exports)
  }
}

/***/ }),
/* 56 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(57)
/* template */
var __vue_template__ = __webpack_require__(58)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/components/ruler.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-08c0abd4", Component.options)
  } else {
    hotAPI.reload("data-v-08c0abd4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 57 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  filters: {
    leadingZero: function leadingZero(value) {
      return ("0" + value).slice(-2);
    }
  }
});

/***/ }),
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("aside", { staticClass: "segel-ruler" }, [
    _c(
      "ul",
      _vm._l(24, function(n) {
        return _c("li", [
          _c(
            "span",
            { class: [n === new Date().getHours() + 1 ? "current" : ""] },
            [
              _vm._v(
                "\n                " +
                  _vm._s(_vm._f("leadingZero")(n - 1)) +
                  "\n            "
              )
            ]
          )
        ])
      })
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-08c0abd4", module.exports)
  }
}

/***/ }),
/* 59 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(60)
/* template */
var __vue_template__ = __webpack_require__(71)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/components/resources.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-3fea2876", Component.options)
  } else {
    hotAPI.reload("data-v-3fea2876", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 60 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__resource_vue__ = __webpack_require__(61);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__resource_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__resource_vue__);
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    resources: {
      type: Array,
      default: function _default() {
        return [];
      }
    },
    bookings: {
      type: Array,
      default: function _default() {
        return [];
      }
    }
  },

  data: function data() {
    return {};
  },


  components: {
    "segel-resource": __WEBPACK_IMPORTED_MODULE_0__resource_vue___default.a
  },

  methods: {
    relatedBookings: function relatedBookings(resource) {
      return this.bookings.filter(function (booking) {
        return booking.resource === resource;
      });
    }
  }
});

/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(62)
/* template */
var __vue_template__ = __webpack_require__(70)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/components/resource.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-071bd91e", Component.options)
  } else {
    hotAPI.reload("data-v-071bd91e", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 62 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__booking_vue__ = __webpack_require__(63);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__booking_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__booking_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__directives_dropzone_js__ = __webpack_require__(69);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    id: {
      type: [String, Number],
      required: true
    },
    name: {
      type: String,
      required: true
    },
    bookings: {
      type: Array,
      default: function _default() {
        return [];
      }
    }
  },

  directives: {
    dropzone: __WEBPACK_IMPORTED_MODULE_1__directives_dropzone_js__["a" /* default */]
  },

  data: function data() {
    return {
      dropTarget: false
    };
  },

  inject: ["state"],

  components: {
    "segel-booking": __WEBPACK_IMPORTED_MODULE_0__booking_vue___default.a
  },

  methods: {
    dragenter: function dragenter() {
      this.dropTarget = true;
    },
    dragleave: function dragleave() {
      this.dropTarget = false;
    },
    dragover: function dragover(e) {
      this.dropTarget = true;
      e.dataTransfer.dropEffect = e.altKey ? "copy" : "move";
    },
    drop: function drop() {
      this.dropTarget = false;
    }
  }
});

/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(64)
/* template */
var __vue_template__ = __webpack_require__(68)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "src/components/booking.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-4698bdf9", Component.options)
  } else {
    hotAPI.reload("data-v-4698bdf9", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 64 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_inRange__ = __webpack_require__(19);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_inRange___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_lodash_inRange__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__directives_draggable_js__ = __webpack_require__(65);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__directives_resizable_js__ = __webpack_require__(67);
//
//
//
//
//
//
//
//
//
//





/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    id: {
      type: [String, Number],
      required: true
    },
    resource: {
      type: [String, Number],
      required: true
    },
    user: {
      type: [String, Number]
    },
    start: {
      type: Number,
      required: true
    },
    end: {
      type: Number,
      required: true
    }
  },

  directives: {
    draggable: __WEBPACK_IMPORTED_MODULE_1__directives_draggable_js__["a" /* default */],
    resizable: __WEBPACK_IMPORTED_MODULE_2__directives_resizable_js__["a" /* default */]
  },

  data: function data() {
    return {};
  },

  inject: ["state"],

  computed: {
    isEditable: function isEditable() {
      // Check if bookings should be editable at all.
      if (!this.state.config.editable) {
        return false;
      }

      // If user is logged in, check that user own the booking or is admin.
      if (this.state.user.isAuthenticated() && this.user !== this.state.user.id && this.state.user.isAdmin() !== true) {
        return false;
      }

      // Past and current bookings can't be edited.
      return this.start > this.state.time.current;
    },
    isInView: function isInView() {
      return __WEBPACK_IMPORTED_MODULE_0_lodash_inRange___default()(this.start, this.state.time.start, this.state.time.end) || __WEBPACK_IMPORTED_MODULE_0_lodash_inRange___default()(this.end, this.state.time.start, this.state.time.end) || this.start < this.state.time.start && this.end > this.state.time.start || this.end < this.state.time.end && this.end > this.state.time.end;
    },
    duration: function duration() {
      return this.end - this.start;
    },
    left: function left() {
      return (this.start - this.state.time.start) / this.state.time.duration() * 100;
    },
    width: function width() {
      return this.duration / this.state.time.duration() * 100;
    }
  }
});

/***/ }),
/* 65 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_interactjs__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_lodash_debounce__ = __webpack_require__(20);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_lodash_debounce___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_lodash_debounce__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__helpers_grid__ = __webpack_require__(9);


// Dependencies.





var handleResize = function handleResize(element, vnode) {
  // Set new grid based on current widths.
  // TODO: Calculate height.
  __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).draggable().snap.targets = __WEBPACK_IMPORTED_MODULE_2__helpers_grid__["a" /* default */].create(vnode.context.$root.$el.clientWidth, 36, vnode.context.state.time.steps);
};

/* harmony default export */ __webpack_exports__["a"] = ({
  bind: function bind(element, binding, vnode) {
    window.addEventListener("resize", __WEBPACK_IMPORTED_MODULE_1_lodash_debounce___default()(handleResize.bind(null, element, vnode), 150));

    __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).draggable({
      enabled: binding.value === undefined ? true : binding.value,
      snap: {
        targets: __WEBPACK_IMPORTED_MODULE_2__helpers_grid__["a" /* default */].create(vnode.context.$root.$el.clientWidth, 36, vnode.context.state.time.steps),
        offset: "startCoords"
      },
      restrict: {
        restriction: ".segel-resources"
      },
      onstart: function onstart() {
        element.classList.add("dragging");
      },
      onmove: function onmove(event) {
        // Get previous position from resource data.
        var x = (vnode.context.dragX || 0) + event.dx;
        var y = (vnode.context.dragY || 0) + event.dy;

        // Translate the element.
        element.style.webkitTransform = element.style.transform = "translate(" + x + "px, " + y + "px)";

        // Update the position.
        vnode.context.dragX = parseFloat(x);
        vnode.context.dragY = parseFloat(y);
      },
      onend: function onend() {
        // Reset booking styles.
        element.classList.remove("dragging");
        element.webkitTransform = element.style.transform = "";
        element.style.height = "";
        vnode.context.dragX = 0;
        vnode.context.dragY = 0;
      }
    });
  },
  unbind: function unbind(element, binding, vnode) {
    window.removeEventListener("resize", __WEBPACK_IMPORTED_MODULE_1_lodash_debounce___default()(handleResize.bind(null, element, vnode), 150));
    __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).unset();
  },
  update: function update(element, binding) {
    // Only set status of draggable if it has changed.
    if (binding.value !== binding.oldValue) {
      // Enable/disable depending on passed in boolean. Always true if nothing was passed.
      __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).draggable().enabled = binding.value === undefined ? true : binding.value;
    }
  }
});

/***/ }),
/* 66 */
/***/ (function(module, exports, __webpack_require__) {

var root = __webpack_require__(5);

/**
 * Gets the timestamp of the number of milliseconds that have elapsed since
 * the Unix epoch (1 January 1970 00:00:00 UTC).
 *
 * @static
 * @memberOf _
 * @since 2.4.0
 * @category Date
 * @returns {number} Returns the timestamp.
 * @example
 *
 * _.defer(function(stamp) {
 *   console.log(_.now() - stamp);
 * }, _.now());
 * // => Logs the number of milliseconds it took for the deferred invocation.
 */
var now = function() {
  return root.Date.now();
};

module.exports = now;


/***/ }),
/* 67 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_interactjs__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_lodash_debounce__ = __webpack_require__(20);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_lodash_debounce___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_lodash_debounce__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__helpers_grid__ = __webpack_require__(9);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__store__ = __webpack_require__(1);


// Dependencies.






var handleResize = function handleResize(element, vnode) {
  // Set new grid based on current widths.
  __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).resizable().snap.targets = __WEBPACK_IMPORTED_MODULE_2__helpers_grid__["a" /* default */].create(vnode.context.$root.$el.clientWidth, 35, vnode.context.state.time.steps);
};

/* harmony default export */ __webpack_exports__["a"] = ({
  bind: function bind(element, binding, vnode) {
    window.addEventListener("resize", __WEBPACK_IMPORTED_MODULE_1_lodash_debounce___default()(handleResize.bind(null, element, vnode), 150));

    __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).resizable({
      enabled: binding.value === undefined ? true : binding.value,
      snap: {
        targets: __WEBPACK_IMPORTED_MODULE_2__helpers_grid__["a" /* default */].create(vnode.context.$root.$el.clientWidth, 35, vnode.context.state.time.steps),
        offset: "startCoords"
      },
      edges: {
        top: false,
        bottom: false,
        left: true,
        right: true
      },
      onmove: function onmove(event) {
        // Get previous position from resource data.
        var x = vnode.context.resizeX || 0;
        var y = vnode.context.resizeY || 0;

        // Update the element style.
        element.style.width = event.rect.width + "px";
        element.style.height = event.rect.height + "px";

        // Translate when resizing from top or left edges.
        x += event.deltaRect.left;
        y += event.deltaRect.top;

        // Translate the element.
        element.style.webkitTransform = element.style.transform = "translate(" + x + "px," + y + "px)";

        // Update the position.
        vnode.context.resizeX = parseFloat(x);
        vnode.context.resizeY = parseFloat(y);
      },
      onend: function onend() {
        var start = Math.round((element.offsetLeft + vnode.context.resizeX) / vnode.context.$root.$el.clientWidth * vnode.context.state.time.duration());
        var end = Math.round(element.getBoundingClientRect().width / vnode.context.$root.$el.clientWidth * vnode.context.state.time.duration());

        // Commit changes to store.
        __WEBPACK_IMPORTED_MODULE_3__store__["a" /* default */].bookings.update({
          id: vnode.context.id,
          resource: vnode.context.resource,
          start: __WEBPACK_IMPORTED_MODULE_2__helpers_grid__["a" /* default */].round(vnode.context.state.time.start + start, vnode.context.state.time.duration(), vnode.context.state.time.steps),
          end: __WEBPACK_IMPORTED_MODULE_2__helpers_grid__["a" /* default */].round(vnode.context.state.time.start + start + end, vnode.context.state.time.duration(), vnode.context.state.time.steps)
        });

        // Reset booking styles.
        element.webkitTransform = element.style.transform = "";
        element.style.width = vnode.context.width + "%";
        element.style.height = "";
        vnode.context.resizeX = 0;
        vnode.context.resizeY = 0;
      }
    });
  },
  unbind: function unbind(element, binding, vnode) {
    window.removeEventListener("resize", __WEBPACK_IMPORTED_MODULE_1_lodash_debounce___default()(handleResize.bind(null, element, vnode), 150));
    __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).unset();
  },
  update: function update(element, binding) {
    // Only set status of resizable if it has changed.
    if (binding.value !== binding.oldValue) {
      // Enable/disable depending on passed in boolean. Always true if nothing was passed.
      __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).resizable().enabled = binding.value === undefined ? true : binding.value;
    }
  }
});

/***/ }),
/* 68 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.isInView
    ? _c("li", {
        directives: [
          {
            name: "draggable",
            rawName: "v-draggable",
            value: _vm.isEditable,
            expression: "isEditable"
          },
          {
            name: "resizable",
            rawName: "v-resizable",
            value: _vm.isEditable,
            expression: "isEditable"
          }
        ],
        class: { "segel-booking": true, editable: _vm.isEditable },
        style: { left: _vm.left + "%", width: _vm.width + "%" }
      })
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-4698bdf9", module.exports)
  }
}

/***/ }),
/* 69 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs__ = __webpack_require__(4);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_interactjs___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_interactjs__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__helpers_grid_js__ = __webpack_require__(9);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__store__ = __webpack_require__(1);


// Dependencies.





/* harmony default export */ __webpack_exports__["a"] = ({
  bind: function bind(element, binding, vnode) {
    __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).dropzone({
      ondragenter: function ondragenter() {
        element.classList.add("droppable");
      },
      ondragleave: function ondragleave() {
        element.classList.remove("droppable");
      },
      ondrop: function ondrop(event) {
        // Convert moved pixels to change in timestamp.
        var change = Math.round(event.dragEvent.dx / vnode.context.$root.$el.clientWidth * vnode.context.state.time.duration());

        // Create copy if ALT key is pressed, otherwise edit existing.
        if (event.dragEvent.altKey) {
          // Add new booking to store.
          __WEBPACK_IMPORTED_MODULE_2__store__["a" /* default */].bookings.create({
            resource: vnode.context.id,
            start: event.relatedTarget.__vue__.start + change,
            end: event.relatedTarget.__vue__.end + change
          });
        } else {
          // Commit changes of existing booking to store.
          __WEBPACK_IMPORTED_MODULE_2__store__["a" /* default */].bookings.update({
            id: event.relatedTarget.__vue__.id,
            resource: vnode.context.id,
            start: event.relatedTarget.__vue__.start + change,
            end: event.relatedTarget.__vue__.end + change
          });
        }

        element.classList.remove("droppable");
      }
    }).on("doubletap", function (event) {
      // Only listen to clicks directly on the resource, not bookings.
      if (event.target !== element) {
        event.stopPropagation();
        return;
      }

      var position = Math.round(event.offsetX / vnode.context.$root.$el.clientWidth * vnode.context.state.time.duration());
      var stepSize = vnode.context.state.time.duration() / vnode.context.state.time.steps;
      var start = __WEBPACK_IMPORTED_MODULE_1__helpers_grid_js__["a" /* default */].round(position + vnode.context.state.time.start, vnode.context.state.time.duration(), vnode.context.state.time.steps);

      // Add new booking to store.
      __WEBPACK_IMPORTED_MODULE_2__store__["a" /* default */].bookings.create({
        resource: vnode.context.id,
        start: start,
        end: start + stepSize * 2
      });
    });
  },
  unbind: function unbind(element) {
    __WEBPACK_IMPORTED_MODULE_0_interactjs___default()(element).unset();
  }
});

/***/ }),
/* 70 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("li", { staticClass: "segel-resource" }, [
    _vm._v("\n    " + _vm._s(_vm.name) + "\n\n    "),
    _c(
      "ul",
      {
        directives: [{ name: "dropzone", rawName: "v-dropzone" }],
        staticClass: "segel-bookings"
      },
      _vm._l(_vm.bookings, function(booking) {
        return _c("segel-booking", {
          key: booking.id,
          attrs: {
            id: booking.id,
            resource: _vm.id,
            user: booking.user,
            start: booking.start,
            end: booking.end
          }
        })
      })
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-071bd91e", module.exports)
  }
}

/***/ }),
/* 71 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "ul",
    { staticClass: "segel-resources" },
    _vm._l(_vm.resources, function(resource) {
      return _c("segel-resource", {
        key: resource.id,
        attrs: {
          id: resource.id,
          name: resource.name,
          bookings: _vm.relatedBookings(resource.id)
        }
      })
    })
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-3fea2876", module.exports)
  }
}

/***/ }),
/* 72 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("section", { staticClass: "segel" }, [
    _c(
      "div",
      { staticClass: "segel-container" },
      [
        _c(
          "ul",
          { staticClass: "segel-grid" },
          _vm._l(24, function(n) {
            return _c("li", [_vm._v(" ")])
          })
        ),
        _vm._v(" "),
        _c("segel-indicator"),
        _vm._v(" "),
        _c("segel-ruler"),
        _vm._v(" "),
        _c("segel-resources", {
          attrs: { resources: _vm.resources, bookings: _vm.bookings }
        })
      ],
      1
    )
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-5c886d6e", module.exports)
  }
}

/***/ }),
/* 73 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue__ = __webpack_require__(10);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_vue__);


// Dependencies.



/**
 * Create base events object.
 * This is just a separate Vue instance that only handles emitting and listening to events.
 *
 * @type {Vue}
 */
var Events = new __WEBPACK_IMPORTED_MODULE_0_vue___default.a();

// Return the instance.
/* harmony default export */ __webpack_exports__["a"] = (Events);

/***/ }),
/* 74 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_castArray__ = __webpack_require__(75);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0_lodash_castArray___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0_lodash_castArray__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_lodash_forEach__ = __webpack_require__(76);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_lodash_forEach___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1_lodash_forEach__);


// Dependencies.




/**
 * Iterate over and pass arguments through callback.
 *
 * @param data - Value or array to pass to callback.
 * @param {Function} callback - Function to process each data value.
 * @constructor
 */
var Process = function Process(data, callback) {
  // Pass each object in array to callback.
  __WEBPACK_IMPORTED_MODULE_1_lodash_forEach___default()(__WEBPACK_IMPORTED_MODULE_0_lodash_castArray___default()(data), function (object) {
    callback(object);
  });
};

// Return the instance.
/* harmony default export */ __webpack_exports__["a"] = (Process);

/***/ }),
/* 75 */
/***/ (function(module, exports, __webpack_require__) {

var isArray = __webpack_require__(6);

/**
 * Casts `value` as an array if it's not one.
 *
 * @static
 * @memberOf _
 * @since 4.4.0
 * @category Lang
 * @param {*} value The value to inspect.
 * @returns {Array} Returns the cast array.
 * @example
 *
 * _.castArray(1);
 * // => [1]
 *
 * _.castArray({ 'a': 1 });
 * // => [{ 'a': 1 }]
 *
 * _.castArray('abc');
 * // => ['abc']
 *
 * _.castArray(null);
 * // => [null]
 *
 * _.castArray(undefined);
 * // => [undefined]
 *
 * _.castArray();
 * // => []
 *
 * var array = [1, 2, 3];
 * console.log(_.castArray(array) === array);
 * // => true
 */
function castArray() {
  if (!arguments.length) {
    return [];
  }
  var value = arguments[0];
  return isArray(value) ? value : [value];
}

module.exports = castArray;


/***/ }),
/* 76 */
/***/ (function(module, exports, __webpack_require__) {

var arrayEach = __webpack_require__(77),
    baseEach = __webpack_require__(78),
    castFunction = __webpack_require__(17),
    isArray = __webpack_require__(6);

/**
 * Iterates over elements of `collection` and invokes `iteratee` for each element.
 * The iteratee is invoked with three arguments: (value, index|key, collection).
 * Iteratee functions may exit iteration early by explicitly returning `false`.
 *
 * **Note:** As with other "Collections" methods, objects with a "length"
 * property are iterated like arrays. To avoid this behavior use `_.forIn`
 * or `_.forOwn` for object iteration.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @alias each
 * @category Collection
 * @param {Array|Object} collection The collection to iterate over.
 * @param {Function} [iteratee=_.identity] The function invoked per iteration.
 * @returns {Array|Object} Returns `collection`.
 * @see _.forEachRight
 * @example
 *
 * _.forEach([1, 2], function(value) {
 *   console.log(value);
 * });
 * // => Logs `1` then `2`.
 *
 * _.forEach({ 'a': 1, 'b': 2 }, function(value, key) {
 *   console.log(key);
 * });
 * // => Logs 'a' then 'b' (iteration order is not guaranteed).
 */
function forEach(collection, iteratee) {
  var func = isArray(collection) ? arrayEach : baseEach;
  return func(collection, castFunction(iteratee));
}

module.exports = forEach;


/***/ }),
/* 77 */
/***/ (function(module, exports) {

/**
 * A specialized version of `_.forEach` for arrays without support for
 * iteratee shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns `array`.
 */
function arrayEach(array, iteratee) {
  var index = -1,
      length = array == null ? 0 : array.length;

  while (++index < length) {
    if (iteratee(array[index], index, array) === false) {
      break;
    }
  }
  return array;
}

module.exports = arrayEach;


/***/ }),
/* 78 */
/***/ (function(module, exports, __webpack_require__) {

var baseForOwn = __webpack_require__(11),
    createBaseEach = __webpack_require__(79);

/**
 * The base implementation of `_.forEach` without support for iteratee shorthands.
 *
 * @private
 * @param {Array|Object} collection The collection to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array|Object} Returns `collection`.
 */
var baseEach = createBaseEach(baseForOwn);

module.exports = baseEach;


/***/ }),
/* 79 */
/***/ (function(module, exports, __webpack_require__) {

var isArrayLike = __webpack_require__(16);

/**
 * Creates a `baseEach` or `baseEachRight` function.
 *
 * @private
 * @param {Function} eachFunc The function to iterate over a collection.
 * @param {boolean} [fromRight] Specify iterating from right to left.
 * @returns {Function} Returns the new base function.
 */
function createBaseEach(eachFunc, fromRight) {
  return function(collection, iteratee) {
    if (collection == null) {
      return collection;
    }
    if (!isArrayLike(collection)) {
      return eachFunc(collection, iteratee);
    }
    var length = collection.length,
        index = fromRight ? length : -1,
        iterable = Object(collection);

    while ((fromRight ? index-- : ++index < length)) {
      if (iteratee(iterable[index], index, iterable) === false) {
        break;
      }
    }
    return collection;
  };
}

module.exports = createBaseEach;


/***/ })
/******/ ])["default"];
});