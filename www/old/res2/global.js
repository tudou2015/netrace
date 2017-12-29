// JavaScript Document
$(document).ready(function(){
//**************导航**************//
  $("#menu li.pli").mouseenter(function(){
    $("#menu li.pli .sub").hide();
	$(this).find(".sub").show();
	$("#menu li.pli").removeClass("curt");	
	$(this).addClass("curt");
  });
  $("#menu li.pli ul.sub").mouseleave(function(){
	$(this).fadeOut();
  });
//**************列表颜色**************//  
  $("ul.list li:odd").css("background-color","#f5f5f5");
  $("ul.list li:odd").mouseenter(function(){
    $(this).css("background-color","#e8eff1");	
  });
  $("ul.list li:odd").mouseleave(function(){
	$(this).css("background-color","#f5f5f5");
  });
  $("ul.list li:even").css("background-color","#fff");
  $("ul.list li:even").mouseenter(function(){
    $(this).css("background-color","#e8eff1");	
  });
  $("ul.list li:even").mouseleave(function(){
	$(this).css("background-color","#fff");
  });
//**************列表颜色**************//
   
  $(".table_1 tr:even").css("background-color","#dee4ef");
  $(".table_1 tr.f").css("background-color","#a3adbf"); 
//**************应用盒子**************//  
  $("ul.appbox li").mouseenter(function(){
    $(this).addClass("on");	
  });  
  $("ul.appbox li").mouseleave(function(){
	$(this).removeClass("on");
  });  
//**************正文字体大小**************//
  $("#s").click(function(){
	$(".b").css("font-size","14px");
	$("#s").css("font-weight","bold");
	$("#b").css("font-weight","normal");
	$("#m").css("font-weight","normal"); 
  });
  $("#m").click(function(){
	$("#m").css("font-weight","bold");
	$("#s").css("font-weight","normal");
	$("#b").css("font-weight","normal");  
	$(".b").css("font-size","16px");
  });
  $("#b").click(function(){
	$("#b").css("font-weight","bold");
	$(".b").css("font-size","18px");
	$("#m").css("font-weight","normal");
	$("#s").css("font-weight","normal");
  });
  
//**************列表颜色**************//  
  var imgwidth = $(".arcbody img").width();
  if(imgwidth > 850) {
	$(this).width("850"); 
  };
//**************弹窗**************// 
  $("#login").click(function(){ 
  	$("#fadebox").height($(document).height()); 
	//$("#fadebox").width($(document.body).width());
	$("#fadebox").width("100%");
	$("#fadebox").show();
	$("#memberbox").fadeIn();
	//$("#fadebox").height("100%");
  });
  $(".close").click(function(){ 
	$("#fadebox").hide();
	$("#memberbox").hide();
  });
  $("#focus").width($(document.body).width());
  $("#focus ul li").width($(document.body).width());
});
//**************滚动**************// 

//**************弹窗**************// 
$(function(){
     var len  = $(".num > li").length;
	 var index = 0;
	 var adTimer;
	 $(".num li").mouseover(function(){
		index  =   $(".num li").index(this);
		showImg(index);
	 }).eq(0).mouseover();	
	 //滑入 停止动画，滑出开始动画.
	 $('.ad').hover(function(){
			 clearInterval(adTimer);
		 },function(){
			 adTimer = setInterval(function(){
			    showImg(index)
				index++;
				if(index==len){index=0;}
			  } , 5000);
	 }).trigger("mouseleave");
})
// 通过控制top ，来显示不同的幻灯片
function showImg(index){
        var adHeight = $(".slie .ad").height();
		$(".slider").stop(true,false).animate({top : -adHeight*index},1000);
		$(".num li").removeClass("on")
			.eq(index).addClass("on");
};
