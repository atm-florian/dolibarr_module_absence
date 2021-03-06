
[view.head3;strconv=no]
		[view.titreCalendar;strconv=no;protect=no] 	
		
		[onshow;block=begin;when [absence.droits]=='1']
			<table class="liste border" style="width:100%">			
				<tr>
					<td>[absence.groupe;strconv=no;protect=no]</td>
					<td>[absence.TGroupe;strconv=no;protect=no]</td>
				</tr>
				<tr>
					<td>[absence.utilisateur;strconv=no;protect=no]</td>
					<td>[absence.TUser;strconv=no;protect=no]</td>
				</tr>
				<tr>
					<td>[absence.type;strconv=no;protect=no]</td>
					<td>[absence.TTypeAbsence;strconv=no;protect=no]</td>
				</tr>
				<tr>
					<td></td>
				 	<td colspan="2">[absence.btValider;strconv=no;protect=no] </td>
				</tr>
			</table>
		 	<br> 
		[onshow;block=end]
	
		<script>
		$('#groupe').change(function(){
				//alert('top');
				$.ajax({
					url: 'script/loadUtilisateurs.php?groupe='+$('#groupe option:selected').val()
					,dataType:'json'
				}).done(function(liste) {
					$("#idUtilisateur").empty(); // remove old options
					$.each(liste, function(key, value) {
					  $("#idUtilisateur").append($("<option></option>")
					     .attr("value", key).text(value));
					});	
				});
		});
		</script>

		
			<div id="agenda">
				 <script type="text/javascript">
        $(document).ready(function() {
            var view="month";          
           
           var DATA_FEED_URL = "./script/absenceCalendarDataFeed.php?idUser=[absence.idUser;strconv=no;protect=no]&idGroupe=[absence.idGroupe;strconv=no;protect=no]&typeAbsence=[absence.typeAbsence;strconv=no;protect=no]&withAgenda=[view.agendaEnabled]"
            var op = {
                view: view,
                theme:0,
                showday: new Date(),
                EditCmdhandler:Edit,
                DeleteCmdhandler:Delete,
                ViewCmdhandler:View,    
                onWeekOrMonthToDay:wtd,
                onBeforeRequestData: cal_beforerequest,
                onAfterRequestData: cal_afterrequest,
                onRequestDataError: cal_onerror, 
                autoload:true,
                url: DATA_FEED_URL + "&method=list",  
                quickAddUrl: false, 
                quickUpdateUrl: false,
                quickDeleteUrl: false   
                ,method:"GET"
                ,enableDrag :false   
                /*,height:false*/ 
            };
                        
            op.height = document.documentElement.clientHeight - 400;
			if(op.height<500)op.height=500;

            op.eventItems =[];

            var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
            $("#caltoolbar").noSelect();
            
            $("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
            onReturn:function(r){                          
                    var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
                    if (p && p.datestrshow) {
                        $("#txtdatetimeshow").text(p.datestrshow);
                    }
	             } 
            });
            function cal_beforerequest(type)
            {
                $("#errorpannel").hide();
                $("#loadingpannel").show();
            }
            function cal_afterrequest(type)
            {
                switch(type)
                {
                    case 1:
                        $("#loadingpannel").hide();
                        break;
                    case 2:
                    case 3:
                    case 4:
                        $("#loadingpannel").html("Success!");
                        window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
                    break;
                }              
               
            }
            function cal_onerror(type,data)
            {
                $("#errorpannel").show();
            }
            function Edit(data)
            {
            
            }    
            function View(data)
            {
               /* var str = "";
                for(x in data) {
                    str += "[" + x + "]: " + data[x] + "\n";
                }
                alert(str);*/
               
               document.location.href=data[9];
                              
            }    
            function Delete(data,callback)
            {           
                
                $.alerts.okButton="Ok";  
                $.alerts.cancelButton="Cancel";  
                hiConfirm('[absence.confirm_delete;strconv=no;protect=no]', '[absence.confirm;strconv=no;protect=no]',function(r){ r && callback(0);});           
            }
            function wtd(p)
            {
               if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $("#showdaybtn").addClass("fcurrent");
            }
            //to show day view
            $("#showdaybtn").click(function(e) {
                //document.location.href="#day";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("day").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            //to show week view
            $("#showweekbtn").click(function(e) {
                //document.location.href="#week";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("week").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //to show month view
            $("#showmonthbtn").click(function(e) {
                //document.location.href="#month";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("month").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            
            $("#showreflashbtn").click(function(e){
                $("#gridcontainer").reload();
            });
            
            //Add a new event
/*            $("#faddbtn").click(function(e) {
                var url ="../wdCalendar/edit.php";
                OpenModelWindow(url,{ width: 500, height: 400, caption: "Créer un nouveau calendrier"});
            });
*/            //go to today
            $("#showtodaybtn").click(function(e) {
                var p = $("#gridcontainer").gotoDate().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }


            });
            //previous date range
            $("#sfprevbtn").click(function(e) {
                var p = $("#gridcontainer").previousRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //next date range
            $("#sfnextbtn").click(function(e) {
                var p = $("#gridcontainer").nextRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
           
           
           
        });
    </script>    

    <div>
    
      <div id="calhead" style="padding-left:1px;padding-right:1px;">          
            <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">[absence.loading;strconv=no;protect=no]</div>
             <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">[absence.err_load_data;strconv=no;protect=no]</div>
            </div>          
            
            <div id="caltoolbar" class="ctoolbar">
            <div>
            	[onshow; block=div; when [view.agendaEnabled]==1]
	            <div id="faddbtn" class="fbutton">
	                <div><span title='Click to Create New Event' class="addcal">
	
	                [absence.new_event;strconv=no;protect=no]               
	                </span></div>
	            </div>
	            <div class="btnseparator"></div>
            </div>
             <div id="showtodaybtn" class="fbutton">
                <div><span title='Click to back to today ' class="showtoday">
                [absence.today;strconv=no;protect=no]</span></div>
            </div>
              <div class="btnseparator"></div>

           <div id="showdaybtn" class="fbutton">
           	[onshow; block=div; when [view.agendaEnabled]==1]
                <div><span title='Day' class="showdayview">[absence.day;strconv=no;protect=no]</span></div>
            </div>
              <div  id="showweekbtn" class="fbutton">
                <div><span title='Week' class="showweekview">[absence.week;strconv=no;protect=no]</span></div>
            </div>
              <div  id="showmonthbtn" class="fbutton fcurrent">
                <div><span title='Month' class="showmonthview">[absence.month;strconv=no;protect=no]</span></div>

            </div>
            <div class="btnseparator"></div>
              <div  id="showreflashbtn" class="fbutton">
                <div><span title='Refresh view' class="showdayflash">[absence.refresh;strconv=no;protect=no]</span></div>
                </div>
             <div class="btnseparator"></div>
            <div id="sfprevbtn" title="Prev"  class="fbutton">
              <span class="fprev"></span>

            </div>
            <div id="sfnextbtn" title="Next" class="fbutton">
                <span class="fnext"></span>
            </div>
            <div class="fshowdatep fbutton">
                    <div>
                        <input type="hidden" name="txtshow" id="hdtxtshow" />
                        <span id="txtdatetimeshow">[absence.loading;strconv=no;protect=no]</span>

                    </div>
            </div>
            
            <div class="clear"></div>
            </div>
      </div>
      <div style="padding:1px;">

        
        <div id="dvCalMain" class="calmain printborder">
            <div id="gridcontainer" style="overflow-y: visible;">
            </div>
        </div>
        
        </div>
     
  </div>
				
	</div>
			