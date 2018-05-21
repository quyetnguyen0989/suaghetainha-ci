(function($){
$('.wcvaheader').click(function(){

    $(this).nextUntil('tr.wcvaheader').slideToggle(100, function(){
    });
});
$('.subcollapsetr').click(function(){

    $(this).nextUntil('tr.subcollapsetr').slideToggle(100, function(){
    });
});
 $('.wcvaattributecolorselect').wpColorPicker();

 $(function() {
$('.wcvadisplaytype').live('change',function(){
    zvalue= $(this).val();
	if (zvalue == "colororimage") {
      
	 $(this).closest("div").find(".wcvaimageorcolordiv").show();
	} else {
	  $(this).closest("div").find(".wcvaimageorcolordiv").hide();
	}
});
});

})(jQuery); 

