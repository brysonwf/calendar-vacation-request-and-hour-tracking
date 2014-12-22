 function buttons() {
	$(".actions .cancel").click(function(){
		
		$.post('php-request-cancel.php', { id: $(this).attr('href').substring(16) }, function(data) {});
		
		//change status to cancel
		$(this).parent().parent().find(".status").addClass("red").removeClass("orange");
		$(this).parent().parent().find(".status").hide();
		$(this).parent().parent().find(".delete-col").show();
		
		//permanatly show undo button
		$(this).parent().addClass("visible");
		
		//add canceled style and allow delete button to be shown
		$(this).parent().parent().addClass("status-red");
		
		//hide delete / show undo
		$(this).hide();
		$(this).parent().find(".undo").show();
	});
	
	$(".actions .undo").click(function(){
		
		$.post('php-request-uncancel.php', { id: $(this).attr('href').substring(14) }, function(data) {});
		
		//change status to cancel
		$(this).parent().parent().find(".status").addClass("orange").removeClass("red").removeClass("green");
		$(this).parent().parent().find(".status").show();
		if($(".nav-vacation").hasClass("active")){
			$(this).parent().parent().find(".status").html("?");
		}
		$(this).parent().parent().find(".delete-col").hide();
		
		//hide undo button
		$(this).parent().removeClass("visible");
		$(this).removeClass("visible");
		
		//remove canceled style and stop delete button from being shown
		$(this).parent().parent().removeClass("status-red");
		
		//hide undo / show delete
		$(this).hide();
		$(this).parent().find(".cancel").show();
	});
	
	$(".delete").click(function(){

		if ($(this).attr('href').substring(0, 24) != '#permanatly-delete-user-'){

			//check to see if i am removing my stuff / administrative stuff
			
			if ($(".nav-admin").hasClass("active")){
				if ($(".nav-user a").html() == $.trim($(this).parent().parent().find(".user").html())){
					$("#vacation-hours-digits").html(parseFloat($("#vacation-hours-digits").html()) - parseFloat($(this).parent().parent().find(".time-span").html()));
				}
			}else if($(".nav-vacation").hasClass("active")){
				$("#vacation-hours-digits").html(parseFloat($("#vacation-hours-digits").html()) - parseFloat($(this).parent().parent().find(".time-span").html()));
			}
			
			$.post('php-request-delete.php', { id: $(this).attr('href').substring(19) }, function(data) {});
			
			$(this).parent().parent().hide();
			
		}else{
			$.post('php-user-delete.php', { id: $(this).attr('href').substring(24) }, function(data) {});
		}
		
	});
	
	$(".admin-OK").click(function(){
		$(this).removeClass("OK_status").addClass("OK_active");
		$(this).parent().find(".NO_active").removeClass("NO_active").addClass("NO_status");
		$(this).parent().find(".unknown_active").removeClass("unknown_active").addClass("unknown_status");
		
		$.post('php-request-ok.php', { id: $(this).attr('href').substring(6) }, function(data) {});
	});
	
	$(".admin-UK").click(function(){
		$(this).removeClass("unknown_status").addClass("unknown_active");
		$(this).parent().find(".NO_active").removeClass("NO_active").addClass("NO_status");
		$(this).parent().find(".OK_active").removeClass("OK_active").addClass("OK_status");
		
		$.post('php-request-uk.php', { id: $(this).attr('href').substring(6) }, function(data) {});
	});
	
	$(".admin-NO").click(function(){
		$(this).removeClass("NO_status").addClass("NO_active");
		$(this).parent().find(".OK_active").removeClass("OK_active").addClass("OK_status");
		$(this).parent().find(".unknown_active").removeClass("unknown_active").addClass("unknown_status");
		
		$.post('php-request-no.php', { id: $(this).attr('href').substring(6) }, function(data) {});
	});
	
	/*
	$(".edit").click(function(){
		$(this).parent().parent().find(".date-edit").show();
		$(this).parent().parent().find(".date").hide();
		$(this).parent().parent().find(".reason").hide();
		$(this).parent().parent().find(".status").hide();
		$(this).parent().parent().find(".delete-col").hide();
	});*/
}

function calculateHours(from, type, now, begin, end, totalHours) {
	
	var dateNow = parseInt(now);
	
	if (begin != ''){
		
		var dateBeginArray = begin.split("/");
		if (dateBeginArray[1] != ""){
			//split test date
			begin = parseInt(dateBeginArray[2]+""+dateBeginArray[0]+""+dateBeginArray[1]);
		}
		
		if (end == ''){
			end = begin;
		}else{
			var dateEndArray = end.split("/");
			if (dateEndArray[1] != ""){
				//split test date
				end = parseInt(dateEndArray[2]+""+dateEndArray[0]+""+dateEndArray[1]);
			}
		}
		
		//clear date field if less than now
		
		if (dateNow > parseInt(begin) && $("#request_off_area").get(0).value != 'Admin'){
			$("#request_date_begin").get(0).value = '';
		}else{
			
			//if endDate is blank or if single day, then set to begin date
			if ($("#request_day_type_single").get(0).checked 
				|| $("#request_day_type_partial").get(0).checked
				|| $("#request_date_end").get(0).value == ''
				|| $("#request_day_type_single").get(0).checked
			){
				$("#request_date_end").get(0).value = $("#request_date_begin").get(0).value;
				end = begin;
			}
			
			if (begin > end){
				$("#request_date_end").get(0).value = $("#request_date_begin").get(0).value;
				end = begin;
			}
			
			if($("#request_day_type_single").get(0).checked || $("#request_day_type_multi").get(0).checked){
				
			
				if ( !( (begin == '') || (end == '') ) ){
					
					$.post('php-calculate-hours.php', { begin: begin, end: end}, function(data) {
						$('#requesting_off_amount').get(0).value = "Request to take off "+data+" hours";
						if ((data > totalHours) && (totalHours != 'Hourly')){
							$("#overage-message").html('<strong>This request will exceed your alloted vacation time and can only be approved by Michael or Andy.</strong><br><br>');
						}else{
							$("#overage-message").html('');
						}
						$("#requesting_off_amount_input").get(0).value = data;
					});
				
				}else{
					$("#requesting_off_amount_input").get(0).value = 0;
				}
			}
		}
	}else{
		$("#requesting_off_amount_input").get(0).value = 0;
		$("#overage-message").html('');
	}
}

