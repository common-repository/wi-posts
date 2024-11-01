<?php

function wi_post_TemplateMenu(){
    require_once( 'includes/TemplateMenu.php' );

    wi_post_TemplateFunc();
}

function wi_post_PostMenu(){
    require_once( 'includes/PostMenu.php' );

    wi_post_PostFunc();
}

function wi_post_RepeaterMenu(){
    require_once( 'includes/RepeaterMenu.php' );

    wi_post_RepeaterFunc();
}

function wi_post_PlaceholderMenu(){
    require_once( 'includes/PlaceholderMenu.php' );

    wi_post_PlaceholderFunc();
}

?>