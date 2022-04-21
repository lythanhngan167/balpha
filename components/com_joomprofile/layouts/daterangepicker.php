<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$fieldId = $displayData['field_id'];
?>
<script>
    (function($) {
        $(document).ready(function(){
            $('#<?php echo $fieldId;?>').daterangepicker({
                autoUpdateInput: false,
                ranges: {
                    '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_TODAY');?>': [moment(), moment()],
                    '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_YESTERDAY');?>': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '<?php echo JText::sprintf('JOOMPROFILE_DATERANGEPICKER_LAST_N_DAYS', 7);?>': [moment().subtract(6, 'days'), moment()],
                    '<?php echo JText::sprintf('JOOMPROFILE_DATERANGEPICKER_LAST_N_DAYS', 30);?>': [moment().subtract(29, 'days'), moment()],
                    '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_THIS_MONTH');?>': [moment().startOf('month'), moment().endOf('month')],
                    '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_LAST_MONTH');?>': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                showDropdowns: true,
                format: 'YYYY-MM-DD',
                separator: ':',
                locale: {
                    applyLabel: '<?php echo JText::_('JSUBMIT');?>',
                    fromLabel: '<?php echo JText::_('JOOMPROFILE_FROM');?>',
                    toLabel: '<?php echo JText::_('JOOMPROFILE_TO');?>',
                    customRangeLabel: '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_CUSTOM_RANGE');?>',
                    daysOfWeek: ['<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_SU');?>',
                        '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_MO');?>',
                        '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_TU');?>',
                        '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_WE');?>',
                        '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_TH');?>',
                        '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_FR');?>',
                        '<?php echo JText::_('JOOMPROFILE_DATERANGEPICKER_SA');?>'
                    ],
                    monthNames: ['<?php echo JText::_('JANUARY');?>',
                        '<?php echo JText::_('FEBRUARY');?>',
                        '<?php echo JText::_('MARCH');?>',
                        '<?php echo JText::_('APRIL');?>',
                        '<?php echo JText::_('May');?>',
                        '<?php echo JText::_('JUNE');?>',
                        '<?php echo JText::_('JULY');?>',
                        '<?php echo JText::_('AUGUST');?>',
                        '<?php echo JText::_('SEPTEMBER');?>',
                        '<?php echo JText::_('OCTOBER');?>',
                        '<?php echo JText::_('NOVEMBER');?>',
                        '<?php echo JText::_('DECEMBER');?>'],
                    firstDay: 1
                },
            });

            $('#<?php echo $fieldId;?>').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ':' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('#<?php echo $fieldId;?>').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });
        });
    })(joomprofile.jQuery);
</script>
<?php
