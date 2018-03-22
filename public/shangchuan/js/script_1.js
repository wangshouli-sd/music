//窗口水平居中
$(window).resize(function(){
    tc_center();
});

function tc_center(){
    var _top=($(window).height()-$(".layerbox").height())/2;
    var _left=($(window).width()-$(".layerbox").width())/2;

    $(".layerbox").css({top:_top,left:_left});
}

$.get('/api/user',{},function (res) {if (res.code == 0) {alert(res.message);window.location.reload() ; }},'json');

$(document).ready(function(){

	//顶部下拉
	$(".head-nav").slide({type:"menu",titCell:".head-info",targetCell:".head-box",effect:"show",titOnClassName:"current",delayTime:100,triggerTime:100,returnDefault:true});

	//导航菜单
	$(".nav-bar ul").slide({type:"menu",titCell:".m",targetCell:".nav-menu",effect:"show",titOnClassName:"current",delayTime:100,triggerTime:100,returnDefault:true});

	//焦点图
	$(".fa-slide").slide({ mainCell:".pic",effect:"left", autoPlay:true, delayTime:300});

	//友情链接
	$(".fa-link").slide({ titCell:".hd a", mainCell:".bd",delayTime:0});

	//瀑布流代码
	var $container = $('.picbox');

    $(".lazy").scrollLoading({
     	callback: function() {
			$container.imagesLoaded(function(){
				$container.masonry('reload');
			});
		}
	});

	$container.imagesLoaded(function(){
		$container.masonry({
			itemSelector: '.item',
			columnWidth: 0 //每两列之间的间隙为5像素
		});
	});

	//浮动导航条和搜索框
	$(".search-fixTop").fixedPointShow({
		zIndex:100,
		st:200
	});

	//搜索TAG切换功能
    $(function(){
        $('.search-tab ul li').on('click',function () {
            $('.search-tab ul li').removeClass('selected');
            $(this).addClass('selected');
        });
    });

	//弹出框调用部分
	$(function(){
		//登录框
		$('.lg-layer').click(function() {
			$('.goodcover').show();
			$('#loginbox').fadeIn();
			tc_center();
		});
		$('.switch-close').click(function() {
			$('#loginbox').hide();
			$('.goodcover').hide();
		});
		$('.goodcover').click(function() {
			$('#loginbox').hide();
			$('.goodcover').hide();
		});

		//注册框
		$('.reg-layer').click(function() {
			$('.goodcover').show();
			$('#registerbox').fadeIn();
			tc_center();
		});
		$('.switch-close').click(function() {
			$('#registerbox').hide();
			$('.goodcover').hide();
		});
		$('.goodcover').click(function() {
			$('#registerbox').hide();
			$('.goodcover').hide();
		});

		//举报
		$('.jb-layer').click(function() {
			$('.overlay').show();
			$('.dialog-box').fadeIn();
			tc_center();
			var commentId = $(this).attr('comment-id');
			var report_user_id = $(this).attr('report-user-id');
			var pins_id = $(this).attr('pins-id');
			jbComment(commentId, report_user_id, pins_id);
		});
		
		$('.dialog-close').click(function() {
			$('.dialog-box').hide();
			$('.overlay').hide();
		});
		$('.overlay').click(function() {
			$('.dialog-box').hide();
			$('.overlay').hide();
		});


		$("#threelogin").click(function() {
			$("#otherway").show();
			$("#zhannei").hide();
		});
		$("#znlogin").click(function() {
			$("#otherway").hide();
			$("#zhannei").show();
		});

		function jbComment(comment_id, report_user_id, pins_id) {
			//alert(comment_id);
			$('#ReportForm .jb-btn').off().on('click', function() {

				var reason = $("input[name='reason']:checked").val();
				$.post('/api/report', {'pins_id': pins_id, 'comment_id': comment_id, 'reason': reason}, function(data) {
					$('.dialog-box').hide();
					$('.overlay').hide();
					alert(data.message);
				}, 'json');
			})
		}

	});

    $(".sign-but").on("click",function(){
    	$.get('/api/sign',{},function (data) {
			if (parseInt(data.code) === 200) {
				$('#sign .sign-prompt').html(data.message);
                $("#sign").show();
                $('.already-sign-but').show();
			}
        },'json');
    });

    $(".sign-m .but").click(function(){
        $("#sign").fadeOut()
    });


	//返回顶部
	$(function(){

		var $bottomTools = $('.bottom_tools');
		var $qrTools = $('.qr_tool');
		var qrImg = $('.qr_img');

		$(window).scroll(function () {
			var scrollHeight = $(document).height();
			var scrollTop = $(window).scrollTop();
			var $windowHeight = $(window).innerHeight();
			scrollTop > 50 ? $("#scrollUp").fadeIn(200).css("display","block") : $("#scrollUp").fadeOut(200);
			$bottomTools.css("bottom", scrollHeight - scrollTop > $windowHeight ? 40 : $windowHeight + scrollTop + 40 - scrollHeight);
		});

		$('#scrollUp').click(function (e) {
			e.preventDefault();
			$('html,body').animate({ scrollTop:0});
		});

		$qrTools.hover(function () {
			qrImg.fadeIn();
		}, function(){
			 qrImg.fadeOut();
		});

	});

	//评论回复事件
	$(function(){

		//设置一个定时器，为BUTTON点击事件用
		var timer=null;
		var number1=0;

		//处理输入的内容是文字还是字母的函数
		function getLength(str){
			return String(str).replace(/[^\x00-\xff]/g,'aa').length;
		};

		//评论事件
		$('#comment-box .comment-area').keyup(function(){
			if(!$(this).val()==''){
				$('#comment-box .btn-primary').removeClass('dis');
			}else{
				$('#comment-box .btn-primary').addClass('dis');
			}
			//Math函数向上取值
			var iNum=Math.ceil(getLength($(this).val())/2);
			if(iNum<=140){
				$('#comment-box span').html(140-iNum).css('color','');
			}else{
				$('#comment-box span').html(iNum-140).css('color','red');
				$('#comment-box .btn-primary').addClass('dis');
			}
		});

		$('#comment-box .btn-primary').click(function(){
			if($(this).hasClass('dis')){
				clearInterval(timer);
				timer=setInterval(function(){
					if(number1==4){
						clearInterval(timer);
						number1=0;
					}else{
						number1++;
					}
					if(number1%2){
						$('#comment-box .comment-area').css('background-color','#FFD9D9');
					}else{
						$('#comment-box .comment-area').css('background-color','');
					}
				},200);
			}else{

				$.post('/api/comment', {'pins_id': $('#pins-id').val(), 'content': $('#textarea').val()}, function(data) {
					if (parseInt(data.code) === 0) {
                        window.location.reload();
					}
				},'json');

				$('#comment-box .comment-area').val('');
				$(this).addClass('dis');
				$('#comment-box .comment-area').blur();
			}
		});

	});

});

/* qq表情插入代码 */
$(document).ready(function(e) {
	ImgIputHandler.Init();
});
var ImgIputHandler={
	facePath:[
		{faceName:"微笑",facePath:"0.gif"},
		{faceName:"撇嘴",facePath:"1.gif"},
		{faceName:"色",facePath:"2.gif"},
		{faceName:"发呆",facePath:"3.gif"},
		{faceName:"得意",facePath:"4.gif"},
		{faceName:"流泪",facePath:"5.gif"},
		{faceName:"害羞",facePath:"6.gif"},
		{faceName:"闭嘴",facePath:"7.gif"},
		{faceName:"大哭",facePath:"9.gif"},
		{faceName:"尴尬",facePath:"10.gif"},
		{faceName:"发怒",facePath:"11.gif"},
		{faceName:"调皮",facePath:"12.gif"},
		{faceName:"龇牙",facePath:"13.gif"},
		{faceName:"惊讶",facePath:"14.gif"},
		{faceName:"难过",facePath:"15.gif"},
		{faceName:"酷",facePath:"16.gif"},
		{faceName:"冷汗",facePath:"17.gif"},
		{faceName:"抓狂",facePath:"18.gif"},
		{faceName:"吐",facePath:"19.gif"},
		{faceName:"偷笑",facePath:"20.gif"},
		{faceName:"可爱",facePath:"21.gif"},
		{faceName:"白眼",facePath:"22.gif"},
		{faceName:"傲慢",facePath:"23.gif"},
		{faceName:"饥饿",facePath:"24.gif"},
		{faceName:"困",facePath:"25.gif"},
		{faceName:"惊恐",facePath:"26.gif"},
		{faceName:"流汗",facePath:"27.gif"},
		{faceName:"憨笑",facePath:"28.gif"},
		{faceName:"大兵",facePath:"29.gif"},
		{faceName:"奋斗",facePath:"30.gif"},
		{faceName:"咒骂",facePath:"31.gif"},
		{faceName:"疑问",facePath:"32.gif"},
		{faceName:"嘘",facePath:"33.gif"},
		{faceName:"晕",facePath:"34.gif"},
		{faceName:"折磨",facePath:"35.gif"},
		{faceName:"衰",facePath:"36.gif"},
		{faceName:"骷髅",facePath:"37.gif"},
		{faceName:"敲打",facePath:"38.gif"},
		{faceName:"再见",facePath:"39.gif"},
		{faceName:"擦汗",facePath:"40.gif"},
		{faceName:"抠鼻",facePath:"41.gif"},
		{faceName:"鼓掌",facePath:"42.gif"},
		{faceName:"糗大了",facePath:"43.gif"},
		{faceName:"坏笑",facePath:"44.gif"},
		{faceName:"左哼哼",facePath:"45.gif"},
		{faceName:"右哼哼",facePath:"46.gif"},
		{faceName:"哈欠",facePath:"47.gif"},
		{faceName:"鄙视",facePath:"48.gif"},
		{faceName:"委屈",facePath:"49.gif"},
		{faceName:"快哭了",facePath:"50.gif"},
		{faceName:"阴险",facePath:"51.gif"},
		{faceName:"亲亲",facePath:"52.gif"},
		{faceName:"吓",facePath:"53.gif"},
		{faceName:"可怜",facePath:"54.gif"},
		{faceName:"菜刀",facePath:"55.gif"},
		{faceName:"西瓜",facePath:"56.gif"},
		{faceName:"啤酒",facePath:"57.gif"},
		{faceName:"篮球",facePath:"58.gif"},
		{faceName:"乒乓",facePath:"59.gif"},
		{faceName:"拥抱",facePath:"78.gif"},
		{faceName:"握手",facePath:"81.gif"},
	]
	,
	Init:function(){
		var isShowImg=false;
		$("#textarea").focusout(function(){
			$(this).css("border-color", "#cccccc");
			$(this).css("box-shadow", "none");
			$(this).css("-moz-box-shadow", "none");
			$(this).css("-webkit-box-shadow", "none");
		});
		$("#textarea").focus(function(){
		$(this).css("border-color", "rgba(19,105,172,.75)");
		$(this).css("box-shadow", "0 0 3px rgba(19,105,192,.5)");
		$(this).css("-moz-box-shadow", "0 0 3px rgba(241,39,232,.5)");
		$(this).css("-webkit-box-shadow", "0 0 3px rgba(19,105,252,3)");
		});
		$(".bq-box").click(function(){
			if(isShowImg==false){
				isShowImg=true;
				$(this).parent().prev().show();
				if($(".faceDiv").children().length==0){
					for(var i=0;i<ImgIputHandler.facePath.length;i++){
						$(".faceDiv").append("<img title=\""+ImgIputHandler.facePath[i].faceName+"\" src=\"http://www.17sucai.com/static/i/face/"+ImgIputHandler.facePath[i].facePath+"\" />");
					}
					$(".faceDiv>img").click(function(){
						isShowImg=false;
						$(this).parent().hide();;
						ImgIputHandler.insertAtCursor($("#textarea")[0],"["+$(this).attr("title")+"]");
					});
				}
			}else{
				isShowImg=false;
				$(this).parent().prev().hide();
			}
		});
	},
	insertAtCursor:function(myField, myValue) {
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = myValue;
		sel.select();
	} else if (myField.selectionStart || myField.selectionStart == "0") {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		var restoreTop = myField.scrollTop;
		myField.value = myField.value.substring(0, startPos) + myValue + myField.value.substring(endPos, myField.value.length);
		if (restoreTop > 0) {
			myField.scrollTop = restoreTop;
		}
		myField.focus();
		myField.selectionStart = startPos + myValue.length;
		myField.selectionEnd = startPos + myValue.length;
	} else {
		myField.value += myValue;
		myField.focus();
	}
}
}

$(function() {
    $('#_fix_btn').on('click', function() {
        var kw = $('#_fix_search').val()
        if(kw) {
            window.location.href="http://www.17sucai.com/search/"+kw;
        }
    });

});

$(function() {
    $("#home-searchInput").on('click', function() {
        $('#search-hot').show();
    });
    $('#home-searchInput').on('keyup', function(a) {

        var keywords = $("#home-searchInput").val();
        $('#search-form').attr('action', '/search/'+keywords);
        $('#search-kw').hide();

        $.getJSON('/api/keywords?kw='+keywords, function(data) {

            $('#search-hot').hide();
            var kwHtml = '' ;
            $.each(data, function(key, v) {
                kwHtml += '<ul class="sokeyup_1"><li class="sokeyup_2">'+v.title+'</li><li class="sokeyup_3">'+v.count+' 结果</li></ul>';
            });

            $('#search-kw').html(kwHtml).show();
            $("#search-kw").on("click", ".sokeyup_1",
                function() {
                    var a = $("#search-kw .sokeyup_1").index(this),
                        b = $("#search-kw .sokeyup_2").eq(a).html();

                    $('#search-form').attr('action', '/search/'+b);
                    $("#search-form").submit();
                });
        });
    });

    $("#search-hot").on("click", ".sokeyup_1",
        function() {
            var a = $("#search-hot .sokeyup_1").index(this),
                b = $("#search-hot .sokeyup_2").eq(a).html();
            $('#search-form').attr('action', '/search/'+b);
            $("#search-form").submit();
        });
    $("#home-searchInput").on('blur', function() {

        setTimeout('$("#search-kw").hide()', 500);
        setTimeout('$("#search-hot").hide()', 500);
    });
})
