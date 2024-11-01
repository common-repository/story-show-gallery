<?php
    /**
     * Plugin Name: Story Show Gallery
     * Plugin URI: https://ssg.flor.cz/wordpress/
     * Description: Vertical photo gallery with optimally placed captions, full screen lightbox, minimalist, no ugly arrows. 
     * Version: 1.10.7
     * Author: Roman Flössler
     * Author URI: https://ssg.flor.cz/
     * License: GPL-2.0+
     * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
     */

defined( 'ABSPATH' ) or die( 'Only for Wordpress' );
error_reporting(E_ALL ^ E_NOTICE); 

class StoryShowGallery {
	private $story_show_gallery_options;
	private $default_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'story_show_gallery_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'story_show_gallery_page_init' ) );	
		
		$this->default_options = array(
			"thumbnails3D" => "thumbnails3D",
			"scrollDuration" => 500,
			"theme" => "dark",
			"bgOpacity" => 100,
			"wordpressGalleryFS" => "wordpressGalleryFS",
			"respectOtherWpGalleryPlugins" => "respectOtherWpGalleryPlugins",
			"socialShare" => "socialShare",
			"preferedCaptionLocation" => 8,
			"logIntoGA" => "logIntoGA",
			"rightClickProtection" => "rightClickProtection",
			'captionsSource' => 'caption',
			"fontSize" => 100,
			
			"imgBorderWidthX" => "1",
			"imgBorderWidthY" => "1",
			"imgBorderColor" => "",
			"imgOutlineColor" => "",
			"imgBorderRadius" => "0",
			"imgBorderRadius" => "0.2",
			"imgBorderShadow" => "imgBorderShadow",

			"watermarkWidth" => 150,
			"watermarkImage" => "",
			"watermarkText" => "",
			"watermarkFontColor" => "",		
			"watermarkOffsetX" => "2",
			"watermarkOffsetY" => "1.2",
			"watermarkOpacity" => "0.5",
			"watermarkFontSize" => "20",
			
			"hint1" => "Browse through Story Show Gallery by:",
			"hint2" => "a mouse wheel <strong>⊚</strong> or arrow keys <strong>↓→↑←</strong>",
			"hint3" => "or <strong>TAP</strong> on the bottom (top) of the screen",
			"hintTouch" => "<strong>Swipe</strong> left (right) or<br><strong>Tap</strong> the bottom (top) of the screen<br> to browse the <i>Story Show Gallery</i>.",
			"hintFS" => "For a better experience <br><a><abbr>⎚</abbr> go full screen</a>",
			"toTheTop" => "Scroll to top",
			"exitLink" => "Exit the Gallery",
			"imageLink" => "The link to selected image:",
			"copyButton" => "⎘ Copy the link to clipboard",
			"linkPaste" => "…and you can paste it anywhere via ctrl+v",
			"landscapeHint" => "<i>↻</i> photos look better in landscape mode <span>^icon^</span>",
			"showLandscapeHint" => "showLandscapeHint"
		);
	}

	public function story_show_gallery_add_plugin_page() {
		add_options_page(
			'ꐠ Story Show Gallery', // page_title
			'Story Show Gallery', // menu_title
			'manage_options', // capability
			'story-show-gallery', // menu_slug
			array( $this, 'story_show_gallery_create_admin_page' ) // function
		);
	}

	public function story_show_gallery_create_admin_page() {
		$ssg_url = plugin_dir_url( __FILE__ );
		// delete_option( 'story_show_gallery_option_name' );
		$this->story_show_gallery_options = get_option( 'story_show_gallery_option_name', $this->default_options ); 
		//printf(serialize($this->story_show_gallery_options));
		?>
		
		<div class="wrap" id="ssg-wrap">
            <h1 id="ssgh1">
			<b style="display: inline-block;transform: scaleX(1.3);">ꐠ</b> Story Show Gallery</h1>
			<br />
            <p class="text green">&#10003; Story Show Gallery is active now. Photos from Wordpress galleries are opening into SSG lightbox. 
            </p>
            <p class="text crucial">
                SSG uses standard WordPress gallery, so create galleries as usual.
                <br /> <br /> <strong class="important">Important!</strong> If you use new Gutenberg gallery, 
                SSG will open only if you set this gallery's option: <img src="<?php echo $ssg_url; ?>img/link-to-media-file.png">
                <br /> You can open a single image into SSG too. Use the same option "Link to: Media file", but in the settings of that image.
            </p>

	<?php
			global $wp_version;
	if ( version_compare( $wp_version, '5.0', '<=' ) ) { ?> 
        <p class="text">You have Wordpress older than version 5.0. Graphic emoji 🤖😟 pasted into text fields won't save.
		<br> Rather don't try it, in Wordpress older than 4.0 an emoji will erase 😱 all settings.</p>
	<?php }	?>	

			<p id="trio" style="margin: 2em 0 0 0;">
				<span onclick="switchTo(1,1,9)">Settings</span>
				<span onclick="switchTo(2,10,17)">Captions</span>
				<span onclick="switchTo(3,18,24)">Border</span>
				<span onclick="switchTo(4,25,29)">Full screen</span>
				<span onclick="switchTo(5,30,37)">Watermark</span>
				<span onclick="switchTo(6,38,43)">System</span>
				<span onclick="switchTo(7,44,55)">Translation</span>
			</p>
			<?php //settings_errors(); ?>

			<form method="post" action="options.php">
				<?php
					settings_fields( 'story_show_gallery_option_group' );
					do_settings_sections( 'story-show-gallery-admin' );
					submit_button();
				?>
			</form>
		
            <h3>Controlling individual gallery or image:</h3>
            <p class="text">
				The global settings are above, but you can also control each gallery or image by CSS classes:<br>
                The <strong class="important">nossg</strong> class selectively deactivate SSG for some images or entire gallery.
                The <strong class="important">fs</strong> class activates fullscreen mode. 
				The <strong class="important">ssg</strong> class creates separate gallery. 
				The <strong class="important">gossg</strong> class includes image into the gallery lightbox only if a user clicks on it. 
				And there are also visual theme classes <strong class="important">ssglight</strong>, <strong class="important">ssgdim</strong>, 
                <strong class="important">ssgdark</strong>, <strong class="important">ssgblack</strong>. <br><br>
                <img src="<?php echo $ssg_url; ?>img/link-css-class.png">&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="https://roman-flossler.github.io/StoryShowGallery/wordpress/#cssclasses" target="_blank"><b>How to set CSS classes</b></a>
            </p>

            <h3>A couple of useful tips:</h3>

            <p class="text">
                Story Show Gallery supports Google Analytics - 
                <a target="_blank" href="https://roman-flossler.github.io/StoryShowGallery/wordpress/#Analytics">
                find out, how long site visitors view each photo</a> <br />
                How to <a target="_blank" href="https://roman-flossler.github.io/StoryShowGallery/wordpress/#deeplink">
                deep link into Story Show Gallery</a> to show a particular photo.
            </p>
         
        </div>

        <div id="ssg-aside">
            <div class="ssg-box logo">
			<a href="https://roman-flossler.github.io/StoryShowGallery/#story-show-gallery-logo" target="_blank">
            <img src="<?php echo $ssg_url; ?>img/ssg-logo.jpg" ></a>
            </div>

            <div class="ssg-box">        
                <br><p>
                You can use Story Show Gallery also outside of Wordpress. E.g. for some simple HTML sites - <a href="https://ssg.flor.cz/" target="_blank">it is easy to implement</a>
                </p>

            </div>
            <div class="ssg-box donate">
                <p>If you like this WordPress plugin, pls make some time for
                <a target="_blank" href="https://wordpress.org/support/plugin/story-show-gallery/reviews/?rate=5#new-post">rating</a>
				</p>
				
				<p>Or you can buy me a 🍺 beer :-)</p>
                <a href="https://www.paypal.me/FlorSSG" target="_blank" >
                <img src="<?php echo $ssg_url; ?>img/donate.png" alt="" width="99" height="49"></a>
            </div>
        </div>

        <p class="author"><a href="https://ssg.flor.cz/wordpress/" target="_blank"> Story Show Gallery</a> by Roman Flössler</p> 
		
		<script>
			function trs( begin, end ) {
				var selector = '';
				var i;
				for(i=begin; i<= end; i++) {
					selector += "tr:nth-child(" + i + ")" + (i != end ? ',' : '');
				}			
				return selector;
			}
			jQuery( trs(9,55) ).hide();

			function switchTo(section, begin, end) {
				jQuery('#trio span').css('top','0');
				jQuery( trs(1,55) ).hide();
				jQuery('#trio span:nth-child(' + section + ')').css('top','2px');
				jQuery( trs(begin, end) ).show();
			}
		</script>

	<?php }

	public function check($args) {		
		printf(
			'<input type="checkbox" name="story_show_gallery_option_name['. $args[0].']" id="'. $args[0].'" value="'. $args[0].'" %s> <label for="'. $args[0].'">'.$args[1].'</label>',
			( isset( $this->story_show_gallery_options[ $args[0]] ) && $this->story_show_gallery_options[ $args[0]] ===  $args[0] ) ? 'checked' : ''
		);
	}

	public function text($args) {
		printf(
			'<label for="'. $args[0].'">'.$args[1].'</label><br>
			<input class="large-text" type="text" name="story_show_gallery_option_name['. $args[0].']" id="'. $args[0].'" value="%s">',
			isset( $this->story_show_gallery_options[$args[0]] ) ? esc_attr( $this->story_show_gallery_options[$args[0]]) : ''
		);
	}
	public function trans($args) {
		printf(
			'<input class="large-text" type="text" name="story_show_gallery_option_name['. $args[0].']" id="'. $args[0].'" value="%s">',
			isset( $this->story_show_gallery_options[$args[0]] ) ? esc_attr( $this->story_show_gallery_options[$args[0]]) : ''
		);
	}
	public function input($args) {
		printf(
			'<input class="compact" type="text" name="story_show_gallery_option_name['. $args[0].']" id="'. $args[0].'" value="%s">
			<label for="'. $args[0].'">'.$args[1].'</label>',
			isset( $this->story_show_gallery_options[$args[0]] ) ? esc_attr( $this->story_show_gallery_options[$args[0]]) : ''
		);
	}
	public function number($args) {
		printf(
			'<input class="compact" onchange="ssgZeroReset(this)" type="number" step="'.$args[2].'" name="story_show_gallery_option_name['. $args[0].']" id="'. $args[0].'" value="%s">
			<label for="'. $args[0].'">'.$args[1].'</label>',
			isset( $this->story_show_gallery_options[$args[0]] ) ? esc_attr( $this->story_show_gallery_options[$args[0]]) : ''
		);
	}
	public function color($args) {
		if($this->story_show_gallery_options[$args[0]] == "" || $this->story_show_gallery_options[$args[0]] == "false") {
			printf(
				'<input class="compact" type="text" name="story_show_gallery_option_name['. $args[0].']" id="'. $args[0].'" value=""  placeholder="Preset" disabled>
				<button class="button" type=button onclick=\'SSGsetColor("'. $args[0]  .'", this)\'>set color</button>
				<label for="'. $args[0].'">'.$args[1].'</label>',
				isset( $this->story_show_gallery_options[$args[0]] ) ? esc_attr( $this->story_show_gallery_options[$args[0]]) : ''
			);			
		} else {
			printf(
				'<input class="compact" type="color" name="story_show_gallery_option_name['. $args[0].']" id="'. $args[0].'" value="%s">
				<button class="button" type=button onclick=\'SSGsetColor("'. $args[0]  .'", this)\'>use preset</button>
				<label for="'. $args[0].'">'.$args[1].'</label>',
				isset( $this->story_show_gallery_options[$args[0]] ) ? esc_attr( $this->story_show_gallery_options[$args[0]]) : ''
			);
			
		}
	}


	public function story_show_gallery_page_init() {
		register_setting(
			'story_show_gallery_option_group', // option_group
			'story_show_gallery_option_name', // option_name
			array( $this, 'story_show_gallery_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'story_show_gallery_setting_section', // id
			'Settings', // title
			array( $this, 'story_show_gallery_section_info' ), // callback
			'story-show-gallery-admin' // page
		);

		add_settings_field(
			'thumbnails3D', // id
			'3D thumbnails', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array( "thumbnails3D", "Activate 3D animated thumbnails in the WP galleries</label>" )
		);

		add_settings_field(
			'ssg_cdn', // id
			'Faster loading', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('ssg_cdn','Faster loading of the newest SSG from 
			<a href="https://www.jsdelivr.com/package/npm/story-show-gallery" target="_blank">JSdelivr CDN</a> - Recommended')
		);

		add_settings_field(
			'scrollDuration', // id
			'Scroll duration', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section,
			array( "scrollDuration", "Duration of scroll animation in miliseconds, set to 0 for no scroll animation.", "50" )
		);

		add_settings_field(
			'theme', // id
			' Visual theme', // title
			array( $this, 'theme_callback' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section' // section
		);

		add_settings_field(
			'rightClickProtection', // id
			'Protection', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('rightClickProtection','Protect photos from being copied via right click menu')
		);

		add_settings_field(
			'bgOpacity ', // id
			'Background opacity', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section,
			array( "bgOpacity", "100 (percent) means full opacity, below 100 the gallery background will be transparent.", "1" )
		);

		add_settings_field(
			'socialShare', // id
			'Share icons', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('socialShare','Display social share icons')
		);

		add_settings_field(
			'separateWpGalleries', // id
			'Separate galleries', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('separateWpGalleries','Show photos from different galleries and individual images separated.
			<br>By default, SSG lightbox shows all images from a post together.')
		);

		add_settings_field(
			'crossCursor', // id
			'Cross cursor', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('crossCursor','Unobtrusive cross cursor')
		);

		add_settings_field(
			'hideImgCaptions', // id
			'Hide img captions', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('hideImgCaptions','It impacts only image captions, not global caption or exif')
		);

		add_settings_field(
			'fontSize', // id
			'Font size', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section,
			array( "fontSize", "100 (percent) means base font size, below 100 the text will be smaller and above 100 larger", "1" )
		);

		add_settings_field(
			'captionsSource', // id
			'Source of captions', // title
			array( $this, 'captionsSource_callback' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section' // section			
		);

		add_settings_field(
			'captionExif', // id
			'EXIF in captions', // title
			array( $this, 'captionExif_callback' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section' // section			
		);		

		add_settings_field(
			'globalAuthorCaption', // id
			'Global caption', // title
			array( $this, 'text' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('globalAuthorCaption','An author signature (or some text or HTML), which will be part of a caption of each photo.')
		);			

		add_settings_field(
			'sideCaptionforSmallerLandscapeImg', // id
			'Side captions', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section' ,// section
			array('sideCaptionforSmallerLandscapeImg','Side captions for small landscape oriented images, where is enough space below them as well as on their side. 
			<br> If unchecked, captions will be under these small images.')
		);


		add_settings_field(
			'preferedCaptionLocation', // id
			'Captions location', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('preferedCaptionLocation','Negative number => more likely side captions. Positive number => more likely captions below the photo.
			If the number is too large (eg: 300 or -300 ) captions will be only in one location regardless format of a photo.',"0.5")
		);

		add_settings_field(
			'hideThumbCaptions', // id
			'Thumbnails captions', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('hideThumbCaptions','Hide captions which belong to gallery thumbnails. Thumbnails can be too small for captions.')
		);

		add_settings_field(
			'imgBorderWidthX', // id
			'Horizontal border', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imgBorderWidthX','Thickness of image horizontal border (in pixels)','1')
		);

		add_settings_field(
			'imgBorderWidthY', // id
			'Vertical border', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imgBorderWidthY','Thickness of image vertical border (in pixels)','1')
		);

		add_settings_field(
			'imgBorderColor', // id
			'Border color', // title
			array( $this, 'color' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imgBorderColor','&nbsp;&nbsp;&nbsp;Preset sets color according to visual theme.')
		);

		add_settings_field(
			'imgOutlineColor', // id
			'Outline color', // title
			array( $this, 'color' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imgOutlineColor','&nbsp;&nbsp;&nbsp; Set some color to display 1px outline around image.<br><br>
			&nbsp;&nbsp;&nbsp;⇑&nbsp;<em>Vertical & horizontal border should be the same, otherwise outline will not fit.</em>')
		);

		add_settings_field(
			'imgBorderRadius', // id
			'Border radius', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imgBorderRadius','Image border radius in percents of the screen width, value 50 will make circle/ellipse','0.05')
		);

		add_settings_field(
			'imgBorderShadow', // id
			'Border shadow', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imgBorderShadow','Display shadow around the border of image as it is defined in the visual theme')
		);

		add_settings_field(
			'imgBorderLightFx', // id
			'Light effect', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imgBorderLightFx','Image border with light gradient effect')
		);


		add_settings_field(
			'wordpressGalleryFS', // id
			'WP Gallery fullscreen', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('wordpressGalleryFS','Photos from Wordpress galleries will show in full screen mode.')
		);

		add_settings_field(
			'mobilePortraitFS', // id
			'Mobile portrait FS', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('mobilePortraitFS','On mobiles, the gallery will launch in full screen portrait mode, but only if FS mode is allowed by some setting or fs class. <br> 
			This option makes rotating into full screen landscape mode much more fluent and without problems.')
		);

		add_settings_field(
			'forceLandscapeMode', // id
			'Forced landscape FS', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('forceLandscapeMode','Even if the user is holding the phone in portrait mode, the gallery will launch in full screen landscape mode.
			Apple does not support this feature.')
		);

		add_settings_field(
			'alwaysFullscreen', // id
			'Always fullscreen', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('alwaysFullscreen','Force SSG to always display photos in full screen.')
		);

		add_settings_field(
			'neverFullscreen', // id
			'Never fullscreen', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('neverFullscreen','Force SSG to never display photos in full screen. There is an exception for mobile devices.<br><br><em style="color:#405292"> 
			SSG lightbox doesn&#39;t have an icon for full screen mode. According to statistics, users simply don&#39;t use it. 
			So it&#39;s up to you, if the gallery will go into full screen.</em>')
		);

		add_settings_field(
			'watermarkText', // id
			'Watermark text', // title
			array( $this, 'text' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkText','Watermark text - use  &lt;br&gt; tag for word wrap')
		);

		add_settings_field(
			'watermarkImage', // id
			'Watermark image', // title
			array( $this, 'text' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkImage','Watermark image URL e.g. https://www.flor.cz/img/florcz.png')
		);

		add_settings_field(
			'watermarkWidth', // id
			'Watermark width', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkWidth','Watermark image width in pixels. It is downsized on smartphones.','1')
		);

		add_settings_field(
			'watermarkFontSize', // id
			'Watermark font size', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkFontSize','Watermark font size in pixels. It is downsized on smartphones.','1')
		);

		add_settings_field(
			'watermarkFontColor', // id
			'Watermark font color', // title
			array( $this, 'color' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkFontColor','&nbsp;&nbsp;&nbsp;Preset is white text + black shadow. If you set color there will be no text shadow')
		);

		add_settings_field(
			'watermarkOffsetX', // id
			'Shift from left', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkOffsetX','Watermark horizontal shift from left border in percents of the photo, use 50 for centering','0.1')
		);

		add_settings_field(
			'watermarkOffsetY', // id
			'Shift from bottom', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkOffsetY','Vertical shift from bottom border in percents of the photo. Negative numbers are in pixels, for placing onto border.','0.1')
		);

		add_settings_field(
			'watermarkOpacity', // id
			'Watermark opacity', // title
			array( $this, 'number' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('watermarkOpacity','0 is for total transparency, 1 is for total opacity','0.01')
		);

		add_settings_field(
			'respectOtherWpGalleryPlugins', // id
			'Respect others', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('respectOtherWpGalleryPlugins','If you uncheck this, SSG gallery will try to override lightbox from other Wordpress gallery plugins.')
		);

		add_settings_field(
			'logIntoGA', // id
			'Track views', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('logIntoGA','Track image views in Google Analytics. SSG supports only ga.js tracking code.')
		);

		add_settings_field(
			'showFirst3ImgsTogether', // id
			'first 3 together', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('showFirst3ImgsTogether','Show first 3 images together - e.g. if the third image is clicked, then image order will be 3,1,2,4,5,6...')
		);

		add_settings_field(
			'scaleLock1', // id
			'Scale lock', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('scaleLock1','Check this if the gallery has scaling problem on your website (very rare).')
		);

		add_settings_field(
			'enlargeImg', // id
			'Enlarge image', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('enlargeImg','Enlarge image above its original resolution. But only if the image is smaller than two third of the screen. Not for mobiles.')
		);

		add_settings_field(
			'fileToLoad', // id
			'HTML signpost', // title
			array( $this, 'text' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('fileToLoad','URL of HTML file which will be loaded behind the gallery (usually a signpost to other galleries)')
		);		

		add_settings_field(
			'hint1', // id
			'Controls hint', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('hint1','')
		);

		add_settings_field(
			'hint2', // id
			'Mouse hint', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('hint2','')
		);

		add_settings_field(
			'hint3', // id
			'Touch hint', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('hint3','')
		);

		add_settings_field(
			'hintTouch', // id
			'Touch devices only', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('hintTouch','')
		);

		add_settings_field(
			'hintFS', // id
			'Fullscreen offer', // title
			array( $this, 'hintFS_callback' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section' // section
		);

		add_settings_field(
			'toTheTop', // id
			'Scroll to top', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('toTheTop','')
		);

		add_settings_field(
			'exitLink', // id
			'Gallery exit', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('exitLink','')
		);

		add_settings_field(
			'imageLink', // id
			'Image link', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('imageLink','')
		);

		add_settings_field(
			'copyButton', // id
			'Copy button', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('copyButton','')
		);

		add_settings_field(
			'linkPaste', // id
			'Paste hint', // title
			array( $this, 'trans' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('linkPaste','')
		);

		add_settings_field(
			'landscapeHint', // id
			'Landscape tip', // title
			array( $this, 'text' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('landscapeHint','<em>^icon^</em> or <em>:-)</em> is replaced with smartphone icon 📱 (due to back compatibility). Content inside &lt;span&gt; is rotated.')
		);

		add_settings_field(
			'showLandscapeHint', // id
			'Show tip', // title
			array( $this, 'check' ), // callback
			'story-show-gallery-admin', // page
			'story_show_gallery_setting_section', // section
			array('showLandscapeHint','in the portrait mode the gallery will suggest to turn phone into landscape mode')
		);
	}

	public function story_show_gallery_sanitize($input) {
		$sanitary_values = array();
		$keys = array('thumbnails3D', 'ssg_cdn', 'scrollDuration', 'theme', 'rightClickProtection', 'bgOpacity', 'socialShare', 'separateWpGalleries', 'crossCursor',
		'hideImgCaptions','fontSize','captionsSource', 'captionExif', 'globalAuthorCaption', 'sideCaptionforSmallerLandscapeImg', 'preferedCaptionLocation', 'hideThumbCaptions', 
		"imgBorderWidthX", "imgBorderWidthY", "imgBorderColor", "imgOutlineColor", "imgBorderRadius", "imgBorderShadow", "imgBorderLightFx",
		'wordpressGalleryFS', 'mobilePortraitFS', 'forceLandscapeMode', 'alwaysFullscreen', 'neverFullscreen',
		'watermarkImage', 'watermarkText', 'watermarkWidth', 'watermarkFontSize', "watermarkFontColor", 'watermarkOffsetX', 'watermarkOffsetY', 'watermarkOpacity', 
		'respectOtherWpGalleryPlugins', 'logIntoGA', 'showFirst3ImgsTogether', 'scaleLock1', 'enlargeImg', 'fileToLoad',		
		'hint1', 'hint2', 'hint3', 'hintTouch', 'hintFS', 'toTheTop', 'exitLink', 'imageLink', 'copyButton', 'linkPaste', 'landscapeHint', 'showLandscapeHint' );

		
		for ($i = 0; $i < sizeof($keys); $i++) {
			$key = $keys[$i];
			if ( isset( $input[ $key ] ) ) {
					$sanitary_values[ $key ] = str_replace("'", "&#039;", $input[ $key ]); 
			} else {
				$sanitary_values[ $key ] = 'false';
			}
		}

		if (! is_numeric($input['bgOpacity'])) {
			$sanitary_values[ 'bgOpacity' ] = 100;
		}
		if (! is_numeric($input['fontSize'])) {
			$sanitary_values[ 'fontSize' ] = 100;
		}

		return $sanitary_values;
	}

	public function story_show_gallery_section_info() {
		
	}



	public function theme_callback() {
		?> <select name="story_show_gallery_option_name[theme]" id="theme" class="compact">
			<?php $selected = (isset( $this->story_show_gallery_options['theme'] ) && $this->story_show_gallery_options['theme'] === 'dim') ? 'selected' : '' ; ?>
			<option value="dim" <?php echo $selected; ?>>Dim</option>
			<?php $selected = (isset( $this->story_show_gallery_options['theme'] ) && $this->story_show_gallery_options['theme'] === 'light') ? 'selected' : '' ; ?>
			<option value="light" <?php echo $selected; ?>>Light</option>
			<?php $selected = (isset( $this->story_show_gallery_options['theme'] ) && $this->story_show_gallery_options['theme'] === 'black') ? 'selected' : '' ; ?>
			<option value="black" <?php echo $selected; ?>>Black</option>
			<?php $selected = (isset( $this->story_show_gallery_options['theme'] ) && $this->story_show_gallery_options['theme'] === 'dark') ? 'selected' : '' ; ?>
			<option value="dark" <?php echo $selected; ?>>Dark</option>
		</select>
		<label for="theme">
		<a href="https://roman-flossler.github.io/StoryShowGallery/#themes" target="_blank"> comparison of visual themes  </a>
		</label>
		 <?php
	}

	
	public function captionsSource_callback() {
		?> <select name="story_show_gallery_option_name[captionsSource]" id="captionsSource" class="compact" style="width:23em">
			<?php $selected = (isset( $this->story_show_gallery_options['captionsSource'] ) && $this->story_show_gallery_options['captionsSource'] === 'caption') ? 'selected' : '' ; ?>
			<option value="caption" <?php echo $selected; ?>>caption field, then alternative text field</option>
			<?php $selected = (isset( $this->story_show_gallery_options['captionsSource'] ) && $this->story_show_gallery_options['captionsSource'] === 'alt') ? 'selected' : '' ; ?>
			<option value="alt" <?php echo $selected; ?>>only alternative text field</option>
		</select>		
		<label for="captionsSource">
		<a href="https://roman-flossler.github.io/StoryShowGallery/img/wp-captions.jpg" target="_blank">More info on a picture</a>
		</label>
		 <?php
	}

	public function captionExif_callback() {
		?> <select name="story_show_gallery_option_name[captionExif]" id="captionExif" class="compact" style="width:23em">
			<?php $selected = (isset( $this->story_show_gallery_options['captionExif'] ) && $this->story_show_gallery_options['captionExif'] === 'none') ? 'selected' : '' ; ?>
			<option value="none" <?php echo $selected; ?>>No EXIF</option>
			<?php $selected = (isset( $this->story_show_gallery_options['captionExif'] ) && $this->story_show_gallery_options['captionExif'] === 'standard') ? 'selected' : '' ; ?>
			<option value="standard" <?php echo $selected; ?>>Standard EXIF</option>
			<?php $selected = (isset( $this->story_show_gallery_options['captionExif'] ) && $this->story_show_gallery_options['captionExif'] === 'trim') ? 'selected' : '' ; ?>
			<option value="trim" <?php echo $selected; ?>>Reduced lens info to save space</option>
			<?php $selected = (isset( $this->story_show_gallery_options['captionExif'] ) && $this->story_show_gallery_options['captionExif'] === 'icon') ? 'selected' : '' ; ?>
			<option value="icon" <?php echo $selected; ?>>EXIF icon</option>
		</select>		
		<label for="captionExif">
		<div class="expander-box"><a href="#">EXIF will only show IF :</a><br>
		<ol>
		<li>Naturally, there has to be EXIF data inside a photo.</li>
		<li>Due to CORS policy, photos has to be on the same domain as whole website.<br>
		<em>Jetpack plugin: deactivate 'Speed up image load times' option, to let photos stay on your domain.</em></li>
		<li>Wordpress might strip EXIF when resize photos from large (original) size. <br>
		So the most safe is to resize photos in photo editor and set image size option in the gallery to "full size". </li>
		<ol>
		</div>
		</label>
		 <?php
	}

	public function hintFS_callback() {
		if (false == strpos($this->story_show_gallery_options['hintFS'], 'abbr')) {
			printf('<label for="hintFS"><em>You can update:&nbsp;&nbsp;&nbsp;</em> For a better experience &lt;br&gt;&lt;a&gt;&lt;abbr&gt;&#x239A;&lt;/abbr&gt; go full screen&lt;/a&gt;</label><br>');
		}

		printf(
			'<input class="large-text" type="text" name="story_show_gallery_option_name[hintFS]" id="hintFS" value="%s">',
			isset( $this->story_show_gallery_options['hintFS'] ) ? esc_attr( $this->story_show_gallery_options['hintFS']) : ''
		);
	}

}
if ( is_admin() )
	$story_show_gallery = new StoryShowGallery();


/* Add SSG script and style  */

add_action( 'wp_enqueue_scripts', 'ssg_scripts' );

function ssg_scripts() {
	$ssg_url = plugin_dir_url( __FILE__ );	
	$story_show_gallery_options = get_option( 'story_show_gallery_option_name' );	
	if ( $story_show_gallery_options == false ) {
		$story_show_gallery_options = array(
			"thumbnails3D" => "thumbnails3D",
			"ssg_cdn" => "",
			"wordpressGalleryFS" => "wordpressGalleryFS",
			"respectOtherWpGalleryPlugins" => "respectOtherWpGalleryPlugins",
			'showFirst3ImgsTogether' => 'false',
			'captionsSource' => 'caption',
			'captionExif' => 'none',
			'hideThumbCaptions' => 'false',
			"imgBorderWidthX" => "1",
			"imgBorderWidthY" => "1",			
			"imgBorderRadius" => "0.2",
			"imgBorderShadow" => "imgBorderShadow",
		);
	}

	if (function_exists('wp_add_inline_script')) {
		$footer_script = true;
	} else {
		$footer_script = false;
	}

	if ( 'none' !== $story_show_gallery_options[ 'captionExif' ] ) {
		wp_enqueue_script( 'exifr', 'https://cdn.jsdelivr.net/npm/exifr@7/dist/lite.umd.js', array( 'jquery' ), null, true );
	}

	if ( 'ssg_cdn' == $story_show_gallery_options[ 'ssg_cdn' ] ) {
		wp_enqueue_script( 'ssg-js', 'https://cdn.jsdelivr.net/npm/story-show-gallery@3/dist/ssg.min.js', array( 'jquery' ), null, $footer_script );
		wp_enqueue_style( 'ssg-style', 'https://cdn.jsdelivr.net/npm/story-show-gallery@3/dist/ssg.min.css', array(), null, 'screen' );
	} else {
		wp_enqueue_script( 'ssg-js', $ssg_url.'ssg-js/ssg.min.js', array( 'jquery' ), null, $footer_script );
		wp_enqueue_style( 'ssg-style', $ssg_url.'ssg-js/ssg.min.css', array(), null, 'screen' );
	}
	
	if ( 'thumbnails3D' == $story_show_gallery_options["thumbnails3D"] ) {
		wp_enqueue_style( 'ssg-thumb-style', $ssg_url.'ssg-js/3D-anim.min.css', array(), null, 'screen' );
	}

	if ( 'caption' == $story_show_gallery_options["captionsSource"] ) {
		$extractCaptions = "jQuery(document).ready(function(){!SSG.jQueryImgCollection&&SSG.beforeRun();var tit=[];SSG.jQueryImgCollection.each(function(index){tit[index]='none';jQuery(this).siblings('figcaption, p').each(function(sindex){if(sindex==0){tit[index]=jQuery(this).html().replace(/(<br>)|(<br\/>)|(<br \/>)/gi,' - ').replace(/(<[^>]*>)+/g,' ').trim()}});if(tit[index]=='none'){jQuery(this).parent().siblings().each(function(sindex){if(sindex==0){tit[index]=jQuery(this).html().replace(/(<br>)|(<br\/>)|(<br \/>)/gi,' - ').replace(/(<[^>]*>)+/g,' ').trim()}})}});SSG.jQueryImgCollection.each(function(index){if(tit[index]!='none'){jQuery(this).attr('data-caption',tit[index])}})});";
	} else {
		$extractCaptions ='';
	}

	if ( 'hideThumbCaptions' == $story_show_gallery_options["hideThumbCaptions"] ) {
		$hideCaptions = "jQuery( document ).ready( function () { jQuery('.wp-block-gallery figure figcaption').hide();jQuery('.gallery figure figcaption').hide();jQuery('.gallery dd.gallery-caption').hide();});";
	} else {
		$hideCaptions ='';
	}	

	$script = '';
	foreach($story_show_gallery_options as $key => $value) {
		if ($key == $value) {
			$final = 'true';
		} else if ( $value == "false") {
			$final = "false";
		} else if ( $key == "fileToLoad" && $value == "") {
			$final = "null";
		} else if ( $key == "landscapeHint") {
			$final = "'" . str_replace( array(":-)","^icon^"), "&#128241;",  $value) . "'";
		} else if ( $value == "") {
			$final = "''";
		} else {
			if( is_numeric($value)) {
				$final = $value;
			} else {
				$final = "'" . $value . "'";
			}
		}

		$script = $script . "SSG.cfg." . $key . " = " . $final . "; ";		
	}

	$script =  $script . "\n" . $extractCaptions . "\n" . $hideCaptions;

	if (function_exists('wp_add_inline_script')) {
		wp_add_inline_script( 'ssg-js', $script, 'after' );
	} else {
		$GLOBALS['inline_settings'] = $script;
	}
}

	/* For WP versions older than 4.5, which don't have wp_add_inline_script  */

	if (! function_exists('wp_add_inline_script')) {

		$inline_settings = '';
		add_action('wp_footer','add_inline_settings');

		function add_inline_settings() { 
			echo "<script type='text/javascript'>".$GLOBALS['inline_settings']."</script> \n";
		}
	}

    /* Admin styles  */

    add_action( 'admin_enqueue_scripts', 'ssg_add_admin_styles' );

    function ssg_add_admin_styles($hook) {
        $current_screen = get_current_screen();
        if ( false === strpos($current_screen->base, 'story-show-gallery') ) {
            return;
        } else {
            wp_enqueue_style( 'ssg_admin_css', plugins_url('css/ssg-admin.css',__FILE__ ) );
			wp_enqueue_script( 'admin-script',  plugins_url('css/ssg-admin.js',__FILE__ ), array( ), null, true );
        }	
    }

    /* Add settings link to plugin page  */

    add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), 'ssg_add_settings_link' );
    function ssg_add_settings_link( $links ) {
        $links[] = '<a href="' .
            admin_url( 'options-general.php?page=story-show-gallery' ) .
            '">' . __( 'Settings' ) . '</a>';
        return $links;
    }

    /* Set gallery shortcode to link to media file  */

    add_filter( 'shortcode_atts_gallery',
        function( $out ){
            $out['link'] = 'file'; 
            return $out;
        }
    );

?>
