
  function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("wse-tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("wse-tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  }
  
  // Get the element with id="wse-tab-intro" and click on it
  document.getElementById('wse-tab-intro').click();
  jQuery(document).ready(function($) {
  if(wse_cur_tab=='support')
  {
    document.getElementById('wse-tab-support').click();
  }
  $(".wse-send-query").on("click", function(e){
    e.preventDefault();   
    var message     = $("#wse_query_message").val();  
    var email       = $("#wse_query_email").val();  
    
    if($.trim(message) !='' && $.trim(email) !='' && wseIsEmail(email) == true){
      $('.wse-send-query').text('Processing...'); 
     $.ajax({
                    type: "POST",    
                    url:ajaxurl,                    
                    dataType: "json",
                    data:{action:"wse_send_query_message",message:message,email:email,wse_security_nonce:wse_script_vars.nonce},
                    success:function(response){                       
                      if(response['status'] =='t'){
                        $(".wse-query-success").show();
                        $(".wse-query-error").hide();
                        $("#wse_query_message").val('');
                        $("#wse_query_email").val(''); 
                        $('.wse-send-query').text('Send Support Request'); 
                      }else{                                  
                        $(".wse-query-success").hide();  
                        $(".wse-query-error").show();
                        $('.wse-send-query').text('Send Support Request'); 
                      }
                    },
                    error: function(response){                    
                    console.log(response);
                    $('.wse-send-query').text('Send Support Request'); 
                    }
                    });   
    }else{
        
        if($.trim(message) =='' && $.trim(email) ==''){
            alert('Please enter the message, email and select customer type');
        }else{
        
        if($.trim(message) == ''){
            alert('Please enter the message');
        }
        if($.trim(email) == ''){
            alert('Please enter the email');
        }
        if(wseIsEmail(email) == false){
            alert('Please enter a valid email');
        }
            
        }
        
    } 
    
    

});

    if($("#wse_enable_cta_ad:checked").val()==1)
    {
      $('.wse_val_tr').show(); 
    }

    $('.wse-cta-banner-upload').click(function(e) {	// Application Icon upload
      e.preventDefault();
      var wse_media_uploader = wp.media({
        title: 'Banner For AD',
        button: {
          text: 'Select Image'
        },
        multiple: false  // Set this to true to allow multiple files to be selected
      })
      .on('select', function() {
        var attachment = wse_media_uploader.state().get('selection').first().toJSON();
        $('.wse_cta_banner').val(attachment.url);
      })
      .open();
    });

    
    $('#wse_enable_cta_ad').click(function() {	
      var default_value=false;
      var check=$('#wse_enable_cta_ad:checked').val();
      if(check=='1')
      {
        default_value=true;
        $('.wse_val_tr').show(); 
      }
      else{
        $('.wse_val_tr').hide(); 
      }

    });

    $("#wse-setting-form").submit(function(){
      
      var cta_enable=($("#wse_enable_cta_ad:checked").val()=='1')?1:0;
      var cta_banner =$("#wse_cta_banner").val();
      var cta_banner_slide =$("#wse_cta_ad_slide").val();
      var cta_btn_text =$("#wse_cta_btn_text").val();
      var cta_btn_link =$("#wse_cta_btn_link").val();

    
        if(cta_banner && cta_banner_slide && cta_btn_text && cta_btn_link)
        {
          if(wseIsImgUrl(cta_banner))
          {
            if(isValidUrl(cta_btn_link))
            {
          $.ajax({
            type: "POST",    
            url:ajaxurl,                    
            dataType: "json",
            data:{action:"wse_save_settings",cta_enable:cta_enable,cta_btn_link:cta_btn_link,cta_btn_text:cta_btn_text,cta_banner_slide:cta_banner_slide,cta_banner:cta_banner,wse_security_nonce:wse_script_vars.nonce},
            success:function(response){                       
              if(response['status'] =='t'){
                alert('Settings Sucessfully Saved'); 
              }else{                                  
              
               alert('Settings were not saved');
              }
            },
            error: function(response){                    
            console.log(response);
            }
            }); 
          }
          else
          {
            alert('Invalid Link url.');
          }
          }
          else{
            alert('Invalid Image url. Please upload image again');
          }
        }
        else
        {
          alert('Please fill mandatory fields (*)');
        }
      


    });


  });

  function wseIsImgUrl(url) {
    const img = new Image();
    img.src = url;
    return new Promise((resolve) => {
      img.onerror = () => resolve(false);
      img.onload = () => resolve(true);
    });
  }
  function wseIsEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function wse_copy(id) {
  if(id)
  {
    var copyText = document.getElementById("wse-input-"+id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value);
    var tooltip = document.getElementById("wse-tooltip-"+id);
    tooltip.innerHTML = "Shortcode Copied";
  }
}

function wse_out(id) {
  if(id)
  {
    var tooltip = document.getElementById("wse-tooltip-"+id);
    tooltip.innerHTML = "Copy Shortcode";
  }
}

function isValidUrl(urlString) {
  var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
  '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
  '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
  '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
  '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
  '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator
return !!urlPattern.test(urlString);
}
