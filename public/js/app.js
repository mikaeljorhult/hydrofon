(window.webpackJsonp=window.webpackJsonp||[]).push([[0],{0:function(e,t,n){n("bUC5"),n("pyCd"),e.exports=n("YT72")},YT72:function(e,t){},bUC5:function(e,t,n){"use strict";n.r(t);n("3yRE");var a=n("UBTA"),r=n.n(a),i=n("zwY0"),s=n.n(i);var o={};o.create=function(e,t,n){return a={x:e/n,y:t},[r.a.createSnapGrid(a)];var a};var d=o,l={},c={x:0,y:0},g=null;l.resource=function(e){r.a.isSet(e)||(r()(e).dropzone({listeners:{dragenter:function(e){e.target.classList.add("droppable")},dragleave:function(e){e.target.classList.remove("droppable")},drop:function(e){var t=e.target,n=e.relatedTarget,a={id:parseInt(n.dataset.id),user_id:parseInt(n.dataset.user),start_time:parseInt(n.dataset.start),end_time:parseInt(n.dataset.end),resource_id:parseInt(t.dataset.id)},r=Math.round((n.getBoundingClientRect().left-t.getBoundingClientRect().left)/t.clientWidth*HYDROFON.Segel.component.data.timestamps.duration+HYDROFON.Segel.component.data.timestamps.start),i=a.end_time-(a.start_time-r);a.start_time=r,a.end_time=i;var s=document.createElement("div");s.classList.add("progress"),n.appendChild(s),HYDROFON.Segel.component.call(e.dragEvent.altKey?"createBooking":"updateBooking",a),n.classList.remove("droppable"),c={x:0,y:0}}}}),r()(e).on("doubletap",(function(e){if("segel-resource"===e.target.className||"segel-bookings"===e.target.className){var t="segel-resource"===e.target.className?e.target:e.target.parentNode,n=Math.round(e.offsetX/t.clientWidth*HYDROFON.Segel.component.data.timestamps.duration),a=Math.round(HYDROFON.Segel.component.data.timestamps.duration/HYDROFON.Segel.component.data.steps),r=n+HYDROFON.Segel.component.data.timestamps.start,i=r+2*a;HYDROFON.Segel.component.call("createBooking",{resource_id:parseInt(t.dataset.id),start_time:r,end_time:i})}})))},l.booking=function(e){!r.a.isSet(e)&&e.classList.contains("editable")?(r()(e).draggable({listeners:{start:function(e){e.altKey&&(g=e.target.cloneNode(!1),e.target.parentNode.appendChild(g)),e.target.classList.add("is-dragging")},move:function(e){c.x+=e.dx,c.y+=e.dy,e.target.style.transform="translate(".concat(c.x,"px, ").concat(c.y,"px)")},end:function(e){e.target.classList.remove("is-dragging"),g&&(e.altKey?HYDROFON.Segel.interactions.booking(g):g.parentNode.removeChild(g),g=null)}},modifiers:[r.a.modifiers.restrict({restriction:".segel-resources"}),r.a.modifiers.snap({targets:HYDROFON.Segel.grid,offset:"startCoords"})]}),r()(e).resizable({edges:{top:!1,bottom:!1,left:".segel-resize-handle__left",right:".segel-resize-handle__right"},listeners:{start:function(e){e.target.classList.add("is-resizing")},move:function(e){var t=e.target.dataset,n=t.x,a=t.y;n=(parseFloat(n)||0)+e.deltaRect.left,a=(parseFloat(a)||0)+e.deltaRect.top,Object.assign(e.target.style,{width:"".concat(e.rect.width,"px"),height:"".concat(e.rect.height,"px"),transform:"translate(".concat(n,"px, ").concat(a,"px)")}),Object.assign(e.target.dataset,{x:n,y:a})},end:function(e){var t=e.target,n=t.parentNode,a=t.dataset.x;a=parseFloat(a)||0;var r={id:parseInt(t.dataset.id),user_id:parseInt(t.dataset.user),start_time:parseInt(t.dataset.start),end_time:parseInt(t.dataset.end),resource_id:parseInt(n.dataset.id)},i=Math.round((t.getBoundingClientRect().left-n.getBoundingClientRect().left)/n.clientWidth*HYDROFON.Segel.component.data.timestamps.duration+HYDROFON.Segel.component.data.timestamps.start),s=Math.round(t.getBoundingClientRect().width/n.clientWidth*HYDROFON.Segel.component.data.timestamps.duration+i);r.start_time=i,r.end_time=s;var o=document.createElement("div");o.classList.add("progress"),t.appendChild(o),HYDROFON.Segel.component.call("updateBooking",r),e.target.classList.remove("is-resizing")}},modifiers:[r.a.modifiers.restrict({restriction:".segel-resources"}),r.a.modifiers.restrictSize(HYDROFON.Segel.size),r.a.modifiers.snap({targets:HYDROFON.Segel.grid,offset:"startCoords"})]}),r()(e).on("doubletap",(function(e){HYDROFON.Segel.component.call("deleteBooking",{id:parseInt(e.target.dataset.id)})}))):e.classList.contains("editable")||r()(e).unset()};var u=l,p=n("yNSA");function m(e,t){var n;if("undefined"==typeof Symbol||null==e[Symbol.iterator]){if(Array.isArray(e)||(n=function(e,t){if(!e)return;if("string"==typeof e)return f(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return f(e,t)}(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var a=0,r=function(){};return{s:r,n:function(){return a>=e.length?{done:!0}:{done:!1,value:e[a++]}},e:function(e){throw e},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,s=!0,o=!1;return{s:function(){n=e[Symbol.iterator]()},n:function(){var e=n.next();return s=e.done,e},e:function(e){o=!0,i=e},f:function(){try{s||null==n.return||n.return()}finally{if(o)throw i}}}}function f(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,a=new Array(t);n<t;n++)a[n]=e[n];return a}HYDROFON.Segel={_component:null,_element:null,initialized:!1,grid:null,size:null,interactions:u,set component(e){this._component=e,this._element=e.el.el,this.calculateGrid(),this.initialized=!0},get component(){return this._component},get element(){return this._element},set resources(e){this._debounceResources(e)},set expanded(e){this._debounceExpanded(e)},_debounceExpanded:Object(p.a)((function(e){HYDROFON.Segel.component.call("setExpanded",e)}),1e3),_debounceResources:Object(p.a)((function(e){HYDROFON.Segel.component.call("setResources",e)}),1e3),calculateGrid:function(){this.grid=d.create(this.element.clientWidth,40,this.component.data.steps),this.size={min:{width:this.element.clientWidth/this.component.data.steps,height:1},max:{width:this.element.clientWidth,height:40}}},handleResize:function(){this.calculateGrid();var e,t=m(this.element.querySelectorAll(".segel-booking"));try{for(t.s();!(e=t.n()).done;){var n=e.value,a=interact(n).draggable(),r=interact(n).resizable();a.modifiers[1].options.targets=this.grid,r.modifiers[2].options.targets=this.grid,r.modifiers[1].options.min=this.size.min,r.modifiers[1].options.max=this.size.max}}catch(e){t.e(e)}finally{t.f()}}},window.interact=r.a,window.flatpickr=s.a},pyCd:function(e,t){}},[[0,1,2]]]);