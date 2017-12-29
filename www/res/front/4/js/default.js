 $(document).ready(
function()
{
  $("#smallbanner .next").click(
  function()
  {
    if($("#smallbannerlist ul").css("left").replace("px","") < (-460 * ($("#smallbannerlist ul li").length - 2)))
    {
      $("#smallbannerlist ul").animate({left:'0px'});
    }
    else
    {
     $("#smallbannerlist ul").animate({left:'-=460px'},'slow');
    }
  }
  );
  $("#smallbanner .prev").click(
  function()
  {
    if($("#smallbannerlist ul").css("left").replace("px","") >= 0)
    {
     $("#smallbannerlist ul").animate({left:"-" + 460 * ($("#smallbannerlist ul li").length - 1) + "px"});
    }
    else
    {
     $("#smallbannerlist ul").animate({left:'+=460px'},'slow');
    }
  }
  );
  setInterval(function(){$("#smallbanner .next").click();},7000);

}
);
