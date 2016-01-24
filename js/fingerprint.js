$(document).ready(function(){
        //for registration
        $(document).on('submit', 'form#registration_form', function() {            
        $.ajax({
          method: $(this).attr('method'),
          url: $(this).attr('action'),
          data: $(this).serialize()
        })
          .done(function( msg ) {
            // console.log("YES");
            // console.log(msg);
            // console.log(JSON.parse(msg));
            data = JSON.parse(msg);
            if(data.status=='true')
            {
                $("#user_register").hide();
                $("event_pg").show();
                $(".modal_close").click();
            }

        });    
        return false;
    });
        //for event registration
        $(document).on('click', '.event-reg', function() {
        obj =this;
        $(obj).addClass('preload-01');
        console.log('event clicked');
        var status = 2;
        if( $(this).hasClass('reg'))
        {
                status = 1;
        }
        dat={'zeit_event':$(this).attr('href').substr(1),'stat':status};
        console.log(dat);
        $.ajax({
          method: 'POST',
          url: 'event_reg.php',
          data: dat
        })
          .done(function( msg ) {
            // console.log("YES");
            // console.log(msg);
            // console.log(JSON.parse(msg));
            data = JSON.parse(msg);
            if(data.status=='true')
            {
                if(data.reg==1)
                {
                        $(obj).addClass('reg');
                        $(obj).removeClass('preload-01');
                        $(obj).html('Registered');
                }
                else
                 {      $(obj).removeClass('reg');
                        $(obj).removeClass('preload-01');
                        $(obj).html('Register');
                 }
            }

        });    
        return false;
    });

        //for TEAM event registration
        //for event registration
        $(document).on('click', '.event-reg-team', function() {
        obj =this;
        console.log('Team event clicked');
        dat={'zeit_event':$(this).attr('href').substr(1),'details':true};
        
        //get the team capacity
        $.ajax({
          method: 'POST',
          url: 'event_reg_team.php',
          data: dat
        })
          .done(function( msg ) {
            console.log("YES");
            console.log(msg);
            data = JSON.parse(msg);
            if(data.status=='true')
            {
                //now create the form
                st="";
                for(i=0;i<data.max_size;i++)
                {
                    st+='<label>Member '+(i+1)+':</label><input type="text" name="team-member-id[]" id="team-member-'+(i+1)+'"/><br />';
                }
                //displaying the form
                $('form#team_form').html(st);
                $('.user_register').hide();
                $('.social-login').hide();
                $('.event-pg').show();
                $('#modal_trigger').click();
                //form displayed
            }

        });    
        return false;
    });
        
        //event current status
        //for event registration
        updateRegisteredEvents();

        //shows the list of registered events
        $('#pPic').click(function(){
            if( $('div.registered-events').is(':visible') )
            {
                $('div.registered-events').hide();
            }
            else
            {
                //waiting logo
                $('div.registered-events').show();
                $('ul#registered-events').html('<li><img src="images/ajax-loader.gif" style="width:38px;height:38px"></li>');
                //sending request
                $.ajax({
                  method: 'POST',
                  url: 'myevents.php',
                })
                  .done(function( msg ) {
                    data = JSON.parse(msg);
                    if(data.status=='true')
                    {
                        $('div.registered-events').show();
                        // console.log(msg);
                        // console.log(data);
                        data = data.data;
                        str='';
                        for(i=0;i<data.length;i++)
                        {
                            str+='<li>'+data[i].name+'</li>';
                        }
                        $('ul#registered-events').html(str);
                    }

                });
            }
        });

});

function updateRegisteredEvents(){
    //event current status
        //for event registration
        $('.event-reg').each(function(index) {
        obj =this;
        dat={'zeit_event':$(this).attr('href').substr(1)};
        $.ajax({
          method: 'POST',
          url: 'event_status.php',
          data: dat
        })
          .done(function( msg ) {
            data = JSON.parse(msg);
            if(data.status=='true')
            {
                if(data.reg==1)
                {
                        $(obj).addClass('reg');
                        $(obj).html('Registered');
                }
                else
                 {      $(obj).removeClass('reg');
                        $(obj).html('Register');
                 }
            }

        });
    });
}


function validateForm(){
        $(function() {
            // validate and process form here
              
                $('.error').removeClass('error');

                var field = $("input#email");
                var status=true;
                if (field.val() == "") {
                        field.addClass('error');
                        field.focus();
                        status = false;
                      }
                field = $("input#college");
                if (field.val() == "") {
                        field.addClass('error');
                        field.focus();
                        status = false;
                      }
                field = $("input#location");
                if (field.val() == "") {
                        field.addClass('error');
                        field.focus();
                        status = false;
                      }
                field = $("input#tos");
                if(!field.is(':checked'))
                {
                        field.addClass('error');
                        status=false;
                }
                //validation passed
                if(status==true)
                        $('form#registration_form').submit();
          });
}