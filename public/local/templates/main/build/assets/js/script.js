$(document).ready(function(){$(".modal").click(function(t){$(t.target).closest(".modal>.block").length||($(".modal>.block, .modal").fadeOut(100),$("html, body").css({overflow:"auto"}),t.stopPropagation())}),$(".modal .close").click(function(){$(".modal, .modal>.block").fadeOut(100),$("html, body").css({overflow:"auto"})}),$("[data-modal]").on("click",function(){$(".modal").fadeOut(100);var t=$(this).attr("data-modal"),a=$("#"+t);a.fadeIn(100),$(a).find(".block").fadeIn(100),$("html, body").css({overflow:"hidden"})}),$(".hamburger").on("click",function(){$(".header, .footer, .content, body, html").toggleClass("open")});var t=15e3;$("textarea").each(function(){$(this).keyup(function(){var a=$(this).val().length;a=t-a,$(this).parent().find(".chars").text(a)}),$(this).focus(function(){$(this).parent().addClass("focus"),$(this).parent().find(".placeholder").css("color","#0066cc")}),$(this).focusout(function(){$(this).parent().find(".placeholder").css("color","#606778"),""===$(this).val()?$(this).parent().removeClass("focus"):$(this).parent().addClass("focus")})});var a=document.querySelectorAll(".label_textarea");document.querySelector("textarea");[].forEach.call(a,function(t,a){var e=t.children[0],o=t;e.addEventListener("keydown",function(){setTimeout(function(){e.style.cssText="height:0px";var t=Math.min(100,e.scrollHeight);o.style.cssText="height:"+t+"px",e.style.cssText="height:"+t+"px"},0)})}),$(function(){$("[data-tabLinks]").on("click",function(){var t=$("[data-tabContent="+$(this).attr("data-tabLinks")+"]");$(this).parent().find("[data-tabLinks]").removeClass("active").filter(this).addClass("active"),t.parent().find("[data-tabContent]").hide().filter(t).show()}),$("[data-tabLinks]").parent().find("[data-tabLinks]:nth-child(1)").trigger("click")})});