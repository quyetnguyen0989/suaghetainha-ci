(function($){
 $('.wcvaattributecolorselect').wpColorPicker();



    var select2 = document.getElementById('display_type')	
onChange2 = function(event) {
    var colordiv              = this.options[this.selectedIndex].value == 'Color';
	var imagediv              = this.options[this.selectedIndex].value == 'Image';
	
  
	document.getElementById('wcvacolorp').style.display      = colordiv ? '' : 'none';
	document.getElementById('wcvaimagep').style.display     = imagediv ? '' : 'none';
	
   
};


 // attach event handler
if (window.addEventListener) {
    select2.addEventListener('change', onChange2, false);
} else {
    // of course, IE < 9 needs special treatment
    select2.attachEvent('onchange2', function() {
        onChange2.apply(select2, arguments);
    });
}


 
})(jQuery); 