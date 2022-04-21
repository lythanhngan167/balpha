<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
$colNum = 5;
$cols = array(
    '215px',
    '120px',
    '60px',
    '80px',
    '80px'
);
if (!function_exists('colPaddingHeader')) {
    function colPaddingHeader()
    {
        return '<td style=""></td>';
    }
}
if (!function_exists('verticalPaddingHeader')) {
    function verticalPaddingHeader($colNum)
    {
        return '<tr><td colspan="' . ($colNum * 3 - 2) . '" style="line-height: 1px;" ></td></tr>';
    }
}
if (!function_exists('verticalPaddingRow')) {
    function verticalPaddingRow($colNum, $border = false)
    {
        $lineHeight = $border ? '2px' : '3px';
        $border = $border ? '<div style="border-bottom: 1px solid #ddd;"></div>' : '';
        return '<tr><td colspan="' . ($colNum * 3 - 2) . '" style="line-height: ' . $lineHeight . ';" >' . $border . '</td></tr>';
    }
}
if (!function_exists('colPaddingRow')) {
    function colPaddingRow()
    {
        return '<td></td>';
    }
}
if (!function_exists('colTemplate')) {
    function colTemplate($width)
    {
        return '<td style="width: ' . $width . '; height: 1px;"></td>';
    }
}
if (!function_exists('colSize')) {
    function colSize($cols)
    {
        $html = '';
        $html .= '<tr>';
        $numCol = count($cols) - 1;
        foreach ($cols as $k => $col) {
            if ($k > 0) {
                $html .= colTemplate('10px');
            }

            $html .= colTemplate($col);
            if ($k != $numCol) {
                $html .= colTemplate('10x');
            }

        }
        $html .= '</tr>';
        return $html;
    }
}
?>
<div style="background-color: #CDDDDD; line-height: 8px;"> Chi tiết đơn hàng</div>
<table width="100%">
    <?php echo colSize($cols); ?>
    <tr>
        <td style=" text-align: left; "><strong><?php echo JText::_('ESHOP_PRODUCT_NAME'); ?></strong>
        </td>
        <?php echo colPaddingHeader(); ?>
        <?php echo colPaddingHeader(); ?>
        <td style="text-align: left; ">
            <strong><?php echo JText::_('ESHOP_MODEL'); ?></strong>
        </td>
        <?php echo colPaddingHeader(); ?>
        <?php echo colPaddingHeader(); ?>
        <td style="text-align: right ;">
            <strong><?php echo JText::_('ESHOP_QUANTITY'); ?></strong>
        </td>
        <?php echo colPaddingHeader(); ?>
        <?php echo colPaddingHeader(); ?>
        <td style=" text-align: right; ">
            <strong><?php echo JText::_('ESHOP_UNIT_PRICE'); ?></strong>
        </td>
        <?php echo colPaddingHeader(); ?>
        <?php echo colPaddingHeader(); ?>
        <td style="text-align: right;">
            <strong><?php echo JText::_('ESHOP_TOTAL'); ?></strong>
        </td>
    </tr>
    <?php echo verticalPaddingRow($colNum, true); ?>
    <?php
    foreach ($orderProducts as $k => $product) {
        $options = $product->options;
        ?>
        <tr>
            <td style="text-align: left;"><?php
                echo ($k+ 1).'. '.$product->product_name;
                for ($i = 0; $n = count($options), $i < $n; $i++) {
                    echo '<br />- ' . $options[$i]->option_name . ': ' . $options[$i]->option_value . (isset($options[$i]->sku) && $options[$i]->sku != '' ? ' (' . $options[$i]->sku . ')' : '');
                }
                ?>

            </td>
            <?php echo colPaddingRow(); ?>
            <?php echo colPaddingRow(); ?>
            <td style="text-align: left;">
                <?php echo $product->product_sku; ?>
            </td>
            <?php echo colPaddingRow(); ?>
            <?php echo colPaddingRow(); ?>
            <td style="text-align: right">
                <?php echo $product->quantity; ?> X  <?php echo $product->unit; ?>
            </td>
            <?php echo colPaddingRow(); ?>
            <?php echo colPaddingRow(); ?>
            <td style="text-align: right">
                <?php echo $product->price; ?>
            </td>
            <?php echo colPaddingRow(); ?>
            <?php echo colPaddingRow(); ?>
            <td style="text-align: right">
                <?php echo $product->total_price; ?>
            </td>
        </tr>
        <?php echo verticalPaddingRow($colNum, true); ?>
        <?php
    }
    $colspan = $colNum * 3 - 5;
    foreach ($orderTotals as $orderTotal) {
        ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" style="text-align: right;">
                <?php echo JText::_($orderTotal->title);
                ?>:
            </td>
            <?php echo colPaddingRow(); ?>
            <?php echo colPaddingRow(); ?>
            <td style="text-align: right;">
                <strong><?php echo $orderTotal->text;
                    ?></strong>
            </td>
        </tr>
        <?php echo verticalPaddingRow($colNum); ?>
        <?php
    }
    ?>
</table>
