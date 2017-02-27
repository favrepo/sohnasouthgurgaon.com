/*!
 * Start Bootstrap - Freelancer Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('body').on('click', '.page-scroll a', function(event) {
        var $anchor = $(this);
       $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

$(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
});

// Floating label headings for the contact form
/*
$(function() {
    $("body").on("input propertychange", ".floating-label-form-group", function(e) {
        $(this).toggleClass("floating-label-form-group-with-value", !! $(e.target).val());
    }).on("focus", ".floating-label-form-group", function() {
        $(this).addClass("floating-label-form-group-with-focus");
    }).on("blur", ".floating-label-form-group", function() {
        $(this).removeClass("floating-label-form-group-with-focus");
    });
});*/

// Closes the Responsive Menu on Menu Item Click
/*$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggle:visible').click();
});*/

  $(".scroll-top").hide();
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('.scroll-top').fadeIn();
			} else {
				$('.scroll-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('.scroll-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		
//  Google Map   28.444034","76.954963
var myCenter=new google.maps.LatLng("28.258916","77.075185");

		function initialize()
		{
		var mapProp = {
		  center:myCenter,
		  zoom:14,
		  mapTypeId:google.maps.MapTypeId.ROADMAP
		  };

		var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);

		var contentString = "Supertech Azaliya";

		  var infowindow = new google.maps.InfoWindow({
			  content: contentString,
			  maxWidth: 200
		  });

		  marker = new google.maps.Marker({
			map:map,
			draggable:false,
			animation: google.maps.Animation.DROP,
			position: myCenter
		  });
		  google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		  });


		//marker.setMap(map);
		}
		function toggleBounce() {

		  if (marker.getAnimation() != null) {
			marker.setAnimation(null);
		  } else {
			marker.setAnimation(google.maps.Animation.BOUNCE);
		  }
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		
//  Form Validation
function formcheck()
      {
        if((document.form1.CustName.value=='') || (document.form1.CustName.value=='Name*'))
        {
          alert('You must enter Name');1
          document.getElementById('CustName').focus();
          return false;
        }
        if((document.form1.CustContact.value=='') || (document.form1.CustContact.value=='Mobile*'))
        {
          alert('Please enter Contact No.');
          document.getElementById('CustContact').focus();
          return false;
        }
        
        if((document.form1.CustEmail.value=='Email*') || (document.form1.CustEmail.value==''))
        {
          alert('You must enter Email id');
          document.getElementById('CustEmail').focus();
          return false;
        }
        
          else if(document.form1.CustEmail.value!='')
          {
            
            var x=document.getElementById('CustEmail').value;
      var atpos=x.indexOf("@");
      var dotpos=x.lastIndexOf(".");
      if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
        {
        alert("Please enter valid Email ID");
        document.getElementById('CustEmail').focus();
        return false;
        }
        
        
      }
        }

//	For top slider	
  
      $("#slider3").responsiveSlides({
        manualControls: '#slider3-pager',
      //  maxwidth: 540,
		auto:true
      });
  

});		 // End Function