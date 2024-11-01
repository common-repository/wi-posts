<?php


function wi_post_PostEditor($ContentID, $Saved){
    global $wpdb;

    $LanguageEdit = __('Edit','wi_post_language');
    $LanguageSave = __('Save','wi_post_language');
    $LanguageSaved = __('Saved','wi_post_language');
    $LanguageTextDescription = __('The Title of your Page','wi_post_language');
    $TemplateDescription = __('Choose a Template','wi_post_language');
    $LanguageTitle = __('Title','wi_post_language');
    $LanguageStatus = __('Status','wi_post_language');
    $LanguageStatusDescription = __('How should be the Page published?','wi_post_language');
    $LanguageDraft = __('Draft','wi_post_language');
    $LanguagePublish = __('Publish','wi_post_language');
    $LanguagePrivate = __('Private','wi_post_language');
    $LanguagePreview = __('Preview','wi_post_language');
    $LanguageRepeaterDescription = __('Save to see Placeholder','wi_post_language');

    

    $PostTitel1 = "";
    $PostTitel2 = "";
    $TemplateID = "";
    $Status = "";
    $Link = "";
    $TemplateEditorButton = 'PostEditorSave';

    if($ContentID > 0){
        $PostList = $wpdb->get_results("SELECT TemplateID, Titel, Link, Status FROM wi_post_Content WHERE ContentID=".$ContentID.""); 
        $PostCount = $wpdb->num_rows;
        if($PostCount > 0){
            foreach($PostList as $Post){
    
                $PostTitel1 = $Post->Titel . ' ' . $LanguageEdit;
                $PostTitel2 = $Post->Titel;
                $TemplateID = $Post->TemplateID;
                $Status = $Post->Status;
                $Link = $Post->Link;
    
            }
        }else{
            echo "<meta http-equiv='refresh' content='0'>";
        }
    }else{
        $TemplateEditorButton = 'PostEditorCreate';
        $PostTitel = "Neu";
    }

    echo'
    
    <div class="wrap">
                <form method="POST">
                    <div id="post-body-content" style="width:800px; min-width:40%">
                        <h1>'.esc_html($PostTitel1).'</h1>
                        <input type="hidden" value='.esc_html($ContentID).' name="ID"/>
                        <table class="form-table" role="presentation" style="width:800px;min-width:40%">
                            <tbody>';

                                echo'
                                <tr class="form-field form-required term-name-wrap">
                                    <th scope="row"><label for="name"><strong>'.esc_html($LanguageTitle).'</strong></label></th>
                                    <td><input name="PostTitel" id="PostTitel" type="text" value="'.esc_html($PostTitel2).'" size="40" aria-required="true">
                                    <p class="description">'.esc_html($LanguageTextDescription).'</p></td>
                                </tr>';

                                if($ContentID == 0){

                                    echo'
                                    <tr class="form-field form-required term-name-wrap">
                                    <th scope="row"><label for="Template"><strong>Template</strong></label></th>
                                        <td>
                                            <select name="Template" id="Template" required>';

                                    //Templates
                                    $TemplateList = $wpdb->get_results("SELECT * FROM wi_post_Templates ORDER BY Titel"); 
                                    $TemplateCount = $wpdb->num_rows;

                                    if($TemplateCount > 0){
                                        foreach($TemplateList as $Template)
                                        {
                                            echo '
                                                <option value="'.esc_html($Template->TemplateID).'">'.esc_html($Template->Titel).'</option>
                                            ';
                                        }
                                    }else{
                                        echo "<meta http-equiv='refresh' content='0'>";
                                    }

                                    echo'
                                        </select> 
                                    <p class="description">'.esc_html($TemplateDescription).'</p></td>
                                    </tr>';

                                }else{

                                    echo'
                                    <tr class="form-field form-required term-name-wrap">
                                    <th scope="row"><label for="Status"><strong>'.esc_html($LanguageStatus).'</strong></label></th>
                                        <td>
                                            <select name="Status" id="Status">';
    
                                                //publish, private, draft
                                                if($Status == "publish"){
                                                    echo '<option value="draft">'.esc_html($LanguageDraft).'</option>';
                                                    echo '<option value="private">'.esc_html($LanguagePrivate).'</option>';
                                                    echo '<option value="publish" selected>'.esc_html($LanguagePublish).'</option>';
                                                }else if($Status == "private"){
                                                    echo '<option value="draft">'.esc_html($LanguageDraft).'</option>';
                                                    echo '<option value="private" selected>'.esc_html($LanguagePrivate).'</option>';
                                                    echo '<option value="publish">'.esc_html($LanguagePublish).'</option>';
                                                }else{
                                                    echo '<option value="draft" selected>'.esc_html($LanguageDraft).'</option>';
                                                    echo '<option value="private">'.esc_html($LanguagePrivate).'</option>';
                                                    echo '<option value="publish">'.esc_html($LanguagePublish).'</option>';
                                                }
    
                                            echo '
                                            </select> 
                                            <p class="description">'.esc_html($LanguageStatusDescription).'</p>
                                        </td>
                                    </tr>';


                                    //Templates
                                    $TemplateList = $wpdb->get_results("SELECT Template FROM wi_post_Templates WHERE TemplateID=".$TemplateID.""); 
                                    $TemplateCount = $wpdb->num_rows;

                                    if($TemplateCount > 0){
                                        foreach($TemplateList as $Template)
                                        {
                                            $RepeaterList = $wpdb->get_results("SELECT Repeater, RepeaterID, Description, Content FROM wi_post_Repeater ORDER BY Repeater"); 
        
                                            foreach($RepeaterList as $Repeater)
                                            {
                                                if(strpos(html_entity_decode($Template->Template), $Repeater->Repeater) !== false){
                                                    $ContentRepeaterList = $wpdb->get_results("SELECT id, Count FROM wi_post_Content_Repeater WHERE ContentID=".$ContentID." AND RepeaterID=".$Repeater->RepeaterID.""); 
                                                    $ContentRepeaterCount = $wpdb->num_rows;

                                                    if($ContentRepeaterCount > 0){
                                                        foreach($ContentRepeaterList as $ContentRepeater)
                                                        {
                                                            echo'
                                                            <tr class="form-field form-required term-name-wrap">
                                                                <th scope="row"><label for="name"><strong>'.htmlspecialchars($Repeater->Repeater).'</strong></label></th>
                                                                <td><input name="Repeater[]" id="Repeater[]" type="number" value="'.esc_html($ContentRepeater->Count).'" size="40" aria-required="true">
                                                                <input type="hidden" value='.esc_html($ContentRepeater->id).' name="RepeaterContentID[]"/>
                                                                <p class="description">'.esc_html($LanguageRepeaterDescription).' | '.esc_html($Repeater->Description).'</p></td>
                                                            </tr>';
        
                                                            
                                                            for ($i = 0; $i < $ContentRepeater->Count; $i++){

                                                                echo '<input type="hidden" value='.esc_html($i).' name="CountList'.esc_html($ContentRepeater->id).'[]"/>';

                                                                $ReplaceRepeater = html_entity_decode($Repeater->Content);

                                                                $PlaceholderList2 = $wpdb->get_results("SELECT Placeholder, PlaceholderID, Description FROM wi_post_Placeholder ORDER BY Placeholder"); 
                                                    
                                                                foreach($PlaceholderList2 as $Placeholder)
                                                                {
                                                                    if(strpos(html_entity_decode($Repeater->Content), $Placeholder->Placeholder) !== false){

                                                                        echo '<input type="hidden" value='.esc_html($Placeholder->PlaceholderID).' name="PlaceholderList'.esc_html($ContentRepeater->id).esc_html($i).'[]"/>';
                    
                                                                        $ContentPlaceholderList2 = $wpdb->get_results("SELECT Input,id FROM wi_post_Content_Repeater_Placeholder WHERE ContentID=".$ContentID." AND PlaceholderID=".$Placeholder->PlaceholderID." AND Number=".$i." AND RepeaterID=".$Repeater->RepeaterID.""); 
                                                                        $ContentPlaceholderCount2 = $wpdb->num_rows;
                                                                        if($ContentPlaceholderCount2 > 0){
                                                                            foreach($ContentPlaceholderList2 as $ContentPlaceholder)
                                                                            {
                                                                                echo'
                                                                                <tr class="form-field form-required term-name-wrap">
                                                                                    <th scope="row"><label for="name"><strong>'.htmlspecialchars($Placeholder->Placeholder).' ('.esc_html(($i + 1)).')</strong></label></th>
                                                                                    <td><input name="Repeater'.esc_html($ContentRepeater->id).esc_html($i).'['.esc_html($Placeholder->PlaceholderID).']" id="Repeater'.esc_html($ContentRepeater->id).esc_html($i).'['.esc_html($Placeholder->PlaceholderID).']" type="text" value="'.esc_html(html_entity_decode($ContentPlaceholder->Input)).'" size="40" aria-required="true">
                                                                                    <input type="hidden" value='.esc_html($ContentPlaceholder->id).' name="ContentRepeaterPlaceholder'.esc_html($ContentRepeater->id).esc_html($i).'['.esc_html($Placeholder->PlaceholderID).']"/>
                                                                                    <p class="description">'.esc_html($Placeholder->Description).'</p></td>
                                                                                </tr>';
                                                                            }
                                                                        }else{

                                                                            $wpdb->insert('wi_post_Content_Repeater_Placeholder', array(
                                                                                'ContentID' => $ContentID,
                                                                                'PlaceholderID' => $Placeholder->PlaceholderID,
                                                                                'RepeaterID' => $Repeater->RepeaterID,
                                                                                'Number' => $i,
                                                                                'Input' => "",
                                                                            ));
                                                                            $lastid = $wpdb->insert_id;

                                                                            echo'
                                                                            <tr class="form-field form-required term-name-wrap">
                                                                                <th scope="row"><label for="name"><strong>'.htmlspecialchars($Placeholder->Placeholder).' ('.esc_html(($i + 1)).')</strong></label></th>
                                                                                <td><input name="Repeater'.esc_html($ContentRepeater->id).esc_html($i).'['.esc_html($Placeholder->PlaceholderID).']" id="Repeater'.esc_html($ContentRepeater->id).esc_html($i).'['.esc_html($Placeholder->PlaceholderID).']" type="text" value="" size="40" aria-required="true">
                                                                                <input type="hidden" value='.esc_html($lastid).' name="ContentRepeaterPlaceholder'.esc_html($ContentRepeater->id).esc_html($i).'['.esc_html($Placeholder->PlaceholderID).']"/>
                                                                                <p class="description">'.esc_html($Placeholder->Description).'</p></td>
                                                                            </tr>';
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }else{

                                                        $wpdb->insert('wi_post_Content_Repeater', array(
                                                            'ContentID' => $ContentID,
                                                            'RepeaterID' => $Repeater->RepeaterID,
                                                            'count' => 0,
                                                        ));
                                                        $lastid = $wpdb->insert_id;

                                                        echo'
                                                        <tr class="form-field form-required term-name-wrap">
                                                            <th scope="row"><label for="name"><strong>'.htmlspecialchars($Repeater->Repeater).'</strong></label></th>
                                                            <td><input name="Repeater[]" id="Repeater[]" type="number" value="0" size="40" aria-required="true">
                                                            <input type="hidden" value='.esc_html($lastid).' name="RepeaterContentID[]"/>
                                                            <p class="description">'.esc_html($Repeater->Description).'</p></td>
                                                        </tr>';
                                                    }

                                                }
                                            }

                                            $PlaceholderList = $wpdb->get_results("SELECT Placeholder, PlaceholderID, Description FROM wi_post_Placeholder ORDER BY Placeholder"); 
        
                                            foreach($PlaceholderList as $Placeholder)
                                            {
                                                if(strpos(html_entity_decode($Template->Template), $Placeholder->Placeholder) !== false){
                                                    $ContentPlaceholderList = $wpdb->get_results("SELECT id, Input FROM wi_post_Content_Placeholder WHERE ContentID=".$ContentID." AND PlaceholderID=".$Placeholder->PlaceholderID.""); 
                                                    $ContentPlaceholderCount = $wpdb->num_rows;

                                                    if($ContentPlaceholderCount > 0){
                                                        foreach($ContentPlaceholderList as $ContentPlaceholder)
                                                        {
                                                            echo'
                                                            <tr class="form-field form-required term-name-wrap">
                                                                <th scope="row"><label for="name"><strong>'.htmlspecialchars($Placeholder->Placeholder).'</strong></label></th>
                                                                <td><input name="PlaceholderContent[]" id="PlaceholderContent[]" type="text" value="'.htmlspecialchars(html_entity_decode($ContentPlaceholder->Input)).'" size="40" aria-required="true">
                                                                <input type="hidden" value='.esc_html($ContentPlaceholder->id).' name="PlaceholderContentID[]"/>
                                                                <p class="description">'.esc_html($Placeholder->Description).'</p></td>
                                                            </tr>';
                                                        }
                                                    }else{

                                                        $wpdb->insert('wi_post_Content_Placeholder', array(
                                                            'ContentID' => $ContentID,
                                                            'PlaceholderID' => $Placeholder->PlaceholderID,
                                                            'Input' => "",
                                                        ));

                                                        $lastid = $wpdb->insert_id;

                                                        echo'
                                                            <tr class="form-field form-required term-name-wrap">
                                                                <th scope="row"><label for="name"><strong>'.htmlspecialchars($Placeholder->Placeholder).'</strong></label></th>
                                                                <td><input name="PlaceholderContent[]" id="PlaceholderContent[]" type="text" value="" size="40" aria-required="true">
                                                                <input type="hidden" value='.esc_html($lastid).' name="PlaceholderContentID[]"/>
                                                                <p class="description">'.esc_html($Placeholder->Description).'</p></td>
                                                            </tr>';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

    echo'
                            </tbody>
                        </table>
                        <div class="edit-tag-actions">';

                        if($Link != ""){
                            echo '<a href="'.esc_url($Link).'" target="_blank">'.esc_html($LanguagePreview).'</a><br><br>';
                        }
    echo'
                            <input type="submit" class="button button-primary" id="'.esc_html($TemplateEditorButton).'" name="'.esc_html($TemplateEditorButton).'" value="'.esc_html($LanguageSave).'">';

                            if($Saved){
                                echo '<div id="SavedDiv" style="margin:10px;">' . esc_html($LanguageSaved) . '</div>';

                                echo '<script>
                                        window.onload = function() {
                                            setTimeout(function(){
                                            document.getElementById("SavedDiv").classList.add("hidden");
                                            },2000)
                                        }
                                    </script>';
                            }
                            


    echo'
                        </div>
                    </div>
                </form>
    </div>
    
    ';



}


?>