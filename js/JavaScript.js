/*-----------------FUNKCIJA ZA NAVIGACIONI BAR (MOBILNE UREDJAJE)--------------------*/
function myFunction() {
    var x = document.getElementById("myLinks");
    if (x.style.display === "block") {
         x.style.display = "none";
    } 
    else {
    x.style.display = "block";
    }
}

$(document).ready(function() {   
	   $('input[name=rate]').change(function(){  
	        $('.rating').submit();  
	   });  
	  });
