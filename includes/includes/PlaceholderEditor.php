<?php


function wi_post_PlaceholderEditor($PlaceholderID, $Saved){
    global $wpdb;

    
    $LanguageEditPlaceholder = __('Edit Placeholder','wi_post_language');
    $LanguageSave = __('Save','wi_post_language');
    $LanguageSaved = __('Saved','wi_post_language');
    $LanguageDescription = __('Description','wi_post_language');
    $LanguageAddNewPlaceholderText = __('You can place the Placeholder into the Template. It will be replaced with your Content.','wi_post_language');
    $LanguageDescriptionText = __('The Description wont be shown anywhere. Its only for you!','wi_post_language');


    $PlaceholderList = $wpdb->get_results("SELECT Placeholder, Description FROM wi_post_Placeholder WHERE PlaceholderID=".$PlaceholderID.""); 
    $PlaceholderCount = $wpdb->num_rows;

    $PlaceholderName = "";
    $PlaceholderDescription = "";

    if($PlaceholderCount > 0){
        foreach($PlaceholderList as $Placeholder){

            $PlaceholderName = $Placeholder->Placeholder;
            $PlaceholderDescription = $Placeholder->Description;

        }
    }else{
        echo "<meta http-equiv='refresh' content='0'>";
    }

    echo'
    
    <div class="wrap">
        <h1>'.esc_html($LanguageEditPlaceholder).'</h1>
        <form method="POST">
            <input type="hidden" value='.esc_html($PlaceholderID).' name="ID"/>
            <table class="form-table" role="presentation" style="width:800px;min-width:40%">
                <tbody>
                    <tr class="form-field form-required term-name-wrap">
                        <th scope="row"><label for="name">Placeholder</label></th>
                        <td><input name="PlaceholderName" id="PlaceholderName" type="text" value="'.htmlspecialchars($PlaceholderName).'" size="40" aria-required="true">
                        <p class="description">'.esc_html($LanguageAddNewPlaceholderText).'</p></td>
                    </tr>
                    <tr class="form-field term-description-wrap">
                        <th scope="row"><label for="PlaceholderDescription">'.esc_html($LanguageDescription).'</label></th>
                        <td><textarea name="PlaceholderDescription" id="PlaceholderDescription" rows="5" cols="50" class="large-text">'.htmlspecialchars($PlaceholderDescription).'</textarea>
                        <p class="description">'.esc_html($LanguageDescriptionText).'</p></td>
                    </tr>
                </tbody>
            </table>
            <div class="edit-tag-actions">
                <input type="submit" class="button button-primary" id="PlaceholderEditorSave" name="PlaceholderEditorSave" value="'.esc_html($LanguageSave).'">';

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
        </form>
    </div>
    
    ';



}


?>