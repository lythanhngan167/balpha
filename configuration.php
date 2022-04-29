<?php
class JConfig {
	public $offline = '0';
	public $offline_message = 'Trang web này đang được bảo trì.</br>Xin quay trở lại sau.';
	public $display_offline_message = '1';
	public $offline_image = '';
	public $sitename = 'B-Alpha';
	public $editor = 'tinymce';
	public $captcha = '0';
	public $list_limit = '20';
	public $access = '1';
	public $debug = '0';
	public $debug_lang = '0';
	public $debug_lang_const = '1';
	public $dbtype = 'mysqli';
	public $host = 'localhost';
	public $user = 'root';
	public $password = 'root';
	public $db = 'balpha2';
	public $dbprefix = 'wmspj_';
	public $live_site = '';
	public $secret = 'jlOf06ZKyOA04aDy';
	public $gzip = '0';
	public $error_reporting = 'default';
	public $helpurl = 'https://help.joomla.org/proxy?keyref=Help{major}{minor}:{keyref}&lang={langcode}';
	public $ftp_host = '';
	public $ftp_port = '';
	public $ftp_user = '';
	public $ftp_pass = '';
	public $ftp_root = '';
	public $ftp_enable = '0';
	public $offset = 'Asia/Ho_Chi_Minh';
	public $mailonline = '1';
	public $mailer = 'smtp';
	public $mailfrom = 'no-reply@bizappco.com';
	public $fromname = 'Biznet';
	public $sendmail = '/usr/sbin/sendmail';
	public $smtpauth = '1';
	public $smtpuser = 'no-reply@bizappco.com';
	public $smtppass = '';
	public $smtphost = 'mail.bizappco.com';
	public $smtpsecure = 'none';
	public $smtpport = '587';
	public $caching = '0';
	public $cache_handler = 'file';
	public $cachetime = '15';
	public $cache_platformprefix = '0';
	public $MetaDesc = 'B-Alpha là đơn vị tiên phong áp dụng giải pháp công nghệ 4.0 hỗ trợ tư vấn viên tìm kiếm khách hàng tiềm năng và để khách hàng yên tâm tối đa khi mua các sản phẩm bảo hiểm.';
	public $MetaKeys = 'bảo hiểm, b-alpha, datacenter, b-alpha insurance, bảo hiểm công nghệ 4.0, bảo hiểm công  nghệ , data center b-alpha, datacenter bảo hiểm, bảo hiểm b-alpha là gì, tuyển dụng b-alpha, tuyển dụng bảo hiểm b-alpha, bao hiểm 4.0, bao hiem cong nghe';
	public $MetaTitle = '1';
	public $MetaAuthor = '1';
	public $MetaVersion = '0';
	public $robots = '';
	public $sef = '1';
	public $sef_rewrite = '1';
	public $sef_suffix = '1';
	public $unicodeslugs = '0';
	public $feed_limit = '10';
	public $feed_email = 'none';
	public $log_path = '/Applications/MAMP/htdocs/bcavietnam2/administrator/logs';
	public $tmp_path = '/Applications/MAMP/htdocs/bcavietnam2/tmp';
	public $lifetime = '5000';
	public $session_handler = 'database';
	public $shared_session = '0';
	public $memcache_persist = '1';
	public $memcache_compress = '0';
	public $memcache_server_host = 'localhost';
	public $memcache_server_port = '11211';
	public $memcached_persist = '1';
	public $memcached_compress = '0';
	public $memcached_server_host = 'localhost';
	public $memcached_server_port = '11211';
	public $redis_persist = '1';
	public $redis_server_host = 'localhost';
	public $redis_server_port = '6379';
	public $redis_server_auth = '';
	public $redis_server_db = '0';
	public $proxy_enable = '0';
	public $proxy_host = '';
	public $proxy_port = '';
	public $proxy_user = '';
	public $proxy_pass = '';
	public $massmailoff = '0';
	public $replyto = '';
	public $replytoname = '';
	public $MetaRights = '';
	public $sitename_pagetitles = '0';
	public $force_ssl = '0';
	public $session_memcache_server_host = 'localhost';
	public $session_memcache_server_port = '11211';
	public $session_memcached_server_host = 'localhost';
	public $session_memcached_server_port = '11211';
	public $session_redis_persist = '1';
	public $session_redis_server_host = 'localhost';
	public $session_redis_server_port = '6379';
	public $session_redis_server_auth = '';
	public $session_redis_server_db = '0';
	public $frontediting = '1';
	public $cookie_domain = '';
	public $cookie_path = '';
	public $asset_id = '1';
	public $sms_enable = '1';
	public $enable_service_menu_signup = '1';
	public $landingpage_link = 'http://b-alpha.vn';
	public $enable_agency_signup = '0';
	public $urlPostAccessTrade = 'https://api.accesstrade.vn/v1/postbacks/conversions';
	public $accesstradeKey = 'gItPJVeK72lR15FS6qkvz8RX0DWoG3m3';
	public $erp_test = '0';
	public $numberDayConfirmSuccessAT = '7';
	public $numberDataLevel1 = '0-20';
	public $numberDataLevel2 = '21-25';
	public $numberDataLevel3 = '26-10000';
	public $numberDayToConfirmDataBiznet = '45';
	public $project_setup_level = array("level_1" => array("0" => "21", "1" => "27", "2" => "32"), "level_2" => array("0" => "21", "1" => "27", "2" => "32", "3" => "35", "4" => "36"), "level_3" => array("0" => "21", "1" => "27", "2" => "32", "3" => "35", "4" => "36"), "level_4" => array(), "level_5" => array());
	public $showed_cat_k2 = array("0" => "86");
	public $showed_top_item = array("0" => "4", "1" => "5", "2" => "40", "3" => "41", "4" => "42", "5" => "43", "6" => "44", "7" => "45", "8" => "46", "9" => "100");
	public $categories_post_send_notification = array("0" => "3", "1" => "86", "2" => "78", "3" => "8", "4" => "107", "5" => "111");
	public $categories_post_send_app_notification = array("0" => "3", "1" => "86");
	public $categories_faq = array(
	"0" => "90",
	"1" => "91",
	"2" => "93",
	"3" => "94",
	"4" => "95",
	"5" => "96");
}
