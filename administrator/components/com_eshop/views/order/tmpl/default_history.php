<div class="order-history">Lịch sử</div>
<!-- Timeline -->
<div class="timeline">

    <!-- Line component -->
    <div class="line text-muted"></div>


    <!-- Panel -->
    <article class="panel panel-primary">

        <!-- Icon -->
        <div class="big_icon">
            <svg class="svg-next-icon svg-next-icon-size-40" width="40" height="40">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                    <g id="Layer_2" data-name="Layer 2">
                        <g id="Layer_1-2" data-name="Layer 1">
                            <path xmlns="http://www.w3.org/2000/svg" fill="#3c94d1"
                                  d="M32,63.5A31.5,31.5,0,1,1,63.5,32,31.54,31.54,0,0,1,32,63.5Z"></path>
                            <path xmlns="http://www.w3.org/2000/svg" fill="#fff"
                                  d="M32,1A31,31,0,1,1,1,32,31,31,0,0,1,32,1m0-1A32,32,0,1,0,64,32,32,32,0,0,0,32,0Z"></path>
                            <path xmlns="http://www.w3.org/2000/svg" fill="#fff"
                                  d="M32,7.8A11.94,11.94,0,1,1,20.06,19.75,11.93,11.93,0,0,1,32,7.8Z"></path>
                            <path xmlns="http://www.w3.org/2000/svg" fill="#fff"
                                  d="M32,59.34A28.67,28.67,0,0,1,8.11,46.52C8.23,38.59,24,34.25,32,34.25s23.77,4.34,23.89,12.26A28.67,28.67,0,0,1,32,59.34Z"></path>
                        </g>
                    </g>
                </svg>
            </svg>
        </div>
        <!-- /Icon -->
        <!-- Heading -->
        <div class="panel-heading">
            <div class="input_box">
                <input class="next-input" name="message" placeholder="Thêm nội dung ghi chú" value="">

            </div>
            <button type="button" id="sendMessage" class="add_message">
                <span><svg class="svg-next-icon svg-next-icon-size-16" width="32" height="32"><svg width="32"
                                                                                                   height="32"
                                                                                                   viewBox="0 0 16 16"
                                                                                                   xmlns="http://www.w3.org/2000/svg"><path
                                    fill-rule="evenodd" clip-rule="evenodd"
                                    d="M1 6.66667L1.00667 2L15 8L1.00667 14L1 9.33333L11 8L1 6.66667ZM2.34 4.02L7.34667 6.16667L2.33333 5.5L2.34 4.02ZM7.34 9.83333L2.33333 11.98V10.5L7.34 9.83333Z"
                                    fill="white"></path></svg></svg></span>
            </button>
        </div>
        <!-- /Heading -->


    </article>
    <!-- /Panel -->

    <div id="historyList">
        <?= EshopHelper::orderHistory($this->item->id)?>
    </div>

</div>
<!-- /Timeline -->
<script type="text/javascript">
    jQuery(document).ready(function ($){
        var component = 'com_eshop';
        var orderId = $('input[name="cid[]"]').val();
        $('#sendMessage').click(function () {
            var message = $('input[name="message"]').val();
            if ($.trim(message) != '') {
                $.ajax({
                    url: 'index.php?option=' + component + '&task=order.trackHistoryAjax',
                    data: 'id=' + orderId + '&message=' + message,
                    dataType: 'json',
                    beforeSend: function () {
                        //billingForm.find('#payment_zone_id').after('<span class="wait">&nbsp;<img src="/administrator/components/com_eshop/assets/images/loading.gif" alt="" /></span>');
                    },
                    complete: function () {
                        //$('.wait').remove();
                    },
                    success: function (json) {
                        if(json['error'] == false){
                            $('#historyList').empty().html(json['html']);
                            $('input[name="message"]').val('');
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                })
            }
        });
    });
</script>
