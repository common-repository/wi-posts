<?php


function wi_post_RepeaterEditor($RepeaterID, $Saved){
    global $wpdb;

    $useHTML = false;

    require_once __DIR__ . '/getOption.php';
    if(wi_post_getOption('useHTML') == "1"){
        $useHTML = true;
    }

    $LanguageSave = __('Save','wi_post_language');
    $LanguageDelete = __('Delete','wi_post_language');
    $PageTitle = __('Edit Repeater','wi_post_language');
    $ErrorNoPlaceholder = __('Please create some Placeholder!','wi_post_language');
    $LanguageSaved = __('Saved','wi_post_language');
    $RepeaterTextDescription = __('Please write your Page in HTML!','wi_post_language');
    $LanguageUseHTML = __('use HTML','wi_post_language');
    $LanguageDontUseHTML = __('use Text Editor','wi_post_language');
    $LanguageAreYouSure = __('Are you sure to use the HTML Editor?','wi_post_language');

    $RepeaterTitle = "";
    $RepeaterDescription = "";
    $RepeaterContent = "";
    $UseHTMLButtonText = "";

    if($useHTML){
        $UseHTMLButtonText = $LanguageDontUseHTML;
    }else{
        $UseHTMLButtonText = $LanguageUseHTML;
    }


    if($RepeaterID == 0){
        //$PageTitle = wi_post_language('Create Repeater');
        $PageTitle = __('Create Repeater','wi_post_language');
    }else{
        $RepeaterList = $wpdb->get_results("SELECT Repeater, Description, Content FROM wi_post_Repeater WHERE RepeaterID=".$RepeaterID.""); 
        $RepeaterCount = $wpdb->num_rows;

        if($RepeaterCount > 0){
            foreach($RepeaterList as $Repeater){
    
                $RepeaterTitle = $Repeater->Repeater;
                $RepeaterDescription = $Repeater->Description;
                $RepeaterContent = html_entity_decode($Repeater->Content);
    
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
                        document.forms.AddToRepeaterArea.RepeaterTextArea.value += text;

                        tinymce.activeEditor.execCommand("mceInsertContent", false, text);
                    }    

                    function myFunction() {
                        document.getElementById("myDropdown").classList.toggle("show");
                    }
                </script>

                <form name="AddToRepeaterArea" method="POST">
                <input type="hidden" value="'.esc_html($RepeaterID).'" name="ID"/>

                    <div id="post-body-content">
                        <div id="sc_name">
                            <input type="text" class="widefat" title="Repeater" value="'.esc_html($RepeaterTitle).'" name="RepeaterTitle" id="RepeaterTitle" required="" placeholder="Enter Repeater">
                        </div>
                        <br>
                        
                        <div id="sc_name">
                            <input type="text" class="widefat" title="Description of Repeater" value="'.esc_html($RepeaterDescription).'" name="RepeaterDescription" id="RepeaterDescription" placeholder="Enter Repeater Description">
                        </div>
                        <br>';
        
                        if($useHTML){
                            echo'
                            <input type="hidden" value="TRUE" name="useHTML"/>
                            <textarea class="block-editor-plain-text" placeholder="Write something in HTML…" style="overflow-x: hidden; overflow-wrap: break-word; resize: none; min-height: 500px; width:100%;" name="RepeaterTextArea" id="RepeaterTextArea">'.htmlspecialchars($RepeaterContent).'</textarea>
                            <p>'.esc_html($RepeaterTextDescription).'</p>';
                        }else{
                            echo '<input type="hidden" value="FALSE" name="useHTML"/>';
                            $initial_data=htmlspecialchars($RepeaterContent);
                            $settings = array(
                            'quicktags' => array('buttons' => 'em,strong,link',),
                            'text_area_name'=>'RepeaterTextArea',//name you want for the textarea
                            'wpautop' => true,
                            'quicktags' => true,
                            'tinymce' => array( 
                                'plugins' => 'textcolor,fullscreen,lists,link'
                            ),
                         
                            );
                            $id = 'RepeaterTextArea';//has to be lower case
                            wp_editor(html_entity_decode($initial_data),$id,$settings);
                        }

                    echo'
                    </div>
                    <div id="postbox-container-1" class="postbox-container">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div id="submitdiv" class="postbox ">
                                <div class="postbox-header"><h2>Repeater</h2></div>
                                <div class="inside">
                                    <input type="submit" name="RepeaterEditorSave" id="RepeaterEditorSave" value="'.esc_html($LanguageSave).'" class="button" style="margin:10px;">';
                                    echo '
                                    <input onclick="useHTMLFunc()" type="button" value="'.esc_html($UseHTMLButtonText).'" id="button" name="button" class="button" style="margin:10px;">
                                    <div id="NewHTMLButton"></div>
                                    <script>
                                        function useHTMLFunc(){
                                            if (confirm("'.esc_html($LanguageAreYouSure).'")) {
                                                // Save it!
                                                document.querySelector(\'#NewHTMLButton\').insertAdjacentHTML(\'afterbegin\', \'<input type="submit" name="HTMLButton2" id="HTMLButton2" value="" style="display:none">\');
                                                document.getElementById("HTMLButton2").click();
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