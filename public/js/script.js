
$(document).ready(function() {
			
	/*$.validator.addMethod(
    "regex",
    function(value, element, regexp) {
        var check = false;
        return this.optional(element) || regexp.test(value);
    },
	"Please check your input."
	);
	$("#login_form").validate( {
	rules: {
			user_name: "required",
			password: "required",
		},
	messages: {
			user_name: "Please select username",
			password: "Please enter password",
		}
	
	});*/
	/*$('.edit_inline').editable('/admin/inlineedit', {
         indicator : 'Saving...',
         submitdata: { _method: "get" },
         tooltip   : 'Click to edit...'
     });*/
	
										
	$('td.edit').click(function(){							 
		$('.ajax').html($('.ajax input').val());
		$('.ajax').removeClass('ajax');
		$(this).addClass('ajax');
		$(this).html('<input id="editbox" size="'+$(this).text().length+'" type="text" value="' + $(this).text() + '">');
		$('#editbox').focus();
	});
	
	$('td.edit').keydown(function(event){
		 var id = $(this).attr('sourceid');
		 var name = $(this).attr('sourcename');		 
		 if(event.which == 13)
		 { 
		 	if($('.ajax input').val()=='')
		 	{
		 	alert('Field Should not empty');
		 	}
		 	else
		 	{
	  		$.ajax({    type: "POST",
			        url:"/profile/inlineedit",
				data: "value="+$('.ajax input').val()+"&rownum="+id+"&field="+name,
				success: function(data){
				
					 $('.ajax').html($('.ajax input').val());
		                         $('.ajax').removeClass('ajax');
		                },
		                error:function(data){
		                	$('.ajax').html($('.ajax input').val());                         						$('.ajax').removeClass('ajax');
		                }
		               });
		         }
		}
		$('#editbox').focusout(function(){
			 $('.ajax').html($('.ajax input').val());
			 $('.ajax').removeClass('ajax');
		});								  
	});
	
/*$(document).ready(function() {
    $(document).click(function(event) {
        // this.append wouldn't work
        alert($(this).attr('class'));
    });
});*/
$(document).click(function(event) {

    if(($(event.target).attr('class')!='edit ajax'))
    {    
    	$('.ajax').html($('.ajax input').val());
	$('.ajax').removeClass('ajax');	
    }   
});

	
});
