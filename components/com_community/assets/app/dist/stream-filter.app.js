(window.webpackJsonp=window.webpackJsonp||[]).push([[5],{161:function(t,i,e){"use strict";e.r(i);const n=jQuery,a=n(".joms-activity-filter"),o=a.find(".joms-activity-filter-action"),l=a.find(".joms-activity-filter-dropdown"),s=a.find(".joms-activity-filter__options");function c(){l.is(":visible")?r():l.show()}function r(){l.hide()}function d(t){let i=n(t.currentTarget),e=i.data("url")||"/";"__filter__"!==i.data("filter")&&(c(),window.location=e)}function u(t){let i=t.target.value,e=l.find("input[type=text]"),n=l.find(".joms-button--primary");"hashtag"!==i&&"keyword"!==i||(e.attr("placeholder",e.data("label-"+i)),n.html(n.data("label-"+i)))}function f(t){let i=n(t.currentTarget),e=i.val(),a=e;a=a.replace(/#/g,""),a.length!==e.length&&i.val(a)}function p(t){let i,e,a,o,l,s;t.preventDefault(),t.stopPropagation(),i=n(t.currentTarget),e=i.closest("li"),a=e.find("select").val(),o=e.find("input"),l=o.val().replace(/^\s+|\s+$/g,""),l&&("hashtag"===a&&(l=l.split(" "),l=l[0]),s=e.data("url"),s=s.replace("__filter__",a),s=s.replace("__value__",l),window.location=s)}function m(t){let i,e,a;t.preventDefault(),t.stopPropagation(),i=n(t.currentTarget),e=i.find("a").data("value"),a=s.find(".noselect > img"),"hidden"===a.css("visibility")&&(a.css("visibility","visible"),joms.ajax({func:"system,ajaxDefaultUserStream",data:[e],callback:t=>{t.error?joms.popup.info("Error",t.error):t.success&&(joms.popup.info("",t.message),s.find(".joms-dropdown").hide(),i.addClass("active").siblings("li").removeClass("active"),a.css("visibility","hidden"))}}))}function v(t){n(t.target).closest(".joms-activity-filter").length||r()}i.default=function(){o.on("click",c),l.on("click","li",d),l.on("change","select",u),l.on("keyup","input[type=text]",f),l.on("click","button.joms-button--primary",p),s.find("li").not(".noselect").addClass("joms-js-filterbar-item"),n(document).on("click",".joms-js-filterbar-item",m),n(document).on("click",v)}}}]);