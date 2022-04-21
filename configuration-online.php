<?php
class JConfig {
	public $offline = '0';
	public $offline_message = 'Trang web này đang được bảo trì.</br>Xin quay trở lại sau. ';
	public $display_offline_message = '1';
	public $offline_image = '';
	public $sitename = 'Biznet';
	public $editor = 'tinymce';
	public $captcha = '0';
	public $list_limit = '20';
	public $access = '1';
	public $debug = '0';
	public $debug_lang = '0';
	public $debug_lang_const = '1';
	public $dbtype = 'mysqli';
	public $host = 'localhost';
	public $user = 'bizn_biznet_user';
	public $password = 'Biznet123456!!!';
	public $db = 'bizn_biznet_db';
	public $dbprefix = 'wmspj_';
	public $live_site = '';
	public $secret = 'jlOf06ZKyOA04aDy';
	public $gzip = '0';
	public $error_reporting = 'none';
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
	public $smtppass = 'Bizappco123!';
	public $smtphost = 'mail.bizappco.com';
	public $smtpsecure = 'none';
	public $smtpport = '587';
	public $caching = '0';
	public $cache_handler = 'file';
	public $cachetime = '15';
	public $cache_platformprefix = '0';
	public $MetaDesc = 'Biznet - là website tư vấn bảo hiểm trực tuyến hàng đầu tại Việt Nam, chuyên so sánh các sản phẩm bảo hiểm và kết nối khách hàng với chuyên gia tư vấn trên toàn quốc.';
	public $MetaKeys = 'bảo hiểm nhân thọ, bảo hiểm phi nhân thọ, bảo hiểm sức khoẻ, bảo hiểm ô tô, bảo hiểm bệnh hiểm nghèo, bảo hiểm du lịch';
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
	public $log_path = '/home/biznet.com.vn/public_html/administrator/logs';
	public $tmp_path = '/home/biznet.com.vn/public_html/tmp';
	public $lifetime = '1000';
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
	public $force_ssl = '2';
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
	public $landingpage_link = 'http://bcavietnam.com';
	public $enable_agency_signup = '0';
	public $urlPostAccessTrade="https://api.accesstrade.vn/v1/postbacks/conversions";
	public $accesstradeKey = 'gItPJVeK72lR15FS6qkvz8RX0DWoG3m3';
	public $numberDayConfirmSuccessAT = 7; // 7 ngày
	public $numberDataLevel1 = '0-10'; // Level: 1 0-10 data
	public $numberDataLevel2 = '11-15'; // Level 2: 11-15 data
	public $numberDataLevel3 = '16-10000'; // Level 3: 16 tro len
	public $numberDayToConfirmDataBiznet = 7; // 7 ngày
}