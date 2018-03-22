(function($){
	var tools={
		TimeOutRun:function (obj, Event, callBack, time) {
			var timeOutId = "",
					reStart = true;
			obj.on(Event, function () {
				clearTimeout(timeOutId);
				if (reStart) {
					timeOutRun(time)
				}
			});
			function timeOutRun(time) {
				reStart = false;
				var timeOutId = setTimeout(function () {
					reStart = true;
					callBack()
				}, time);
			}
		},
		log:function(log){
			if(window.console){}
		}
	};
	function FixedPointShow($el,config){
		var def = {
			zIndex:100,        //Z轴
			st:200               //滚动多少距离的时候出现导航
		};
		this.opts = $.extend(true,def,config);
		this.el = $el;
	}
	FixedPointShow.prototype = {
		scrollNav:function(){
			var isOpen = false,This = this,
				fixTopH = This['el'].outerHeight();

			This['el'].css({"z-index":this.opts.zIndex});

			tools.TimeOutRun($(window),"scroll",function(){
				var st = $(this).scrollTop();
				if(st > This.opts.st){
					!isOpen && This.el.animate({"top":0},400,function(){
						isOpen = true;
					});
				}else{
					This.el.animate({"top":-fixTopH},400,function(){
						isOpen = false;
					});
				}
				tools.log(st)
			},200)
		}
	};
	FixedPointShow.prototype.constructor = FixedPointShow;
	$.extend($.fn,{
		fixedPointShow:function(config){
			//IE6 返回
			if(!("maxHeight" in document.createElement("div").style)){
				$(this).remove();
				return
			}
			var fixedPointNav = new FixedPointShow($(this));
			fixedPointNav.scrollNav();
			return this;
		}
	});
}(jQuery));