<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

$expire_date =  date("Y-m-d H:i:s",strtotime("-".EXPIRED_DATA_CUSTOMER." months"));
//echo $expire_date;

?>
<button class="btn btn-warning" id="delete-expired-data" onclick="deleteExpiredData()" type="button">Xóa Data hết hạn</button>

<script type="text/javascript">
function deleteExpiredData(){
	var r = confirm("Bạn có chắc muốn xóa Data hết hạn <?php echo EXPIRED_DATA_CUSTOMER; ?> tháng?");
	if (r == true) {
		jQuery.ajax({
        url: "<?php echo JUri::base(); ?>index.php?option=com_customer&task=customers.deleteExpiredData",
        type : "POST",
        dataType:"text",
        success: function (result) {
            if (result == '1') {
                alert("Xóa Data hết hạn <?php echo EXPIRED_DATA_CUSTOMER; ?> tháng thành công!");
								location.reload();
                return;
            }
            if (result == '0') {
                alert("Xóa Data hết hạn không thành công, vui lòng thử lại!");
                location.reload();
                return;
            }

        }
    });
	}
}
</script>
