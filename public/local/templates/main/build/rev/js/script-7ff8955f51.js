$(document).ready(function(){$(".modal").click(function(o){$(o.target).closest(".modal>.block").length||($(".modal>.block, .modal").fadeOut(100),$("html, body").css({overflow:"auto"}),o.stopPropagation())}),$(".modal .close").click(function(){$(".modal, .modal>.block").fadeOut(100),$("html, body").css({overflow:"auto"})}),$("[data-modal]").on("click",function(){var o=$(this).attr("data-modal"),l=$("#"+o);l.fadeIn(100),$(l).find(".block").fadeIn(100),$("html, body").css({overflow:"hidden"})}),$(".hamburger").on("click",function(){$(".header, .footer, .content, body, html").toggleClass("open")}),$(".slider").slick({arrows:!1,slidesToShow:1,slidesToScroll:1,dots:!0}),$(".carusel").slick({arrows:!1,slidesToShow:5,slidesToScroll:1,autoplay:!0,responsive:[{breakpoint:1023,settings:{slidesToShow:3,slidesToScroll:1}},{breakpoint:580,settings:{slidesToShow:1,slidesToScroll:1}}]})});