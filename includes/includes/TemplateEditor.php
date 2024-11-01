<?php


function wi_post_TemplateEditor($TemplateID, $Saved){
    global $wpdb;

    $useHTML = false;

    require_once __DIR__ . '/getOption.php';
    if(wi_post_getOption('useHTML') == "1"){
        $useHTML = true;
    }

    $LanguageSave = __('Save','wi_post_language');
    $LanguageDelete = __('Delete','wi_post_language');
    $PageTitle = __('Edit Template','wi_post_language');
    $ErrorNoPlaceholder = __('Please create some Placeholder!','wi_post_language');
    $ErrorNoRepeater = __('Please create some Repeater!','wi_post_language');
    $LanguageSaved = __('Saved','wi_post_language');
    $TemplateTextDescription = __('Please write your Page in HTML!','wi_post_language');
    $LanguageUseHTML = __('use HTML','wi_post_language');
    $LanguageDontUseHTML = __('use Text Editor','wi_post_language');
    $LanguageAreYouSure = __('Are you sure to use the HTML Editor?','wi_post_language');

    $TemplateTitle = "";
    $TemplateContent = "";
    $UseHTMLButtonText = "";

    if($useHTML){
        $UseHTMLButtonText = $LanguageDontUseHTML;
    }else{
        $UseHTMLButtonText = $LanguageUseHTML;
    }


    if($TemplateID == 0){
        //$PageTitle = wi_post_language('Create Template');
        $PageTitle = __('Create Template','wi_post_language');
    }else{
        $TemplateList = $wpdb->get_results("SELECT Template, Titel FROM wi_post_Templates WHERE TemplateID=".$TemplateID.""); 
        $TemplateCount = $wpdb->num_rows;

        if($TemplateCount > 0){
            foreach($TemplateList as $Template){
    
                $TemplateTitle = $Template->Titel;
                $TemplateContent = str_replace(array("<!-- wp:html -->","<!-- /wp:html -->"), "", $Template->Template);
    
            }
        }else{
            echo "<meta http-equiv='refresh' content='0'>";
        }
    }




    


    echo '



    <div class="wrap">
	    <h1 class="wp-heading-inline">
	        '.esc_html($PageTitle).'
        </h1>
        <hr class="wp-header-end">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">

                <script>
                    function input(objButton){
                        var text = objButton.value;
                        document.forms.AddToTemplateArea.TemplateTextArea.value += text;

                        tinymce.activeEditor.execCommand("mceInsertContent", false, text);
                    }    

                    function myFunction() {
                        document.getElementById("myDropdown").classList.toggle("show");
                    }
                </script>

                <form name="AddToTemplateArea" method="POST">
                <input type="hidden" value="'.esc_html($TemplateID).'" name="ID"/>

                    <div id="post-body-content">
                        <div id="sc_name">
                            <input type="text" class="widefat" title="Name of Template" value="'.esc_html($TemplateTitle).'" name="TemplateTitle" id="TemplateTitle" required="" placeholder="Enter template name">
                        </div>
                        <br>';
        
                        if($useHTML){
                            echo'
                            <input type="hidden" value="TRUE" name="useHTML"/>
                            <textarea class="block-editor-plain-text" placeholder="Write something in HTML…" style="overflow-x: hidden; overflow-wrap: break-word; resize: none; min-height: 500px; width:100%;" name="TemplateTextArea" id="TemplateTextArea">'.htmlspecialchars(html_entity_decode($TemplateContent)).'</textarea>
                            <p>'.esc_html($TemplateTextDescription).'</p>';
                        }else{
                            echo '<input type="hidden" value="FALSE" name="useHTML"/>';
                            $initial_data=htmlspecialchars(html_entity_decode($TemplateContent));
                            $settings = array(
                            'quicktags' => array('buttons' => 'em,strong,link',),
                            'text_area_name'=>'TemplateTextArea',//name you want for the textarea
                            'wpautop' => true,
                            'quicktags' => true,
                            'tinymce' => array( 
                                'plugins' => 'textcolor,fullscreen,lists,link'
                            ),
                         
                            );
                            $id = 'TemplateTextArea';//has to be lower case
                            wp_editor(html_entity_decode($initial_data),$id,$settings);
                        }

                    echo'
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div id="submitdiv" class="postbox ">
                                <div class="postbox-header"><h2>Template</h2></div>
                                <div class="inside">
                                    <input type="submit" name="TemplateEditorSave" id="TemplateEditorSave" value="'.esc_html($LanguageSave).'" class="button" style="margin:10px;">';
                                    echo '
                                    <input onclick="useHTMLFunc()" type="button" value="'.esc_html($UseHTMLButtonText).'" id="button" name="button" class="button" style="margin:10px;">
                                    <div id="NewHTMLButton"></div>
                                    <script>
                                        function useHTMLFunc(){
                                            if (confirm("'.esc_js($LanguageAreYouSure).'")) {
                                                // Save it!
                                                document.querySelector(\'#NewHTMLButton\').insertAdjacentHTML(\'afterbegin\', \'<input type="submit" name="HTMLButton" id="HTMLButton" value="" style="display:none">\');
                                                document.getElementById("HTMLButton").click();
                                            } else {
                                                // Do nothing!
                                            }
                                        }
                                    </script>';

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
echo '
                                </div>
                            </div>
                            <div id="submitdiv" class="postbox ">
                                <div class="postbox-header"><h2>Repeater</h2></div>
                                <div class="inside">';


                                
                                    $RepeaterList = $wpdb->get_results("SELECT Repeater FROM wi_post_Repeater ORDER BY Repeater"); 
                                    $RepeaterCount = $wpdb->num_rows;

                                    if($RepeaterCount > 0){
                                        foreach($RepeaterList as $Repeater)
                                        {
    
                                            echo '<input onclick="input(this)" type="button" value="'.esc_html($Repeater->Repeater).'" id="button" name="button" class="button" style="margin:10px;">';
    
                                        }
                                    }else{
                                        echo '<div style="margin:10px;">' . esc_html($ErrorNoRepeater) . '</div>';
                                    }
echo'
                                </div>
                            </div>
                            <div id="submitdiv" class="postbox ">
                                <div class="postbox-header"><h2>Placeholder</h2></div>
                                <div class="inside">';


                                
                                    $PlaceholderList = $wpdb->get_results("SELECT Placeholder FROM wi_post_Placeholder ORDER BY Placeholder"); 
                                    $PlayeholderCount = $wpdb->num_rows;

                                    if($PlayeholderCount > 0){
                                        foreach($PlaceholderList as $Placeholder)
                                        {
    
                                            echo '<input onclick="input(this)" type="button" value="'.esc_html($Placeholder->Placeholder).'" id="button" name="button" class="button" style="margin:10px;">';
    
                                        }
                                    }else{
                                        echo '<div style="margin:10px;">' . esc_html($ErrorNoPlaceholder) . '</div>';
                                    }
echo'
                                </div>
                            </div>';
                            $PlaceholderList2 = $wpdb->get_results("SELECT Link_Placeholder FROM wi_post_Content WHERE Link<>''"); 
                            $PlayeholderCount2 = $wpdb->num_rows;

                            if($PlayeholderCount2 > 0){

                                echo'
                                <div id="submitdiv" class="postbox ">
                                    <div class="postbox-header"><h2>Link Placeholder</h2></div>
                                    <div class="inside">';

                                       foreach($PlaceholderList2 as $Placeholder)
                                        {
    
                                            echo '<input onclick="input(this)" type="button" value="'.esc_html($Placeholder->Link_Placeholder).'" id="button" name="button" class="button" style="margin:10px;">';
    
                                        }

                                echo '
                                    </div>
                                </div>';
                            }
echo'
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
	

    ';




}



?>