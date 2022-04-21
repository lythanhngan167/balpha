<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('formbehavior.chosen', 'select');
?>
<style>
    .bg-warning {
        background-color: orange;
        padding: 5px;
        color: white;
    }

    .bg-danger {
        background-color: red;
        padding: 5px;
        color: white;
    }

    .bg-success {
        background-color: green;
        padding: 5px;
        color: white;
    }

    .bg-primary {
        background-color: blue;
        padding: 5px;
        color: white;
    }

    /*#toolbar-download, #toolbar-edit, #toolbar-delete, .nav-tabs {
        display: none;
    }*/

    .report h3, .report h4 {
        text-align: center;
    }

    .report span {
        color: red;
    }

    .report ul {
        padding: 0;
        margin: 0;
        text-align: center;
    }

    .report ul li {
        list-style: none;
        padding: 5px 0;
        margin: 0 5px 15px 5px;
        display: inline-block;
        width: 23%;
        border: 1px solid #c6c6c6;
    }

    .report p {
        margin: 0;
    }
    .price {
        color: red;
    }
</style>

<?php
//https://longbill.github.io/jquery-date-range-picker/
$document = JFactory::getDocument();
$document->addScript('/administrator/components/com_eshop/assets/js/moment.js');
$document->addScript('/administrator/components/com_eshop/assets/js/jquery.daterangepicker.min.js');

$document->addStyleSheet('/administrator/components/com_eshop/assets/css/daterangepicker.css');
?>
<?php
$input = JFactory::getApplication()->input;
$datarange = $input->get('daterange', '', 'STRING');
if ($datarange == '') {
    $datarange = date('d/m/Y') . ' - ' . date('d/m/Y');
}
?>


<form action="index.php?option=com_eshop&view=productreports" method="post" name="adminForm" id="adminForm">
    <div id="j-main-container">
        <p>
            <?php
            echo 'Ngày đặt hàng :' . $datarange;
            ?>
        </p>
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search"
                       class="element-invisible"><?php echo JText::_('ESHOP_FILTER_SEARCH_ORDERS_DESC'); ?></label>
                <input type="text" name="search" id="filter_search"
                       placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                       value="<?php echo $this->escape($this->state->search); ?>" class="hasTooltip"
                       title="<?php echo JHtml::tooltipText('ESHOP_SEARCH_ORDERS_DESC'); ?>"/>
            </div>

            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible">Ngày đặt hàng:</label>
                <input type="text" name="daterange" id="daterange" value="<?= $datarange ?>"
                       placeholder="Ngày đặt hàng"/>
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="Hôm nay"
                        onclick="document.getElementById('filter_search').value=''; document.getElementById('daterange').value='<?= $this->getToday(); ?>'; if(document.getElementById('task')){document.getElementById('task').value='';} this.form.submit();">
                    <span>Hôm nay</span></button>
                <button type="submit" class="btn hasTooltip" title="Hôm qua"
                        onclick="document.getElementById('filter_search').value=''; document.getElementById('daterange').value='<?= $this->getYesterday(); ?>'; if(document.getElementById('task')){document.getElementById('task').value='';} this.form.submit();">
                    <span>Hôm qua</span></button>


                <button type="submit" class="btn hasTooltip"
                        title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><span
                            class="icon-search"></span></button>
                <button type="button" class="btn hasTooltip"
                        title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
                        onclick="document.getElementById('filter_search').value=''; document.getElementById('daterange').value=''; if(document.getElementById('task')){document.getElementById('task').value='';} this.form.submit();"><span
                            class="icon-remove"></span></button>
            </div>
            <div class="btn-group pull-right hidden-phone">

            </div>
        </div>
        <div class="clearfix"></div>


        <table class="adminlist table table-striped">
            <thead>
            <tr>

                <th width="5%" class="text_left">
                    #
                </th>
                <th width="10%" class="text_center">
                    <?php echo JHtml::_('grid.sort', 'Tên sản phẩm', '`op`.product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_left" width="20%">
                    <?php echo JHtml::_('grid.sort', 'Mã sản phẩm', '`op`.product_sku', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo 'Đơn giá'; ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo 'Số lượng'; ?>
                </th>
                <th class="text_center" width="10%">
                    <?php echo 'Tổng tiền'; ?>
                </th>

            </tr>
            </thead>
            <tbody>
            <?php
            $k = 0;
            $total = 0;
            for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                $row = &$this->items[$i];
                $link = JRoute::_('index.php?option=com_eshop&task=order.edit&cid[]=' . $row->id . '&page=stockreports');
                $checked = JHtml::_('grid.id', $i, $row->id);
                ?>
                <tr class="<?php echo "row$k"; ?>">

                    <td class="">

                        <?php echo $i + 1; ?>

                    </td>
                    <td class="">
                        <?php echo $row->product_name; ?>
                    </td>
                    <td class="text_left">
                        <?php echo $row->product_sku; ?>
                    </td>
                    <td class="text_center">
                        <span class="price"> <?php echo $this->currency->format($row->price, EshopHelper::getConfigValue('default_currency_code')); ?></span> / <?php echo $row->weight_name; ?>
                    </td>
                    <td class="text_center">

                        <?php echo $row->num; ?>

                    </td>

                    <td class="text_center">
                        <span class="price"><?php echo $this->currency->format($row->price * $row->num, EshopHelper::getConfigValue('default_currency_code')); ?></span>
                    </td>

                </tr>
                <?php
                $k = 1 - $k;
                $total += $row->price * $row->num;
            }
            ?>
            <tr class="<?php echo "row$k"; ?>">
                <td colspan="5"></td>
                <td class="text_center"><span class="price"><?php echo $this->currency->format($total, EshopHelper::getConfigValue('default_currency_code')); ?></span></td>
            </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

<script>
    var jq = jQuery.noConflict();
    jq(function ($) {


        $('input[name="daterange"]').dateRangePicker({
            startOfWeek: 'monday',
            separator: ' - ',
            format: 'DD/MM/YYYY',
            autoClose: true,
            maxDays: 1,
            minDays: 1,
            time: {
                enabled: false
            }
        }).bind('datepicker-apply', function (event, obj) {
            /* This event will be triggered when user clicks on the apply button */
            console.log(obj);
        })
            .bind('datepicker-close', function () {
                /* This event will be triggered before date range picker close animation */
                console.log('before close');
            });


    });

</script>
