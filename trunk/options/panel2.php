<?php
// Avoid direct access to this piece of code
if (strpos($_SERVER['SCRIPT_FILENAME'], basename(__FILE__))){
	header('Location: /');
	exit;
}
$subscribe_plugin_url = is_ssl()?str_replace('http://', 'https://', WP_PLUGIN_URL):WP_PLUGIN_URL;

$sql = "SELECT DATE_FORMAT(`dt`,'%d/%m/%Y') s_day, COUNT(*) s_count
	FROM $wp_subscribe_reloaded->table_subscriptions
	GROUP BY s_day ASC";
	
	echo $sql;

$subscribe_result_set = $wpdb->get_results($sql, ARRAY_A);

// generate xml - $subscribe_xml
?>

<div class="metabox-holder wide" style="width:770px">
	<div class="postbox">
		<h3><?php 
			if (!$day_filter_active){
				_e( 'Pageviews by day - Click on a day for hourly metrics', 'wp-slimstat-view' ); 
			}
			else{
				_e( 'Pageviews by hour', 'wp-slimstat-view' ); 
			}
			?></h3>
		<?php 
		if (empty($subscribe_result_set)){ ?>
			<p class="nodata"><?php _e('No data to display','wp-slimstat-view') ?></p>
		<?php } else { ?>
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="765" height="170">
         <param name="movie" value="<?php echo $subscribe_plugin_url ?>/subscribe-to-comments-reloaded/options/swf/fcf.swf" />
         <param name="FlashVars" value="&dataXML=<?php echo $subscribe_xml ?>&chartWidth=765&chartHeight=170">
         <param name="quality" value="high" />
         <embed src="<?php echo $subscribe_plugin_url ?>/subscribe-to-comments-reloaded/options/swf/fcf.swf" flashVars="&dataXML=<?php echo $subscribe_xml ?>&chartWidth=765&chartHeight=170" quality="high" width="765" height="170" name="line" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		</object>
		<?php } ?>
	</div>
</div>

