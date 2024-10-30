<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<br /><br />
<div>
<p>
<strong>instructions:</strong>
<ul>
<li>Set Host, Username and Password of Serendipity database same as wordpress database. </li>
<li>Delete users of wordPress site excluding admin user.</li>
<li>To remove duplication of users ID in users table, please make unique ID of admin user ID in user table and user meta table which are not exists in SPIP users IDs.</li>
</ul>
</p>
</div>
<?php
wp_verify_nonce( $nonce, 'delete_post-' . $_REQUEST['post_id'] );
//Check form submition
if(isset($_POST['submit'])){
	//$my_nonce = sanitize_text_field($_REQUEST['my_nonce']); //9ecae76edc
	$serendipityDBname_DBPrefix = sanitize_text_field($_POST['serendipityDBname_DBPrefix']);
	$dbExplode 	= explode(".",$serendipityDBname_DBPrefix);
	$dbName 	= $dbExplode[0];
	$host 		= sanitize_text_field($_POST['host']);
	$port 		= sanitize_text_field($_POST['port']);
	$username 	= sanitize_text_field($_POST['username']);
	$password 	= sanitize_text_field($_POST['password']);
	
	if($serendipityDBname_DBPrefix != 'serendipityDBname.DBPrefix' ){
		// Check db
		// 1st Method - Declaring $wpdb as global and using it to execute an SQL query statement that returns a PHP object
		//WP DB Prefix
		$lwpdb = new wpdb( $username, $password, $dbName, $host );
		//$lwpdb->show_errors();
		//---------------------------------------------XXXXXX---------------------------------------------------------
		//Insert records into users meta
		$jUser = $lwpdb->get_results( $lwpdb->prepare( "SELECT
						u.authorid user_id,
						u.username user_login
						FROM ".$serendipityDBname_DBPrefix."_authors u
						ORDER BY u.authorid","",""));
		global $wpdb;
		$wpdb->show_errors();
		$wpPrefix = $wpdb->prefix;
		if($jUser){
			foreach($jUser as $jUserVal){
				//$this_id = $jUserVal->user_id."<br />";
				$user_login = $jUserVal->user_login;
				$password = $jUserVal->password;
				//Insert into users
				if($user_login){
					$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpPrefix."users ( user_login, user_pass, user_nicename, user_email, user_registered, user_status, display_name )
					VALUES ( '$user_login', '$password', '', '', '', '', '' )","",""));
					$this_id=$wpdb->insert_id;
				}
				
				if($this_id){
				$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'rich_editing', 'true' )","",""));
				//Insert comment shortcuts status
				$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'comment_shortcuts', 'false' )","",""));
				//Insert admin color
				$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'admin_color', 'fresh' )","",""));
				//Insert Nickname
				$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'use_ssl', 0 )","",""));
				//Insert show admin bar front status
				$wpdb->query( $wpdb->prepare( "INSERT INTO ".$wpPrefix."usermeta ( user_id, meta_key, meta_value ) VALUES ( '$this_id', 'show_admin_bar_front', 'true' )","",""));
				}
				$i++;
				}
			echo '<span style="color:green;">Users has been inserted successfully. !!! ENJOY !!!</span>';
			}
		}else{
			echo '<span style="color:red;">Error establishing a database connection. </span>';
		}
}else{
$serendipityDBname_DBPrefix='serendipityDBname.DBPrefix';
}
?>
<form method="post">
<table>
<tr><th>Insert serendipity database name with prefix</th></tr>
<tr><th><span style="color:red;"> (ex - serendipityDBname.DBPrefix) *</span></th><td><input type="text" name="serendipityDBname_DBPrefix" id="serendipityDBname_DBPrefix" onfocus="this.value=='serendipityDBname.DBPrefix'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='serendipityDBname.DBPrefix':this.value=this.value;" value="<?php if(isset($serendipityDBname_DBPrefix)) { echo $serendipityDBname_DBPrefix; } ?>" maxlength="100" size="30px;"></td></tr>
<tr><th>Hostname <span style="color:red;">*</span></th><td><input type="text" name="host" id="host" onfocus="this.value=='Hostname'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Hostname':this.value=this.value;" value="<?php if(isset($host)) { echo $host; } ?>" maxlength="100" size="30px;"></td></tr>
<tr><th>Port <span style="color:red;">*</span></th><td><input type="text" name="port" id="port" onfocus="this.value=='Port'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Port':this.value=this.value;" value="<?php if(isset($port)) { echo $port; } ?>" maxlength="100" size="30px;"></td></tr>
<tr><th>Username <span style="color:red;">*</span></th><td><input type="text" name="username" id="username" onfocus="this.value=='Username'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Username':this.value=this.value;" value="<?php if(isset($username)) { echo $username; } ?>" maxlength="100" size="30px;"></td></tr>
<tr><th>Password <span style="color:red;">*</span></th><td><input type="password" name="password" id="password" onfocus="this.value=='Password'?this.value='':this.value=this.value;" onblur="this.value==''?this.value='Password':this.value=this.value;" value="<?php if(isset($password)) { echo $password; } ?>" maxlength="100" size="30px;"></td></tr>
<tr><td>&nbsp;</td><td><input type="submit" name="submit"></td></tr>
</tr>
</table>
</form><font face="Arial, Helvetica, sans-serif"></font>

