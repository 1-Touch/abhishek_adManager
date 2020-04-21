$(document).ready(function(){
    $("#campaign-form").validate({
        rules: {
          campaign: {
            required: true,
              },
          objective: {
            required: true,
              },
          status: {
            required: true
              },          
          category: {
            required: true,
              },           
        },
        messages: {
          campaign: {
              required: "Please enter campaign's name",
                },
          objective: {
              required: "Please select campaign's objective",
                },
          status: {
              required: "Please select campaign's status",
                },          
          category: {
              required: "Please select campaign's category",
                },
        },
        submitHandler: function(form) {
            //var formData = new FormData($("#image")[0]);
            
            $.ajax({
                url: "create_campaign.php",
                type: "post",
                data: $(form).serialize(),
                success: function(response) {
                    var res = JSON.parse(response);                    
                    console.log(res);
                    if(res.hasOwnProperty('id')){
                        $("#campaign-form").trigger('reset');
                        $('#campaign-response').removeClass("alert-info");
                        $('#campaign-response').addClass("alert-success");
                        $('#campaign-response').html("");
                        $('#campaign-response').html("Campaign Created Successfully");
                        //$('label').removeClass('state-success');
                        $("#campaign-response").show().delay(5000).fadeOut();  
                     }else{
                        $('#campaign-response').removeClass("alert-success");
                        $('#campaign-response').addClass("alert-info");
                        $('#campaign-response').html("");
                        $('#campaign-response').html(response);
                        $("#campaign-response").show().delay(5000).fadeOut();
                     }
                     location.reload();
                }            
            });
        }
    });
    
});