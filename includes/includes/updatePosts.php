<?php


function wi_post_updatePosts($ContentID){
    global $wpdb;

    $PostStatus = "";
    $TitleOfPost = "";

    $FullText = "";

    $PostList2 = $wpdb->get_results("SELECT TemplateID, Status, Titel FROM wi_post_Content WHERE ContentID=".$ContentID.""); 
    $PostCount2 = $wpdb->num_rows;
    if($PostCount2 > 0){
        foreach($PostList2 as $Post2){

            $PostStatus = $Post2->Status;
            $TitleOfPost = $Post2->Titel;

            $TemplateList2 = $wpdb->get_results("SELECT Template FROM wi_post_Templates WHERE TemplateID=".$Post2->TemplateID.""); 
            $TemplateCount2 = $wpdb->num_rows;

            if($TemplateCount2 > 0){
                foreach($TemplateList2 as $Template2)
                {
                    $FullText = html_entity_decode($Template2->Template);
                }
            }
            $RepeaterList = $wpdb->get_results("SELECT Repeater, RepeaterID, Content FROM wi_post_Repeater ORDER BY Repeater"); 

            foreach($RepeaterList as $Repeater)
            {
                if(strpos($FullText, $Repeater->Repeater) !== false){

                    $ContentRepeaterList = $wpdb->get_results("SELECT Count FROM wi_post_Content_Repeater WHERE ContentID=".$ContentID." AND RepeaterID=".$Repeater->RepeaterID.""); 
                    $ContentRepeaterCount = $wpdb->num_rows;
                    if($ContentRepeaterCount > 0){
                        foreach($ContentRepeaterList as $ContentRepeater)
                        {
                            $ContentForRepeaterFull = "";
                            for ($i = 0; $i < $ContentRepeater->Count; $i++){
                                $ReplaceRepeater = html_entity_decode($Repeater->Content);

                                $PlaceholderList2 = $wpdb->get_results("SELECT Placeholder, PlaceholderID FROM wi_post_Placeholder ORDER BY Placeholder"); 
                    
                                foreach($PlaceholderList2 as $Placeholder)
                                {
                                    if(strpos($ReplaceRepeater, $Placeholder->Placeholder) !== false){
                    
                                        $ContentPlaceholderList2 = $wpdb->get_results("SELECT Input FROM wi_post_Content_Repeater_Placeholder WHERE ContentID=".$ContentID." AND PlaceholderID=".$Placeholder->PlaceholderID." AND Number=".$i." AND RepeaterID=".$Repeater->RepeaterID.""); 
                                        $ContentPlaceholderCount2 = $wpdb->num_rows;
                                        if($ContentPlaceholderCount2 > 0){
                                            foreach($ContentPlaceholderList2 as $ContentPlaceholder)
                                            {
                                                $ReplaceRepeater = str_replace($Placeholder->Placeholder, html_entity_decode($ContentPlaceholder->Input), $ReplaceRepeater);
                                            }
                                        }else{
                                            $ReplaceRepeater = str_replace($Placeholder->Placeholder, "", $ReplaceRepeater);
                                        }
                    
                                    }
                                }

                                $ContentForRepeaterFull .= $ReplaceRepeater;
                            }

                            $FullText = str_replace($Repeater->Repeater, $ContentForRepeaterFull, $FullText);
                        }
                    }else{
                        $FullText = str_replace($Repeater->Repeater, "", $FullText);
                    }

                }
            }

            $PlaceholderList = $wpdb->get_results("SELECT Placeholder, PlaceholderID FROM wi_post_Placeholder ORDER BY Placeholder"); 

            foreach($PlaceholderList as $Placeholder)
            {
                if(strpos($FullText, $Placeholder->Placeholder) !== false){

                    $ContentPlaceholderList = $wpdb->get_results("SELECT Input FROM wi_post_Content_Placeholder WHERE ContentID=".$ContentID." AND PlaceholderID=".$Placeholder->PlaceholderID.""); 
                    $ContentPlaceholderCount = $wpdb->num_rows;
                    if($ContentPlaceholderCount > 0){
                        foreach($ContentPlaceholderList as $ContentPlaceholder)
                        {
                            $FullText = str_replace($Placeholder->Placeholder, html_entity_decode($ContentPlaceholder->Input), $FullText);
                        }
                    }else{
                        $FullText = str_replace($Placeholder->Placeholder, "", $FullText);
                    }

                }
            }
            $ContentLinkList = $wpdb->get_results("SELECT Link, Link_Placeholder FROM wi_post_Content"); 

            foreach($ContentLinkList as $ContentLink)
            {
                if(strpos($FullText, $ContentLink->Link_Placeholder) !== false && $ContentLink->Link != ""){

                    $FullText = str_replace($ContentLink->Link_Placeholder, $ContentLink->Link, $FullText);

                }
            }
        }
    }
    


    $CheckPostList = $wpdb->get_results("SELECT ID, post_name FROM ".$wpdb->prefix."posts WHERE wi_post_post=".$ContentID.""); 
    $CheckPostCount = $wpdb->num_rows;

    $Link = "";
    

    if($CheckPostCount > 0){
        //UPDATE
        
        $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}posts SET post_content='".$FullText."', post_title='".$TitleOfPost."', post_status='".$PostStatus."' WHERE wi_post_post=".$ContentID.""));
        
        $Link = get_bloginfo('url') . '/' . $CheckPostList[0]->post_name;
        
    }else{
        
        //INSERT
        $user = get_current_user_id();

        $Link = URLDecoder(strtolower($TitleOfPost));

        $wpdb->insert($wpdb->prefix . 'posts', array(
            'post_author' => $user,
            'post_content' => $FullText,
            'post_title' => $TitleOfPost,
            'post_status' => $PostStatus,
            'post_name' => $Link,
            'post_type' => "post",
            'comment_status' => "closed",
            'ping_status' => "publish",
            'post_excerpt' => "",
            'to_ping' => "",
            'pinged' => "",
            'post_content_filtered' => "",
            'wi_post_post' => $ContentID,
        ));

        $Link = get_bloginfo('url') . '/' . $Link;
        
    }


    
    $wpdb->query($wpdb->prepare("UPDATE wi_post_Content SET Link='".$Link."' WHERE ContentID=".$ContentID.""));

}

function URLDecoder($string)
{
 $string = str_replace("ä", "ae", $string);
 $string = str_replace("ü", "ue", $string);
 $string = str_replace("ö", "oe", $string);
 $string = str_replace("Ä", "Ae", $string);
 $string = str_replace("Ü", "Ue", $string);
 $string = str_replace("Ö", "Oe", $string);
 $string = str_replace("ß", "ss", $string);
 $string = str_replace("´", "", $string);
 $string = str_replace(" ", "-", $string);
 return $string;
}


?>