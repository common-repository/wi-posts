<?php


function wi_post_PlaceholderFunc(){

    echo '<br><form action="https://www.paypal.com/donate" method="post" target="_top">
        <input type="hidden" name="hosted_button_id" value="QU2M5UYRMVTSC" />
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
        <img alt="" border="0" src="https://www.paypal.com/en_DE/i/scr/pixel.gif" width="1" height="1" />
        </form>
        
        ';


    global $wpdb;

    
    $LanguageDelete = __('Delete','wi_post_language');
    $LanguageEdit = __('Edit','wi_post_language');
    $LanguageMultiAction = __('MultiAction','wi_post_language');
    $LanguageMultiActionSelect = __('Choose Multiaction','wi_post_language');
    $LanguageAddNewPlaceholder = __('Add New Placeholder','wi_post_language');
    $LanguageDescription = __('Description','wi_post_language');
    $LanguageAddNewPlaceholderText = __('You can place the Placeholder into the Template. It will be replaced with your Content.','wi_post_language');
    $LanguageDescriptionText = __('The Description wont be shown anywhere. Its only for you!','wi_post_language');
    $LanguageSubmit = __('Submit','wi_post_language');
    $LanguageNotFound = __('Not Found','wi_post_language');

    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['EditPlaceholder']))){
        
        $ID = sanitize_text_field($_POST['ID']);

        if(is_numeric($ID) != false){

            require_once __DIR__ . '/PlaceholderEditor.php';

            wi_post_PlaceholderEditor($ID, false);

        }


    }else if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['PlaceholderEditorSave']))){
        
        
        $ID = sanitize_text_field($_POST['ID']);

        if(is_numeric($ID) != false){

            $NameOfPlaceholder = sanitize_text_field($_POST['PlaceholderName']);
            $NameOfDescription = sanitize_text_field($_POST['PlaceholderDescription']);

            $wpdb->query($wpdb->prepare("UPDATE wi_post_Placeholder SET Placeholder='".$NameOfPlaceholder."', Description='".$NameOfDescription."' WHERE PlaceholderID=".$ID.""));
            
            require_once __DIR__ . '/updatePosts.php';
            $ContentPlaceholderList = $wpdb->get_results("SELECT DISTINCT ContentID FROM wi_post_Content_Placeholder WHERE PlaceholderID=".$ID.""); 
            foreach($ContentPlaceholderList as $ContentPlaceholder)
            {

                wi_post_updatePosts($ContentPlaceholder->ContentID);

            }

            require_once __DIR__ . '/PlaceholderEditor.php';

            wi_post_PlaceholderEditor($ID, true);

        }

    }else{


    echo'
    <div class="wrap nosubsub">
        <h1 class="wp-heading-inline">Placeholder</h1>
        <hr class="wp-header-end">
        <div id="col-container" class="wp-clearfix">
            <div id="col-left">
                <div class="col-wrap">
                    <div class="form-wrap">
                        <form method="POST">
                            <h2>'.esc_html($LanguageAddNewPlaceholder).'</h2>
                            <div class="form-field form-required term-name-wrap">
                                <label for="tag-name">Placeholder</label>
                                <input name="PlaceholderName" id="PlaceholderName" type="text" value="" size="40" aria-required="true">
                                <p>'.esc_html($LanguageAddNewPlaceholderText).'</p>
                            </div>
                            <div class="form-field term-description-wrap">
                                <label for="tag-description">'.esc_html($LanguageDescription).'</label>
                                <textarea name="PlaceholderDescription" id="PlaceholderDescription" rows="5" cols="40"></textarea>
                                <p>'.esc_html($LanguageDescriptionText).'</p>
                            </div>
                            <p class="submit">
                                <input type="submit" name="CreatePlaceholder" id="CreatePlaceholder" class="button button-primary" value="'.esc_html($LanguageAddNewPlaceholder).'">
                                <span class="spinner"></span>
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            <div id="col-right">
                <form method="POST">
                    <div class="col-wrap">
                        <div class="tablenav top">
                            <div class="alignleft actions bulkactions">
                                <label for="bulk-action-selector-top" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
                                <select name="MultipleSelect3" id="MultipleSelect3">
                                    <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                                    <option value="delete">'.esc_html($LanguageDelete).'</option>
                                </select>
                                <input type="submit" id="MultipleAction3" name="MultipleAction3" class="button action" value="'.esc_html($LanguageSubmit).'">
                            </div>
                            <br class="clear">
                        </div>
                        <h2 class="screen-reader-text">Tags list</h2>
                        <table class="wp-list-table widefat fixed striped table-view-list tags">
                            <thead>
                                <tr>
                                    <td id="cb" class="manage-column column-cb check-column">
                                        <label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label>
                                        <input id="cb-select-all-1" type="checkbox">
                                    </td>
                                    <th scope="col" id="name" class="manage-column column-name column-primary sortable desc" style="height:35px;">
                                        <span>Placeholder</span>
                                    </th>
                                    <th scope="col" id="description" class="manage-column column-description sortable desc">
                                        <span>'.esc_html($LanguageDescription).'</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody id="the-list" data-wp-lists="list:tag">';

                            //Templates
                            $PlaceholderList = $wpdb->get_results("SELECT * FROM wi_post_Placeholder ORDER BY Placeholder"); 
                            $PlaceholderCount = $wpdb->num_rows;

                            if($PlaceholderCount > 0){
                                foreach($PlaceholderList as $Placeholder)
                                {
                                    $PlaceholderName = $Placeholder->Placeholder;
                                    $PlaceholderDescription = $Placeholder->Description;
                                    $PlaceholderID = $Placeholder->PlaceholderID;
    
                                    if($PlaceholderDescription == ""){
                                        $PlaceholderDescription = "-";
                                    }
    
                                    echo'
                                    <tr id="tag-3" class="level-0">
                                        <th scope="row" class="check-column">
                                            <label class="screen-reader-text" for="cb-select-3">Test auswählen</label>
                                            <input type="checkbox" name="Placeholder[]" value="'.esc_html($PlaceholderID).'" id="cb-select-3">
                                        </th>
    
                                        <form method="POST">
                                            <input type="hidden" value="'.esc_html($PlaceholderID).'" name="ID"/>
                                            <td class="name column-name has-row-actions column-primary" data-colname="Name">
                                                <strong>
                                                    <input class="row-title" type="submit" name="EditPlaceholder" value="'.esc_html($PlaceholderName).'" aria-label="„'.esc_html($PlaceholderName).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'">
                                                </strong>
                                                <br>
                                                <div class="row-actions">
                                                    <span class="edit"><input class="row-title" type="submit" name="EditPlaceholder" value="'.esc_html($LanguageEdit).'" aria-label="„'.esc_html($PlaceholderName).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"> | </span>
                                                    <span class="trash"><input class="row-title" type="submit" name="DeletePlaceholder" value="'.esc_html($LanguageDelete).'" aria-label="„'.esc_html($PlaceholderName).'“ ('.esc_html($LanguageDelete).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"></span>
                                                </div>
                                            </td>
                                            <td class="description column-description" data-colname="Beschreibung">
                                                <span aria-hidden="true">'.esc_html($PlaceholderDescription).'</span>
                                            </td>
                                        </form>
                                    </tr>';
                                }
                            }else{
                                echo'<tr class="no-items"><td class="colspanchange" colspan="3">'.esc_html($LanguageNotFound).'</td></tr>';
                            }
echo'
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td id="cb" class="manage-column column-cb check-column">
                                        <label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label>
                                        <input id="cb-select-all-1" type="checkbox">
                                    </td>
                                    <th scope="col" id="name" class="manage-column column-name column-primary sortable desc" style="height:35px;">
                                        <span>Placeholder</span>
                                    </th>
                                    <th scope="col" id="description" class="manage-column column-description sortable desc">
                                        <span>'.esc_html($LanguageDescription).'</span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="tablenav bottom">
                            <div class="alignleft actions bulkactions">
                                <label for="bulk-action-selector-bottom" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
                                <select name="MultipleSelect4" id="MultipleSelect4">
                                    <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                                    <option value="delete">'.esc_html($LanguageDelete).'</option>
                                </select>
                                <input type="submit" id="MultipleAction4" name="MultipleAction4" class="button action" value="'.esc_html($LanguageSubmit).'">
                            </div>
                            <br class="clear">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    ';

                            
    }


    
    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['DeletePlaceholder']))){

        $value = sanitize_text_field($_POST['ID']);

        if(is_numeric($value) != false){

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Placeholder WHERE PlaceholderID=".$value.""
                ) 
            );
            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content_Repeater_Placeholder WHERE PlaceholderID=".$value.""
                ) 
            );

            require_once __DIR__ . '/updatePosts.php';
            $ContentPlaceholderList = $wpdb->get_results("SELECT DISTINCT ContentID FROM wi_post_Content_Placeholder WHERE PlaceholderID=".$value.""); 
            foreach($ContentPlaceholderList as $ContentPlaceholder)
            {

                wi_post_updatePosts($ContentPlaceholder->ContentID);

            }

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content_Placeholder WHERE PlaceholderID=".$value.""
                ) 
            );

            echo "<meta http-equiv='refresh' content='0'>";

        }

    }
    

    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['CreatePlaceholder']))){

        $NameOfPlaceholder = sanitize_text_field($_POST['PlaceholderName']);
        $DescriptionofPlaceholder = sanitize_text_field($_POST['PlaceholderDescription']);

        $wpdb->insert('wi_post_Placeholder', array(
            'Placeholder' => $NameOfPlaceholder,
            'Description' => $DescriptionofPlaceholder,
        ));
        
        echo "<meta http-equiv='refresh' content='0'>";

    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and ((isset($_POST['MultipleAction3'])) || (isset($_POST['MultipleAction4'])))){

        $Choose = "";

        if((isset($_POST['MultipleAction3']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect3']);

        }else if((isset($_POST['MultipleAction4']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect4']);

        }

        if($Choose == "delete"){
            require_once __DIR__ . '/updatePosts.php';

            foreach (sanitize_text_field($_POST['Placeholder']) as &$value) {

                $value = sanitize_text_field($value);

                if(is_numeric($value) != false){
                
 
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Placeholder WHERE PlaceholderID=".$value.""
                        ) 
                    );
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content_Repeater_Placeholder WHERE PlaceholderID=".$value.""
                        ) 
                    );

                    $ContentPlaceholderList = $wpdb->get_results("SELECT DISTINCT ContentID FROM wi_post_Content_Placeholder WHERE PlaceholderID=".$value.""); 
                    foreach($ContentPlaceholderList as $ContentPlaceholder)
                    {
            
                        wi_post_updatePosts($ContentPlaceholder->ContentID);
            
                    }
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content_Placeholder WHERE PlaceholderID=".$value.""
                        ) 
                    );

                }

            }
            
            echo "<meta http-equiv='refresh' content='0'>";

        }
    }



}


?>