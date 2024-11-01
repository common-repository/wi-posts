<?php




function wi_post_RepeaterFunc(){

    echo '<br><form action="https://www.paypal.com/donate" method="post" target="_top">
        <input type="hidden" name="hosted_button_id" value="QU2M5UYRMVTSC" />
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
        <img alt="" border="0" src="https://www.paypal.com/en_DE/i/scr/pixel.gif" width="1" height="1" />
        </form>
        
        ';

    global $wpdb;

    
    $LanguageCreate = __('Create','wi_post_language');
    $LanguageDelete = __('Delete','wi_post_language');
    $LanguageMultiAction = __('MultiAction','wi_post_language');
    $LanguageMultiActionSelect = __('Choose Multiaction','wi_post_language');
    $LanguageCreateAt = __('Create at','wi_post_language');
    $LanguageSubmit = __('Submit','wi_post_language');
    $LanguageEdit = __('Edit','wi_post_language');
    $LanguageNotFound = __('Not Found','wi_post_language');
    $LanguageDescription = __('Description','wi_post_language');


    $RepeaterCount = 0;


    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['NewRepeater']))){
        require_once __DIR__ . '/RepeaterEditor.php';

        wi_post_RepeaterEditor(0, false);

    }else if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['EditRepeater']))){
        
        $ID = sanitize_text_field($_POST['ID']);

        if(is_numeric($ID) != false){

            require_once __DIR__ . '/RepeaterEditor.php';

            wi_post_RepeaterEditor($ID, false);

        }


    }else if($_SERVER['REQUEST_METHOD'] == "POST" and ((isset($_POST['RepeaterEditorSave'])) || (isset($_POST['HTMLButton2'])))){

        $ID = sanitize_text_field($_POST['ID']);

        if(is_numeric($ID) != false){


            $TitleOfRepeater = sanitize_text_field($_POST['RepeaterTitle']);
            $DescriptionOfRepeater = sanitize_text_field($_POST['RepeaterDescription']);
            $TextOfRepeater = "";
            $UseHTML = sanitize_text_field($_POST['useHTML']);

            if($UseHTML == "TRUE"){
                $TextOfRepeater = sanitize_text_field( htmlentities(htmlspecialchars_decode($_POST['RepeaterTextArea'])));
            }else{
                $TextOfRepeater = sanitize_text_field( htmlentities(htmlspecialchars_decode(preg_replace("/\r\n|\r|\n/",'<br>',$_POST['RepeaterTextArea']))));
            }

            if($ID == "0"){
        
                $wpdb->insert('wi_post_Repeater', array(
                    'Description' => $DescriptionOfRepeater,
                    'Repeater' => $TitleOfRepeater,
                    'Content' => $TextOfRepeater,
                ));

                $ID = $wpdb->insert_id;
            }




            if((isset($_POST['HTMLButton2']))){
                require_once __DIR__ . '/getOption.php';
                if($UseHTML == "TRUE"){
                    wi_post_setOption('useHTML', "0");
                }else{
                    wi_post_setOption('useHTML', "1");
                }
            }
            
            $wpdb->query($wpdb->prepare("UPDATE wi_post_Repeater SET Repeater='".$TitleOfRepeater."',Description='".$DescriptionOfRepeater."',Content='".$TextOfRepeater."' WHERE RepeaterID=".$ID.""));


            require_once __DIR__ . '/updatePosts.php';
            $ContentRepeaterList = $wpdb->get_results("SELECT DISTINCT ContentID FROM wi_post_Content_Repeater WHERE RepeaterID=".$ID.""); 
            foreach($ContentRepeaterList as $ContentRepeater)
            {

                wi_post_updatePosts($ContentRepeater->ContentID);

            }

            require_once __DIR__ . '/RepeaterEditor.php';

            wi_post_RepeaterEditor($ID, true);
        }

    }else{



    echo '
    <form method="POST">
        <div class="wrap">
            <h1 class="wp-heading-inline">
            Repeaters</h1>
        
            <input class="page-title-action" type="submit" name="NewRepeater" value="'.esc_html($LanguageCreate).'">
        <hr class="wp-header-end">
    </form>';

    echo '<br>';

    echo '<form method="POST">
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="MultipleSelect7" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
            <select name="MultipleSelect7" id="MultipleSelect7">
                <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                <option value="delete">'.esc_html($LanguageDelete).'</option>
            </select>
            <input type="submit" id="MultipleAction7" name="MultipleAction7" class="button action" value="'.esc_html($LanguageSubmit).'">
        </div>
        <br class="clear">
    </div>';



    echo '<h2 class="screen-reader-text">Seitenliste</h2><table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input id="cb-select-all-1" type="checkbox"></td>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">&nbsp;&nbsp;<span>Repeater</span></th>
            <th scope="col" id="author" class="manage-column column-author">'.esc_html($LanguageDescription).'</th>
        </tr>
    </thead>

    <tbody id="the-list">';
            

     
    //Repeaters
    $RepeaterList = $wpdb->get_results("SELECT RepeaterID, Description, Repeater FROM wi_post_Repeater ORDER BY Repeater"); 
    $RepeaterCount = $wpdb->num_rows;

    if($RepeaterCount > 0){
        foreach($RepeaterList as $Repeater)
        {
    
            $RepeaterDescription = $Repeater->Description;
            $RepeaterID = $Repeater->RepeaterID;
            $Repeater = $Repeater->Repeater;
    
            echo'
            <tr class="iedit author-self level-0 post-1137019 type-page status-publish hentry">
    
                <th scope="row" class="check-column">
                    <input id="RepeaterCheckbox" type="checkbox" name="Repeater[]" value="'.esc_html($RepeaterID).'">
                </th>
    
                <form method="POST">
                    <input type="hidden" value="'.esc_html($RepeaterID).'" name="ID"/>
                
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Titel">
                        <div class="locked-info">
                            <span class="locked-avatar"></span>
                            <span class="locked-text"></span>
                        </div>
                        <strong>
                            <input class="row-title" type="submit" name="EditRepeater" value="'.esc_html($Repeater).'" aria-label="„'.esc_html($Repeater).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'">
                        </strong>
                        <div class="row-actions">
                            <span class="edit"><input class="row-title" type="submit" name="EditRepeater" value="'.esc_html($LanguageEdit).'" aria-label="„'.esc_html($Repeater).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"> | </span>
                            <span class="trash"><input class="row-title" type="submit" name="DeleteRepeater" value="'.esc_html($LanguageDelete).'" aria-label="„'.esc_html($Repeater).'“ ('.esc_html($LanguageDelete).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"></span>
                        </div>
                    </td>
                        
                    <td class="author column-author" data-colname="Autor">'.esc_html($RepeaterDescription).'</td>
    
                </form>
            
            </tr>';
        }
    }else{
        echo'<tr class="no-items"><td class="colspanchange" colspan="3">'.esc_html($LanguageNotFound).'</td></tr>';
    }




    echo '</tbody>

    <tfoot>
        <tr>
            <td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Alle auswählen</label><input id="cb-select-all-2" type="checkbox"></td>
            <th scope="col" class="manage-column column-author"><span>Repeater</span></th>
            <th scope="col" class="manage-column column-author">'.esc_html($LanguageDescription).'</th>
        </tr>
    </tfoot>

    </table>';

    echo '
    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <label for="MultipleSelect8" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
            <select name="MultipleSelect8" id="MultipleSelect8">
                <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                <option value="delete">'.esc_html($LanguageDelete).'</option>
            </select>
            <input type="submit" id="MultipleAction8" name="MultipleAction8" class="button action" value="'.esc_html($LanguageSubmit).'">
        </div>
        <div class="alignleft actions"></div>
        <div class="tablenav-pages one-page">
            <span class="displaying-num">'.esc_html($RepeaterCount).' Repeater</span>
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

    
    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['DeleteRepeater']))){

        $value = sanitize_text_field($_POST['ID']);

        if(is_numeric($value) != false){

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Repeater WHERE RepeaterID=".$value.""
                ) 
            );

            $ContentRepeaterList = $wpdb->get_results("SELECT DISTINCT ContentID FROM wi_post_Content_Repeater WHERE RepeaterID=".$value.""); 
            foreach($ContentRepeaterList as $ContentRepeater)
            {

                wi_post_updatePosts($ContentRepeater->ContentID);

            }
            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content_Repeater WHERE RepeaterID=".$value.""
                ) 
            );

            echo "<meta http-equiv='refresh' content='0'>";

        }

    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and ((isset($_POST['MultipleAction7'])) || (isset($_POST['MultipleAction8'])))){

        $Choose = "";

        if((isset($_POST['MultipleAction7']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect7']);

        }else if((isset($_POST['MultipleAction8']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect8']);

        }

        if($Choose == "delete"){

            foreach ($_POST['Repeater'] as &$value) {

                $value = sanitize_text_field($value);

                if(is_numeric($value) != false){
 
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Repeater WHERE RepeaterID=".$value.""
                        ) 
                    );

                    $ContentRepeaterList = $wpdb->get_results("SELECT DISTINCT ContentID FROM wi_post_Content_Repeater WHERE RepeaterID=".$value.""); 
                    foreach($ContentRepeaterList as $ContentRepeater)
                    {
            
                        wi_post_updatePosts($ContentRepeater->ContentID);
            
                    }
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content_Repeater WHERE RepeaterID=".$value.""
                        ) 
                    );

                }
            }
            
            echo "<meta http-equiv='refresh' content='0'>";

            

        }
    }
}




?>