<?php
namespace gh_connects\theme;


//spin it up
#\nv_dailyvibe\theme\Template::get_instance();

class Template extends \GreenheartConnects {
    private $file;
    private $args = array();

    public function __construct( $file ) {//template only renders files in the modules filetree
        $this->file = self::get_plugin_path('theme/views/'.$file);
    }
    public function __set( $key, $val) {
        $this->args[$key] = $val;
    }
    public function __get( $key ){
        return (isset($this->args[$key]) ) ? $this->args[$key] : null;
    }
    public function render(){
        //buff
        ob_start();
        //bring params into local variables
        foreach($this->args as $k => $v) {
            $$k = $v;
        }
        //get template for view
        include( $this->file );

        $output_str = ob_get_contents();
        ob_end_clean();
        return $output_str;
    }
      
}