(self.webpackChunk=self.webpackChunk||[]).push([[773],{368:(e,t,n)=>{"use strict";var i=n(306);var r=n(496),a=n.n(r);var s={};s.create=function(e,t,n){return i={x:e/n,y:t},[a().createSnapGrid(i)];var i};const o=s;var l={grid:null,size:null,timestamps:null},d={x:0,y:0},c=null;l.resource=function(e){a().isSet(e)||(a()(e).dropzone({listeners:{dragenter:function(e){e.target.classList.add("droppable")},dragleave:function(e){e.target.classList.remove("droppable")},drop:function(e){var t=e.target,n=e.relatedTarget,i={id:parseInt(n.dataset.id),user_id:parseInt(n.dataset.user),start_time:parseInt(n.dataset.start),end_time:parseInt(n.dataset.end),resource_id:parseInt(t.dataset.id)},r=Math.round((n.getBoundingClientRect().left-t.getBoundingClientRect().left)/t.clientWidth*l.timestamps.duration+l.timestamps.start),a=i.end_time-(i.start_time-r);i.start_time=r,i.end_time=a;var s=document.createElement("div");s.classList.add("progress"),n.appendChild(s),HYDROFON.Segel.component.call(e.dragEvent.altKey?"createBooking":"updateBooking",i),n.classList.remove("droppable"),d={x:0,y:0}}}}),a()(e).on("doubletap",(function(e){if(e.target.classList.contains("segel-resource")||e.target.classList.contains("segel-bookings")){var t=e.target.classList.contains("segel-resource")?e.target:e.target.parentNode,n=Math.round(e.offsetX/t.clientWidth*l.timestamps.duration),i=Math.round(l.timestamps.duration/l.steps),r=n+l.timestamps.start,a=r+2*i;HYDROFON.Segel.component.call("createBooking",{resource_id:parseInt(t.dataset.id),start_time:r,end_time:a})}})))},l.booking=function(e){!a().isSet(e)&&e.classList.contains("editable")?(a()(e).draggable({listeners:{start:function(e){e.altKey&&(c=e.target.cloneNode(!1),e.target.parentNode.appendChild(c)),e.target.classList.add("is-dragging")},move:function(e){d.x+=e.dx,d.y+=e.dy,e.target.style.transform="translate(".concat(d.x,"px, ").concat(d.y,"px)")},end:function(e){e.target.classList.remove("is-dragging"),c&&(e.altKey?l.booking(c):c.parentNode.removeChild(c),c=null)}},modifiers:[a().modifiers.restrict({restriction:".segel-resources"}),a().modifiers.snap({targets:l.grid,offset:"startCoords"})]}),a()(e).resizable({edges:{top:!1,bottom:!1,left:".segel-resize-handle__left",right:".segel-resize-handle__right"},listeners:{start:function(e){e.target.classList.add("is-resizing")},move:function(e){var t=e.target.dataset,n=t.x,i=t.y;n=(parseFloat(n)||0)+e.deltaRect.left,i=(parseFloat(i)||0)+e.deltaRect.top,Object.assign(e.target.style,{width:"".concat(e.rect.width,"px"),height:"".concat(e.rect.height,"px"),transform:"translate(".concat(n,"px, ").concat(i,"px)")}),Object.assign(e.target.dataset,{x:n,y:i})},end:function(e){var t=e.target,n=t.parentNode,i=t.dataset.x;i=parseFloat(i)||0;var r={id:parseInt(t.dataset.id),user_id:parseInt(t.dataset.user),start_time:parseInt(t.dataset.start),end_time:parseInt(t.dataset.end),resource_id:parseInt(n.dataset.id)},a=Math.round((t.getBoundingClientRect().left-n.getBoundingClientRect().left)/n.clientWidth*l.timestamps.duration+l.timestamps.start),s=Math.round(t.getBoundingClientRect().width/n.clientWidth*l.timestamps.duration+a);r.start_time=a,r.end_time=s;var o=document.createElement("div");o.classList.add("progress"),t.appendChild(o),HYDROFON.Segel.component.call("updateBooking",r),e.target.classList.remove("is-resizing")}},modifiers:[a().modifiers.restrict({restriction:".segel-resources"}),a().modifiers.restrictSize(l.size),a().modifiers.snap({targets:l.grid,offset:"startCoords"})]}),a()(e).on("doubletap",(function(e){HYDROFON.Segel.component.call("deleteBooking",{id:parseInt(e.target.dataset.id)})}))):e.classList.contains("editable")||a()(e).unset()};const u=l;function p(e,t){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=function(e,t){if(!e)return;if("string"==typeof e)return m(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return m(e,t)}(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var i=0,r=function(){};return{s:r,n:function(){return i>=e.length?{done:!0}:{done:!1,value:e[i++]}},e:function(e){throw e},f:r}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var a,s=!0,o=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return s=e.done,e},e:function(e){o=!0,a=e},f:function(){try{s||null==n.return||n.return()}finally{if(o)throw a}}}}function m(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,i=new Array(t);n<t;n++)i[n]=e[n];return i}var g=n(667);n(691);i.Z.data("resourceTree",(function(e){var t,n,i;return{visible:null===(t=e.visible)||void 0===t||t,expanded:null!==(n=e.expanded)&&void 0!==n?n:[],selected:null!==(i=e.selected)&&void 0!==i?i:[],date:e.date,datepicker:null,debounceExpanded:null,debounceSelected:null,multipleSelect:function(e){if(!e.target.classList.contains("hidden")&&e.altKey){var t=e.target.closest(".resourcelist-category"),n=Array.from(t.querySelectorAll(":scope > .resourcelist-children > .resourcelist-resource input")),i=n.filter((function(e){return e.checked}));if(n.length===i.length){var r=i.map((function(e){return e.value}));this.selected=this.selected.filter((function(e){return-1===r.indexOf(e)}))}else{var a=n.filter((function(e){return!e.checked})).map((function(e){return e.value}));this.selected=this.selected.concat(a)}e.preventDefault()}},init:function(){var e=this;this.$watch("expanded",(function(t){HYDROFON.Segel.initialized&&(clearTimeout(e.debounceExpanded),e.debounceExpanded=setTimeout((function(){HYDROFON.Segel.expanded=t}),1e3))})),this.$watch("selected",(function(t){clearTimeout(e.debounceSelected),e.debounceSelected=setTimeout((function(){HYDROFON.Segel.initialized?HYDROFON.Segel.resources=t:e.$refs.form.submit()}),1e3)})),window.livewire.on("dateChanged",(function(t){e.date=t.date,e.datepicker.setDate(e.date,!1)})),this.datepicker=flatpickr(this.$refs.datepicker,{allowInput:!0,altFormat:"Y-m-d",dateFormat:"Y-m-d",time_24hr:!0,onChange:function(e,t,n){var i=new Date(e[0].getTime()-60*e[0].getTimezoneOffset()*1e3).getTime()/1e3;if(HYDROFON.Segel.initialized){var r={start:i,end:i+HYDROFON.Segel.component.data.timestamps.duration,duration:HYDROFON.Segel.component.data.timestamps.duration};HYDROFON.Segel.component.call("setTimestamps",r)}else this.$refs.form.submit()}})}}})),i.Z.data("segel",(function(e){return{start:e.start,duration:e.duration,steps:e.steps,current:0,init:function(){var e=this;setInterval((function(){e.current=Math.round(((new Date).getTime()-60*(new Date).getTimezoneOffset()*1e3)/1e3)}),1e3),this.setupTimestamps(),this.calculateGrid(),this.setupInteractions(),this.$watch("start, duration, steps",this.setupTimestamps)},base:(t={},n="x-on:resize.window.debounce.500",i=function(){this.handleResize()},n in t?Object.defineProperty(t,n,{value:i,enumerable:!0,configurable:!0,writable:!0}):t[n]=i,t),setupTimestamps:function(){u.steps=this.steps,u.timestamps={start:this.start,duration:this.duration}},setupInteractions:function(){this.$el.querySelectorAll(".segel-resource").forEach(u.resource),this.$el.querySelectorAll(".segel-booking").forEach(u.booking)},calculateGrid:function(){u.grid=o.create(this.$el.clientWidth,41,this.steps),u.size={min:{width:this.$el.clientWidth/this.steps,height:1},max:{width:this.$el.clientWidth,height:41}}},handleResize:function(){this.calculateGrid();var e,t=p(this.$el.querySelectorAll(".segel-booking"));try{for(t.s();!(e=t.n()).done;){var n=e.value;if(a().isSet(n)){var i=a()(n).draggable(),r=a()(n).resizable();i.modifiers[1].options.targets=u.grid,r.modifiers[2].options.targets=u.grid,r.modifiers[1].options.min=u.size.min,r.modifiers[1].options.max=u.size.max}}}catch(e){t.e(e)}finally{t.f()}}};var t,n,i})),window.Alpine=i.Z,i.Z.start(),window.interact=a(),window.flatpickr=g.Z},691:()=>{HYDROFON.Segel={_component:null,_element:null,initialized:!1,set component(e){this._component=e,this._element=e.el,this.initialized=!0},get component(){return this._component},get element(){return this._element},set resources(e){HYDROFON.Segel.component.call("setResources",e)},set expanded(e){HYDROFON.Segel.component.call("setExpanded",e)}}},580:()=>{}},e=>{var t=t=>e(e.s=t);e.O(0,[170,898],(()=>(t(368),t(580))));e.O()}]);