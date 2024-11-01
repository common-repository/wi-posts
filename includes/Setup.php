<?php


function wi_post_Setup(){

    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $InsertTemplate = "CREATE TABLE wi_post_Templates (
    TemplateID mediumint(9) NOT NULL AUTO_INCREMENT,
    Template text NOT NULL,
    Titel text NOT NULL,
    author text NOT NULL,
    date TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (TemplateID)
    ) $charset_collate;";

    $InsertPlaceholder = "CREATE TABLE IF NOT EXISTS wi_post_Placeholder (
    PlaceholderID mediumint(9) NOT NULL AUTO_INCREMENT,
    Placeholder text NOT NULL,
    Description text NOT NULL,
    PRIMARY KEY  (PlaceholderID)
    ) $charset_collate;";

    $InsertRepeater = "CREATE TABLE IF NOT EXISTS wi_post_Repeater (
    RepeaterID mediumint(9) NOT NULL AUTO_INCREMENT,
    Repeater text NOT NULL,
    Content text NOT NULL,
    Description text NOT NULL,
    PRIMARY KEY  (RepeaterID)
    ) $charset_collate;";

    $InsertContentPlaceholder = "CREATE TABLE IF NOT EXISTS wi_post_Content_Placeholder (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    ContentID mediumint(9),
    PlaceholderID mediumint(9),
    Input text NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    $InsertContentRepeaterPlaceholder = "CREATE TABLE IF NOT EXISTS wi_post_Content_Repeater_Placeholder (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    ContentID mediumint(9),
    RepeaterID mediumint(9),
    PlaceholderID mediumint(9),
    Number mediumint(9),
    Input text NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

    $InsertContentRepeater = "CREATE TABLE IF NOT EXISTS wi_post_Content_Repeater (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    ContentID mediumint(9),
    RepeaterID mediumint(9),
    count mediumint(9),
    PRIMARY KEY  (id)
    ) $charset_collate;";

    $InsertContent = "CREATE TABLE IF NOT EXISTS wi_post_Content (
    ContentID mediumint(9) NOT NULL AUTO_INCREMENT,
    TemplateID mediumint(9),
    Titel text NOT NULL,
    Link text,
    Status text,
    Link_Placeholder text,
    author text NOT NULL,
    date TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (ContentID)
    ) $charset_collate;";

    $wpdb->query( 
        $wpdb->prepare( 
            "ALTER TABLE {$wpdb->prefix}posts ADD COLUMN IF NOT EXISTS wi_post_post int(25) DEFAULT 0"
        ) 
    );

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $InsertTemplate );
    dbDelta( $InsertPlaceholder );
    dbDelta( $InsertContentPlaceholder );
    dbDelta( $InsertContentRepeaterPlaceholder );
    dbDelta( $InsertContentRepeater );
    dbDelta( $InsertContent );
    dbDelta( $InsertRepeater );

    $SettingList = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name='useHTML'"); 
    $SettingCount = $wpdb->num_rows;

    if($SettingCount == 0){

        $wpdb->query( 
            $wpdb->prepare( 
                "INSERT INTO {$wpdb->prefix}options (autoload, option_name, option_value) VALUES ('no', 'useHTML', '0')"
            ) 
        );
    }


    //Examples???
}

function wi_post_Role(){
    $role = add_role( 'wi_post_role', 'WI-Role', array(
        'read' => true, // True allows that capability
    ) );
}

?>