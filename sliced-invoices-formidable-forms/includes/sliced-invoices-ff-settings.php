<?php global $formidablePro;
if(isset($siff_values->enable)){ ?>
    <style>
        .sliced-ff-main{display: none;}
    </style>
<?php }
if(isset($siff_values->type) && $siff_values->type == 'quote'){ ?>
    <script>
        jQuery(document).ready(function($){        
            $('.siff-invoice').addClass('siff-none');
            $('.siff-quote').removeClass('siff-none');                
        });
    </script>
<?php } ?>
<table class="form-table sliced-ff-main">
    <tbody>
    
        <tr>
            <td colspan="3">
                <label class="switch">
                    <input type="checkbox" name="sliced_ff_enable" id="sliced_ff_enable" value="1"<?php echo(isset($siff_values->enable) && $siff_values->enable == 1  ? ' checked="checked"' : ""); ?> />
                    <span class="slider round"></span>
                </label>                
            </td>
        </tr>
        
        <tr>
            <th style="width: 230px;"><label>Type</label></th>
            <td colspan="2">
                <input type="radio" name="sliced_ff_type" class="sliced_ff_type" id="sliced_ff_type1" value="invoice"<?php echo(!isset($siff_values->type) || $siff_values->type == 'invoice'  ? ' checked="checked"' : ""); ?> /> <label for="sliced_ff_type1" style="position: relative;top: -2px;">Invoice</label>
                &nbsp;&nbsp;&nbsp;
                <input type="radio" name="sliced_ff_type" class="sliced_ff_type" id="sliced_ff_type2" value="quote"<?php echo(isset($siff_values->type) && $siff_values->type == 'quote'  ? ' checked="checked"' : ""); ?> /> <label for="sliced_ff_type2" style="position: relative;top: -2px;">Quote</label>
            </td>
        </tr> 
        
        <?php do_action('sliced_invoices_formidable_before_formfields',$siff_field); ?> 
              
        <tr><td style="font-size: 18px;padding-top: 20px;"><strong>Map Fields:</strong></td><td style="padding-top: 20px;"><i style="font-weight: bold;">Field</i></td><td style="padding-top: 20px;"><i style="font-weight: bold;">Form Field</i></td></tr>
        
        <tr>
            <td></td><th><label>Client Name<span class="siff-required">*</span></label></th>
            <td><select name="sliced_ff_cname" id="sliced_ff_cname" class="siff-form-field" data-val="<?php echo(isset($siff_values->name) ? $siff_values->name : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: text, hidden.</i></td>
        </tr>
        
        <tr>
            <td></td><th><label>Client Email<span class="siff-required">*</span></label></th>
            <td><select name="sliced_ff_cemail" id="sliced_ff_cemail" class="siff-form-field" data-val="<?php echo(isset($siff_values->email) ? $siff_values->email : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['email'])) echo $siff_field['email']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: email</i></td>
        </tr>
        
        <tr>
            <td></td><th><label>Business Name</label></th>
            <td><select name="sliced_ff_cbusiness" id="sliced_ff_cbusiness" class="siff-form-field" data-val="<?php echo(isset($siff_values->business_name) ? $siff_values->business_name : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: text, hidden.</i></td>
        </tr>
        
        <tr>
            <td></td><th><label>Business Address</label></th>
            <td><select name="sliced_ff_caddress" id="sliced_ff_caddress" class="siff-form-field" data-val="<?php echo(isset($siff_values->business_address) ? $siff_values->business_address : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                <?php if(isset($siff_field['textarea'])) echo $siff_field['textarea']; ?>
                <?php if(isset($siff_field['rte'])) echo $siff_field['rte']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: textarea,<?php echo($formidablePro?" rich-text,":"") ?> text, hidden.</i></td>
        </tr>
        
        <tr>
            <td></td><th><label>Extra Info</label></th>
            <td><select name="sliced_ff_cinfo" id="sliced_ff_cinfo" class="siff-form-field" data-val="<?php echo(isset($siff_values->extra_info) ? $siff_values->extra_info : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                <?php if(isset($siff_field['textarea'])) echo $siff_field['textarea']; ?>
                <?php if(isset($siff_field['rte'])) echo $siff_field['rte']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: textarea,<?php echo($formidablePro?" rich-text,":"") ?> text, hidden.</td>
        </tr>
        
        <tr>
            <td></td><th><label>Website</label></th>
            <td><select name="sliced_ff_cwebsite" id="sliced_ff_cwebsite" class="siff-form-field" data-val="<?php echo(isset($siff_values->website) ? $siff_values->website : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>                
                <?php if(isset($siff_field['url'])) echo $siff_field['url']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: textarea, url, text, hidden.</td>
        </tr>
                
        <tr class="siff-invoice">
            <td></td><th><label>Order Number</label></th>
            <td><select name="sliced_ff_iorder" id="sliced_ff_iorder" class="siff-form-field" data-val="<?php echo(isset($siff_values->order_number) ? $siff_values->order_number : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                <?php if(isset($siff_field['number'])) echo $siff_field['number']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: textarea, text, hidden, number.</td>
        </tr>
        
        <tr>
            <td></td><th><label>Title</label><span class="siff-required">*</span></th>
            <td><select name="sliced_ff_title" id="sliced_ff_stitle" class="siff-form-field" data-val="<?php echo(isset($siff_values->title) ? $siff_values->title : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>                                
            </select>&nbsp;&nbsp;<i>Use field type: textarea, text, hidden.</td>
        </tr>
        
        <tr>
            <td></td><th><label>Description</label></th>
            <td><select name="sliced_ff_description" id="sliced_ff_sdescription" class="siff-form-field" data-val="<?php echo(isset($siff_values->description) ? $siff_values->description : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                <?php if(isset($siff_field['textarea'])) echo $siff_field['textarea']; ?>
                <?php if(isset($siff_field['rte'])) echo $siff_field['rte']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: textarea,<?php echo($formidablePro?" rich-text,":"") ?> text, hidden.</td>
        </tr>
        
        <tr>
            <td></td><th><label>Terms &amp; Conditions</label></th>
            <td><select name="sliced_ff_terms" id="sliced_ff_sterms" class="siff-form-field" data-val="<?php echo(isset($siff_values->terms) ? $siff_values->terms : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                <?php if(isset($siff_field['textarea'])) echo $siff_field['textarea']; ?>
                <?php if(isset($siff_field['rte'])) echo $siff_field['rte']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: textarea,<?php echo($formidablePro?" rich-text,":"") ?> text, hidden.</td>
        </tr>
        
        <tr>
            <td></td><th><label>Tax Rate (%)</label></th>
            <td><select name="sliced_ff_tax" id="sliced_ff_stax" class="siff-form-field" data-val="<?php echo(isset($siff_values->tax_rate) ? $siff_values->tax_rate : ""); ?>">
                <option value="">--Choose Field--</option>
                <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                <?php if(isset($siff_field['number'])) echo $siff_field['number']; ?>
            </select>&nbsp;&nbsp;<i>Use field type: number, text, hidden.</td>
        </tr>
        
        <?php if($formidablePro){ ?>        
            <tr>
                <td></td><th><label class="siff-none siff-quote">Valid Until Date</label><label class="siff-invoice">Due Date</label></th>
                <td><select name="sliced_ff_date" id="sliced_ff_date" class="siff-form-field" data-val="<?php echo(isset($siff_values->date) ? $siff_values->date : ""); ?>">
                    <option value="">--Choose Field--</option>
                    <?php if(isset($siff_field['date'])) echo $siff_field['date']; ?>
                </select>&nbsp;&nbsp;<i>Use field type: date.</td>
            </tr>
            
            <tr>
                <td></td><th><label>Line Items<span class="siff-required">*</span></label></th>
                <td>
                    <select name="sliced_ff_items" id="sliced_ff_items" class="siff-form-field" data-val="<?php echo(isset($siff_values->line_item) ? $siff_values->line_item : ""); ?>">
                        <option value="">--Choose Field--</option>
                        <?php if(isset($siff_field['divider'])) echo $siff_field['divider']; ?>
                    </select>&nbsp;&nbsp;<i>Use field type: Repeater.</i>             
                 </td>
            </tr>
            
            <tr>
                <td colspan="2"></td>
                <td><table id="line_items" style="width: 100%;">
                    <tr>
                        <td style="width: 100px;"><i style="font-weight: bold;">Field</i></td>
                        <td><i style="font-weight: bold;">Form Field (Added under repeater of selected 'Line Items')</i></td>
                    </tr>
                    
                    <tr>
                        <td>Quantity<span class="siff-required">*</span></td>
                        <td class="sliced-ff-qty">
                            <select name="sliced_ff_item_qty" id="sliced_ff_item_qty" class="line-item-meta">
                                <option value="">--Choose Field--</option>
                                <?php echo $qty ?>
                            </select>&nbsp;&nbsp;<i>Use field type: number, hidden.
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Title<span class="siff-required">*</span></td>
                        <td class="sliced-ff-title">
                            <select name="sliced_ff_item_title" id="sliced_ff_item_title" class="line-item-meta">
                                <option value="">--Choose Field--</option>
                                <?php echo $title ?>
                            </select>&nbsp;&nbsp;<i>Use field type: text, hidden.
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Description</td>
                        <td class="sliced-ff-desc">
                            <select name="sliced_ff_item_desc" id="sliced_ff_item_desc" class="line-item-meta">
                                <option value="">--Choose Field--</option>
                                <?php echo $desc ?>
                            </select>&nbsp;&nbsp;<i>Use field type: textarea, text, hidden.
                        </td>
                    </tr>
                    
                    <tr>
                        <td>Amount<span class="siff-required">*</span></td>
                        <td class="sliced-ff-amt">
                            <select name="sliced_ff_item_amt" id="sliced_ff_item_amt" class="line-item-meta">
                                <option value="">--Choose Field--</option>
                                <?php echo $amt ?>
                            </select>&nbsp;&nbsp;<i>Use field type: number, hidden.
                        </td>
                    </tr>
                </table>
                <input type="hidden" value="<?php echo SIFF_AJAX_LOADER ?>" id="siff_ajax_loader" />
                </td>
            </tr>        
        <?php }
        else{ ?>
            <tr>
                <td></td><th><label>Line Items<span class="siff-required">*</span></label></th>
                <td>
                    <p>For line items, select fields for quantity, title, description and  amount. You can add more line items using "Add more" button</p>
                    
                </td>
            </tr>
            
            <?php $line_metas = "";
            if(isset($siff_values->line_item_meta) && $siff_values->line_item_meta != "") $line_metas = maybe_unserialize($siff_values->line_item_meta);
            if(is_array($line_metas) && isset($line_metas[0]['qty'])){
                $i = 0;
                foreach($line_metas as $lm){ $i++; ?>
                
                    <tr id="line_<?php echo $i; ?>" class="line-items">
                        <td colspan="2"></td>
                        <td><table>
                                       
                            <tr>
                                <td style="width: 80px;">Quantity<span class="siff-required">*</span></td>
                                <td style="width: 200px;">
                                    <select name="sliced_ff_item_qty[]" id="siff-qty<?php echo $i; ?>" class="line-item-meta siff-form-field" data-val="<?php echo $lm['qty'] ?>">
                                        <option value="">--Choose Field--</option>
                                        <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>                            
                                        <?php if(isset($siff_field['number'])) echo $siff_field['number']; ?>
                                    </select>&nbsp;&nbsp;<i style="display: block;">Use field type: number, hidden.
                                </td>
                                
                                <td style="width: 50px;padding: 0 9px 0 30px;">Title<span class="siff-required">*</span></td>
                                <td style="width: 200px;">
                                    <select name="sliced_ff_item_title[]" id="siff-title<?php echo $i; ?>" class="line-item-meta siff-form-field" data-val="<?php echo $lm['title'] ?>">
                                        <option value="">--Choose Field--</option>
                                        <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                                        <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>                            
                                    </select>&nbsp;&nbsp;<i style="display: block;">Use field type: text, hidden.
                                </td>
                            </tr>
                                            
                            <tr>
                                <td>Description</td>
                                <td>
                                    <select name="sliced_ff_item_desc[]" id="siff-desc<?php echo $i; ?>" class="line-item-meta siff-form-field" data-val="<?php echo $lm['desc'] ?>">
                                        <option value="">--Choose Field--</option>
                                        <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                                        <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                                        <?php if(isset($siff_field['textarea'])) echo $siff_field['textarea']; ?>
                                    </select>&nbsp;&nbsp;<i style="display: block;">Use field type: textarea, text, hidden.
                                </td>
                                
                                <td style="padding: 0 9px 0 30px;">Amount<span class="siff-required">*</span></td>
                                <td class="sliced-ff-amt">
                                    <select name="sliced_ff_item_amt[]" id="siff-amt<?php echo $i; ?>" class="line-item-meta siff-form-field" data-val="<?php echo $lm['amt'] ?>">
                                        <option value="">--Choose Field--</option>
                                        <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>                            
                                        <?php if(isset($siff_field['number'])) echo $siff_field['number']; ?>
                                    </select>&nbsp;&nbsp;<i style="display: block;">Use field type: number, hidden.
                                </td>
                            </tr>
                            
                            <tr><?php if($i==1){ ?>
                                    <td colspan="4" style="padding-bottom: 0px;"><a href="javascript:void(0);" id="si-line-add">Add more</a></td>
                                <?php }else{ ?>
                                    <td colspan="4" style="padding-bottom: 0px;"><a href="javascript:void(0);" class="si-line-remove">Remove</a></td>
                            <?php } ?></tr>
                                            
                        </table></td>
                    </tr>
                    
                <?php }
            }
            else{ ?>
                <tr id="line_1" class="line-items">
                    <td colspan="2"></td>
                    <td><table>
                                    
                        <tr>
                            <td style="width: 80px;">Quantity<span class="siff-required">*</span></td>
                            <td style="width: 200px;">
                                <select name="sliced_ff_item_qty[]" id="" class="siff-qty line-item-meta">
                                    <option value="">--Choose Field--</option>
                                    <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>                            
                                    <?php if(isset($siff_field['number'])) echo $siff_field['number']; ?>
                                </select>&nbsp;&nbsp;<i style="display: block;">Use field type: number, hidden.
                            </td>
                            
                            <td style="width: 50px;padding-left: 40px;">Title<span class="siff-required">*</span></td>
                            <td style="width: 200px;">
                                <select name="sliced_ff_item_title[]" class="siff-title line-item-meta">
                                    <option value="">--Choose Field--</option>
                                    <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                                    <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>                            
                                </select>&nbsp;&nbsp;<i style="display: block;">Use field type: text, hidden.
                            </td>
                        </tr>
                                        
                        <tr>
                            <td>Description</td>
                            <td>
                                <select name="sliced_ff_item_desc[]" class="siff-desc line-item-meta">
                                    <option value="">--Choose Field--</option>
                                    <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>
                                    <?php if(isset($siff_field['text'])) echo $siff_field['text']; ?>
                                    <?php if(isset($siff_field['textarea'])) echo $siff_field['textarea']; ?>
                                </select>&nbsp;&nbsp;<i style="display: block;">Use field type: textarea, text, hidden.
                            </td>
                            
                            <td style="padding-left: 40px;">Amount<span class="siff-required">*</span></td>
                            <td class="sliced-ff-amt">
                                <select name="sliced_ff_item_amt[]" class="siff-amt line-item-meta">
                                    <option value="">--Choose Field--</option>
                                    <?php if(isset($siff_field['hidden'])) echo $siff_field['hidden']; ?>                            
                                    <?php if(isset($siff_field['number'])) echo $siff_field['number']; ?>
                                </select>&nbsp;&nbsp;<i style="display: block;">Use field type: number, hidden.
                            </td>
                        </tr>
                        
                        <tr><td colspan="4" style="padding-bottom: 0px;"><a href="javascript:void(0);" id="si-line-add">Add more</a></td></tr>                
                    </table></td>
                </tr>
            <?php }
        } ?>
        
        <?php do_action('sliced_invoices_formidable_after_formfields',$siff_field); ?>       
         
        <tr class="siff-invoice">
            <th style="padding-top: 25px;">Set Invoice Status</th>
            <td colspan="2" style="padding-top: 20px;">
                <select name="sliced_ff_istatus" id="sliced_ff_istatus">
                    <?php $istatus = array('draft','cancelled','overdue','paid','unpaid');
                    foreach($istatus as $is){
                        if(isset($siff_values->status) && $siff_values->type == "invoice" && $siff_values->status != ""){
                            $selected = ($siff_values->status == $is ? ' selected="selected"' : "");
                        }else if($is == 'draft') $selected = ' selected="selected"';
                        else $selected = "";
                        echo '<option value="'.$is.'"'.$selected.'>'.ucfirst($is).($is == 'draft' ? " (Default)" : "").'</option>';
                    } ?>                    
                </select>                
            </td>
        </tr>
         
        <tr class="siff-none siff-quote">
            <th style="padding-top: 25px;">Set Quote Status</th>
            <td colspan="2" style="padding-top: 20px;">                               
                <select name="sliced_ff_qstatus" id="sliced_ff_qstatus">
                    <?php $qstatus = array('draft','accepted','cancelled','declined','expired','sent');
                    foreach($qstatus as $qs){
                        if(isset($siff_values->status) && $siff_values->type == "quote" && $siff_values->status != ""){
                            $selected = ($siff_values->status == $qs ? ' selected="selected"' : "");
                        }else if($qs == 'draft') $selected = ' selected="selected"';
                        else $selected = "";
                        echo '<option value="'.$qs.'"'.$selected.'>'.ucfirst($qs).($qs == 'draft' ? " (Default)" : "").'</option>';
                    } ?>                    
                </select>
            </td>
        </tr>
        
        <tr>
            <th style="padding: 15px 0px;">Send To Client</th>
            <td colspan="2" style="padding: 15px 0px;">
                <input type="radio" value="1" name="sliced_ff_client" id="sliced_ff_client1"<?php echo(isset($siff_values->send_to_client) && $siff_values->send_to_client == 1 ? ' checked="checked"' : ""); ?> /><label for="sliced_ff_client1" style="position: relative;top: -2px;">Yes</label>
                &nbsp;&nbsp;&nbsp;
                <input type="radio" value="0" name="sliced_ff_client" id="sliced_ff_client2"<?php echo(!isset($siff_values->send_to_client) || $siff_values->send_to_client == 0 ? ' checked="checked"' : ""); ?> /><label for="sliced_ff_client2" style="position: relative;top: -2px;">No</label>
                &nbsp;&nbsp; (If set to "Yes", the quote/invoice will automatically be emailed to the client as soon as it is created.)
            </td>
        </tr>
                    
    </tbody>
</table>