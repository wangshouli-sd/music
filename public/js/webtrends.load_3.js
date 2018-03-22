// WebTrends SmartSource Data Collector Tag
// Version: V10.0  
// Modify: 20160908 by YJ

window.webtrendsAsyncInit=function(){ 
	var dcs=new Webtrends.dcs().init({ 
		domain:"wtrace.cmgame.com", 
		dcsid:"dcs04rceqcjgi98mnrh6y56rt_5c6j",
		fpcdom:".migu.cn", 
		timezone:8, 
		i18n:true, 
		dcsdelay: 500,
		plugins:{} 
	});

	//WebSite Name;
	dcs.WT.branch="mg_web";
	
	//Tracking Custom Data;
	try{
	}catch(ce){};

	//Tracking Trigger;
	dcs.track();
	

	//overwrite trackEvent of v9;
	dcs.addSelector("input,a", {
		transform: function(dcsObject, multiTrackObject) {
			var e = multiTrackObject.element || {};
			var evt = multiTrackObject.event || {};
			var t,j;
			if (!e.type){
				if(e.id) t=e.id;
				else t=e.pathname?e.pathname:"unknown";
				j="Link";
			}else{
				t = e.id||e.name||"Unknown";
				j=e.type;
			}
			multiTrackObject.argsa.push("DCS.dcsuri","/event.gif","WT.event",t,"WT.obj",j,"WT.nv",_tag.N(evt),"WT.pos",_tag.P(evt));
		},
		delayTime: 100
	});




};


if (!window._tag){
	function _wt() {};
	_wt.prototype.trackEvent = function() {	};
	_wt.prototype.E=function($h,$i){var e=$h.target||$h.srcElement;while(e.tagName&&(e.tagName.toLowerCase()!=$i.toLowerCase())){e=e.parentElement||e.parentNode;e=e||{};};return e;};
	_wt.prototype.P=function($h){var x=$h.clientX;var y=$h.clientY;$j=(document.documentElement!=undefined&&document.documentElement.clientHeight!=0)?document.documentElement:document.body;var $k=window.pageXOffset==undefined?$j.scrollLeft:window.pageXOffset;var $l=window.pageYOffset==undefined?$j.scrollTop:window.pageYOffset;return(x+$k)+"x"+(y+$l);};
	_wt.prototype.N=function($h){var id="";var $m="";var $c=["div","table"];var $n=$c.length;var i,e,$o;for(i=0;i<$n;i++){$o=$c[i];if($o.length){e=this.E($h,$o);id=(e.getAttribute&&e.getAttribute("id"))?e.getAttribute("id"):"";$m=e.className||"";if(id.length||$m.length)break;}};return id.length?id:$m;};Function.prototype.wtbind=function($p){var $q=this;var $r=function(){return $q.apply($p,arguments);};return $r;};
	_wt.prototype.dcsMultiTrack=function(){
		if(window.Webtrends)Webtrends.multiTrack({"argsa":arguments,delayTime: 100});	
	}
	var _tag=new _wt();
}

(function(){ 
	var s=document.createElement("script"); s.async=true; s.src="/assets/pc/js/webtrends.min.js"; 
	var s2=document.getElementsByTagName("script")[0]; s2.parentNode.insertBefore(s,s2); 
}()); 
