/**
 * ScrollOut.
 * @link https://scroll-out.github.io/
 */
var ScrollOut=function(){"use strict"
function S(e,t,n){return e<t?t:n<e?n:e}function w(e){return+(0<e)-+(e<0)}var t={}
function A(e){return t[e]||(t[e]=e.replace(/([A-Z])/g,n))}function n(e){return"-"+e[0].toLowerCase()}var D=window,E=document.documentElement
function L(e,t){return e&&0!==e.length?e.nodeName?[e]:[].slice.call(e[0].nodeName?e:(t||E).querySelectorAll(e)):[]}var P,b=function(e,t){for(var n in t)e.setAttribute("data-"+A(n),t[n])},H=[]
function O(){H.slice().forEach(function(e){return e()}),P=H.length?requestAnimationFrame(O):0}function x(){}var y="scroll",W="resize",_="addEventListener",N="removeEventListener",T=0
return function(h){var o,c,l,i,p,g,t,s=(h=h||{}).onChange||x,f=h.onHidden||x,u=h.onShown||x,a=h.cssProps?(o=h.cssProps,function(e,t){for(var n in t)(!0===o||o[n])&&e.style.setProperty("--"+A(n),(r=t[n],Math.round(1e4*r)/1e4))
var r}):x,e=h.scrollingElement,m=e?L(e)[0]:D,X=e?L(e)[0]:E,r=++T,v=function(e,t,n){return e[t+r]!==(e[t+r]=JSON.stringify(n))},n=function(){i=!0},d=function(){i&&(i=!1,l=L(h.targets||"[data-scroll]",L(h.scope||X)[0]).map(function(e){return{$:e,ctx:{}}}))
var v=X.clientWidth,d=X.clientHeight,e=w(-p+(p=X.scrollLeft||D.pageXOffset)),t=w(-g+(g=X.scrollTop||D.pageYOffset)),n=X.scrollLeft/(X.scrollWidth-v||1),r=X.scrollTop/(X.scrollHeight-d||1)
c={scrollDirX:e,scrollDirY:t,scrollPercentX:n,scrollPercentY:r},l.forEach(function(e){for(var t=e.$,n=t,r=0,o=0;r+=n.offsetLeft,o+=n.offsetTop,(n=n.offsetParent)&&n!==m;);var i=t.clientWidth,c=t.clientHeight,l=(S(r+i,p,p+v)-S(r,p,p+v))/i,s=(S(o+c,g,g+d)-S(o,g,g+d))/c,f=S((p-(i/2+r-v/2))/(v/2),-1,1),u=S((g-(c/2+o-d/2))/(d/2),-1,1),a=+(h.offset?h.offset<=g:(h.threshold||0)<l*s)
e.ctx={elementHeight:c,elementWidth:i,intersectX:1===l?0:w(r-p),intersectY:1===s?0:w(o-g),offsetX:r,offsetY:o,viewportX:f,viewportY:u,visible:a,visibleX:l,visibleY:s}})},Y=(t=function(){if(l){var e={scrollDirX:c.scrollDirX,scrollDirY:c.scrollDirY}
v(X,"_SA",e)&&b(X,e),v(X,"_S",c)&&a(X,c)
for(var t=l.length-1;-1<t;t--){var n=l[t],r=n.$,o=n.ctx,i=o.visible
v(r,"_SO",o)&&a(r,o),v(r,"_SV",i)&&(b(r,{scroll:i?"in":"out"}),s(r,o,X),(i?u:f)(r,o,X)),i&&h.once&&l.splice(t,1)}}},H.push(t),P||O(),function(){!(H=H.filter(function(e){return e!==t})).length&&P&&(P=0,cancelAnimationFrame(P))})
return n(),d(),D[_](W,n),m[_](y,d),{index:n,teardown:function(){Y(),D[N](W,n),m[N](y,d)},update:d}}}();

/*! ScrollMagic v2.0.6 | (c) 2018 Jan Paepke (@janpaepke) | license & info: http://scrollmagic.io */
/* https://raw.githubusercontent.com/janpaepke/ScrollMagic/master/scrollmagic/minified/plugins/debug.addIndicators.min.js */
!function(e,r){"function"==typeof define&&define.amd?define(["ScrollMagic"],r):r("object"==typeof exports?require("scrollmagic"):e.ScrollMagic||e.jQuery&&e.jQuery.ScrollMagic)}(this,function(e){"use strict";var r="0.85em",t="9999",i=15,o=e._util,n=0;e.Scene.extend(function(){var e,r=this;r.addIndicators=function(t){if(!e){var i={name:"",indent:0,parent:void 0,colorStart:"green",colorEnd:"red",colorTrigger:"blue"};t=o.extend({},i,t),n++,e=new s(r,t),r.on("add.plugin_addIndicators",e.add),r.on("remove.plugin_addIndicators",e.remove),r.on("destroy.plugin_addIndicators",r.removeIndicators),r.controller()&&e.add()}return r},r.removeIndicators=function(){return e&&(e.remove(),this.off("*.plugin_addIndicators"),e=void 0),r}}),e.Controller.addOption("addIndicators",!1),e.Controller.extend(function(){var r=this,t=r.info(),n=t.container,s=t.isDocument,d=t.vertical,a={groups:[]};this._indicators=a;var g=function(){a.updateBoundsPositions()},p=function(){a.updateTriggerGroupPositions()};return n.addEventListener("resize",p),s||(window.addEventListener("resize",p),window.addEventListener("scroll",p)),n.addEventListener("resize",g),n.addEventListener("scroll",g),this._indicators.updateBoundsPositions=function(e){for(var r,t,s,g=e?[o.extend({},e.triggerGroup,{members:[e]})]:a.groups,p=g.length,u={},c=d?"left":"top",l=d?"width":"height",f=d?o.get.scrollLeft(n)+o.get.width(n)-i:o.get.scrollTop(n)+o.get.height(n)-i;p--;)for(s=g[p],r=s.members.length,t=o.get[l](s.element.firstChild);r--;)u[c]=f-t,o.css(s.members[r].bounds,u)},this._indicators.updateTriggerGroupPositions=function(e){for(var t,g,p,u,c,l=e?[e]:a.groups,f=l.length,m=s?document.body:n,h=s?{top:0,left:0}:o.get.offset(m,!0),v=d?o.get.width(n)-i:o.get.height(n)-i,b=d?"width":"height",G=d?"Y":"X";f--;)t=l[f],g=t.element,p=t.triggerHook*r.info("size"),u=o.get[b](g.firstChild.firstChild),c=p>u?"translate"+G+"(-100%)":"",o.css(g,{top:h.top+(d?p:v-t.members[0].options.indent),left:h.left+(d?v-t.members[0].options.indent:p)}),o.css(g.firstChild.firstChild,{"-ms-transform":c,"-webkit-transform":c,transform:c})},this._indicators.updateTriggerGroupLabel=function(e){var r="trigger"+(e.members.length>1?"":" "+e.members[0].options.name),t=e.element.firstChild.firstChild,i=t.textContent!==r;i&&(t.textContent=r,d&&a.updateBoundsPositions())},this.addScene=function(t){this._options.addIndicators&&t instanceof e.Scene&&t.controller()===r&&t.addIndicators(),this.$super.addScene.apply(this,arguments)},this.destroy=function(){n.removeEventListener("resize",p),s||(window.removeEventListener("resize",p),window.removeEventListener("scroll",p)),n.removeEventListener("resize",g),n.removeEventListener("scroll",g),this.$super.destroy.apply(this,arguments)},r});var s=function(e,r){var t,i,s=this,a=d.bounds(),g=d.start(r.colorStart),p=d.end(r.colorEnd),u=r.parent&&o.get.elements(r.parent)[0];r.name=r.name||n,g.firstChild.textContent+=" "+r.name,p.textContent+=" "+r.name,a.appendChild(g),a.appendChild(p),s.options=r,s.bounds=a,s.triggerGroup=void 0,this.add=function(){i=e.controller(),t=i.info("vertical");var r=i.info("isDocument");u||(u=r?document.body:i.info("container")),r||"static"!==o.css(u,"position")||o.css(u,{position:"relative"}),e.on("change.plugin_addIndicators",l),e.on("shift.plugin_addIndicators",c),G(),h(),setTimeout(function(){i._indicators.updateBoundsPositions(s)},0)},this.remove=function(){if(s.triggerGroup){if(e.off("change.plugin_addIndicators",l),e.off("shift.plugin_addIndicators",c),s.triggerGroup.members.length>1){var r=s.triggerGroup;r.members.splice(r.members.indexOf(s),1),i._indicators.updateTriggerGroupLabel(r),i._indicators.updateTriggerGroupPositions(r),s.triggerGroup=void 0}else b();m()}};var c=function(){h()},l=function(e){"triggerHook"===e.what&&G()},f=function(){var e=i.info("vertical");o.css(g.firstChild,{"border-bottom-width":e?1:0,"border-right-width":e?0:1,bottom:e?-1:r.indent,right:e?r.indent:-1,padding:e?"0 8px":"2px 4px"}),o.css(p,{"border-top-width":e?1:0,"border-left-width":e?0:1,top:e?"100%":"",right:e?r.indent:"",bottom:e?"":r.indent,left:e?"":"100%",padding:e?"0 8px":"2px 4px"}),u.appendChild(a)},m=function(){a.parentNode.removeChild(a)},h=function(){a.parentNode!==u&&f();var r={};r[t?"top":"left"]=e.triggerPosition(),r[t?"height":"width"]=e.duration(),o.css(a,r),o.css(p,{display:e.duration()>0?"":"none"})},v=function(){var n=d.trigger(r.colorTrigger),a={};a[t?"right":"bottom"]=0,a[t?"border-top-width":"border-left-width"]=1,o.css(n.firstChild,a),o.css(n.firstChild.firstChild,{padding:t?"0 8px 3px 8px":"3px 4px"}),document.body.appendChild(n);var g={triggerHook:e.triggerHook(),element:n,members:[s]};i._indicators.groups.push(g),s.triggerGroup=g,i._indicators.updateTriggerGroupLabel(g),i._indicators.updateTriggerGroupPositions(g)},b=function(){i._indicators.groups.splice(i._indicators.groups.indexOf(s.triggerGroup),1),s.triggerGroup.element.parentNode.removeChild(s.triggerGroup.element),s.triggerGroup=void 0},G=function(){var r=e.triggerHook(),t=1e-4;if(!(s.triggerGroup&&Math.abs(s.triggerGroup.triggerHook-r)<t)){for(var o,n=i._indicators.groups,d=n.length;d--;)if(o=n[d],Math.abs(o.triggerHook-r)<t)return s.triggerGroup&&(1===s.triggerGroup.members.length?b():(s.triggerGroup.members.splice(s.triggerGroup.members.indexOf(s),1),i._indicators.updateTriggerGroupLabel(s.triggerGroup),i._indicators.updateTriggerGroupPositions(s.triggerGroup))),o.members.push(s),s.triggerGroup=o,void i._indicators.updateTriggerGroupLabel(o);if(s.triggerGroup){if(1===s.triggerGroup.members.length)return s.triggerGroup.triggerHook=r,void i._indicators.updateTriggerGroupPositions(s.triggerGroup);s.triggerGroup.members.splice(s.triggerGroup.members.indexOf(s),1),i._indicators.updateTriggerGroupLabel(s.triggerGroup),i._indicators.updateTriggerGroupPositions(s.triggerGroup),s.triggerGroup=void 0}v()}}},d={start:function(e){var r=document.createElement("div");r.textContent="start",o.css(r,{position:"absolute",overflow:"visible","border-width":0,"border-style":"solid",color:e,"border-color":e});var t=document.createElement("div");return o.css(t,{position:"absolute",overflow:"visible",width:0,height:0}),t.appendChild(r),t},end:function(e){var r=document.createElement("div");return r.textContent="end",o.css(r,{position:"absolute",overflow:"visible","border-width":0,"border-style":"solid",color:e,"border-color":e}),r},bounds:function(){var e=document.createElement("div");return o.css(e,{position:"absolute",overflow:"visible","white-space":"nowrap","pointer-events":"none","font-size":r}),e.style.zIndex=t,e},trigger:function(e){var i=document.createElement("div");i.textContent="trigger",o.css(i,{position:"relative"});var n=document.createElement("div");o.css(n,{position:"absolute",overflow:"visible","border-width":0,"border-style":"solid",color:e,"border-color":e}),n.appendChild(i);var s=document.createElement("div");return o.css(s,{position:"fixed",overflow:"visible","white-space":"nowrap","pointer-events":"none","font-size":r}),s.style.zIndex=t,s.appendChild(n),s}}});

/**
 * Parallax.
 */
// ScrollOut({
// 	targets: '.section.parallax .parallax-image',
// 	cssProps: {
// 		viewportY: true,
// 	},
// });

// /**
//  * Fade/move animations.
//  */
// ScrollOut({
// 	targets: [
// 		'.section.fadein',
// 		'.section.fadeinup',
// 		'.section.fadeindown',
// 		'.section.fadeinleft',
// 		'.section.fadeinright'
// 	],
// 	onShown: function(el, ctx, doc) {
// 		// If scrolling up into view.
// 		if ( 1 === ctx.intersectY ) {
// 			el.classList.add( 'animate' );
// 		}
// 	},
// 	onHidden: function(el, ctx, doc) {
// 		// If scrolling down out of view.
// 		if ( 1 === ctx.intersectY ) {
// 			el.classList.remove( 'animate' );
// 		}
// 	},
// 	onChange: function(el, ctx, doc) {
// 		// If in view, or already scrolled passed, mostly when loading the page.
// 		var views = ["-1","0"];
// 		if ( views.indexOf( ctx.intersectY ) ) {
// 			el.classList.add( 'animate' );
// 		}
// 	}
// });

( function( document, $, undefined ) {

	var $window    = $(window);
	var controller = new ScrollMagic.Controller();

	// Parallax.
	// $( '.section.parallax' ).each( function(e){

	// 	var $section = $(this);
	// 	var $image   = $section.find( '.parallax-image' );

	// 	// Parallax.
	// 	var parallaxScene = new ScrollMagic.Scene({
	// 		triggerElement: $section,
	// 		triggerHook: 'onEnter',
	// 		duration: getDuration(),
	// 	})
	// 	.on( 'progress', function(e) {
	// 		var distance = '-' + e.progress * 20 + '%';
	// 		// jQuery 1.8+ handles browser prefixes.
	// 		$image.css( 'transform', 'translateY(' + distance + ')' );
	// 	})
	// 	.addTo(controller);

	// 	function getDuration() {
	// 		return $window.height() + $section.height();
	// 	}
	// 	function updateDuration() {
	// 		parallaxScene.offset( getDuration() );
	// 	}
	// 	$window.on( 'resize orientationchange', function(){
	// 		updateDuration();
	// 	});

	// });

	// get all slides
	// var sections = document.querySelectorAll( 'section.parallax' );

	// // create scene for every slide
	// for (var i=0; i < sections.length; i++) {
	// 	new ScrollMagic.Scene({
	// 		triggerElement: sections[i],
	// 		triggerHook: 'onLeave',
	// 	})
	// 	.setPin(sections[i])
	// 	.addIndicators() // add indicators (requires plugin)
	// 	.addTo(controller);
	// }

	// Fades motion.

	// Fade In.
	$( '.section.fadein' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInUpScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.addIndicators()
		.on( 'progress', function(e) {
			$content.css({
				'opacity': e.progress,
			});
		})
		.on( 'enter', function(e) {
			$content.addClass( 'enter' );
		})
		.on( 'end', function(e) {
			$content.addClass( 'end' );
		})
		.addTo(controller);

		// fadeInUpScene.setClassToggle( $content, 'blahhhhh' );

	});

	// Fade In Up.
	$( '.section.fadeinup' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInUpScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.addIndicators()
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateY(' + transform + ')',
			});
		})
		.addTo(controller);

		// fadeInUpScene.setClassToggle( $content, 'blahhhhh' );

	});

	// Fade In Down.
	$( '.section.fadeindown' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInUpScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.addIndicators()
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateY(-' + transform + ')',
			});
		})
		.addTo(controller);

		// fadeInUpScene.setClassToggle( $content, 'blahhhhh' );

	});

	// Fade In Left.
	$( '.section.fadeinleft' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInUpScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.addIndicators()
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateX(' + transform + ')',
			});
		})
		.addTo(controller);

	});

	// Fade In Right.
	$( '.section.fadeinright' ).each( function(e){

		var $section = $(this);
		var $content = $section.find( '.section-content' );

		var fadeInUpScene = new ScrollMagic.Scene({
			triggerElement: $section[0],
			triggerHook: .8, // 20% up the page.
			duration: '30%',
		})
		.addIndicators()
		.on( 'progress', function(e) {
			var transform = ( 48 - ( e.progress * 48 ) ) + 'px';
			$content.css({
				'opacity': e.progress,
				'transform': 'translateX(-' + transform + ')',
			});
		})
		.addTo(controller);

	});

})( document, jQuery );
