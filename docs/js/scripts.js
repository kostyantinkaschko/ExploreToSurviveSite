
function addScrollListener(){const e=document.querySelector(".top-line");function n(){window.scrollY>0?e.classList.add("with-background"):e.classList.remove("with-background")}return window.addEventListener("scroll",n),n}function checkWindowSize(){const e=addScrollListener();window.innerWidth<=943&&window.removeEventListener("scroll",e)}function disableScroll(){if(window.innerWeight<=944){window.pageYOffset||document.documentElement.scrollTop,window.pageXOffset||document.documentElement.scrollLeft;document.body.style.overflow="hidden",document.documentElement.style.overflow="hidden"}}window.addEventListener("load",checkWindowSize),window.addEventListener("resize",checkWindowSize),document.getElementById("community-button").addEventListener("click",(function(){var e=document.getElementById("community");e.classList.add("highlighted"),setTimeout((function(){e.classList.remove("highlighted")}),3e3)}));