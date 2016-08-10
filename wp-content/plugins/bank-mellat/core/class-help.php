<?php
class help{
	
	public function __construct(){

		add_action( 'admin_menu', array( &$this, 'adminMenu' ) );
		
	}
	
	public function adminMenu(){
		
		add_submenu_page( 'bank-mellat', __( 'راهنما افزونه پرداخت آنلاین', 'WPBEGPAY' ), __( 'راهنما', 'WPBEGPAY' ), 'manage_options', 'WPBEGPAY-help', array( &$this, 'help' ) );
	}
	
	/**
	 * Orders page
	 *
	 * @since 2.7
	 */
	public function help() {
		
		if(isset($_GET['update']))
			if($_GET['update'] == 'true')
				$this->update();
?>
		<div class="wrap">
			<h2>
				<?php _e( 'راهنما افزونه پرداخت آنلاین', 'WPBEGPAY' ); ?>
			</h2>
			
			<?php echo sprintf('<a class="button" href="%s">%s</a></br></br>', admin_url( 'admin.php?page=WPBEGPAY-help&update=true', 'http' ), 'بروزرسانی آموزش ها'); ?>
			
			<div class="accordion">
				<?php
					$helpFile = plugin_dir_path( __FILE__ ) . "help.json";

					if (!file_exists($helpFile))
						die("پرونده آموزش وجود ندارد!");
					
					$helpContent = file_get_contents($helpFile);

					$helps = json_decode($helpContent, true);
										
					$counter = 1;
					
					foreach($helps['HELPS'] as $help){
						
						echo '<div class="accordion-section">
							<a class="accordion-section-title" href="#accordion-'. $counter .'">'. base64_decode($help["title"]) .'</a>
							<div id="accordion-'. $counter .'" class="accordion-section-content">
								<p>'. base64_decode($help["content"]) .'</p>
							</div><!--end .accordion-section-content-->
						</div><!--end .accordion-section-->';
						
						$counter++;
					}
				
				
				
				?>
		</div>

<?php
	
	}
	
	private function update(){
		
		$helpUrl = "http://help.pay-system.ir/?page_id=2&cat=Wordpress%20free%20plugin";
		
		if(!$this->isDomainAvailible($helpUrl))
			return;
		
		$helpContent = file_get_contents($helpUrl);
		
		$helpFile = plugin_dir_path( __FILE__ ) . "help.json";
		
		if (file_exists($helpFile))
			unlink($helpFile);
		
		file_put_contents($helpFile, $helpContent);
		
	}
	
    private function isDomainAvailible($domain){
		
	   //check, if a valid url is provided
	   if(!filter_var($domain, FILTER_VALIDATE_URL)){
		   return false;
	   }

	   //initialize curl
	   $curlInit = curl_init($domain);
	   curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
	   curl_setopt($curlInit,CURLOPT_HEADER,true);
	   curl_setopt($curlInit,CURLOPT_NOBODY,true);
	   curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

	   //get answer
	   $response = curl_exec($curlInit);

	   curl_close($curlInit);

	   if ($response) return true;

	   return false;
    }
}
?>