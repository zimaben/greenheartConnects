<?php
namespace gh_connects\admin;
use gh_connects\theme\Modules as Modules;
use gh_connects\control\Core as Core;
//spin it
\gh_connects\admin\SwitchPost::run();
/* This class is used with a huge tip of the hat to open source code written by Ren Aysha: https://wordpress.org/plugins/tako-movable-comments/ */

class SwitchPost extends \GreenheartConnects {
    public static function run(){
        //Add metabox
        \add_action( 'add_meta_boxes', array( get_class(), 'add_convert_metabox' ) );
        //Add meta save function 
        \add_action( 'save_post', array( get_class(), 'ghc_save_convert_metabox') );

    }
    public static function add_convert_metabox(){
        \add_meta_box(
            'ghc_convert_posttype',
            'Convert Livestream to Video?',
            array( get_class(), 'ghc_the_convert_metabox'),
            'streams',
            'normal',
            'high'
        );
    }
    public static function ghc_convert_eval_javascript(){
        $html = '<script>';
        $html .= 'function ghc_validate_vidembed(e){';
        $html .= 'var txtarea=document.getElementById("ghc_video_embed").value;';
        $html .= 'if( txtarea.length > 10 ){';
        $html .= '   var contain = document.getElementById("ghc_convertoptions");';
        $html .= '   var dialogue = document.getElementById("ghc_convert_eval_response");';
        $html .= '   if(contain.classList.contains("active") ) contain.classList.remove("active");';
        $html .= '      dialogue.innerText = "Embed Code Added. The Post Type will switch on Post Save.";';
        $html .= '      dialogue.setAttribute("style", "");';
        $html .= '   } else {';
        $html .= '      var dialogue = document.getElementById("ghc_convert_eval_response");';
        $html .= '      dialogue.innerText = "The embed code must be added for post type to switch";';
        $html .= '      dialogue.setAttribute("style", "");';
        $html .= '      return false;';
        $html .= '   }';
        $html .= '}';
        $html .= '</script>';

        return $html;
    }
	public static function ghc_the_convert_metabox() {
        global $post;
        \wp_nonce_field( 'ghc_convertpost_nonce', 'ghc_convertpost_nonce' );

        echo self::ghc_convert_eval_javascript();
        $html = '<div class="widgefat convertbutton">';
        $html .= '<div id="ghc_convert_eval_response" style="display:none;"></div>';
        $html .= '<script>function activate_ghc_convert(e){ var target = document.getElementById(e.target.dataset.target);target.classList.toggle("active");}</script>';
        $html .= '<div data-target="ghc_convertoptions" class="fakebutton" onclick="activate_ghc_convert(event);return false;">Convert this post Video</div>';
        $html .= '<style>.fakebutton {cursor:pointer;line-height:36px;height: 36px;width: 177px;text-align:center;border-radius: 4px;border: 1px solid #ccc;background: #e1e1e1;}.ghc_convertoptions{display:none;} .ghc_convertoptions.active{display:block;}</style>';
        $html .= '<div id="ghc_convertoptions" class="ghc_convertoptions">';
        $html .= '<p>This will add your Livestream to the Videos section. This action can\'t be undone.</p>';
        $html .= '<label for="ghc_video_embed">Enter the embed code for the video:</label>';
        $html .= '<textarea style="width:100%" id="ghc_video_embed" name="ghc_video_embed"></textarea>';
        $html .= '<div class="fakebutton" data-target="ghc_video_embed" onclick="ghc_validate_vidembed(event);return false;">Convert It!</div>';
		$html .= '</div>';

		echo $html;
	}

	public static function ghc_save_convert_metabox( $post_id ) {
		// If conditions for early Bail 
        if ( !isset( $_POST['ghc_convertpost_nonce'] ) || 
        ! wp_verify_nonce( $_POST['ghc_convertpost_nonce'], 'ghc_convertpost_nonce' ) ) {
            return false; 
        } 
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return false;
        
        if (!\current_user_can('edit_post', $post_id)) return false;
        
        if ( !\get_post_type($post_id) === 'streams'  ) return false;

        //do we have the new embed code?
        if (!isset( $_POST['ghc_video_embed']) || trim($_POST['ghc_video_embed']) == '' ) return false;

        //Get New Info from Metabox
        $newembedcode = $_POST['ghc_video_embed'];
        
        //get existing post data
        $ogtitle = \get_the_title( $post_id );
        $ogexcerpt = \get_the_excerpt( $post_id );

        //if the page has already been copied
        $isVideo = \get_page_by_title( $ogtitle, 'OBJECT', 'videos' );

        if($isVideo) return false;
        
        //get Postmeta
        $ogpostdate = \get_post_meta( $post_id, 'ghc_stream_start', true );
        $ogauthbio = \get_post_meta( $post_id, 'ghc_author_bio', true );
        $ogauthname = \get_post_meta( $post_id, 'ghc_author_name', true);

        $postarr = array(
            'post_date'         => $ogpostdate, 
            'post_content'      => '',
            'post_title'        => $ogtitle, 
            'post_excerpt'      => $ogexcerpt, 
            'post_status'       => 'publish',
            'post_type'         => 'videos',
            'comment_status'    => 'open',
            'meta_input'        => array( 
                                        'ghc_video_path' => esc_attr($newembedcode),
                                        'ghc_video_type' => 'embed',
                                        'ghc_author_bio' => $ogauthbio,
                                        'ghc_video_author' => $ogauthname
                                    ),
        );
        /* IMPORTANT, calling wp_insert_post inside save loops the save function, resulting
        in and infinite loop without this post type check */
        if ( \get_post_type($post_id) === 'streams'  ){ 

           # $new_post_id = true;
            $new_post_id = \wp_insert_post( $postarr );
            if ( \is_wp_error( $new_post_id ) ) {
                $error_code = array_key_first( $user_id->errors );
                $error_message = $user_id->errors[$error_code][0];
                error_log($error_message);
                return false;
            } 
        }
        #FEATURED MEDIA
        //get Featured Image
        $featured_image_id = \get_post_thumbnail_id( $post_id );
        set_post_thumbnail( $new_post_id, $featured_image_id );
        
        #COMMENTS
        global $wpdb;
        $comm_args = array(
            'post_id' => $post_id,   //131
        );
        $comments = get_comments( $comm_args );
        $comments_done = false;
        if( empty( $comments )) $comments_done = true;
        foreach($comments as $comment){
            $comment_ID = (int)$comment->comment_ID;
            $comment_post_ID = $new_post_id;
            $new = compact( 'comment_post_ID' );
            if( ! self::tako_nested_comments_exist($comment_ID ) ){
                $update = $wpdb->update( $wpdb->comments, $new, compact( 'comment_ID' ) );
            } else {
                $var = array_merge( self::tako_get_subcomments( self::get_direct_subcomments( $comment_ID ) ), compact( 'comment_ID' ) );
                $val = implode( ',', array_map( 'intval', $var ) );
                $comments_done = $wpdb->query( "UPDATE $wpdb->comments SET comment_post_ID = $new_post_id WHERE comment_ID IN ( $val )" );
            }          
        }

        if($featured_image_id && $comments_done ){
            #EVERYTHING MOVED - DELETE OLD POST 

            #\wp_delete_post( $post_id, false);

        } else {
            #ADMIN NOTICE - DO LATER
        }
           
    }
    public static function tako_nested_comments_exist( $comment_ID ) {
		$comments_args = array( 'parent' => $comment_ID );
		$comments = get_comments( $comments_args );

		return $comments;
    }
    public static function tako_get_subcomments( $comments ){
		global $wpdb;
		// implode the array; this is the current 'parent'
		$parents = implode( ',', array_map( 'intval', $comments ) );
		$nested = array(); // this will store all the subcomments

		do {
			// initializing the an array (or emptying the array)
			$subs = array();
			// get the subcomments under the parent
			$subcomments = $wpdb->get_results( "SELECT comment_ID FROM $wpdb->comments WHERE comment_parent IN ( $parents )" );
			// store the subcomment under $subs and $nested
			foreach( $subcomments as $subcomment ) {
				$subs[] = $subcomment->comment_ID;
				$nested[] = $subcomment->comment_ID;
			}
			// implode the array and assign it as parents
			$parents = implode( ',', array_map( 'intval', $subs ) );
		// loop will stop once $subs is empty
		} while( !empty( $subs ) );

		// merge all the subcomments with the initial parent comments
		$merge = array_merge( $comments, $nested );

		return $merge;
    }
    public static function get_direct_subcomments( $comment_ID ){
		$comments_args = array( 'parent' => $comment_ID );
		$comments = get_comments( $comments_args );
		$comments_id = array();

		foreach( $comments as $comment ) {
			$comments_id[] = $comment->comment_ID;
		}
		return $comments_id;
	}
}