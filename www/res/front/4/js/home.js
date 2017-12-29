
$(document).ready(function(){
    L.init();
});
var sys=new function(){
  this.post=function(url,par,fun,msg){
      if(typeof(msg)!='string'||msg==''){
          msg="操作成功";
      }
      if(typeof(fun)!='function'){
          fun=function(data){
             L.msg(msg);
          };
      }
      $.post(url,par,function(data){
            if(data.code!=0){
                 L.Dlg(data.data);
                  return ;
              }else{
                  fun(data);
              }
      },'json');
  };  
};



var L=new function(){
    
     this.select=function(tclass){
         var li=$(tclass+" .ace[gid]:checked");
        var len=li.length;
        if(len===0){           
            return "";}
        var slist=new Array();
        for(var i=0;i<len;i++){
            slist.push($(li[i]).attr("gid"));
        }
        var str=slist.join(",");
        return str;
    };
    
    
    
    
    
    
       this.onenter=function(k){
        if(k.keyCode!==13) return;
        var jstr=$(this).attr("onenter");
       eval(jstr);
    };
     this.msg=function(t){
        artDialog.tips(t);
    };
     this.Dlg=function(t){
            art.dialog({
                    content:t,
                    ok: true
                   });
};
    
    this.init=function(){
        
         $("[onenter]").keyup(L.onenter);
        
        artDialog.tips = function (content, time) {
    return artDialog({
        id: 'Tips',
        title: false,
        cancel: false,
        fixed: true,
        lock: false
    })
    .content('<div style="padding: 0 1em;">' + content + '</div>')
    .time(time || 1.5);
};

$(".support [vkey]").click(home_vkey_click);

 
  $("[data_vkey] .votebtn").click(fenxiang_li);
  
  $('#m_case_district,#m_case_city').change(m_case_district_change);
  
  $('.mobile_video .video').click(video_click);
 
 
 
 $('.fenxiang_yixin').click(fenxiang_yixin);
  $('.fenxiang_yixin_s').click(fenxiang_yixin_s);
  
    $('.fenxiang_s').click(fenxiang_s);
  
  
  $('[level]').mousemove(level_mouse);
 
 
 /*
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////临时关闭投票功能
 if($('.votebtn')[0]){
 $(".votebtn").addClass('disabled'); $(".votebtn").attr('title','本次大赛入围作品投票将于9月10日开放');
 
 $(".vote .tip").removeClass('none');
 $("#vote_msg").text('本次大赛入围作品投票将于9月10日开放');
 }
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////临时关闭投票功能
 */
 
 
 
 
 $('#video_content .bdsharebuttonbox [mdata]').click(function (){
     window._bd_share_config.share.bdText='唱的太棒啦！快来三金西瓜霜#网易云音乐校园歌手大赛#为TA投票吧！校园歌王争霸，唱出你的态度！顶级唱片公司加盟，大牌明星现场助阵，更多有态度的音乐，尽在比赛官网！一起来参与！http://xy.music.163.com/——网易云音乐，音乐有态度，持学生证到必胜客，立享八折！'; 
     window._bd_share_config.share.bdUrl= location.href;
     
      fenxiangtongji($(this).attr('mdata'), location.href);
 });
 
 
 
 
 
 
 
    }; 
};



var video;
//// video=new VIDEO();
function VIDEO(){
 
    this.get_video_input=function(){
        var d={}; 
        d.city=$('#city').val();
        d.district=$("#district").val();
        if(d.city==='null'||d.district==='null'){
            return false;
        }
        
        d.name=$('#name').val();
        d.gender=$("#gender").val();
        d.summary=$('#summary').val();
        d.works=$("#works").val();
        d.video_url=$('#video_url').val();
        d.school=$("#school").val();
        d.phone=$('#phone').val();
        d.img=$("#fimg").attr('src');
        d.playlength=$('#playlength').val();
        d.vid=$('#vid').val();
        d.video_name=$('#video_name').val();
        return d; 
    };
    
    this.praise=function(key){
        sys.post('/api/add_praise_video.html',{key:key});
    };
    
    this.init=function(){
        $("#district").change(function(){
            var v=$(this).val();
            if(v!=='null'){
                video.up_type('district',video.DE_OK);
            }else{
                video.up_type('district',video.DE_ERROR);
            }
        });
        
       $("#city").change(function(){
            var v=$(this).val();
            if(v!=='null'){
                video.up_type('city',video.DE_OK);
            }else{
                video.up_type('city',video.DE_ERROR);
            }
        });  
        
         $("#school").change(function(){
            var v=$(this).val();
            if(v!==''){
                video.up_type('school',video.DE_OK);
            }else{
                video.up_type('school',video.DE_ERROR);
            }
        });  
        
          $("#name").change(function(){
            var v=$(this).val();
            if(v!==''){
                video.up_type('name',video.DE_OK);
            }else{
                video.up_type('name',video.DE_ERROR);
            }
        });  
        
        
          $("#school").change(function(){
            var v=$(this).val();
            if(v!==''){
                video.up_type('school',video.DE_OK);
            }else{
                video.up_type('school',video.DE_ERROR);
            }
        });  
        
        
          $("#phone").change(function(){
            var v=$(this).val();
            if(v!==''){
                video.up_type('phone',video.DE_OK);
            }else{
                video.up_type('phone',video.DE_ERROR);
            }
        });  
        
        /*
        $("#summary").change(function(){
            var v=$(this).val();
            if(v!==''){
                video.up_type('summary',video.DE_OK);
            }else{
                video.up_type('summary',video.DE_ERROR);
            }
        });  
        */
        
          $("#works").change(function(){
            var v=$(this).val();
            if(v!==''){
                video.up_type('works',video.DE_OK);
            }else{
                video.up_type('works',video.DE_ERROR);
            }
        });  
        
        
      $(".submitbtn").click(video.submitbtn);  
        
        
    };
    this.submitbtn=function(){
        
        if(!$('#agreement').is(':checked')){
            L.msg("您必须选择\"我已经阅读并同意\"");
            return ;
        }
        
        
         L.msg('正在提交...');
        //	document.domain = "163.com";
            var v=$("#district").val();
            if(v!=='null'){
                video.up_type('district',video.DE_OK);
            }else{
                video.up_type('district',video.DE_ERROR);
            }
    
        
       
            var v= $("#city").val();
            if(v!=='null'){
                video.up_type('city',video.DE_OK);
            }else{
                video.up_type('city',video.DE_ERROR);
            }
         
        
            var v= $("#school").val();
            if(v!==''){
                video.up_type('school',video.DE_OK);
            }else{
                video.up_type('school',video.DE_ERROR);
            }
           var v= $("#name").val();
            if(v!==''){
                video.up_type('name',video.DE_OK);
            }else{
                video.up_type('name',video.DE_ERROR);
            }
           var v= $("#school").val();
            if(v!==''){
                video.up_type('school',video.DE_OK);
            }else{
                video.up_type('school',video.DE_ERROR);
            }
           var v= $("#phone").val();
            if(v!==''){
                video.up_type('phone',video.DE_OK);
            }else{
                video.up_type('phone',video.DE_ERROR);
            }
            /*
             var v= $("#summary").val();
            if(v!==''){
                video.up_type('summary',video.DE_OK);
            }else{
                video.up_type('summary',video.DE_ERROR);
            } 
            */
            
            var v= $("#works").val();
            if(v!==''){
                video.up_type('works',video.DE_OK);
            }else{
                video.up_type('works',video.DE_ERROR);
            }
            
            var errl=$('.tishi .error');///获取错误的列表
            for(var i=0;i<errl.length;i++){
                var ob=$(errl[i]);
                var st=ob.css('display');
                if(st==='block'){
                     L.msg('填写的信息有错误,请修改');
                return ;
                }
            }
             
       /**
             var data=upvideo.shangchuan.data;
            if(data===false){
                L.msg('没有上传视频,请上传');
                return ;
            }
            if(data.msg!=='ok'){
                  L.msg('出现错误,请从新上传:'.data.msg);
                return ;
            }
            
            if(data.playlength>120){
                  L.msg('时长不超过2分钟');
                return ;
            }
            */
             if($('.upload_success').css('display')==='none'){
                L.msg('没有上传视频,请上传');
                return ;
            }
           
           
           
            
            
            if(upvideo.is_submitb===false)///如果没有提交视频,那么提交视频,或者直接提交表单
               ///  upvideo.submitb();///提交视频,视频提交成功以后,会有个事件,提交表单
                   video_post();
             else
                 video.wancheng_submit();
    };
    
    
    
    
    
    this.up_type=function(id,cla){
         $('[tishi='+id+']').find('span').css('display','none');
        $('[tishi='+id+']').find('.'+cla).css('display','block');
    };
    this.DE_OK='ok';
    this.DE_ERROR='error';
    
    
    this.wancheng_submit=function(){////提交视频后提交资料
         var data=video.get_video_input();
        if(data===false){
            L.msg("查填写的信息不完整");
            return ;
        }
          $.post('/index.php/home/I/api_add_video',data,function(sd){
            if(sd.code!==0){
                alert(sd.data);
            }else{
                location.reload();
            }
        },'json');
    };
    
    
    
    
}
var upvideo;
////upvideo =new _UPVIDEO();
function _UPVIDEO(){
  
   this.shangchuan_if=document.getElementById('shangchuan_if').contentWindow;
   this.shangchuan=this.shangchuan_if.window.upfile;
   this.shangchuan.wancheng=function(){
        upvideo.fanhui=upvideo.shangchuan.fanhui;
          $("#img").val(upvideo.fanhui.imgpath); 
          $("#video_url").val(upvideo.fanhui.pageurl);
          $("#vid").val(upvideo.fanhui.vid); 
          video.wancheng_submit();
   };
 //    document.domain="163.com";
   this.shangchuan.jindu=function(jindu){
              $("#uploadRate").text(jindu); 
           $('#jindu_style').css('width',jindu);
   };
    this.shangchuan.timeRemaining=function(jindu){
          
   };
     this.shangchuan.uploadRate=function(jindu){
       
   };
      this.shangchuan.begin_up=function(){
           $(".up_time").css('display','block');
            $(".iframe_video_li").css('display','none');
            $("#video_name").val(upvideo.shangchuan.file_name);
            $('#file_name_span').text(upvideo.shangchuan.file_name);
   };
  this.shangchuan.uploadcomplete=function(){////上传完成
      
          if(upvideo.shangchuan.data.playlength>120){
                  L.msg('时长不超过2分钟');
                return ;
            }
      
      
         if(typeof(upvideo.shangchuan.data.snapshot[2])==='string'){
          $("#fimg").attr('src',upvideo.shangchuan.data.snapshot[2]);}
    
          $("#playlength").val(upvideo.shangchuan.data.playlength);
          $(".up_time").css('display','none');
          $(".upload_success").css('display','block');
          
          
          
          
          upvideo.is_upvideo=true;
   };
   
     this.shangchuan.getdata=function(){////获取视频信息
    var data={};
        var L=upvideo.shangchuan.data;
        data.rvideoid = L.vid;
        data.imagepath =$("#fimg").attr('src');
        data.filepath = L.filepath;
        data.soureip = L.soureip;
        data.playlength = L.playlength;
        data.filename = L.filename;
        
        data.title=$("#works").val();
       data.description=$("#summary").val();
        data.tag =$("#works").val();////     : 视频标签
       data.topicid='1000';////频道号
       data.type='89';
        return data;
   };
   
   
   
   
   
   
   
   
   this.Cancel=function(){
     upvideo.shangchuan_if.location.reload();
       $(".up_time").css('display','none');
       $(".upload_success").css('display','none');
        $('#jindu_style').css('width','0%');
     //  $("#shangchuan_if").css('display','block');
   };
   
   this.fanhui=false;
   this.chongchuan=function(){
    //   $(".upload_success").css('display','none');
    //   $(".up_time").css('display','none');
        upvideo.Cancel();
   };
   
   this.genghuanimg=function(){
     
        if(upvideo.img_s<upvideo.shangchuan.data.snapshot.length){upvideo.img_s++;}else{upvideo.img_s=0;}
           $("#fimg").attr('src',upvideo.shangchuan.data.snapshot[upvideo.img_s]);
   };
   
   this.img_s=0;
   
   
   this.submitb=function(){
       upvideo.shangchuan.post();
   };
   
   this.is_submitb=false;///用来标记是否提交视频了
   this.is_upvideo=false;///标记没有上传
  this.data;
}

 
 
 
function vote(vkey){
    if($('.vote .votebtn').hasClass('disabled')) return;
    
    L.msg('提交中...'); 
    $.post('/api/add_praise_video.html',{vkey:vkey,jiami:jiami_post()},function(data){
         if(data.code!=0){
               ///  L.Dlg(data.data);
               $(".vote .votebtn").addClass('disabled');
               $(".vote .tip").removeClass('none');
               $("#vote_msg").text(data.data);
                  return ;
              }else{
                  $("#praise").text(data.data);
              }
              
    },'json');
}
function home_vkey_click(){
	return;////去掉大拇指投票功能
    L.msg('提交中...');
    var obj=$(this);
    var vkey=obj.attr('vkey');
        $.post('/api/add_praise_video.html',{vkey:vkey},function(data){
         if(data.code!=0){
               ///  L.Dlg(data.data);
               L.msg(data.data);
                  return ;
              }else{
                  obj.text(data.data);
              }
              
    },'json');
}

function fenxiang_li(){ 
    var obj=$(this).parent();
    var vkey=obj.attr('data_vkey');
        if(obj.find('.votebtn').hasClass('disabled')){
            return;
        }
    
     L.msg('提交中...'); 
      $.post('/api/add_praise_video.html',{vkey:vkey,jiami:jiami_post()},function(data){
         if(data.code!=0){
               ///  L.Dlg(data.data);
               L.msg(data.data);
                obj.find('.votebtn').addClass('disabled');
                obj.find('.tip').css('display','block');
                obj.find('.msg').text(data.data);
                if(data.data==='一天只能投10张票,明天再来吧'){
                    $(".votebtn").addClass('disabled');
                }
                  return ;
              }else{
                  obj.find('.support [vkey]').text(data.data);
              }
              
    },'json');
}


function weiruwei_sousuo(){
    var dis=$('#weiruwei_dis').val();
    var key=$('#weiruwei_val').val();
    location.href='/index.php/home/i/case?district='+dis+'&key='+encodeURIComponent(key)+'#not_in';
}

function m_case_district_change(){
    var dis=$('#m_case_district').val();
    var city=$('#m_case_city').val();
    location.href='/index.php/home/i/case?district='+dis+'&city='+city+'#votecontent';
}

function case_sousuo_click(){
    var case_sousuo_type=$('#case_sousuo_type').val();
    var key=$('#case_input').val();
    location.href='/index.php/home/i/case?case_sousuo_type='+case_sousuo_type+'&key='+encodeURIComponent(key)+'#not_in';
}
function bofang(){
    $('.mobile_video .video_img').css('display','none');
      $('.mobile_video .video_play').css('display','none');
    $('.mobile_video .video')[0].play();  
    
}

function video_click(){
    if(this.paused) {  
 
this.play();  
 
}  
 
else {  
 
this.pause();  
 
}  
}


function  video_post(){
    upvideo.is_submitb=true;
    var data=upvideo.shangchuan.getdata();
    document.domain="163.com"; 
    var so_iframe=document.getElementById('crossdomain').contentWindow;
    var YUI=so_iframe.window.Y; 
      YUI.io("http://so.v.163.com/ugc/addvideoasync.do", {
					method: "POST",
					data:data,
					on: {
						success: function(M, L) {
                                                    
                                                //    document.domain=old_url;
                                                 ///   var fh=YUI.JSON.parse(L.responseText);
                                                  var  fh=eval('('+L.responseText+')');
                                                   
							if ( fh.info == "add success") { 
                                                         ///  $("#img").val(upvideo.fanhui.imgpath); 
                                                            $("#video_url").val(fh.pageurl);
                                                            $("#vid").val(fh.vid); 
                                                          
                                                            video.wancheng_submit();
							}else{
                                                            alert('出错了:'+fh.info);
                                                        }
						}
					}
				});
}


function fenxiang_yixin(){
    /*
     * appkey ：你在易信开放平台申请的appId，审核通过的应用将在内容来源处显示你填写的应用名称，否则显示“未审核应用”。用户在分享界面不可修改。
type ：图片分享类型的分享请设置为"webpage"。用户在分享界面不可修改。
title ：从你的应用中预设分享出去的标题。用户在分享界面不可修改。
desc ：从你的应用中预设分享出去的文本，用户在分享界面不可修改。
userdesc ：你的应用为用户预设的输入文本，用户可自行修改。
pic ：缩略图url，建议尺寸300px*300px。用户在分享界面不可修改。
url ：该图文资源对应的网页地址url，易信中点击该消息时将调用webview进行访问。用户在分享界面不可修改。该字段必需。
     */
    var sharedata={
        appkey:'yx67d6fb9a5fae4408bb2c7dc188a9d972',
        type:'webpage',
        userdesc:'唱的太棒啦！快来三金西瓜霜#网易云音乐校园歌手大赛#为TA投票吧！校园歌王争霸，唱出你的态度！顶级唱片公司加盟，大牌明星现场助阵，更多有态度的音乐，尽在比赛官网！一起来参与！http://xy.music.163.com/——网易云音乐，音乐有态度，持学生证到必胜客，立享八折！',
        pic:$('#fimg').val(),
        url:location.href
    };
    shareToYixin(sharedata);
}
function fenxiang_yixin_s(){
    var obj=$(this).parent().parent();
    obj;
    var fxjson=obj.attr('data');
    var fxdata=eval('('+fxjson+')');
        var sharedata={
        appkey:'yx67d6fb9a5fae4408bb2c7dc188a9d972',
        type:'webpage',
        userdesc:fxdata.text,
        pic:fxdata.pic,
        url:fxdata.url
    };
    shareToYixin(sharedata);
    fenxiangtongji('yx',fxdata.url);
    return false;
}


function shareToYixin(sharedata){
    var _s=[];
    for(var i in sharedata){
        if(sharedata.hasOwnProperty(i)){
            sharedata[i]!=null&&_s.push(
                    i.toString()+'='+encodeURIComponent(sharedata[i].toString()||'')
                    );
        }
    }
    var url='http://open.yixin.im/share?';
    window.open(url+_s.join('&'));
}

function fenxiang_s(){
        var obj=$(this).parent().parent(); 
    var fxjson=obj.attr('data');
    var fxdata=eval('('+fxjson+')'); 
    window._bd_share_config.share.bdText=fxdata.text;
     window._bd_share_config.share.bdPic=fxdata.pic;
     window._bd_share_config.share.bdUrl=fxdata.url;
     
   fenxiangtongji($(this).attr('mdata'),fxdata.url);
     
}
function xuanshouchengnuo(){
    $('#fullcontent,#agreeflow').css('display','block');
 $('#agreeflow .close').click(function(){
    $('#fullcontent,#agreeflow').css('display','none');
});
$("#fullcontent").css("width", (document.body.clientWidth > document.documentElement.clientWidth ? document.body.clientWidth : document.documentElement.clientWidth)-1);
$("#fullcontent").css("height", document.body.clientHeight > document.documentElement.clientHeight ? document.body.clientHeight : document.documentElement.clientHeight);

}

 function level_mouse(){
 //   var level= $(this).attr('level');
  //  $('#step_index').attr('class','step step'+level);
  
 }


function step_li_level(th){
    var href=$(th).find('a').attr('href');
    location.href=href;
}




$(document).ready(
function()
{
   
    
    /*////////////////////////////////////////////如果有这一行，就会没有滑动功能了
     $("#videomenu span").mouseover(
  function()
  {
	current = $(this);
    var num= $(this).attr("tabindex");
    $("#videolist .itemlist").animate({left:-900*num},'slow');
    $('#videomenu span').attr('class','').eq(num).attr('class','current');  
  }
  );
  return ;
    */////////////////////////////////////////////////////
    
    
    
  $(".listitem li").hover(
  function(){$(this).addClass("current");},
  function(){$(this).removeClass("current");}
  );
  $(".listitem li .share").hover(
  function(){
   $(this).find(".none").show();
  },
  function(){
   $(this).find(".none").hide();
  }
  );

});
function slideContentChange(args) {
					$('#videomenu span').removeClass('current');
					$('#videomenu span:eq(' + (args.currentSlideNumber - 1) + ')').addClass('current');
					/* indicator */
					//$(args.sliderObject).parent().parent().find('.iosSliderButtons .button').removeClass('selected');
					//$(args.sliderObject).parent().parent().find('.iosSliderButtons .button:eq(' + (args.currentSlideNumber - 1) + ')').addClass('selected');
					
				}
				
				function slideContentComplete(args) {
					
					if(!args.slideChanged) return false;
					
					/* animation */
					$(args.sliderObject).find('.text1, .text2').attr('style', '');
					
					$(args.currentSlideObject).children('.text1').animate({
						right: '100px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					
					$(args.currentSlideObject).children('.text2').delay(200).animate({
						right: '50px',
						opacity: '1'
					}, 400, 'easeOutQuint');
					
				}
				
function slideContentLoaded(args) {

        /* animation */
        $(args.sliderObject).find('.text1, .text2').attr('style', '');

        $(args.currentSlideObject).children('.text1').animate({
                right: '100px',
                opacity: '1'
        }, 400, 'easeOutQuint');

        $(args.currentSlideObject).children('.text2').delay(200).animate({
                right: '50px',
                opacity: '1'
        }, 400, 'easeOutQuint');

        /* indicator */
        $('#videomenu span').removeClass('current');
        $('#videomenu span:eq(' + (args.currentSlideNumber - 1) + ')').addClass('current');

}

function fenxiangtongji(value,source){ 
    $.post('/api/fenxiangtongji.html',{value:value,source:source},function(d){
        
    }); 
}






/**
*
*  Base64 encode / decode
*
*  @author haitao.tu
*  @date   2010-04-26
*  @email  tuhaitao@foxmail.com
*
*/
 
function Base64() {
 
	// private property
	_keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
 
	// public method for encoding
	this.encode = function (input) {
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
		input = _utf8_encode(input);
		while (i < input.length) {
			chr1 = input.charCodeAt(i++);
			chr2 = input.charCodeAt(i++);
			chr3 = input.charCodeAt(i++);
			enc1 = chr1 >> 2;
			enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
			enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
			enc4 = chr3 & 63;
			if (isNaN(chr2)) {
				enc3 = enc4 = 64;
			} else if (isNaN(chr3)) {
				enc4 = 64;
			}
			output = output +
			_keyStr.charAt(enc1) + _keyStr.charAt(enc2) +
			_keyStr.charAt(enc3) + _keyStr.charAt(enc4);
		}
		return output;
	};
 
	// public method for decoding
	this.decode = function (input) {
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		while (i < input.length) {
			enc1 = _keyStr.indexOf(input.charAt(i++));
			enc2 = _keyStr.indexOf(input.charAt(i++));
			enc3 = _keyStr.indexOf(input.charAt(i++));
			enc4 = _keyStr.indexOf(input.charAt(i++));
			chr1 = (enc1 << 2) | (enc2 >> 4);
			chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
			chr3 = ((enc3 & 3) << 6) | enc4;
			output = output + String.fromCharCode(chr1);
			if (enc3 != 64) {
				output = output + String.fromCharCode(chr2);
			}
			if (enc4 != 64) {
				output = output + String.fromCharCode(chr3);
			}
		}
		output = _utf8_decode(output);
		return output;
	};
 
	// private method for UTF-8 encoding
	_utf8_encode = function (string) {
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
		for (var n = 0; n < string.length; n++) {
			var c = string.charCodeAt(n);
			if (c < 128) {
				utftext += String.fromCharCode(c);
			} else if((c > 127) && (c < 2048)) {
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			} else {
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
		return utftext;
	};
 
	// private method for UTF-8 decoding
	_utf8_decode = function (utftext) {
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while ( i < utftext.length ) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			} else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			} else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	};
}
/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (value !== undefined && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {};

		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}

		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));


/*

function jiami_post(){
    var key_word=$.cookie('key_word');
    var usera=navigator.userAgent;
    var hei=window.screen.height;
    var wid=window.screen.width;
    var poda={};
    poda.usera=usera;
    poda.usera=usera;
    poda.hei=hei;
    poda.wid=wid;
    poda.time=$('#keypassword').val();
    poda.key_word=key_word;
    var str=JSON.stringify(poda); 
    
      var ba=new Base64();
      str=ba.encode(str);
    
    return str;
      ///  key_word = "7240501409911147";
       //     poda.time =key_word;
     
    
        var key = CryptoJS.enc.Latin1.parse(key_word);
        var iv =    CryptoJS.enc.Latin1.parse(key_word);
        var encrypted = CryptoJS.AES.encrypt(str, key, { iv: iv, mode: CryptoJS.mode.CBC, padding: CryptoJS.pad.ZeroPadding });
            return encrypted.toString();
     
}
 
*/

function jiami_post(){
    var key_word=$.cookie('key_word');
    var usera=navigator.userAgent;
    var hei=window.screen.height;
    var wid=window.screen.width;
    var poda={};
    poda.usera=usera;
    poda.usera=usera;
    poda.hei=hei;
    poda.wid=wid; 
    poda.key_word=$.cookie('c_name');
    var str=JSON.stringify(poda); 
    
      var ba=new Base64();
      str=ba.encode(str);
    
    return str;
 
     
}
























 














