$(document).ajaxStart(function() { Pace.restart(); });

$(document).ready(function() {

	window.setTimeout(function() {
		$(".alert-auto").fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();
		});
	}, 3000);

	$(".select2").select2();

	$(".select2tag").select2({
		tags: true,
		maximumSelectionLength: 1
	});


	$(".colorpicker").colorpicker();

	$('.summernoteLarge').summernote({height: 400});
	$('.summernote').summernote({height: 200});


	// ISSUES BOARD HANDLER
	$(function () {
		//"use strict";
		//jQuery UI sortable for the todo list
		$(".todo-list").sortable({
		  placeholder: "sort-highlight",
	      connectWith: ".todo-list",
		  handle: ".handle",
		  forcePlaceholderSize: true,
		  zIndex: 999999,
	      update: function (event, ui) {
	          var issueid = ui.item.context.id;
	          //var newstatus = ui.item.context.closest('.todo-list').id;
			  var newstatus = ui.item.context.parentElement.id;
	          var formData = {issueid:issueid,status:newstatus};
	          //alert(newstatus);
	          $.ajax({
	              data: formData,
	              type: 'POST',
	              url: 'index.php?qa=updateIssueStatus'
	          });

	      }
		});
	});


	// LOAD JQUERY KNOB
	$(function() {
		$(".knob").knob({
			draw : function () {
				// "tron" case
				if(this.$.data('skin') == 'tron') {

					var a = this.angle(this.cv)  // Angle
						, sa = this.startAngle          // Previous start angle
						, sat = this.startAngle         // Start angle
						, ea                            // Previous end angle
						, eat = sat + a                 // End angle
						, r = true;

					this.g.lineWidth = this.lineWidth;

					this.o.cursor
						&& (sat = eat - 0.3)
						&& (eat = eat + 0.3);

					if (this.o.displayPrevious) {
						ea = this.startAngle + this.angle(this.value);
						this.o.cursor
							&& (sa = ea - 0.3)
							&& (ea = ea + 0.3);
						this.g.beginPath();
						this.g.strokeStyle = this.previousColor;
						this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
						this.g.stroke();
					}

					this.g.beginPath();
					this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
					this.g.stroke();

					this.g.lineWidth = 2;
					this.g.beginPath();
					this.g.strokeStyle = this.o.fgColor;
					this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
					this.g.stroke();
					return false;
				}
			}
		});
	});
});

var myRefreshTimeout;

function startAutorefresh(refreshPeriod) {
	myRefreshTimeout = setTimeout("window.location.reload();",refreshPeriod);
}

function stopAutorefresh() {
	clearTimeout(myRefreshTimeout);
	window.location.hash = 'stop'
}


function showM(url) {
	$('.modal-content').empty();

	$('.modal-content').load(url);
	$('#myModal').modal('show');
	stopAutorefresh();
}

function goBack() {
    window.history.back()
}




function autoresizeiframe() {

	$(".reply-iframe").load(function() {
		var h = $(this).contents().find("body").height();
		$(this).height( h+15 );
	});

}

$('.ticket-tab-button').click(function(){

	$('.reply-iframe').each(function() {
		$(this).attr("src", $(this).attr("src"));
	});

	autoresizeiframe();
});

$('iframe').ready(function(){
	autoresizeiframe();
});
