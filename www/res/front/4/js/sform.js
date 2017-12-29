/***************************************负责上传图片**********************************************************************************************/
 
var supimg=false;
function sform_init(){
     if(supimg) return ;
    supimg=true;
    shangchuantupian();
    html_edit();
    select_view();
        $('[xz=int]').keypress(function(){
	 return (/[\d.]/.test(String.fromCharCode(event.keyCode)));
	});
}
function html_edit(){
         var edit=$("[edit=html]");
    for(var i=0;i<edit.length;i++){
        var editstr=$(edit[i]);
        cr_edit(editstr);
    }
    
};

function cr_edit(editstr){
    var e=KindEditor.create($(editstr),{
        uploadJson:'/home/Api/upload_json'
    });
    $(editstr)[0].obj_edit=e;
}
function sform(){
   var ht=$("[edit=html]");
      for(var i=0;i<ht.length;i++){
          var obj=$(ht[i])[0].obj_edit;
          obj.sync();
       }
       /*
   var merror=$(".errorMessage");
   for(var i=1;i<merror.length;i++){
       if($(merror[i]).css('display')!=='none'){
           m.tishi($(merror[i]).text());
           return false;
       }
   }
*/

   
    return true;
}
function shangchuantupian(){

    var upbutton=function(img){
    
            var imgid=img.attr("img");
            var img_str=$("."+imgid);
            var thumid=img.attr("thum");
            var url;
            if(thumid!='false'){
                url='/home/Api/upload_json?thumb=true';
            }else{
                  url='/home/Api/upload_json';
            }
    var uploadbutton = KindEditor.uploadbutton({
        button : img[0],
        fieldName : 'imgFile',
    url : url,
        afterUpload : function(data) {
                if (data.error === 0) {  
                $("#"+imgid).val(data.url);
                 if(thumid!='false'){
                     $("#"+thumid).val(data.thumb);
                     img_str.attr("src",data.thumb);
                  }else{
                      img_str.attr("src",data.url);
                  }
              
                 
                      art.dialog.list['art_upimg'].close();
                } else {
                     
                        art.dialog.list['art_upimg'].content(data.message);  
                }
        }
});
uploadbutton.fileBox.change(function(e) {
        uploadbutton.submit();
        art.dialog({id: 'art_upimg',title: "上传封面"});
});

    };
    
       var imgl=$("[img]");
    for(var i=0;i<imgl.length;i++){
        var imgstr=$(imgl[i]);
        upbutton(imgstr);
    }
      
};
/*************************************************************************************************************************************/
function open_type_idv(objstr,tse){
    var th=this;
    var obj=$(objstr);
    var attribute=$(tse).attr("attribute");
    obj.css("display","block");
    obj.find(">ul").css("display","block");
    obj.find("li").click(function(){
        if($(this).hasClass("lis")){
            
             $(this).find(">ul").css("display","block");
             }else{
             var tid= $(this).attr("tid");
             var title=$(this).attr("title");
             var img=$(this).find("img").attr("src");
             th.setval(tid,title,img);
             
        }
          return false;
    });
    this.setval=function(tid,title,img){
        th.close();
        $(".html_type").attr('tid',tid);
        $(".html_type img").attr("src",img);
        $(".html_type span").text(title);
        $("#"+attribute).val(tid);
    };
    this.close=function(){
        obj.find("ul").css("display","none");
        obj.css("display","none");
    };
      $("body").click(function(){
       if(stop){   
        th.close();
    }
    });
    setTimeout(function (){
         stop=true;
    },100);
    stop=false;
}
var stop=false;








function select_view(){
    $(".select_view li").click(function(){
        var $sj=$(this);
        var $bo=$sj.parent().parent();
        var val=$sj.attr('val');
        if(typeof(val)==='undefined') val="";
        
        var click=$bo.attr("click");
        if(typeof(click)==='undefined') click="";
         
        var click_obj=$bo.attr("click_obj");
        if(typeof(click_obj)==='undefined') click_obj="";
        
        var ty=$bo.attr("sy");
         if(ty=='dan'){
            $bo.find("li").removeClass("on");
            $bo.find("[val="+val+"]").addClass("on"); 
        }
        if(ty=='duo'){
            if($sj.hasClass("on")){
                $sj.removeClass("on");
            }else{
                $sj.addClass("on");
            }
        }
        var li_on=$bo.find('li.on');
        var va='';
        for(var i=0;i<li_on.length;i++){
            if(i===0){ va=$(li_on[i]).attr("val");}else{
            va+=","+$(li_on[i]).attr("val");}
        }
       $bo.attr("val",va);
       var inputid=$bo.attr('inputid');
       if(inputid){
           $("#"+inputid).val(va);
       }
        if(click!=''){
            var strclick="select_view_"+click+"('"+val+"')";
            eval(strclick);
        }
     /*    if(click_obj!=''){
            var strclick="select_view_"+click_obj+"("+$bo+")";
            eval(strclick);
        }*/
    });
}
function select_view_type(obj,ty){
     if(ty=='dan'){
         var list= $(obj);
         for(var i=0;i<list.length;i++){
            view_type_danxuan(list[i]);
         }
        $(obj).attr("sy",'dan');
    }else{
         $(obj).attr("sy",'duo');
    }
  
}
function view_type_danxuan(ol){
       var ls=$(ol);
        ls.find("li").removeClass("on");
        $(ls.find("li")[0]).addClass("on");
        var va=$(ls.find("li")[0]).attr('val');
        ls.attr("val",va);
}
function set_select_view(obj,da){ 
            var $obj=$(obj);
            $obj.attr("val",da);            
            $obj.find('li').removeClass("on");
            if(typeof(da)=='string'){
            var li=da.split(",");
            for(var i=0;i<li.length;i++){
                $obj.find('[val='+li[i]+']').addClass("on");
            }
        }else{
             $obj.find('[val='+da+']').addClass("on");
        }
}
function box(strfun,msg){
    
    if(!msg||msg==='') {
        msg="确认需要删除吗?";
    }
    if(!strfun) return ;
    if(typeof(strfun)==='string'){
        var fun=function(){eval(strfun);};
    }
      if(typeof(strfun)==='function'){
        var fun=strfun;
    }
art.dialog({
    content:msg,
    ok:fun,
    cancelVal: '关闭',
    cancel: true //为true等价于function(){}
});  
}


///var district=new DISTRICT();
var district;
function DISTRICT(){
    this.int=function(){
        $('[type_dis=district]').change(function(){
            var sq=$(this).val();
            district.load_city(sq);
        });
    };
    this.load_city=function(id){
        district.city=id;
        $('[type_dis=city]').empty();
         district.add_city('null','请选择城市');
        if(id==='null') return;
        var das=district.data_json[id];
        for(var i=0;i<das.length;i++){
            district.add_city(das[i].id,das[i].name);
        }
    };
    this.add_city=function(key,name){
        var htm='<option value="'+key+'">'+name+'</option>';
         $('[type_dis=city]').append(htm);
    };
    this.city;
};