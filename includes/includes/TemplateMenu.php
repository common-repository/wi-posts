<?php




function wi_post_TemplateFunc(){

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
    $LanguageTitle = __('Title','wi_post_language');
    $LanguageAuthor = __('Author','wi_post_language');
    $LanguageDate = __('Date','wi_post_language');
    $LanguageCreateAt = __('Create at','wi_post_language');
    $LanguageSubmit = __('Submit','wi_post_language');
    $LanguageEdit = __('Edit','wi_post_language');
    $LanguageNotFound = __('Not Found','wi_post_language');


    $TemplateCount = 0;


    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['NewTemplate']))){
        require_once __DIR__ . '/TemplateEditor.php';

        wi_post_TemplateEditor(0, false);

    }else if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['EditTemplate']))){

        $ID = sanitize_text_field($_POST['ID']);

        if(is_numeric($ID) != false){

            require_once __DIR__ . '/TemplateEditor.php';

            wi_post_TemplateEditor($ID, false);

        }


    }else if($_SERVER['REQUEST_METHOD'] == "POST" and ((isset($_POST['TemplateEditorSave'])) || (isset($_POST['HTMLButton'])))){

        $ID = sanitize_text_field($_POST['ID']);

        if(is_numeric($ID) != false){


            $TitleOfTemplate = sanitize_text_field($_POST['TemplateTitle']);
            $TextOfTemplate = "";
            $UseHTML = sanitize_text_field($_POST['useHTML']);

            if($UseHTML == "TRUE"){
                $TextOfTemplate = sanitize_text_field( htmlentities(htmlspecialchars_decode($_POST['TemplateTextArea'])));
            }else{
                $TextOfTemplate = sanitize_text_field( htmlentities(htmlspecialchars_decode(preg_replace("/\r\n|\r|\n/",'<br>',$_POST['TemplateTextArea']))));
            }

            if(strpos($TextOfTemplate, '<!-- wp:html -->') === false){
                $TextOfTemplate = '<!-- wp:html -->' . $TextOfTemplate . "<!-- /wp:html -->";
            }

            if($ID == "0"){
                $user = wp_get_current_user();
                $Author = $user->nickname;
        
                $wpdb->insert('wi_post_Templates', array(
                    'Titel' => $TitleOfTemplate,
                    'Template' => $TextOfTemplate,
                    'author' => $Author,
                ));

                $ID = $wpdb->insert_id;
            }




            if((isset($_POST['HTMLButton']))){
                require_once __DIR__ . '/getOption.php';
                if($UseHTML == "TRUE"){
                    wi_post_setOption('useHTML', "0");
                }else{
                    wi_post_setOption('useHTML', "1");
                }
            }
            
            $wpdb->query($wpdb->prepare("UPDATE wi_post_Templates SET Titel='".$TitleOfTemplate."',Template='".$TextOfTemplate."' WHERE TemplateID=".$ID.""));


            require_once __DIR__ . '/updatePosts.php';

            $WikiList = $wpdb->get_results("SELECT ContentID FROM wi_post_Content WHERE TemplateID=".$ID.""); 
            foreach($WikiList as $Wiki)
            {

                wi_post_updatePosts($Wiki->ContentID);

            }

            require_once __DIR__ . '/TemplateEditor.php';

            wi_post_TemplateEditor($ID, true);

        }

    }else{



    echo '
    <form method="POST">
        <div class="wrap">
            <h1 class="wp-heading-inline">
            Templates</h1>
        
            <input class="page-title-action" type="submit" name="NewTemplate" value="'.esc_html($LanguageCreate).'">
        <hr class="wp-header-end">
    </form>';

    echo '<br>';

    echo '<form method="POST">
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="MultipleSelect1" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
            <select name="MultipleSelect1" id="MultipleSelect1">
                <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                <option value="delete">'.esc_html($LanguageDelete).'</option>
            </select>
            <input type="submit" id="MultipleAction1" name="MultipleAction1" class="button action" value="'.esc_html($LanguageSubmit).'">
        </div>
        <br class="clear">
    </div>';



    echo '<h2 class="screen-reader-text">Seitenliste</h2><table class="wp-list-table widefat fixed striped table-view-list pages">
    <thead>
        <tr>
            <td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input id="cb-select-all-1" type="checkbox"></td>
            <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">&nbsp;&nbsp;<span>'.esc_html($LanguageTitle).'</span></th>
            <th scope="col" id="author" class="manage-column column-author">'.esc_html($LanguageAuthor).'</th>
            <th scope="col" id="author" class="manage-column column-author">'.esc_html($LanguageDate).'</th>
        </tr>
    </thead>

    <tbody id="the-list">';
            

     
    //Templates
    $TemplateList = $wpdb->get_results("SELECT * FROM wi_post_Templates ORDER BY Titel"); 
    $TemplateCount = $wpdb->num_rows;

    if($TemplateCount > 0){
        foreach($TemplateList as $Template)
        {
    
            $TemplateCreationDate = $Template->date;
            $TemplateAuthor = $Template->author;
            $TemplateID = $Template->TemplateID;
            $TemplateTitle = $Template->Titel;
    
            echo'
            <tr class="iedit author-self level-0 post-1137019 type-page status-publish hentry">
    
                <th scope="row" class="check-column">
                    <input id="TemplateCheckbox" type="checkbox" name="Template[]" value="'.esc_html($TemplateID).'">
                </th>
    
                <form method="POST">
                    <input type="hidden" value='.esc_html($TemplateID).' name="ID"/>
                
                    <td class="title column-title has-row-actions column-primary page-title" data-colname="Titel">
                        <div class="locked-info">
                            <span class="locked-avatar"></span>
                            <span class="locked-text"></span>
                        </div>
                        <strong>
                            <input class="row-title" type="submit" name="EditTemplate" value="'.esc_html($TemplateTitle).'" aria-label="„'.esc_html($TemplateTitle).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'">
                        </strong>
                        <div class="row-actions">
                            <span class="edit"><input class="row-title" type="submit" name="EditTemplate" value="'.esc_html($LanguageEdit).'" aria-label="„'.esc_html($TemplateTitle).'“ ('.esc_html($LanguageEdit).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"> | </span>
                            <span class="trash"><input class="row-title" type="submit" name="DeleteTemplate" value="'.esc_html($LanguageDelete).'" aria-label="„'.esc_html($TemplateTitle).'“ ('.esc_html($LanguageDelete).')", style="background:none; border:none; color:#30ceff; cursor:pointer" onMouseOver="this.style.color=\'#3858e9\'" onMouseOut="this.style.color=\'#30ceff\'"></span>
                        </div>
                    </td>
                        
                    <td class="author column-author" data-colname="Autor">'.esc_html($TemplateAuthor).'</td>
                    
                    <td class="date column-date" data-colname="Datum">'.esc_html($LanguageCreateAt).'<br>'.esc_html($TemplateCreationDate).'</td>
    
                </form>
            
            </tr>';
        }
    }else{
        echo'<tr class="no-items"><td class="colspanchange" colspan="4">'.esc_html($LanguageNotFound).'</td></tr>';
    }




    echo '</tbody>

    <tfoot>
        <tr>
            <td class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-2">Alle auswählen</label><input id="cb-select-all-2" type="checkbox"></td>
            <th scope="col" class="manage-column column-author"><span>'.esc_html($LanguageTitle).'</span></th>
            <th scope="col" class="manage-column column-author">'.esc_html($LanguageAuthor).'</th>
            <th scope="col" class="manage-column column-author">'.esc_html($LanguageDate).'</th>	
        </tr>
    </tfoot>

    </table>';

    echo '
    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <label for="MultipleSelect2" class="screen-reader-text">'.esc_html($LanguageMultiActionSelect).'</label>
            <select name="MultipleSelect2" id="MultipleSelect2">
                <option value="-1">'.esc_html($LanguageMultiAction).'</option>
                <option value="delete">'.esc_html($LanguageDelete).'</option>
            </select>
            <input type="submit" id="MultipleAction2" name="MultipleAction2" class="button action" value="'.esc_html($LanguageSubmit).'">
        </div>
        <div class="alignleft actions"></div>
        <div class="tablenav-pages one-page">
            <span class="displaying-num">'.esc_html($TemplateCount).' Templates</span>
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

    
    if($_SERVER['REQUEST_METHOD'] == "POST" and (isset($_POST['DeleteTemplate']))){

        $value = sanitize_text_field($_POST['ID']);

        if(is_numeric($value) != false){

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Templates WHERE TemplateID=".$value.""
                ) 
            );

            $WikiList = $wpdb->get_results("SELECT ContentID FROM wi_post_Content WHERE TemplateID=".$value.""); 
            foreach($WikiList as $Wiki)
            {
                $wpdb->query( 
                    $wpdb->prepare( 
                        "DELETE FROM {$wpdb->prefix}posts WHERE wi_post_post=".$Wiki->ContentID.""
                    ) 
                );
                $wpdb->query( 
                    $wpdb->prepare( 
                        "DELETE FROM wi_post_Content_Placeholder WHERE ContentID=".$Wiki->ContentID.""
                    ) 
                );
                $wpdb->query( 
                    $wpdb->prepare( 
                        "DELETE FROM wi_post_Content_Repeater WHERE ContentID=".$Wiki->ContentID.""
                    ) 
                );
                $wpdb->query( 
                    $wpdb->prepare( 
                        "DELETE FROM wi_post_Content_Repeater_Placeholder WHERE ContentID=".$Wiki->ContentID.""
                    ) 
                );
            }

            $wpdb->query( 
                $wpdb->prepare( 
                    "DELETE FROM wi_post_Content WHERE TemplateID=".$value.""
                ) 
            );

            echo "<meta http-equiv='refresh' content='0'>";

        }

    }
    
    if($_SERVER['REQUEST_METHOD'] == "POST" and ((isset($_POST['MultipleAction2'])) || (isset($_POST['MultipleAction1'])))){

        $Choose = "";

        if((isset($_POST['MultipleAction2']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect2']);

        }else if((isset($_POST['MultipleAction1']))){

            $Choose = sanitize_text_field($_POST['MultipleSelect1']);

        }

        if($Choose == "delete"){

            foreach ($_POST['Template'] as &$value) {

                $value = sanitize_text_field($value);

                if(is_numeric($value) != false){
 
                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Templates WHERE TemplateID=".$value.""
                        ) 
                    );

                    $WikiList = $wpdb->get_results("SELECT ContentID FROM wi_post_Content WHERE TemplateID=".$value.""); 
                    foreach($WikiList as $Wiki)
                    {
                        $wpdb->query( 
                            $wpdb->prepare( 
                                "DELETE FROM {$wpdb->prefix}posts WHERE wi_post_post=".$Wiki->ContentID.""
                            ) 
                        );
                        $wpdb->query( 
                            $wpdb->prepare( 
                                "DELETE FROM wi_post_Content_Placeholder WHERE ContentID=".$Wiki->ContentID.""
                            ) 
                        );
                        $wpdb->query( 
                            $wpdb->prepare( 
                                "DELETE FROM wi_post_Content_Repeater WHERE ContentID=".$Wiki->ContentID.""
                            ) 
                        );
                        $wpdb->query( 
                            $wpdb->prepare( 
                                "DELETE FROM wi_post_Content_Repeater_Placeholder WHERE ContentID=".$Wiki->ContentID.""
                            ) 
                        );
                    }

                    $wpdb->query( 
                        $wpdb->prepare( 
                            "DELETE FROM wi_post_Content WHERE TemplateID=".$value.""
                        ) 
                    );
                }
            }
            
            echo "<meta http-equiv='refresh' content='0'>";

        }
    }
}




?>