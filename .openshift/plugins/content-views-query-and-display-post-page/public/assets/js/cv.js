/*! content-views 09-2016 */
/*!
 * Bootstrap v3.3.5 (http://getbootstrap.com)
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under the MIT license
 */
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(a){function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one("bsTransitionEnd",function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b(),a.support.transition&&(a.event.special.bsTransitionEnd={bindType:a.support.transition.end,delegateType:a.support.transition.end,handle:function(b){return a(b.target).is(this)?b.handleObj.handler.apply(this,arguments):void 0}})})}(jQuery),+function(a){function b(b){return this.each(function(){var d=a(this),e=d.data("bs.carousel"),f=a.extend({},c.DEFAULTS,d.data(),"object"==typeof b&&b),g="string"==typeof b?b:f.cvslide;e||d.data("bs.carousel",e=new c(this,f)),"number"==typeof b?e.to(b):g?e[g]():f.interval&&e.pause().cycle()})}var c=function(b,c){this.$element=a(b),this.$indicators=this.$element.find(".pt-cv-carousel-indicators"),this.options=c,this.paused=null,this.sliding=null,this.interval=null,this.$active=null,this.$items=null,this.options.keyboard&&this.$element.on("keydown.bs.carousel",a.proxy(this.keydown,this)),"hover"==this.options.pause&&!("ontouchstart"in document.documentElement)&&this.$element.on("mouseenter.bs.carousel",a.proxy(this.pause,this)).on("mouseleave.bs.carousel",a.proxy(this.cycle,this))};c.VERSION="3.3.5",c.TRANSITION_DURATION=600,c.DEFAULTS={interval:5e3,pause:"hover",wrap:!0,keyboard:!0},c.prototype.keydown=function(a){if(!/input|textarea/i.test(a.target.tagName)){switch(a.which){case 37:this.prev();break;case 39:this.next();break;default:return}a.preventDefault()}},c.prototype.cycle=function(b){return b||(this.paused=!1),this.interval&&clearInterval(this.interval),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},c.prototype.getItemIndex=function(a){return this.$items=a.parent().children(".item"),this.$items.index(a||this.$active)},c.prototype.getItemForDirection=function(a,b){var c=this.getItemIndex(b),d="prev"==a&&0===c||"next"==a&&c==this.$items.length-1;if(d&&!this.options.wrap)return b;var e="prev"==a?-1:1,f=(c+e)%this.$items.length;return this.$items.eq(f)},c.prototype.to=function(a){var b=this,c=this.getItemIndex(this.$active=this.$element.find(".item.active"));return a>this.$items.length-1||0>a?void 0:this.sliding?this.$element.one("slid.bs.carousel",function(){b.to(a)}):c==a?this.pause().cycle():this.slide(a>c?"next":"prev",this.$items.eq(a))},c.prototype.pause=function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition&&(this.$element.trigger(a.support.transition.end),this.cycle(!0)),this.interval=clearInterval(this.interval),this},c.prototype.next=function(){return this.sliding?void 0:this.slide("next")},c.prototype.prev=function(){return this.sliding?void 0:this.slide("prev")},c.prototype.slide=function(b,d){var e=this.$element.find(".item.active"),f=d||this.getItemForDirection(b,e),g=this.interval,h="next"==b?"left":"right",i=this;if(f.hasClass("active"))return this.sliding=!1;var j=f[0],k=a.Event("slide.bs.carousel",{relatedTarget:j,direction:h});if(this.$element.trigger(k),!k.isDefaultPrevented()){if(this.sliding=!0,g&&this.pause(),this.$indicators.length){this.$indicators.find(".active").removeClass("active");var l=a(this.$indicators.children()[this.getItemIndex(f)]);l&&l.addClass("active")}var m=a.Event("slid.bs.carousel",{relatedTarget:j,direction:h});return a.support.transition&&this.$element.hasClass("slide")?(f.addClass(b),f[0].offsetWidth,e.addClass(h),f.addClass(h),e.one("bsTransitionEnd",function(){f.removeClass([b,h].join(" ")).addClass("active"),e.removeClass(["active",h].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger(m)},0)}).emulateTransitionEnd(c.TRANSITION_DURATION)):(e.removeClass("active"),f.addClass("active"),this.sliding=!1,this.$element.trigger(m)),g&&this.cycle(),this}};var d=a.fn.carousel;d||(a.fn.carousel=b,a.fn.carousel.Constructor=c);var e=function(c){var d,e=a(this),f=a(e.attr("data-target")||(d=e.attr("href"))&&d.replace(/.*(?=#[^\s]+$)/,""));if(f.hasClass("carousel")){var g=a.extend({},f.data(),e.data()),h=e.attr("data-cvslide-to");h&&(g.interval=!1),b.call(f,g),h&&f.data("bs.carousel").to(h),c.preventDefault()}};a(document).ready(function(){a(".pt-cv-wrapper").on("click.bs.carousel.data-api","[data-cvslide]",e).on("click.bs.carousel.data-api","[data-cvslide-to]",e)}),a(window).on("load",function(){a('[data-ride="cvcarousel"]',".pt-cv-wrapper").each(function(){var c=a(this);b.call(c,c.data())})})}(jQuery),+function(a){function b(b){var c,d=b.attr("data-target")||(c=b.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,"");return a(d)}function c(b){return this.each(function(){var c=a(this),e=c.data("bs.collapse"),f=a.extend({},d.DEFAULTS,c.data(),"object"==typeof b&&b);!e&&f.toggle&&/show|hide/.test(b)&&(f.toggle=!1),e||c.data("bs.collapse",e=new d(this,f)),"string"==typeof b&&e[b]()})}var d=function(b,c){this.$element=a(b),this.options=a.extend({},d.DEFAULTS,c),this.$trigger=a('[data-toggle="collapse"][href="#'+b.id+'"],[data-toggle="collapse"][data-target="#'+b.id+'"]'),this.transitioning=null,this.options.parent?this.$parent=this.getParent():this.addAriaAndCollapsedClass(this.$element,this.$trigger),this.options.toggle&&this.toggle()};d.VERSION="3.3.5",d.TRANSITION_DURATION=350,d.DEFAULTS={toggle:!0},d.prototype.dimension=function(){var a=this.$element.hasClass("width");return a?"width":"height"},d.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var b,e=this.$parent&&this.$parent.children(".panel").children(".in, .collapsing");if(!(e&&e.length&&(b=e.data("bs.collapse"),b&&b.transitioning))){var f=a.Event("show.bs.collapse");if(this.$element.trigger(f),!f.isDefaultPrevented()){e&&e.length&&(c.call(e,"hide"),b||e.data("bs.collapse",null));var g=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[g](0).attr("aria-expanded",!0),this.$trigger.removeClass("collapsed").attr("aria-expanded",!0),this.transitioning=1;var h=function(){this.$element.removeClass("collapsing").addClass("collapse in")[g](""),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!a.support.transition)return h.call(this);var i=a.camelCase(["scroll",g].join("-"));this.$element.one("bsTransitionEnd",a.proxy(h,this)).emulateTransitionEnd(d.TRANSITION_DURATION)[g](this.$element[0][i])}}}},d.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var b=a.Event("hide.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.dimension();this.$element[c](this.$element[c]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded",!1),this.$trigger.addClass("collapsed").attr("aria-expanded",!1),this.transitioning=1;var e=function(){this.transitioning=0,this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")};return a.support.transition?void this.$element[c](0).one("bsTransitionEnd",a.proxy(e,this)).emulateTransitionEnd(d.TRANSITION_DURATION):e.call(this)}}},d.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()},d.prototype.getParent=function(){return a(this.options.parent).find('[data-toggle="collapse"][data-parent="'+this.options.parent+'"]').each(a.proxy(function(c,d){var e=a(d);this.addAriaAndCollapsedClass(b(e),e)},this)).end()},d.prototype.addAriaAndCollapsedClass=function(a,b){var c=a.hasClass("in");a.attr("aria-expanded",c),b.toggleClass("collapsed",!c).attr("aria-expanded",c)};var e=a.fn.collapse;e||(a.fn.collapse=c,a.fn.collapse.Constructor=d),a(document).ready(function(){a(".pt-cv-wrapper").on("click.bs.collapse.data-api",'[data-toggle="collapse"]',function(d){var e=a(this);e.attr("data-target")||d.preventDefault();var f=b(e),g=f.data("bs.collapse"),h=g?"toggle":e.data();c.call(f,h)})})}(jQuery),+function(a){function b(b){var c=b.attr("data-target");c||(c=b.attr("href"),c=c&&/#[A-Za-z]/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,""));var d=c&&a(c);return d&&d.length?d:b.parent()}function c(c){c&&3===c.which||(a(e).remove(),a(f).each(function(){var d=a(this),e=b(d),f={relatedTarget:this};e.hasClass("open")&&(c&&"click"==c.type&&/input|textarea/i.test(c.target.tagName)&&a.contains(e[0],c.target)||(e.trigger(c=a.Event("hide.bs.dropdown",f)),c.isDefaultPrevented()||(d.attr("aria-expanded","false"),e.removeClass("open").trigger("hidden.bs.dropdown",f))))}))}function d(b){return this.each(function(){var c=a(this),d=c.data("bs.dropdown");d||c.data("bs.dropdown",d=new g(this)),"string"==typeof b&&d[b].call(c)})}var e=".dropdown-backdrop",f='[data-toggle="dropdown"]',g=function(b){a(b).on("click.bs.dropdown",this.toggle)};g.VERSION="3.3.5",g.prototype.toggle=function(d){var e=a(this);if(!e.is(".disabled, :disabled")){var f=b(e),g=f.hasClass("open");if(c(),!g){"ontouchstart"in document.documentElement&&!f.closest(".navbar-nav").length&&a(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(a(this)).on("click",c);var h={relatedTarget:this};if(f.trigger(d=a.Event("show.bs.dropdown",h)),d.isDefaultPrevented())return;e.trigger("focus").attr("aria-expanded","true"),f.toggleClass("open").trigger("shown.bs.dropdown",h)}return!1}},g.prototype.keydown=function(c){if(/(38|40|27|32)/.test(c.which)&&!/input|textarea/i.test(c.target.tagName)){var d=a(this);if(c.preventDefault(),c.stopPropagation(),!d.is(".disabled, :disabled")){var e=b(d),g=e.hasClass("open");if(!g&&27!=c.which||g&&27==c.which)return 27==c.which&&e.find(f).trigger("focus"),d.trigger("click");var h=" li:not(.disabled):visible a",i=e.find(".dropdown-menu"+h);if(i.length){var j=i.index(c.target);38==c.which&&j>0&&j--,40==c.which&&j<i.length-1&&j++,~j||(j=0),i.eq(j).trigger("focus")}}}};var h=a.fn.dropdown;h||(a.fn.dropdown=d,a.fn.dropdown.Constructor=g),a(document).ready(function(){a(".pt-cv-wrapper").on("click.bs.dropdown.data-api",c).on("click.bs.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.bs.dropdown.data-api",f,g.prototype.toggle).on("keydown.bs.dropdown.data-api",f,g.prototype.keydown).on("keydown.bs.dropdown.data-api",".dropdown-menu",g.prototype.keydown)})}(jQuery),+function(a){function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new c(this)),"string"==typeof b&&e[b]()})}var c=function(b){this.element=a(b)};c.VERSION="3.3.5",c.TRANSITION_DURATION=150,c.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a"),f=a.Event("hide.bs.tab",{relatedTarget:b[0]}),g=a.Event("show.bs.tab",{relatedTarget:e[0]});if(e.trigger(f),b.trigger(g),!g.isDefaultPrevented()&&!f.isDefaultPrevented()){var h=a(d);this.activate(b.closest("li"),c),this.activate(h,h.parent(),function(){e.trigger({type:"hidden.bs.tab",relatedTarget:b[0]}),b.trigger({type:"shown.bs.tab",relatedTarget:e[0]})})}}},c.prototype.activate=function(b,d,e){function f(){g.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!1),b.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded",!0),h?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu").length&&b.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!0),e&&e()}var g=d.find("> .active"),h=e&&a.support.transition&&(g.length&&g.hasClass("fade")||!!d.find("> .fade").length);g.length&&h?g.one("bsTransitionEnd",f).emulateTransitionEnd(c.TRANSITION_DURATION):f(),g.removeClass("in")};var d=a.fn.tab;d||(a.fn.tab=b,a.fn.tab.Constructor=c);var e=function(c){c.preventDefault(),b.call(a(this),"show")};a(document).ready(function(){a(".pt-cv-wrapper").on("click.bs.tab.data-api",'[data-toggle="tab"]',e).on("click.bs.tab.data-api",'[data-toggle="pill"]',e)})}(jQuery),/*!
 * Bootstrap paginator v0.5
 * Copyright 2013 Yun Lai <lyonlai1984@gmail.com>
 * Licensed http://www.apache.org/licenses/LICENSE-2.0
 */
function(a){var b=function(a,b){this.init(a,b)},c=null;b.prototype={init:function(b,c){this.$element=a(b);{var d=c&&c.bootstrapMajorVersion?c.bootstrapMajorVersion:a.fn.bootstrapPaginator.defaults.bootstrapMajorVersion;this.$element.attr("id")}if(2===d&&!this.$element.is("div"))throw"in Bootstrap version 2 the pagination must be a div element. Or if you are using Bootstrap pagination 3. Please specify it in bootstrapMajorVersion in the option";if(d>2&&!this.$element.is("ul"))throw"in Bootstrap version 3 the pagination root item must be an ul element.";this.currentPage=1,this.lastPage=1,this.setOptions(c),this.initialized=!0},setOptions:function(b){this.options=a.extend({},this.options||a.fn.bootstrapPaginator.defaults,b),this.totalPages=parseInt(this.options.totalPages,10),this.numberOfPages=parseInt(this.options.numberOfPages,10),b&&"undefined"!=typeof b.currentPage&&this.setCurrentPage(b.currentPage),this.listen(),this.render(),this.initialized||this.lastPage===this.currentPage||this.$element.trigger("page-changed",[this.lastPage,this.currentPage])},listen:function(){this.$element.off("page-clicked"),this.$element.off("page-changed"),"function"==typeof this.options.onPageClicked&&this.$element.bind("page-clicked",this.options.onPageClicked),"function"==typeof this.options.onPageChanged&&this.$element.on("page-changed",this.options.onPageChanged),this.$element.bind("page-clicked",this.onPageClicked)},destroy:function(){this.$element.off("page-clicked"),this.$element.off("page-changed"),this.$element.removeData("bootstrapPaginator"),this.$element.empty()},show:function(a){this.setCurrentPage(a),this.render(),this.lastPage!==this.currentPage&&this.$element.trigger("page-changed",[this.lastPage,this.currentPage])},showNext:function(){var a=this.getPages();a.next&&this.show(a.next)},showPrevious:function(){var a=this.getPages();a.prev&&this.show(a.prev)},showFirst:function(){var a=this.getPages();a.first&&this.show(a.first)},showLast:function(){var a=this.getPages();a.last&&this.show(a.last)},onPageItemClicked:function(a){var b=a.data.type,c=a.data.page;this.$element.trigger("page-clicked",[a,b,c])},onPageClicked:function(b,c,d,e){var f=a(b.currentTarget);switch(d){case"first":f.bootstrapPaginator("showFirst");break;case"prev":f.bootstrapPaginator("showPrevious");break;case"next":f.bootstrapPaginator("showNext");break;case"last":f.bootstrapPaginator("showLast");break;case"page":f.bootstrapPaginator("show",e)}},render:function(){var b=this.getValueFromOption(this.options.containerClass,this.$element),c=this.options.size||"normal",d=this.options.alignment||"left",e=this.getPages(),f=2===this.options.bootstrapMajorVersion?a("<ul></ul>"):this.$element,g=2===this.options.bootstrapMajorVersion?this.getValueFromOption(this.options.listContainerClass,f):null,h=null,i=null,j=null,k=null,l=null,m=0;switch(c.toLowerCase()){case"large":case"small":case"mini":this.$element.addClass(a.fn.bootstrapPaginator.sizeArray[this.options.bootstrapMajorVersion][c.toLowerCase()])}if(2===this.options.bootstrapMajorVersion)switch(d.toLowerCase()){case"center":this.$element.addClass("pagination-centered");break;case"right":this.$element.addClass("pagination-right")}for(this.$element.addClass(b),this.$element.empty(),2===this.options.bootstrapMajorVersion&&(this.$element.append(f),f.addClass(g)),this.pageRef=[],e.first&&(h=this.buildPageItem("first",e.first),h&&f.append(h)),e.prev&&(i=this.buildPageItem("prev",e.prev),i&&f.append(i)),m=0;m<e.length;m+=1)l=this.buildPageItem("page",e[m]),l&&f.append(l);e.next&&(j=this.buildPageItem("next",e.next),j&&f.append(j)),e.last&&(k=this.buildPageItem("last",e.last),k&&f.append(k))},buildPageItem:function(b,c){var d=a("<li></li>"),e=a("<a></a>"),f="",g="",h=this.options.itemContainerClass(b,c,this.currentPage),i=this.getValueFromOption(this.options.itemContentClass,b,c,this.currentPage),j=null;switch(b){case"first":if(!this.getValueFromOption(this.options.shouldShowPage,b,c,this.currentPage))return;f=this.options.itemTexts(b,c,this.currentPage),g=this.options.tooltipTitles(b,c,this.currentPage);break;case"last":if(!this.getValueFromOption(this.options.shouldShowPage,b,c,this.currentPage))return;f=this.options.itemTexts(b,c,this.currentPage),g=this.options.tooltipTitles(b,c,this.currentPage);break;case"prev":if(!this.getValueFromOption(this.options.shouldShowPage,b,c,this.currentPage))return;f=this.options.itemTexts(b,c,this.currentPage),g=this.options.tooltipTitles(b,c,this.currentPage);break;case"next":if(!this.getValueFromOption(this.options.shouldShowPage,b,c,this.currentPage))return;f=this.options.itemTexts(b,c,this.currentPage),g=this.options.tooltipTitles(b,c,this.currentPage);break;case"page":if(!this.getValueFromOption(this.options.shouldShowPage,b,c,this.currentPage))return;f=this.options.itemTexts(b,c,this.currentPage),g=this.options.tooltipTitles(b,c,this.currentPage)}return d.addClass(h).append(e),e.addClass(i).html(f).on("click",null,{type:b,page:c},a.proxy(this.onPageItemClicked,this)),this.options.pageUrl&&e.attr("href",this.getValueFromOption(this.options.pageUrl,b,c,this.currentPage)),this.options.useBootstrapTooltip?(j=a.extend({},this.options.bootstrapTooltipOptions,{title:g}),e.tooltip(j)):e.attr("title",g),d},setCurrentPage:function(a){if(a>this.totalPages||1>a)throw"Page out of range";this.lastPage=this.currentPage,this.currentPage=parseInt(a,10)},getPages:function(){var a=this.totalPages,b=this.currentPage%this.numberOfPages===0?(parseInt(this.currentPage/this.numberOfPages,10)-1)*this.numberOfPages+1:parseInt(this.currentPage/this.numberOfPages,10)*this.numberOfPages+1,c=[],d=0,e=0;for(b=1>b?1:b,d=b,e=0;e<this.numberOfPages&&a>=d;d+=1,e+=1)c.push(d);return c.first=1,c.prev=this.currentPage>1?this.currentPage-1:1,c.next=this.currentPage<a?this.currentPage+1:a,c.last=a,c.current=this.currentPage,c.total=a,c.numberOfPages=this.options.numberOfPages,c},getValueFromOption:function(a){var b=null,c=Array.prototype.slice.call(arguments,1);return b="function"==typeof a?a.apply(this,c):a}},c=a.fn.bootstrapPaginator,a.fn.bootstrapPaginator=function(c){var d=arguments,e=null;return a(this).each(function(f,g){var h=a(g),i=h.data("bootstrapPaginator"),j="object"!=typeof c?null:c;if(!i)return i=new b(this,j),h=a(i.$element),void h.data("bootstrapPaginator",i);if("string"==typeof c){if(!i[c])throw"Method "+c+" does not exist";e=i[c].apply(i,Array.prototype.slice.call(d,1))}else e=i.setOptions(c)}),e},a.fn.bootstrapPaginator.sizeArray={2:{large:"pagination-large",small:"pagination-small",mini:"pagination-mini"},3:{large:"pagination-lg",small:"pagination-sm",mini:""}},a.fn.bootstrapPaginator.defaults={containerClass:"",size:"normal",alignment:"left",bootstrapMajorVersion:2,listContainerClass:"",itemContainerClass:function(a,b,c){return b===c?"active":""},itemContentClass:function(){return""},currentPage:1,numberOfPages:5,totalPages:1,pageUrl:function(){return null},onPageClicked:null,onPageChanged:null,useBootstrapTooltip:!1,shouldShowPage:function(a,b,c){var d=!0;switch(a){case"first":d=1!==c;break;case"prev":d=1!==c;break;case"next":d=c!==this.totalPages;break;case"last":d=c!==this.totalPages;break;case"page":d=!0}return d},itemTexts:function(a,b){switch(a){case"first":return PT_CV_PAGINATION.first;case"prev":return PT_CV_PAGINATION.prev;case"next":return PT_CV_PAGINATION.next;case"last":return PT_CV_PAGINATION.last;case"page":return b}},tooltipTitles:function(a,b,c){switch(a){case"first":return PT_CV_PAGINATION.goto_first;case"prev":return PT_CV_PAGINATION.goto_prev;case"next":return PT_CV_PAGINATION.goto_next;case"last":return PT_CV_PAGINATION.goto_last;case"page":return b===c?PT_CV_PAGINATION.current_page+" "+b:PT_CV_PAGINATION.goto_page+" "+b}},bootstrapTooltipOptions:{animation:!0,html:!0,placement:"top",selector:!1,title:"",container:!1}},a.fn.bootstrapPaginator.Constructor=b}(window.jQuery),/**
 * CV JS
 * @author    PT Guy <http://www.contentviewspro.com/>
 * @license   GPL-2.0+
 */
function(a){"use strict";a.PT_CV_Public=a.PT_CV_Public||{},PT_CV_PUBLIC=PT_CV_PUBLIC||{};var b=PT_CV_PUBLIC._prefix;a.PT_CV_Public=function(b){this.options=a.extend({},b),this.pagination()},a.PT_CV_Public.prototype={pagination:function(){var c=this,d=function(a,b){c._setup_pagination(a,b,function(){PT_CV_PUBLIC.paging=0})};a("."+b+"pagination."+b+"ajax").each(function(){var b=a(this),c=a(this).attr("data-totalpages");a(this).bootstrapPaginator({bootstrapMajorVersion:3,currentPage:1,totalPages:c,numberOfPages:PT_CV_PUBLIC.page_to_show,shouldShowPage:function(a){if(!(c&&10>c))return!0;switch(a){case"first":case"last":return!1;default:return!0}},onPageClicked:function(a,c,e,f){d(b,f)}})})},_setup_pagination:function(a,c,d){var e=this;if(PT_CV_PUBLIC.paging=PT_CV_PUBLIC.paging||0,!PT_CV_PUBLIC.paging){PT_CV_PUBLIC.paging=1;var f=a.attr("data-sid"),g=a.next("."+b+"spinner"),h=a;a.parent("."+b+"pagination-wrapper").length&&(h=a.parent("."+b+"pagination-wrapper"));var i=h.closest("."+b+"wrapper").children("."+b+"view");if(i.hasClass(b+"timeline")&&(i=i.children(".tl-items").first()),h.find("."+b+"more").length>0){var j=i.children("."+b+"page").first();j.length>0&&(i=j)}e._get_page(f,c,g,i,d)}},_get_page:function(c,d,e,f,g){var h=this,i=h._active_page(d,f,g);if(i)return g&&"function"==typeof g&&g(),void a("body").trigger(b+"pagination-finished-simple");var j={action:"pagination_request",sid:c,page:d,lang:PT_CV_PUBLIC.lang,ajax_nonce:PT_CV_PUBLIC._nonce,custom_data:window.cvdata};a.ajax({type:"POST",url:PT_CV_PUBLIC.ajaxurl,data:j,beforeSend:function(){e.addClass("active")}}).done(function(c){e.removeClass("active"),c.indexOf(b+"no-post")<0&&f.append(c),h._active_page(d,f,g),g&&"function"==typeof g&&g(),a("body").trigger(b+"pagination-finished",[f,a(c)])})},_active_page:function(c,d){var e=!1,f='[data-id="'+b+"page-"+parseInt(c)+'"]';if(d.children(f).length){e=!0,d.children().hide(),d.children(f).show(),a("html, body").animate({scrollTop:d.children(f).offset().top-160},1e3);var g=d.closest("."+b+"wrapper").children("."+b+"pagination-wrapper");2===g.length&&g.each(function(){a(this).find("a").eq(c).trigger("click")})}return e}},a(function(){new a.PT_CV_Public})}(jQuery);