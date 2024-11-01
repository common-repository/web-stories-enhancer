<?php
if (!defined('ABSPATH')) exit;

class Web_Stories_Enhancer_Shortcode
  {
    

    public function __construct()
    {
      add_shortcode('web_stories_enhancer', array($this, 'webstoriesenhancer_webstories_story_shortcode'));
      if(!is_admin())
      {
        add_action('template_redirect', array($this,'wse_add_cta_banner'),10);
      }
      
    }

    public function webstoriesenhancer_webstories_story_shortcode($atts)
    {

      $atts = shortcode_atts(
        array(
          'type' => 'circle_carousel',
          'columns' => '2',
        ), $atts, 'web_stories_enhancer' );

      $wse_content =  '';

      if ( in_array( 'makestories-helper/makestories.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        // do stuff only if makestories-helper is installed and active
        $wse_active_status = 'makestories';
        $wse_post_type ='makestories_story';
      }else if ( in_array( 'web-stories/web-stories.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        // do stuff only if web-stories is installed and active
        $wse_active_status = 'webstories';
        $wse_post_type ='web-story';
      }else{
        // do nothing
        $wse_active_status = '';
        $wse_post_type ='';
      }

      if($wse_active_status !='' && $wse_post_type !=''){

        if( $atts['type']=='circle_carousel')
        {
          $wse_content .= '<style>.web_stories_enhancer_main{width: 100%;margin: 0 auto;margin: 10px auto;position: relative;clear: both;display: block;overflow: hidden;background: transparent;border-radius: 5px;}';
          $wse_content .= '.web_stories_enhancer_main_inner{border-radius: 8px;margin: 10px 0;}';
          $wse_content .= '.web_stories_enhancer_main_column{outline: none;overflow-y: hidden;}';
          $wse_content .= '#text-2{display:none;}';
          $wse_content .= '.web_stories_enhancer_main_column ul {list-style: none; display: flex;    margin: 0;}';
          $wse_content .= '.web_stories_enhancer_main_column ul li{    list-style-type: none;text-align: center;}';
          $wse_content .= '.web_stories_article_thumbnail{padding: 0;text-align: center;margin: 0 auto;line-height: 0;border: solid 1px #f00;border-radius: 50px;padding: 1px;}';
          $wse_content .= '.web_stories_article_thumbnail img{border-radius:50%; width:66px; height:66px;}';
          $wse_content .= '.web_stories_enhancer_main_column .web_stories_article{width: 76px;padding: 0 4px;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);-webkit-tap-highlight-color: transparent;}';
          $wse_content .= '.web_stories_enhancer_main_column .web_stories_article .web_stories_article_title{font-size: 13px;display: block;overflow: hidden;text-align: center;text-overflow: ellipsis;white-space: nowrap;}</style>';

        }
        else if( $atts['type']=='box_carousel')
        {
          
          $wse_content .= '<style>.box_carousel .web_stories_enhancer_main{width: 100%;margin: 0 auto;margin: 10px auto;position: relative;clear: both;display: block;overflow: hidden;background: transparent;border-radius: 5px;}';
          $wse_content .= '.box_carousel .web_stories_enhancer_main_inner{border-radius: 8px;margin: 10px 0;}';
          $wse_content .= '.box_carousel .web_stories_enhancer_main_column{outline: none;overflow-y: hidden;}';
          $wse_content .= '.box_carousel #text-2{display:none;}';
          $wse_content .= '.box_carousel .web_stories_enhancer_main_column ul {list-style: none; display: flex;    margin: 0;}';
          $wse_content .= '.box_carousel .web_stories_enhancer_main_column ul li{    list-style-type: none;text-align: center;}';
          $wse_content .= '.box_carousel .web_stories_article_thumbnail{padding: 0;text-align: center;margin: 0 auto;line-height: 0;border:none;border-radius: 50px;padding: 1px;}';
          $wse_content .= '.box_carousel .web_stories_article_thumbnail img{border-radius: 5px;width: auto;height:auto;}';
          $wse_content .= '.box_carousel .web_stories_enhancer_main_column .web_stories_article{width: 100px;padding: 0 4px;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);-webkit-tap-highlight-color: transparent;}';
          $wse_content .= '.box_carousel .web_stories_enhancer_main_column .web_stories_article .web_stories_article_title{font-size: 13px;display: block;overflow: hidden;text-align: center;text-overflow: ellipsis;white-space: nowrap;}';
          $wse_content .= '</style>';

          
        }

        else if( $atts['type']=='grid')
        {
          $width=100;
          if($atts['columns'])
          {
            $width=intval(100/intval($atts['columns']));
          }
          $wse_content .= '<style>.grid .web_stories_enhancer_main{width: 100%;margin: 0 auto;margin: 10px auto;position: relative;clear: both;display: block;overflow: hidden;background: transparent;border-radius: 5px;}';
          $wse_content .= '.grid .web_stories_enhancer_main_inner{border-radius: 8px;margin: 10px 0;}';
          $wse_content .= '.grid .web_stories_enhancer_main_column{outline: none;overflow-y: hidden;}';
          $wse_content .= '.grid #text-2{display:none;}';
          $wse_content .= '.grid .web_stories_enhancer_main_column ul {list-style: none; display:block; margin: 0;}';
          $wse_content .= '.grid .web_stories_enhancer_main_column ul li{    list-style-type: none;text-align: center;float:left;width:'.$width.'%}';
          $wse_content .= '.grid .web_stories_article_thumbnail{padding: 0;text-align: center;margin: 0 auto;border:none;line-height: 0;border-radius: 50px;padding: 1px;}';
          $wse_content .= '.grid .web_stories_article_thumbnail img{border-radius: 5px;width: 180px;height: 270px;}';
          $wse_content .= '.grid .web_stories_enhancer_main_column .web_stories_article{width: 190px;padding: 0 4px;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);-webkit-tap-highlight-color: transparent;}';
          $wse_content .= '.grid .web_stories_enhancer_main_column .web_stories_article .web_stories_article_title{font-size: 13px;display: block;overflow: hidden;text-align: center;text-overflow: ellipsis;white-space: nowrap;}';
          $wse_content .= '@media only screen and (max-width: 600px) {.grid .web_stories_enhancer_main_column ul li{ width:50%}}';
          $wse_content .= '</style>';
          
        }
      
          $wse_content .= '<div class="'.$atts['type'].' web_stories_enhancer_main">';
          $wse_content .= '<div class="web_stories_enhancer_main_inner">';
          $wse_content .= '<div class="web_stories_enhancer_main_column"><ul>';



          $wse_args = array(
            'post_type' => $wse_post_type,
            'post_status' => 'publish',
            'posts_per_page' => 20, 
            'orderby' => 'title',
            'order' => 'ASC',
          );

          $wse_loop = new WP_Query($wse_args);

          if ($wse_loop->have_posts()) :
            while ($wse_loop->have_posts()) : $wse_loop->the_post();

              $wse_content .= '<li><a href="' . esc_url( get_permalink()) . '"><div class="web_stories_article"><div class="web_stories_article_thumbnail">';
              
              if($wse_active_status == 'makestories'):
                $wse_postMeta = get_post_meta(get_the_ID());
                $wse_posterImage = "https://ss.makestories.io/get?story=" . $wse_postMeta['story_id'][0];
                if($wse_posterImage !=''){
                $wse_content .= '<img class="makestories" src="' . esc_url($wse_posterImage) . '" alt="' . esc_attr( get_the_title() ) . '"  />';
                }
              endif;
              if($wse_active_status == 'webstories'):
                if (has_post_thumbnail()) {
                  if( $atts['type']=='circle_carousel')
                  {
                    $thumbnail=get_the_post_thumbnail_url(get_the_ID(), array(66, 66));
                  }
                  else if( $atts['type']=='grid')
                  {
                    $thumbnail=get_the_post_thumbnail_url(get_the_ID(), array(360, 540));
                  }else{

                    $thumbnail=get_the_post_thumbnail_url(get_the_ID(), 'medium');
                  }
                 
                $wse_content .= '<img class="webstories" src="' . esc_url($thumbnail) . '" alt="' . esc_attr( get_the_title() ) . '"  />';
                }
              endif;
              
              $wse_content .= '</div><div class="web_stories_article_title">' .esc_html( get_the_title()) . '</div></div></a></li>';

            endwhile;
          endif;
          wp_reset_postdata(); 

          $wse_content .= '</ul></div></div></div>';

        }

      // Return the wse_content
      return $wse_content;
    }


    public function wse_add_cta_banner(){
      global $wp_query;
     
      if(!$wp_query->post)
      {
        return;
      }
      $post_type=$wp_query->post->post_type;
      if($post_type=='web-story')
      {
        $post_content=$wp_query->post->post_content;
        if(isset($post_content) && !empty($post_content))
        {
          
          $settings=get_option('wse_settings');
          if(isset($settings['cta_enable']) && $settings['cta_enable']==1 && isset($settings['cta_banner'])&& isset($settings['cta_btn_link']))
          {

              $our_ad_slide='<amp-story-page id="797f2fd5-3d50-4efa-96e7-82adde43f73d" auto-advance-after="7s" class="i-amphtml-layout-container" i-amphtml-layout="container">
              <amp-story-grid-layer template="vertical" aspect-ratio="412:618" class="grid-layer i-amphtml-layout-container" i-amphtml-layout="container" style="--aspect-ratio:412/618;">
                <div class="_87f9c71 page-fullbleed-area">
                  <div class="page-safe-area">
                    <div class="_6120891">
                      <div class="_89d52dd mask" id="el-1658094e-1b1f-4ce0-aba3-06b1521c70e3">
                        <div data-leaf-element="true" class="_411385a">
                          <amp-img layout="fill" src="{{background_url}}" alt="page8_img1" class="i-amphtml-layout-fill i-amphtml-layout-size-defined" i-amphtml-layout="fill"></amp-img>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </amp-story-grid-layer>
              <amp-story-grid-layer template="vertical" aspect-ratio="412:618" class="grid-layer i-amphtml-layout-container" i-amphtml-layout="container" style="--aspect-ratio:412/618;">
                <div class="page-fullbleed-area">
                  <div class="page-safe-area"></div>
                </div>
              </amp-story-grid-layer>
              <amp-story-page-outlink layout="nodisplay" class="i-amphtml-layout-nodisplay" hidden="hidden" i-amphtml-layout="nodisplay">
                <a href="{{insert_btn_link}}" rel="sponsored nofollow noreferrer" target="_blank">{{insert_btn_text}}</a>
              </amp-story-page-outlink>
            </amp-story-page>';

            $insert_pos=$settings['cta_banner_slide']?$settings['cta_banner_slide']:2; 
         
            $custom_slide= str_replace(array('{{background_url}}','{{insert_btn_link}}','{{insert_btn_text}}'),array( $settings['cta_banner'],$settings['cta_btn_link'],$settings['cta_btn_text']),$our_ad_slide);
        
              @ $domDocument = new DOMDocument();
              libxml_use_internal_errors(true);
              $domContent=$domDocument->loadHTML($post_content);
              $webstory_pages = $domDocument->getElementsByTagName('amp-story-page');
              $count=count($webstory_pages);
              if($count>=$insert_pos)
              {
                $target_position=$webstory_pages->item($insert_pos);
                $dom_to_add = new DOMDocument();
                @ $dom_to_add->loadHTML($custom_slide);
                $new_element = $dom_to_add->documentElement;
                $imported_element = $domDocument->importNode($new_element, true);
                $target_position->parentNode->insertBefore($imported_element, $target_position->previousSibling);
                $output = @ $domDocument->saveHTML();
                $wp_query->post->post_content=$output;
              }
            } 
        }
      }
      
}

   
  }