<?php
   if ( ! defined( 'ABSPATH' ) ) {
   	exit;
   }
   class Web_Stories_Enhancer_Support
  {

    public function __construct()
    {
      add_action( 'wp_ajax_wse_send_query_message', array($this,'sendQueryMessage'));
    }

    private function wse_sanitize_textarea_field( $str ) {
    
      if ( is_object( $str ) || is_array( $str ) ) {
          return '';
      }
      
      $str = (string) $str;
      
      $filtered = wp_check_invalid_utf8( $str );
      
      if ( strpos( $filtered, '<' ) !== false ) {
          $filtered = wp_pre_kses_less_than( $filtered );
          // This will strip extra whitespace for us.
          $filtered = wp_strip_all_tags( $filtered, false );
      
          // Use HTML entities in a special case to make sure no later
          // newline stripping stage could lead to a functional tag.
          $filtered = str_replace( "<\n", "&lt;\n", $filtered );
      }
      
      $filtered = trim( $filtered );
      
      $found = false;
      while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) {
          $filtered = str_replace( $match[0], '', $filtered );
          $found    = true;
      }
      
      if ( $found ) {
          // Strip out the whitespace that may now exist after removing the octets.
          $filtered = trim( preg_replace( '/ +/', ' ', $filtered ) );
      }
      
      return $filtered;
    }
    
    public function sendQueryMessage(){   
		    
        if ( ! isset( $_POST['wse_security_nonce'] ) ){
           return; 
        }
        if ( !wp_verify_nonce( $_POST['wse_security_nonce'], 'wse-admin-nonce' ) ){
           return;  
        }   
        $message        = $this->wse_sanitize_textarea_field($_POST['message']); 
        $email          = $this->wse_sanitize_textarea_field($_POST['email']);   
                                
        if(function_exists('wp_get_current_user')){
    
            $user           = wp_get_current_user();
    
            $message = '<p>'.$message.'</p><br><br>'.'Query from Web Stories Enhancer plugin support tab';
            
            $user_data  = $user->data;        
            $user_email = $user_data->user_email;     
            
            if($email){
                $user_email = $email;
            }            
            //php mailer variables        
            $sendto    = 'team@magazine3.in';
            $subject   = "Web Stories Enhancer Query";
            
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'From: '. esc_attr($user_email);            
            $headers[] = 'Reply-To: ' . esc_attr($user_email);
            // Load WP components, no themes.   
    
            $sent = wp_mail($sendto, $subject, $message, $headers); 
    
            if($sent){
    
                 echo json_encode(array('status'=>'t'));  
    
            }else{
    
                echo json_encode(array('status'=>'f'));            
    
            }
            
        }
                        
        wp_die();           
    }
    


}