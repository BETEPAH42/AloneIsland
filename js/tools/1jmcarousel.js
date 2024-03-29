(function($){$.fn.jMyCarousel=function(o){o=$.extend({btnPrev:null,btnNext:null,mouseWheel:true,auto:false,speed:500,easing:'linear',vertical:false,circular:true,visible:'4',start:0,scroll:1,step:50,eltByElt:false,evtStart:'mouseover',evtStop:'mouseout',beforeStart:null,afterEnd:null},o||{});return this.each(function(){var running=false,animCss=o.vertical?"top":"left",sizeCss=o.vertical?"height":"width";var div=$(this),ul=$("ul",div),tLi=$("li",ul),tl=tLi.size(),v=o.visible;var mousewheelN=0;var defaultBtn=(o.btnNext===null&&o.btnPrev===null)?true:false;var cssU=(v.toString().indexOf("%")!=-1?'%':(v.toString().indexOf("px")!=-1)?'px':'el');var direction=null;if(o.circular){var imgSet=tLi.clone();ul.prepend(imgSet).append(imgSet.clone());}
var li=$("li",ul);div.css("visibility","visible");li.css("overflow","hidden").css("float",o.vertical?"none":"left").children().css("overflow","hidden");if(!o.vertical){li.css("display","inline");}
if(li.children().get(0).tagName.toLowerCase()=='a'&&!o.vertical){li.children().css('float','left');}
if(o.vertical&&jQuery.browser.msie){li.css('line-height','4px').children().css('margin-bottom','-4px');}
ul.css("margin","0").css("padding","0").css("position","relative").css("list-style-type","none").css("z-index","1");div.css("overflow","hidden").css("position","relative").css("z-index","2").css("left","0px");var liSize=o.vertical?height(li):width(li);var liSizeV=o.vertical?elHeight(li):height(li);var curr=o.start;var nbAllElts=li.size();var ulSize=liSize*nbAllElts;var nbElts=tl;var eltsSize=nbElts*liSize;var allEltsSize=nbAllElts*liSize;var step=o.step=='default'?liSize:o.step;o.btnPrev=defaultBtn?$('<input type="button" class="'+(o.vertical?'up':'prev')+'" />'):$(o.btnPrev);o.btnNext=defaultBtn?$('<input type="button" class="'+(o.vertical?'down':'next')+'" />'):$(o.btnNext);var prev=o.btnPrev;var next=o.btnNext;if(defaultBtn&&o.auto!==true){prev.css({'opacity':'0.6'});next.css({'opacity':'0.6'});div.prepend(prev);div.prepend(next);o.btnPrev=prev;o.btnNext=next;}
if(o.eltByElt){step=liSize;if(o.start%liSize!==0){var imgStart=parseInt(o.start/liSize);curr=o.start=(imgStart*liSize);}}
if(o.circular){o.start+=(liSize*tl);curr+=(liSize*tl);}
var divSize,cssSize,cssUnity;if(cssU=='%'){divSize=0;cssSize=parseInt(v);cssUnity="%";}
else if(cssU=='px'){divSize=parseInt(v);cssSize=parseInt(v);cssUnity="px";}
else{divSize=liSize*parseInt(v);cssSize=liSize*parseInt(v);cssUnity="px";}
ul.css(sizeCss,ulSize+"px").css(animCss,-(o.start));div.css(sizeCss,cssSize+cssUnity);if(o.vertical&&cssUnity=='%'){var pxsize=((liSize*nbElts)*(parseInt(v)/100));div.css(sizeCss,pxsize+'px');}
if(divSize===0){divSize=div.width();}
if(o.vertical){div.css("width",liSizeV+'px');ul.css("width",liSizeV+'px');li.css('margin-bottom',(parseInt(li.css('margin-bottom'))*2)+'px');li.eq(li.size()-1).css('margin-bottom',li.css('margin-top'));}else{div.css('height',liSizeV+'px');ul.css('height',liSizeV+'px');}
if(cssU=='%'){v=divSize/li.width();if(v%1!==0){v+=1;}
v=parseInt(v);}
var divVSize=div.height();if(defaultBtn){next.css({'z-index':200,'position':'absolute'});prev.css({'z-index':200,'position':'absolute'});if(o.vertical){prev.css({'width':prev.width(),'height':prev.height(),'top':'0px','left':parseInt(liSizeV/2)-parseInt(prev.width()/2)+'px'});next.css({'width':prev.width(),'height':prev.height(),'top':(divVSize-prev.height())+'px','left':parseInt(liSizeV/2)-parseInt(prev.width()/2)+'px'});}
else{prev.css({'left':'0px','top':parseInt(liSizeV/2)-parseInt(prev.height()/2)+'px'});next.css({'right':'0px','top':parseInt(liSizeV/2)-parseInt(prev.height()/2)+'px'});}}
if(o.btnPrev){$(o.btnPrev).bind(o.evtStart,function(){if(defaultBtn){o.btnPrev.css('opacity',0.9);}
running=true;direction='backward';return backward();});$(o.btnPrev).bind(o.evtStop,function(){if(defaultBtn){o.btnPrev.css('opacity',0.6);}
running=false;direction=null;return stop();});}
if(o.btnNext){$(o.btnNext).bind(o.evtStart,function(){if(defaultBtn){o.btnNext.css('opacity',0.9);}
running=true;direction='forward';return forward();});$(o.btnNext).bind(o.evtStop,function(){if(defaultBtn){o.btnNext.css('opacity',0.6);}
running=false;direction=null;return stop();});}
if(o.auto===true){running=true;forward();}
if(o.mouseWheel&&div.mousewheel){div.mousewheel(function(e,d){if(!o.circular&&(d>0?(curr+divSize<ulSize):(curr>0))||o.circular){mousewheelN+=1;if(running===false){if(d>0){forward(step,true);}
else{backward(step,true);}
running=true;}}});}
function forward(stepsize,once){var s=(stepsize?stepsize:step);if(running===true&&direction==="backward"){return;}
if(!o.circular){if(curr+s+(o.vertical?divVSize:divSize)>eltsSize){s=eltsSize-(curr+(o.vertical?divVSize:divSize));}}
ul.animate(animCss=="left"?{left:-(curr+s)}:{top:-(curr+s)},o.speed,o.easing,function(){curr+=s;if(o.circular){if(curr+(o.vertical?divVSize:divSize)+liSize>=allEltsSize){ul.css(o.vertical?'top':'left',-curr+eltsSize);curr-=eltsSize;}}
if(!once&&running){forward();}
else if(once){if(--mousewheelN>0){this.forward(step,true);}
else{running=false;direction=null;}}});}
function backward(stepsize,once){var s=(stepsize?stepsize:step);if(running===true&&direction==="forward"){return;}
if(!o.circular){if(curr-s<0){s=curr-0;}}
ul.animate(animCss=="left"?{left:-(curr-s)}:{top:-(curr-s)},o.speed,o.easing,function(){curr-=s;if(o.circular){if(curr<=liSize){ul.css(o.vertical?'top':'left',-(curr+eltsSize));curr+=eltsSize;}}
if(!once&&running){backward();}
else if(once){if(--mousewheelN>0){backward(step,true);}
else{running=false;direction=null;}}});}
function stop(){if(!o.eltByElt){ul.stop();curr=0-parseInt(ul.css(animCss));}
running=false;direction=null;}
function imgSize(el,dimension){if(dimension=='width'){return el.find('img').width();}
else{return el.find('img').height();}}
function elHeight(el){var elImg=el.find('img');if(o.vertical){return parseInt(el.css('margin-left'))+parseInt(el.css('margin-right'))+parseInt(elImg.width())+parseInt(el.css('border-left-width'))+parseInt(el.css('border-right-width'))+parseInt(el.css('padding-right'))+parseInt(el.css('padding-left'));}
else{return parseInt(el.css('margin-top'))+parseInt(el.css('margin-bottom'))+parseInt(elImg.width())+parseInt(el.css('border-top-height'))+parseInt(el.css('border-bottom-height'))+parseInt(el.css('padding-top'))+parseInt(el.css('padding-bottom'));}}
function debug(html){$('#debug').html($('#debug').html()+html+"<br/>");}});};function css(el,prop){return parseInt($.css(el[0],prop))||0;}
function width(el){return el[0].offsetWidth+css(el,'marginLeft')+css(el,'marginRight');}
function height(el){return el[0].offsetHeight+css(el,'marginTop')+css(el,'marginBottom');}})(jQuery);