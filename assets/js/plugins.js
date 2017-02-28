// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function noop() {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.

(function(a){a.fn.extend({tabify:function(e){function c(b){hash=a(b).find("a").attr("href");return hash=hash.substring(0,hash.length-4)}function f(b){a(b).addClass("active");a(c(b)).show();a(b).siblings("li").each(function(){a(this).removeClass("active");a(c(this)).hide()})}return this.each(function(){function b(){location.hash&&a(d).find("a[href="+location.hash+"]").length>0&&f(a(d).find("a[href="+location.hash+"]").parent())}var d=this,g={ul:a(d)};a(this).find("li a").each(function(){a(this).attr("href", a(this).attr("href")+"-tab")});location.hash&&b();setInterval(b,100);a(this).find("li").each(function(){a(this).hasClass("active")?a(c(this)).show():a(c(this)).hide()});e&&e(g)})}})})(jQuery);


/*! Copyright (c) 2011 Piotr Rochala (http://rocha.la)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 0.6.5
 * 
 */
(function(d){jQuery.fn.extend({slimScroll:function(m){var a=d.extend({wheelStep:20,width:"auto",height:"250px",size:"7px",color:"#000",position:"right",distance:"1px",start:"top",opacity:0.4,alwaysVisible:!1,disableFadeOut:!1,railVisible:!1,railColor:"#333",railOpacity:"0.2",railClass:"slimScrollRail",barClass:"slimScrollBar",wrapperClass:"slimScrollDiv",allowPageScroll:!1,scroll:0,touchScrollStep:200},m);this.each(function(){function e(i,d,e){var f=i;d&&(f=parseInt(c.css("top"))+i*parseInt(a.wheelStep)/
100*c.outerHeight(),d=b.outerHeight()-c.outerHeight(),f=Math.min(Math.max(f,0),d),c.css({top:f+"px"}));g=parseInt(c.css("top"))/(b.outerHeight()-c.outerHeight());f=g*(b[0].scrollHeight-b.outerHeight());e&&(f=i,i=f/b[0].scrollHeight*b.outerHeight(),c.css({top:i+"px"}));b.scrollTop(f);n();j()}function t(){p=Math.max(b.outerHeight()/b[0].scrollHeight*b.outerHeight(),m);c.css({height:p+"px"})}function n(){t();clearTimeout(u);g==~~g&&(k=a.allowPageScroll,v!=g&&b.trigger("slimscroll",0==~~g?"top":"bottom"));
v=g;p>=b.outerHeight()?k=!0:(c.stop(!0,!0).fadeIn("fast"),a.railVisible&&h.stop(!0,!0).fadeIn("fast"))}function j(){a.alwaysVisible||(u=setTimeout(function(){if((!a.disableFadeOut||!l)&&!q&&!r)c.fadeOut("slow"),h.fadeOut("slow")},1E3))}var l,q,r,u,w,p,g,v,m=30,k=!1,b=d(this);if(b.parent().hasClass("slimScrollDiv"))scroll&&(c=b.parent().find(".slimScrollBar"),h=b.parent().find(".slimScrollRail"),e(b.scrollTop()+parseInt(scroll),!1,!0));else{var y=d("<div></div>").addClass(a.wrapperClass).css({position:"relative",
overflow:"hidden",width:a.width,height:a.height});b.css({overflow:"hidden",width:a.width,height:a.height});var h=d("<div></div>").addClass(a.railClass).css({width:a.size,height:"100%",position:"absolute",top:0,display:a.alwaysVisible&&a.railVisible?"block":"none","border-radius":a.size,background:a.railColor,opacity:a.railOpacity,zIndex:90}),c=d("<div></div>").addClass(a.barClass).css({background:a.color,width:a.size,position:"absolute",top:0,opacity:a.opacity,display:a.alwaysVisible?"block":"none",
"border-radius":a.size,BorderRadius:a.size,MozBorderRadius:a.size,WebkitBorderRadius:a.size,zIndex:99}),x="right"==a.position?{right:a.distance}:{left:a.distance};h.css(x);c.css(x);b.wrap(y);b.parent().append(c);b.parent().append(h);c.draggable({axis:"y",containment:"parent",start:function(){r=!0},stop:function(){r=!1;j()},drag:function(){e(0,d(this).position().top,!1)}});h.hover(function(){n()},function(){j()});c.hover(function(){q=!0},function(){q=!1});b.hover(function(){l=!0;n();j()},function(){l=
!1;j()});b.bind("touchstart",function(a){a.originalEvent.touches.length&&(w=a.originalEvent.touches[0].pageY)});b.bind("touchmove",function(b){b.originalEvent.preventDefault();b.originalEvent.touches.length&&e((w-b.originalEvent.touches[0].pageY)/a.touchScrollStep,!0)});var s=function(a){if(l){var a=a||window.event,b=0;a.wheelDelta&&(b=-a.wheelDelta/120);a.detail&&(b=a.detail/3);e(b,!0);a.preventDefault&&!k&&a.preventDefault();k||(a.returnValue=!1)}};(function(){window.addEventListener?(this.addEventListener("DOMMouseScroll",
s,!1),this.addEventListener("mousewheel",s,!1)):document.attachEvent("onmousewheel",s)})();t();"bottom"==a.start?(c.css({top:b.outerHeight()-c.outerHeight()}),e(0,!0)):"object"==typeof a.start&&(e(d(a.start).position().top,null,!0),a.alwaysVisible||c.hide())}});return this}});jQuery.fn.extend({slimscroll:jQuery.fn.slimScroll})})(jQuery);

 

(function(e){e.fn.raty=function(l){options=e.extend({},e.fn.raty.defaults,l);if(this.attr("id")===undefined){c("Invalid selector!");return;}$this=e(this);if(options.number>20){options.number=20;}if(options.path.substring(options.path.length-1,options.path.length)!="/"){options.path+="/";}var q=$this.attr("id"),x=options.path,v=options.cancelOff,t=options.cancelOn,r=options.showHalf,o=options.starHalf,h=options.starOff,n=options.starOn,s=options.onClick,g=0,m="";if(!isNaN(options.start)&&options.start>0){g=(options.start>options.number)?options.number:options.start;}for(var p=1;p<=options.number;p++){m=(options.number<=options.hintList.length&&options.hintList[p-1]!==null)?options.hintList[p-1]:p;starFile=(g>=p)?n:h;$this.append('<img id="'+q+"-"+p+'" src="'+x+starFile+'" alt="'+p+'" title="'+m+'" class="'+q+'"/>').append((p<options.number)?"&nbsp;":"");}$this.append('<input id="'+q+'-score" type="hidden" name="'+options.scoreName+'"/>');e("#"+q+"-score").val(g);if(r){var k=e("input#"+q+"-score").val(),j=Math.ceil(k),u=(j-k).toFixed(1);if(u>=0.3&&u<=0.7){j=j-0.5;e("img#"+q+"-"+Math.ceil(j)).attr("src",x+o);}else{if(u>=0.8){j--;}else{e("img#"+q+"-"+j).attr("src",x+n);}}}if(!options.readOnly){if(options.showCancel){var w='<img src="'+x+options.cancelOff+'" alt="x" title="'+options.cancelHint+'" class="button-cancel"/>';if(options.cancelPlace=="left"){$this.prepend(w+"&nbsp;");}else{$this.append("&nbsp;").append(w);}$this.css("width",options.number*30+20);e("#"+q+" img.button-cancel").live("mouseenter",function(){e(this).attr("src",x+t);e("img."+q).attr("src",x+h);}).live("mouseleave",function(){e(this).attr("src",x+v);e("img."+q).trigger("mouseout");}).live("click",function(){e("input#"+q+"-score").val(0);if(s){s(0);}});}else{$this.css("width",options.number*30);}e("img."+q).live("mouseenter",function(){var y=e("img."+q).length;for(var z=1;z<=y;z++){if(z<=this.alt){e("img#"+q+"-"+z).attr("src",x+n);}else{e("img#"+q+"-"+z).attr("src",x+h);}}}).live("click",function(){e("input#"+q+"-score").val(this.alt);if(s){s(this.alt);}});$this.live("mouseleave",function(){var D=e(this).attr("id"),z=e("img."+D).length,C=e("input#"+D+"-score").val();for(var A=1;A<=z;A++){if(A<=C){e("img#"+D+"-"+A).attr("src",x+n);}else{e("img#"+D+"-"+A).attr("src",x+h);}}if(r){var C=e("input#"+D+"-score").val(),y=Math.ceil(C),B=(y-C).toFixed(1);if(B>=0.3&&B<=0.7){y=y-0.5;e("img#"+D+"-"+Math.ceil(y)).attr("src",x+o);}else{if(B>=0.8){y--;}else{e("img#"+D+"-"+y).attr("src",x+n);}}}}).css("cursor","pointer");}else{$this.css("cursor","default");}return $this;};e.fn.raty.defaults={cancelHint:"cancel this rating!",cancelOff:"cancel-off.png",cancelOn:"cancel-on.png",cancelPlace:"left",hintList:["bad","poor","regular","good","gorgeous"],number:5,path:"img/",readOnly:false,scoreName:"score",showCancel:false,showHalf:false,starHalf:"star-half.png",start:0,starOff:"star-off.png",starOn:"star-on.png"};e.fn.raty.readOnly=function(g){if(g){e("img."+$this.attr("id")).die();$this.css("cursor","default").die();}else{d();f();b();$this.css("cursor","pointer");}return e.fn.raty;};e.fn.raty.start=function(g){a(g);return e.fn.raty;};e.fn.raty.click=function(h){var g=(h>=options.number)?options.number:h;a(g);if(options.onClick){options.onClick(g);}else{c('You should add the "onClick: function() {}" option.');}return e.fn.raty;};function d(){var g=$this.attr("id");e("img."+g).live("mouseenter",function(){var h=e("img."+g).length;for(var j=1;j<=h;j++){if(j<=this.alt){e("img#"+g+"-"+j).attr("src",options.path+options.starOn);}else{e("img#"+g+"-"+j).attr("src",options.path+options.starOff);}}});}function f(){$this.live("mouseleave",function(){var k=e(this).attr("id");var g=e("img."+k).length;var j=e("input#"+k+"-score").val();for(var h=1;h<=g;h++){if(h<=j){e("img#"+k+"-"+h).attr("src",options.path+options.starOn);}else{e("img#"+k+"-"+h).attr("src",options.path+options.starOff);}}});}function b(){var g=$this.attr("id");e("img."+g).live("click",function(){e("input#"+g+"-score").val(this.alt);});}function a(k){var j=$this.attr("id"),g=e("img."+j).length;e("input#"+j+"-score").val(k);for(var h=1;h<=g;h++){if(h<=k){e("img#"+j+"-"+h).attr("src",options.path+options.starOn);}else{e("img#"+j+"-"+h).attr("src",options.path+options.starOff);}}}function c(g){if(window.console&&window.console.log){window.console.log(g);}}})(jQuery);



/*!
 * Infinite Ajax Scroll, a jQuery plugin 
 * Version v0.1.4
 * http://webcreate.nl/
 *
 * Copyright (c) 2011 Jeroen Fiege
 * Licensed under the MIT License: 
 * http://webcreate.nl/license
 */
(function(b){b.ias=function(d){var m=b.extend({},b.ias.defaults,d);var c=new b.ias.util();var j=new b.ias.paging();var h=(m.history?new b.ias.history():false);var f=this;r();function r(){j.onChangePage(function(x,v,w){if(h){h.setPage(x,w)}m.onPageChange.call(this,x,w,v)});s();if(h&&h.havePage()){q();pageNum=h.getPage();c.forceScrollTop(function(){if(pageNum>1){l(pageNum);curTreshold=p(true);b("html,body").scrollTop(curTreshold)}else{s()}})}return f}function s(){n();b(window).scroll(g)}function g(){scrTop=b(window).scrollTop();wndHeight=b(window).height();curScrOffset=scrTop+wndHeight;if(curScrOffset>=p()){t(curScrOffset)}}function q(){b(window).unbind("scroll",g)}function n(){b(m.pagination).hide()}function p(v){el=b(m.container).find(m.item).last();if(el.size()==0){return 0}treshold=el.offset().top+el.height();if(!v){treshold+=m.tresholdMargin}return treshold}function t(w,v){urlNextPage=b(m.next).attr("href");if(!urlNextPage){return q()}j.pushPages(w,urlNextPage);q();o();e(urlNextPage,function(y,x){result=m.onLoadItems.call(this,x);if(result!==false){b(x).hide();curLastItem=b(m.container).find(m.item).last();curLastItem.after(x);b(x).fadeIn()}b(m.pagination).replaceWith(b(m.pagination,y));k();s();if(v){v.call(this)}})}function e(w,x){var v=[];b.get(w,null,function(y){b(m.container,y).find(m.item).each(function(){v.push(this)});if(x){x.call(this,y,v)}},"html")}function l(v){curTreshold=p(true);if(curTreshold>0){t(curTreshold,function(){q();if((j.getCurPageNum(curTreshold)+1)<v){l(v);b("html,body").animate({scrollTop:curTreshold},400,"swing")}else{b("html,body").animate({scrollTop:curTreshold},1000,"swing");s()}})}}function u(){loader=b(".ias_loader");if(loader.size()==0){loader=b("<div class='ias_loader'><img src='"+m.loader+"'/></div>");loader.hide()}return loader}function o(v){loader=u();el=b(m.container).find(m.item).last();el.after(loader);loader.fadeIn()}function k(){loader=u();loader.remove()}};function a(c){if(window.console&&window.console.log){window.console.log(c)}}b.ias.defaults={container:"#container",item:".item",pagination:"#pagination",next:".next",tresholdMargin:0,history:true,onPageChange:function(){},onLoadItems:function(){},};b.ias.util=function(){var d=false;var f=false;var c=this;e();function e(){b(window).load(function(){d=true})}this.forceScrollTop=function(g){b("html,body").scrollTop(0);if(!f){if(!d){setTimeout(function(){c.forceScrollTop(g)},1)}else{g.call();f=true}}}};b.ias.paging=function(){var e=[[0,document.location.toString()]];var h=function(){};var d=1;j();function j(){b(window).scroll(g)}function g(){scrTop=b(window).scrollTop();wndHeight=b(window).height();curScrOffset=scrTop+wndHeight;curPageNum=c(curScrOffset);curPagebreak=f(curScrOffset);if(d!=curPageNum){h.call(this,curPageNum,curPagebreak[0],curPagebreak[1])}d=curPageNum}function c(k){for(i=(e.length-1);i>0;i--){if(k>e[i][0]){return i+1}}return 1}this.getCurPageNum=function(k){return c(k)};function f(k){for(i=(e.length-1);i>=0;i--){if(k>e[i][0]){return e[i]}}return null}this.onChangePage=function(k){h=k};this.pushPages=function(k,l){e.push([k,l])}};b.ias.history=function(){var d=false;var c=false;e();function e(){c=!!(window.history&&history.pushState&&history.replaceState);c=false}this.setPage=function(g,f){this.updateState({page:g},"",f)};this.havePage=function(){return(this.getState()!=false)};this.getPage=function(){if(this.havePage()){stateObj=this.getState();return stateObj.page}return 1};this.getState=function(){if(c){stateObj=history.state;if(stateObj&&stateObj.ias){return stateObj.ias}}else{haveState=(window.location.hash.substring(0,7)=="#/page/");if(haveState){pageNum=parseInt(window.location.hash.replace("#/page/",""));return{page:pageNum}}}return false};this.updateState=function(g,h,f){if(d){this.replaceState(g,h,f)}else{this.pushState(g,h,f)}};this.pushState=function(g,h,f){if(c){history.pushState({ias:g},h,f)}else{hash=(g.page>0?"#/page/"+g.page:"");window.location.hash=hash}d=true};this.replaceState=function(g,h,f){if(c){history.replaceState({ias:g},h,f)}else{this.pushState(g,h,f)}}}})(jQuery);


/*
 * Treeview 1.4.1 - jQuery plugin to hide and show branches of a tree
 * 
 * http://bassistance.de/jquery-plugins/jquery-plugin-treeview/
 * http://docs.jquery.com/Plugins/Treeview
 *
 * Copyright (c) 2007 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.treeview.js 5759 2008-07-01 07:50:28Z joern.zaefferer $
 *
 */

;(function($) {

	// TODO rewrite as a widget, removing all the extra plugins
	$.extend($.fn, {
		swapClass: function(c1, c2) {
			var c1Elements = this.filter('.' + c1);
			this.filter('.' + c2).removeClass(c2).addClass(c1);
			c1Elements.removeClass(c1).addClass(c2);
			return this;
		},
		replaceClass: function(c1, c2) {
			return this.filter('.' + c1).removeClass(c1).addClass(c2).end();
		},
		hoverClass: function(className) {
			className = className || "hover";
			return this.hover(function() {
				$(this).addClass(className);
			}, function() {
				$(this).removeClass(className);
			});
		},
		heightToggle: function(animated, callback) {
			animated ?
				this.animate({ height: "toggle" }, animated, callback) :
				this.each(function(){
					jQuery(this)[ jQuery(this).is(":hidden") ? "show" : "hide" ]();
					if(callback)
						callback.apply(this, arguments);
				});
		},
		heightHide: function(animated, callback) {
			if (animated) {
				this.animate({ height: "hide" }, animated, callback);
			} else {
				this.hide();
				if (callback)
					this.each(callback);				
			}
		},
		prepareBranches: function(settings) {
			if (!settings.prerendered) {
				// mark last tree items
				this.filter(":last-child:not(ul)").addClass(CLASSES.last);
				// collapse whole tree, or only those marked as closed, anyway except those marked as open
				this.filter((settings.collapsed ? "" : "." + CLASSES.closed) + ":not(." + CLASSES.open + ")").find(">ul").hide();
			}
			// return all items with sublists
			return this.filter(":has(>ul)");
		},
		applyClasses: function(settings, toggler) {
			// TODO use event delegation
			this.filter(":has(>ul):not(:has(>a))").find(">span").unbind("click.treeview").bind("click.treeview", function(event) {
				// don't handle click events on children, eg. checkboxes
				if ( this == event.target )
					toggler.apply($(this).next());
			}).add( $("a", this) ).hoverClass();
			
			if (!settings.prerendered) {
				// handle closed ones first
				this.filter(":has(>ul:hidden)")
						.addClass(CLASSES.expandable)
						.replaceClass(CLASSES.last, CLASSES.lastExpandable);
						
				// handle open ones
				this.not(":has(>ul:hidden)")
						.addClass(CLASSES.collapsable)
						.replaceClass(CLASSES.last, CLASSES.lastCollapsable);
						
	            // create hitarea if not present
				var hitarea = this.find("div." + CLASSES.hitarea);
				if (!hitarea.length)
					hitarea = this.prepend("<div class=\"" + CLASSES.hitarea + "\"/>").find("div." + CLASSES.hitarea);
				hitarea.removeClass().addClass(CLASSES.hitarea).each(function() {
					var classes = "";
					$.each($(this).parent().attr("class").split(" "), function() {
						classes += this + "-hitarea ";
					});
					$(this).addClass( classes );
				})
			}
			
			// apply event to hitarea
			this.find("div." + CLASSES.hitarea).click( toggler );
		},
		treeview: function(settings) {
			
			settings = $.extend({
				cookieId: "treeview"
			}, settings);
			
			if ( settings.toggle ) {
				var callback = settings.toggle;
				settings.toggle = function() {
					return callback.apply($(this).parent()[0], arguments);
				};
			}
		
			// factory for treecontroller
			function treeController(tree, control) {
				// factory for click handlers
				function handler(filter) {
					return function() {
						// reuse toggle event handler, applying the elements to toggle
						// start searching for all hitareas
						toggler.apply( $("div." + CLASSES.hitarea, tree).filter(function() {
							// for plain toggle, no filter is provided, otherwise we need to check the parent element
							return filter ? $(this).parent("." + filter).length : true;
						}) );
						return false;
					};
				}
				// click on first element to collapse tree
				$("a:eq(0)", control).click( handler(CLASSES.collapsable) );
				// click on second to expand tree
				$("a:eq(1)", control).click( handler(CLASSES.expandable) );
				// click on third to toggle tree
				$("a:eq(2)", control).click( handler() ); 
			}
		
			// handle toggle event
			function toggler() {
				$(this)
					.parent()
					// swap classes for hitarea
					.find(">.hitarea")
						.swapClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
						.swapClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea )
					.end()
					// swap classes for parent li
					.swapClass( CLASSES.collapsable, CLASSES.expandable )
					.swapClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
					// find child lists
					.find( ">ul" )
					// toggle them
					.heightToggle( settings.animated, settings.toggle );
				if ( settings.unique ) {
					$(this).parent()
						.siblings()
						// swap classes for hitarea
						.find(">.hitarea")
							.replaceClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
							.replaceClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea )
						.end()
						.replaceClass( CLASSES.collapsable, CLASSES.expandable )
						.replaceClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
						.find( ">ul" )
						.heightHide( settings.animated, settings.toggle );
				}
			}
			this.data("toggler", toggler);
			
			function serialize() {
				function binary(arg) {
					return arg ? 1 : 0;
				}
				var data = [];
				branches.each(function(i, e) {
					data[i] = $(e).is(":has(>ul:visible)") ? 1 : 0;
				});
				$.cookie(settings.cookieId, data.join(""), settings.cookieOptions );
			}
			
			function deserialize() {
				var stored = $.cookie(settings.cookieId);
				if ( stored ) {
					var data = stored.split("");
					branches.each(function(i, e) {
						$(e).find(">ul")[ parseInt(data[i]) ? "show" : "hide" ]();
					});
				}
			}
			
			// add treeview class to activate styles
			this.addClass("treeview");
			
			// prepare branches and find all tree items with child lists
			var branches = this.find("li").prepareBranches(settings);
			
			switch(settings.persist) {
			case "cookie":
				var toggleCallback = settings.toggle;
				settings.toggle = function() {
					serialize();
					if (toggleCallback) {
						toggleCallback.apply(this, arguments);
					}
				};
				deserialize();
				break;
			case "location":
				var current = this.find("a").filter(function() {
					return this.href.toLowerCase() == location.href.toLowerCase();
				});
				if ( current.length ) {
					// TODO update the open/closed classes
					var items = current.addClass("selected").parents("ul, li").add( current.next() ).show();
					if (settings.prerendered) {
						// if prerendered is on, replicate the basic class swapping
						items.filter("li")
							.swapClass( CLASSES.collapsable, CLASSES.expandable )
							.swapClass( CLASSES.lastCollapsable, CLASSES.lastExpandable )
							.find(">.hitarea")
								.swapClass( CLASSES.collapsableHitarea, CLASSES.expandableHitarea )
								.swapClass( CLASSES.lastCollapsableHitarea, CLASSES.lastExpandableHitarea );
					}
				}
				break;
			}
			
			branches.applyClasses(settings, toggler);
				
			// if control option is set, create the treecontroller and show it
			if ( settings.control ) {
				treeController(this, settings.control);
				$(settings.control).show();
			}
			
			return this;
		}
	});
	
	// classes used by the plugin
	// need to be styled via external stylesheet, see first example
	$.treeview = {};
	var CLASSES = ($.treeview.classes = {
		open: "open",
		closed: "closed",
		expandable: "expandable",
		expandableHitarea: "expandable-hitarea",
		lastExpandableHitarea: "lastExpandable-hitarea",
		collapsable: "collapsable",
		collapsableHitarea: "collapsable-hitarea",
		lastCollapsableHitarea: "lastCollapsable-hitarea",
		lastCollapsable: "lastCollapsable",
		lastExpandable: "lastExpandable",
		last: "last",
		hitarea: "hitarea"
	});
	
})(jQuery);