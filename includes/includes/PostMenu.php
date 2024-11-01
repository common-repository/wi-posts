<?php

function wi_post_PostFunc(){

    echo '<br><form action="https://www.paypal.com/donate" method="post" target="_top">
<input type="hidden" name="hosted_button_id" value="QU2M5UYRMVTSC" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_DE/i/scr/pixel.gif" width="1" height="1" />
</form>

        ';

    global $wpdb;


    $LanguagePosts = __('Posts','wi_post_language');
    $LanguageCreate = __('Create','wi_post_language');
    $LanguageDelete = __('Delete','wi_post_language');
    $LanguageMultiAction = __('MultiAction','wi_post_language');
    $LanguageMultiActionSelect = __('Choose Multiaction','wi_post_language');
    $LanguageTitle = __('Title','wi_post_language');
    $LanguageAuthor = __('Author','wi_post_language');
    $LanguageDate = __('Date','wi_post_language');
    $LanguageCreateAt = __('Create at','wi_post_language');
    $LanguageSubmit = __('Submit','wi_post_language');
    $LanguageEdit = __('Edit','wi_post_language');
    $LanguageNotFound = __('Not Found','wi_post_language');
    $LanguageStatus = __('Status','wi_post_language');
    $LanguagePreview = __('Preview','wi_post_language');
    $LanguagePublish = __('Publish','wi_post_language');
    $LanguagePrivate = __('Private','wi_post_language');
    $LanguageDraft = __('Draft','wi_post_language');



    $PostCount = 0;


    
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['NewPost']))){
        require_once __DIR__ . '/PostEditor.php' ;

        wi_post_PostEditor(0, false);

    }else if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['EditPost']))){
        
        $ID = sanitize_text_field($_POST['ID']);

        if(is_numeric($ID) != false){

            require_once __DIR__ . '/PostEditor.php' ;

            wi_post_PostEditor($ID, false);

        }


    }else if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['PostEditorSave']))){
        

        $TheID = sanitize_text_field($_POST['ID']);

        if(is_numeric($TheID) != false){

            $TitleOfPost = sanitize_text_field($_POST['PostTitel']);
            $PlaceholderContent = array_map( 'sanitize_text_field', array_map( 'htmlentities', array_map( 'htmlspecialchars_decode', $_POST['PlaceholderContent'] )));
            $PlaceholderContentID = array_map( 'sanitize_text_field', $_POST['PlaceholderContentID']);
            $PostStatus = sanitize_text_field($_POST['Status']);
            $Repeater = array_map( 'sanitize_text_field', $_POST['Repeater']);
            $RepeaterContentID = array_map( 'sanitize_text_field', $_POST['RepeaterContentID']);
            
            for($z = 0; $z < count($Repeater);$z++){
                $wpdb->query($wpdb->prepare("UPDATE wi_post_Content_Repeater SET count=".$Repeater[$z]." WHERE ContentID=".$TheID." AND id=".$RepeaterContentID[$z].""));

                if($_POST['CountList'.$RepeaterContentID[$z].''] != null){
                    $CountListArray = array_map( 'sanitize_text_field', $_POST['CountList'.$RepeaterContentID[$z].'']);
                    
                    foreach ($CountListArray as $y){

                        $PlaceholderList = array_map( 'sanitize_text_field', $_POST['PlaceholderList'.$RepeaterContentID[$z].$y.'']);

                        foreach($PlaceholderList as $IdOfPlaceholder){
                            $wpdb->query($wpdb->prepare("UPDATE wi_post_Content_Repeater_Placeholder SET Input='".sanitize_text_field( htmlentities(htmlspecialchars_decode($_POST['Repeater'.$RepeaterContentID[$z].$y.''][$IdOfPlaceholder])))."' WHERE ContentID=".$TheID." AND id=".sanitize_text_field($_POST['ContentRepeaterPlaceholder'.$RepeaterContentID[$z].$y.''][$IdOfPlaceholder]).""));
                        }
                    }
                }
            }

            for($i = 0; $i < count($PlaceholderContent);$i++){
                $wpdb->query($wpdb->prepare("UPDATE wi_post_Content_Placeholder SET Input='".$PlaceholderContent[$i]."' WHERE ContentID=".$TheID." AND id=".$PlaceholderContentID[$i].""));
            }

            $wpdb->query($wpdb->prepare("UPDATE wi_post_Content SET Titel='".$TitleOfPost."', Status='".$PostStatus."' WHERE ContentID=".$TheID.""));

            require_once __DIR__ . '/updatePosts.php';
            wi_post_updatePosts($TheID);

            require_once __DIR__ . '/PostEditor.php' ;
            wi_post_PostEditor($TheID, true);

        }

        

    }else if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['PostEditorCreate']))){

        $TemplateOfPost = sanitize_text_field($_POST['Template']);

        if(is_numeric($TemplateOfPost) != false){

            $TitleOfPost = sanitize_text_field($_POST['PostTitel']);
            $user = wp_get_current_user();
            $Author = $user->nickname;

            $wpdb->insert('wi_post_Content', array(
                'Titel' => $TitleOfPost,
                'TemplateID' => $TemplateOfPost,
                'author' => $Author,
                'Link_Placeholder' => '%'.$TitleOfPost.'%',
            ));
            $lastid = $wpdb->insert_id;

            require_once __DIR__ . '/PostEditor.php' ;

            wi_post_PostEditor($lastid, true);

        }

    }else{



    echo '
    <form method="POST">
        <div class="wrap">
            <h1 class="wp-heading-inline">
            '.esc_html($LanguagePosts).'</h1>
        
            <input class="page-title-action" type="submit" name="NewPost" value="'.esc_html($LanguageCreate).'">
        <hr class="wp-header-end">
    </form>';

    echo '<br>';

    echo '<form method="POST">
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="MultipleSelect6" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
            <select name="MultipleSelect6" id="MultipleSelect6">
                <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                <option value="delete">'.esc_html($LanguageDelete).'</option>
            </select>
            <input type="submit" id="MultipleAction6" name="MultipleAction6" class="button action" value="'.esc_html($LanguageSubmit).'">
        </div>
        <br class="clear">
    </div>';



    echo '<h2 class="screen-reader-text">Seitenliste</h2><table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input id="cb-select-all-1" type="checkbox"></td>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">&nbsp;&nbsp;<span>'.esc_html($LanguageTitle).'</span></th>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">&nbsp;&nbsp;<span>Template</span></th>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">&nbsp;&nbsp;<span>'.esc_html($LanguageStatus).'</span></th>
            <th scope="col" id="author" class="manage-column column-author">'.esc_html($LanguageAuthor).'</th>
            <th scope="col" id="author" class="manage-column column-author">'.esc_html($LanguageDate).'</th>
        </tr>
    </thead>

    <tbody id="the-list">';
            

     
    //Posts
    $PostList = $wpdb->get_results("SELECT * FROM wi_post_Content ORDER BY Titel"); 
    $PostCount = $wpdb->num_rows;

    if($PostCount > 0){
        foreach($PostList as $Post)
        {
    
            $PostCreationDate = $Post->date;
            $PostAuthor = $Post->author;
            $PostID = $Post->ContentID;
            $PostTitle = $Post->Titel;
            $TemplateID = $Post->TemplateID;
            $PostLink = $Post->Link;

            $TemplateTitle = "Nicht gefunden";
            $TemplateList = $wpdb->get_results("SELECT Titel FROM wi_post_Templates WHERE TemplateID=".$TemplateID.""); 
            $TemplateCount = $wpdb->num_rows;
            foreach($TemplateList as $Template)
            {
                $TemplateTitle = $Template->Titel;
            }

            $PostLinkCode = "";

            if($PostLink != ""){
                $PostLinkCode = ' | <span class="view"><a href="'.esc_url($PostLink).'" rel="bookmark" aria-label=" ansehen" target="_blank">'.esc_html($LanguagePreview).'</a></span>';
            }


    
            echo'
            <tr class="iedit author-self level-0 post-1137019 type-page status-publish hentry">
    
                <th scope="row" class="check-column">
                    <input id="PostCheckbox" type="checkbox" name="Post[]" value="'.esc_html($PostID).'">
                </th>
    
                <form method="POST">
                    <input type="hidden" value='.esc_html($PostID).' name="ID"/>
                
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Titel">
                        <div class="locked-info">
                            <span class="locked-avatar"></span>
                            <span class="locked-text"></span>
                        </div>
                        <strong>
                            <input class="row-title" type="submit" name="EditPost" value="'.esc_html($PostTitle).'" aria-label="„'.esc_html($PostTitle).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'">
                        </strong>
                        <div class="row-actions">
                            <span class="edit"><input class="row-title" type="submit" name="EditPost" value="'.esc_html($LanguageEdit).'" aria-label="„'.esc_html($PostTitle).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"> | </span>
                            <span class="trash"><input class="row-title" type="submit" name="DeletePost" value="'.esc_html($LanguageDelete).'" aria-label="„'.esc_html($PostTitle).'“ ('.esc_html($LanguageDelete).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"></span>
                            '.$PostLinkCode.'
                        </div>
                    </td>
                        
                    <td class="author column-author" data-colname="Autor">'.esc_html($TemplateTitle).'</td>';
                        
                    if($Post->Status == "publish"){
                        echo '<td class="author column-author" data-colname="Autor">'.esc_html($LanguagePublish).'</td>';
                    }else if($Post->Status == "private"){
                        echo '<td class="author column-author" data-colname="Autor">'.esc_html($LanguagePrivate).'</td>';
                    }else{
                        echo '<td class="author column-author" data-colname="Autor">'.esc_html($LanguageDraft).'</td>';
                    }
                        
                    echo'
                    <td class="author column-author" data-colname="Autor">'.esc_html($PostAuthor).'</td>
                    
                    <td class="date column-date" data-colname="Datum">'.esc_html($LanguageCreateAt).'<br>'.esc_html($PostCreationDate).'</td>
    
                </form>
            
            </tr>';
        }
    }else{
        echo'<tr class="no-items"><td class="colspanchange" colspan="6">'.esc_html($LanguageNotFound).'</td></tr>';
    }




    echo '</tbody>

    <tfoot>
        <tr>
            <td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Alle auswählen</label><input id="cb-select-all-2" type="checkbox"></td>
            <th scope="col" class="manage-column column-author"><span>'.esc_html($LanguageTitle).'</span></th>
            <th scope="col" class="manage-column column-author">Template</th>
            <th scope="col" class="manage-column column-author"><span>'.esc_html($LanguageStatus).'</span></th>
            <th scope="col" class="manage-column column-author">'.esc_html($LanguageAuthor).'</th>
            <th scope="col" class="manage-column column-author">'.esc_html($LanguageDate).'</th>	
        </tr>
    </tfoot>

    </table>';

    echo '
    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <label for="MultipleSelect5" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
            <select name="MultipleSelect5" id="MultipleSelect5">
                <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                <option value="delete">'.esc_html($LanguageDelete).'</option>
            </select>
            <input type="submit" id="MultipleAction5" name="MultipleAction5" class="button action" value="'.esc_html($LanguageSubmit).'">
        </div>
        <div class="alignleft actions"></div>
        <div class="tablenav-pages one-page">
            <span class="displaying-num">'.esc_html($PostCount).' Posts</span>
            <span class="pagination-links">
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                <span class="screen-reader-text">Aktuelle Seite</span>
                <span id="table-paging" class="paging-input">
                    <span class="tablenav-paging-text">1 von 
                        <span class="total-pages">1</span>
                    </span>
                </span>
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
            </span>
        </div>
        <br class="clear">
    </div></form>';

    
    }

    
    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['DeletePost']))){

        $value = sanitize_text_field($_POST['ID']);

        if(is_numeric($value) != false){

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content WHERE ContentID=".$value.""
                ) 
            );

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content_Placeholder WHERE ContentID=".$value.""
                ) 
            );

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM {$wpdb->prefix}posts WHERE wi_post_post=".$value.""
                ) 
            );
            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content_Repeater WHERE ContentID=".$value.""
                ) 
            );
            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content_Repeater_Placeholder WHERE ContentID=".$value.""
                ) 
            );

            echo "<meta http-equiv='refresh' content='0'>";

        }

    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and ((isset($_POST['MultipleAction5'])) || (isset($_POST['MultipleAction6'])))){

        $Choose = "";

        if((isset($_POST['MultipleAction5']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect5']);

        }else if((isset($_POST['MultipleAction6']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect6']);

        }

        if($Choose == "delete"){

            foreach ($_POST['Post'] as &$value) {

                $value = sanitize_text_field($value);

                if(is_numeric($value) != false){
 
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content WHERE ContentID=".$value.""
                        ) 
                    );

                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content_Placeholder WHERE ContentID=".$value.""
                        ) 
                    );

                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM {$wpdb->prefix}posts WHERE wi_post_post=".$value.""
                        ) 
                    );
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content_Repeater WHERE ContentID=".$value.""
                        ) 
                    );
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content_Repeater_Placeholder WHERE ContentID=".$value.""
                        ) 
                    );
                }
            }
            
            echo "<meta http-equiv='refresh' content='0'>";

        }
    }

    

}
?>