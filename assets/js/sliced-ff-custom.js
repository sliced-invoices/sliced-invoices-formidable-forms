jQuery(document).ready(function($) {
    
    $(".sliced_ff_type").change(function(){        
        if($(this).val() == "invoice"){
            $('.siff-invoice').removeClass('siff-none');
            $('.siff-quote').addClass('siff-none');
        }else{
            $('.siff-invoice').addClass('siff-none');
            $('.siff-quote').removeClass('siff-none');
        }
    }); 
    
    $(".siff-form-field").each(function(){
        var thisId  = $(this).attr('id');
        var thisVal = $(this).attr('data-val');
        if(thisVal != 0 && thisVal != ""){
            $("#"+thisId).val(thisVal);
        }                
    });
    $('.sliced-ff-main').show();
    
    var formSumbit = 0;
    var formAction = window.location.href;
    $("#form_settings_page form").removeClass("frm_form_settings");
    
    $("body").on("click",".frm_submit_settings_btn",function(){                
        if($("#sliced_ff_enable").prop('checked') === true){        
            var qtyErr = 0, titleErr = 0, amtErr = 0;
            $(".siff-qty").each(function(){
                if($(this).val() == "") qtyErr++;
            });
            $(".siff-title").each(function(){
                if($(this).val() == "") titleErr++;
            });
            $(".siff-amt").each(function(){
                if($(this).val() == "") amtErr++;
            });
        if($('#sliced_ff_cname').val()!='' && $('#sliced_ff_cemail').val()!='' && $('#sliced_ff_stitle').val()!='' && qtyErr==0 && amtErr==0 &&titleErr==0){
            $("#form_settings_page form").addClass("frm_form_settings");
            if(formSumbit == 0){
                formSumbit++;
                $(".frm-form-setting-tabs li.active a").trigger("click");            
                $(".frm_submit_settings_btn").trigger("click");
            }
        }else{
            alert("Sliced Invoices: You have enabled sliced invoice on this form, please fillup mandatory fields!");
            $(this).removeClass('frm_loading_form').text("Update");                        
            return false;
        }     
       }else{
            if(formSumbit == 0){
                formSumbit++;
                $("#form_settings_page form").addClass("frm_form_settings");
                $(".frm-form-setting-tabs li.active a").trigger("click");
                $(".frm_submit_settings_btn").trigger("click");                                
            }
       }      
    });
    
    $("#sliced_ff_items").change(function(){               
        if($(this).val() != ""){
            $(this).after('<img id="siff-loader" src="'+$("#siff_ajax_loader").val()+'" style="position: relative;top: 8px;left: 5px;margin-right: 5px;" />');
            $.ajax({
                type : "POST",
                url  : ajaxurl,
                data : {action : "sliced_ff_field_meta", id : $(this).val()},
                success : function(response){
                    $("#siff-loader").remove();
                    var data = JSON.parse($.trim(response));                    
                    $("#sliced_ff_item_qty").html(data.qty);
                    $("#sliced_ff_item_title").html(data.title);
                    $("#sliced_ff_item_amt").html(data.amt);
                    $("#sliced_ff_item_desc").html(data.desc);
                }
            });    
        }else{
            $('.line-item-meta').html('<option value="">--Choose Field--</option>');
        }
    });
    
    $("#si-line-add").click(function(){
        var lastID = 0;
        $("tr.line-items").each(function(){
            lastID = $(this).attr("id");
        });
        var cnt = parseFloat(lastID.split("line_").join("")) + 1;        
        var newLine = $("#"+lastID).clone();
        $(newLine).attr('id','line_'+cnt);
        $(newLine).find('#si-line-add').addClass('si-line-remove').removeAttr('id').text('Remove');
        $(newLine).find('select').val('').removeAttr('id').removeAttr('data-val');
        $(newLine).find('select option:selected').removeAttr('selected');      
        $("#"+lastID).after(newLine);
    });
    
    $("body").on('click','.si-line-remove',function(){
        $(this).closest("tr.line-items").remove();
    });
    
});