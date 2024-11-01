<?php


function wi_post_getOption($OptionName){
    global $wpdb;

    $OptionList = $wpdb->get_results("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name='".$OptionName."'"); 
    $OptionCount = $wpdb->num_rows;

    if($OptionCount > 0){
        foreach($OptionList as $Option)
        {
            return $Option->option_value;
        }
    }

    return null;

}

function wi_post_setOption($OptionName, $Option){
    global $wpdb;
    
    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}options SET option_value='".$Option."' WHERE option_name='".$OptionName."'"));

}




?>