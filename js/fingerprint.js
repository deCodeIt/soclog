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
                        $(obj).html('Registered');
                }
                else
                 {      $(obj).removeClass('reg');
                        $(obj).html('Register');
                 }
            }

        });    
        return false;
    });

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

        //shows the list of registered events
        $('#pPic').click(function(){
            if( $('div.registered-events').is(':visible') )
            {
                $('div.registered-events').hide();
            }
            else
            {
                $.ajax({
                  method: 'POST',
                  url: 'myevents.php',
                })
                  .done(function( msg ) {
                    data = JSON.parse(msg);
                    if(data.status=='true')
                    {
                        $('div.registered-events').show();
                        console.log(msg);
                        console.log(data);
                    }

                });
            }
        });

});


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