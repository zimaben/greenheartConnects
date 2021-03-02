<?php
namespace obp_emailer\options;

Options::get_instance();

class Options extends \obp_emailer\Emailer {
    private static $instance = null;
    private $options;
    public static function get_instance(){
        if (self::$instance == null){
            self::$instance = new self;
        }
        return self::$instance;
    }
    
    /* CONTRUCTOR */
    public function __construct() {
        \add_action( 'admin_menu', array( get_class(), 'add_options_page' ) );
        \add_action( 'admin_init', array( get_class(), 'register_theme_settings' ) );
        \add_action( 'send_email_from_options', array( get_class(), 'send_the_option_email'));
    }

    public static function send_email_from_options(){
        \do_action('send_email_from_options');
    }
    
    public static function add_options_page() {
        //create settings menu under Settings
        \add_submenu_page( 
            'options-general.php', //parent
            self::parentName . ' Email Settings', //Page title
            self::parentName . ' Email Settings', //Menu title
            'manage_options', 
            strtolower(self::parentName) .'-options', 
            array(get_class(), 'theme_settings_page'), 
            99
        );
    }
    public static function register_theme_settings() {
        
        
  

        \add_settings_section(
            strtolower(self::parentName) .'-options',
            ucfirst(strtolower(self::parentName)) . ' Email Options',
            array(get_class(), 'print_section_info'),
            strtolower(self::parentName) .'-options'
        );
        
        \register_setting( strtolower(self::parentName).'-options', 'update_avatar_sendto');
        \register_setting( strtolower(self::parentName).'-options', 'update_avatar_sendfrom');
        \register_setting( strtolower(self::parentName).'-options', 'update_avatar_sendfromname');
        \register_setting( strtolower(self::parentName).'-options', 'update_avatar_message');
        \register_setting( strtolower(self::parentName).'-options', 'update_avatar_subject');

        $optionclass= 'formrow';
        $optionclasshidden = 'formrow theme_admin_hidden';
        
        \add_settings_field( 'update_avatar_sendto', 'Send Email to this address', array(get_class(), 'do_input_update_avatar_sendto'), strtolower(self::parentName) .'-options', strtolower(self::parentName) .'-options', array('class' => $optionclass, 'label_for' => 'update_avatar_sendto') );
        \add_settings_field( 'update_avatar_sendfrom', 'Send Email from this address', array(get_class(), 'do_input_update_avatar_sendfrom'), strtolower(self::parentName) .'-options', strtolower(self::parentName) .'-options', array('class' => $optionclass, 'label_for' => 'update_avatar_sendfrom') );
        \add_settings_field( 'update_avatar_sendfromname', 'Name on the From line:', array(get_class(), 'do_input_update_avatar_sendfromname'), strtolower(self::parentName) .'-options', strtolower(self::parentName) .'-options', array('class' => $optionclass, 'label_for' => 'update_avatar_sendfromname') );    
        \add_settings_field( 'update_avatar_message', 'Message:', array(get_class(), 'do_editor_update_avatar_message'), strtolower(self::parentName) .'-options', strtolower(self::parentName) .'-options', array('class' => $optionclass, 'label_for' => 'update_avatar_message') );
        \add_settings_field( 'update_avatar_subject', 'Subject:', array(get_class(), 'do_input_update_avatar_subject'), strtolower(self::parentName) .'-options', strtolower(self::parentName) .'-options', array('class' => $optionclass, 'label_for' => 'update_avatar_subject') );
        
    }

    public static function print_section_info(){
        echo '<p>'.ucfirst(strtolower(self::parentName)).' - '.self::description.'</p>';
    }
    public function do_input_update_avatar_sendto(){
        //INPUT
        $fieldname = str_replace("do_input_", "", __FUNCTION__);
        printf(
            '<input type="text" id="'.$fieldname.'" name="'.$fieldname.'" value="%s" readonly />',
           # ( \get_option($fieldname) ) ? esc_attr( \get_option($fieldname) ) : ''
            'Logged-In User'
        );
    }
    public function do_input_update_avatar_sendfrom(){
        //INPUT
        $fieldname = str_replace("do_input_", "", __FUNCTION__);
        printf(
            '<input type="text" id="'.$fieldname.'" name="'.$fieldname.'" value="%s" />',
            ( \get_option($fieldname) ) ? esc_attr( \get_option($fieldname) ) : ''
        );
    }
    public function do_input_update_avatar_sendfromname(){
        //INPUT
        $fieldname = str_replace("do_input_", "", __FUNCTION__);
        printf(
            '<input type="text" id="'.$fieldname.'" name="'.$fieldname.'" value="%s" />',
            ( \get_option($fieldname) ) ? esc_attr( \get_option($fieldname) ) : ''
        );
    }
    public function do_input_update_avatar_subject(){
        //INPUT
        $fieldname = str_replace("do_input_", "", __FUNCTION__);
        printf(
            '<input type="text" id="'.$fieldname.'" name="'.$fieldname.'" value="%s" />',
            ( \get_option($fieldname) ) ? esc_attr( \get_option($fieldname) ) : ''
        );
    }
    public function do_editor_update_avatar_message(){
        $fieldname = str_replace("do_editor_", "", __FUNCTION__);
        $content = \get_option($fieldname);
        wp_editor( $content, $fieldname, $settings = array('textarea_rows'=> '10') );

    }
    //replace "option_name" with the name of your option and add settings field
    public function do_checkbox_option_name(){
        //CHECKBOX
        $fieldname = str_replace("do_checkbox_", "", __FUNCTION__);
        printf(
            '<input onclick="doConditionalLogic(event);" type="checkbox" id="'.$fieldname.'" name="'.$fieldname.'" value="yes" %s/>',
            ( \get_option($fieldname) && \get_option($fieldname) == "yes") ? 'checked': ''
        );
    }

    public static function theme_settings_page() {
        ?>
        <div class="wrap">
        <h1><?php echo ucfirst(strtolower(self::parentName))?>  Settings</h1>
        <style> .theme_admin_hidden{display:none !important;}</style>

        <script>
        let admin_ajax = "<?php echo \get_site_url() . '/wp-admin/admin-ajax.php' ?>";
        /* set form conditional logic here */
        /* currently only booleans like checkboxes or radios are supported but can be expanded */
        let condition_terms = [ 'true', 'false', 'empty', 'notempty', 'checked'];
        let conditions = [
            { 
                'id': 'theme_bootstrap_version',
                'dependency': 'is_theme_bootstrap',
                'condition': 'checked',
                'checkvalue': null
            },
            {
                'id': 'theme_bootstrap_cdn',
                'dependency': 'is_theme_bootstrap',
                'condition': 'checked',
                'checkvalue': null
            }];
        function sendTestEmail(e){
            let url = admin_ajax + '?action=emailer_options_test';
            do_sendTestEmail(url, {} );
        }    
        async function do_sendTestEmail(location = '', data = {}) {

            const settings = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body:data
                    });
            return settings.json();   
        }
        function doConditionalLogic(e){
            let thisID = e.target.id;
            
            conditions.forEach(function(condition) {
                if(condition.dependency === thisID){
                    //run condition
                    if(condition_terms.indexOf(condition.condition) !== -1){
                        switch(condition.condition){
                            case 'true': 
                                if( condition.checkvalue){
                                    if(e.target.value.trim().toLowerCase() == condition.checkvalue.trim().toLowerCase() ){
                                        let target = document.getElementById(condition.id);
                                        if(target) target.closest('.formrow').classList.remove('theme_admin_hidden');
                                    } else {
                                        let target = document.getElementById(condition.id);
                                        if(target) target.closest('.formrow').classList.add('theme_admin_hidden');
                                    }
                                } else {
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.add('theme_admin_hidden');
                                }
                                break;
                            case 'false':
                                if( condition.checkvalue){
                                    if(e.target.value !== condition.checkvalue){
                                        let target = document.getElementById(condition.id);
                                        if(target) target.closest('.formrow').classList.remove('theme_admin_hidden');
                                    } else {

                                        let target = document.getElementById(condition.id);
                                        if(target) target.closest('.formrow').classList.add('theme_admin_hidden');
                                        }
                                } else {
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.add('theme_admin_hidden');
                                }
                                break;
                            case 'empty':
                                if(e.target.value = null || e.target.value == '' || !e.target.value ){
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.remove('theme_admin_hidden');
                                } else {
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.add('theme_admin_hidden');
                                }
                            break;
                            case 'notempty':
                                if(e.target.value = null || e.target.value == '' || !e.target.value ){
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.add('theme_admin_hidden');
                                } else {
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.remove('theme_admin_hidden');
                                }
                            break;
                            case 'checked':
                                if(e.target.checked){
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.remove('theme_admin_hidden');      
                                } else {
                                    let target = document.getElementById(condition.id);
                                    if(target) target.closest('.formrow').classList.add('theme_admin_hidden');
                                }
                            default: return false;
                        } //end switch
                    }
                } 
            });

        }

        </script>
        
            <form method="post" action="options.php">
            <?php \settings_fields( strtolower(self::parentName) .'-options' ); ?>
            <?php \do_settings_sections( strtolower(self::parentName).'-options' ); ?>                
            <?php submit_button(); ?>
        
        </form>
        <a class="nolink" href="#" onclick="sendTestEmail(event);"><h4>send test email</h4></a>
        </div>
<?php 
    }
}