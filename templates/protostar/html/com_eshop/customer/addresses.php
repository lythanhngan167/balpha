<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage    EShop
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

$language = JFactory::getLanguage();
$tag = $language->getTag();
$bootstrapHelper = $this->bootstrapHelper;
$rowFluidClass = $bootstrapHelper->getClassMapping('row');
$pullLeftClass = $bootstrapHelper->getClassMapping('pull-left');
$pullRightClass = $bootstrapHelper->getClassMapping('pull-right');
$btnClass = $bootstrapHelper->getClassMapping('btn');

if (!$tag) {
    $tag = 'en-GB';
}
?>
<div class="customer-box address-page">
    <div class="customer-box__content">
        <div class="customer-box__head">
            <h1 class="customer-box__head--title"><?php echo JText::_('ESHOP_ADDRESS_HISTORY'); ?></h1>
        </div>
        <?php
        if (isset($this->success)) {
            ?>
            <div class="success"><?php echo $this->success; ?></div>
            <?php
        }
        if (isset($this->warning)) {
            ?>
            <div class="warning"><?php echo $this->warning; ?></div>
            <?php
        }

        if (!count($this->addresses)) {
            ?>
            <div class="no-content"><?php echo JText::_('ESHOP_NO_ADDRESS'); ?></div>
            <?php
        } else {
            ?>
            <div class="<?php echo $rowFluidClass; ?>">
                <div class="col-md-12">
                    <form id="adminForm">
                        <ul class="list-address">
                        <?php
                        foreach ($this->addresses as $address) {
                            ?>

                                <li>
                                    <div class="row">
                                        <div class="col-md-8 col-xs-12">
                                            <?php
                                            $addressText = "";
                                            $lastName = (EshopHelper::isFieldPublished('lastname') && $address->lastname != '') ? " " .$address->lastname : '';
                                            $addressText .= '<p class="txt-fullname">Họ và tên: <strong>'.$address->firstname.$lastName.'</strong></p>';
                                            if (EshopHelper::isFieldPublished('telephone') && $address->telephone != '') {
                                                $addressText .= "<p class='txt-telephone'>Số điện thoại: <strong>" . $address->telephone."</strong></p>";
                                            }
                                            $addressText .= "<p class='txt-address'>Địa chỉ: <strong>" . $address->address_1;
                                            if (EshopHelper::isFieldPublished('address_2') && $address->address_2 != '') {
                                                $addressText .= ", " . $address->address_2;
                                            }

                                            if (EshopHelper::isFieldPublished('zone_id') && $address->zone_name != '') {
                                                $addressText .= ", " . $address->zone_name;
                                            }
                                            if (EshopHelper::isFieldPublished('country_id') && $address->country_name != '') {
                                                $addressText .= ", " . $address->country_name;
                                            }
                                            if (EshopHelper::isFieldPublished('city') && $address->city != '') {
                                                $addressText .= ", " . $address->city;
                                            }
                                            if (EshopHelper::isFieldPublished('postcode') && $address->postcode != '') {
                                                $addressText .= ", " . $address->postcode;
                                            }
                                            $addressText .= "</strong></p>";
                                            if($address->email != ''){
                                                $addressText .= "<p class='txt-email'>Email : <strong>" . $address->email . "</strong></p>";
                                            }

                                            if (EshopHelper::isFieldPublished('fax') && $address->fax != '') {
                                                $addressText .= "<p class='txt-fax'>Fax: <strong>" . $address->fax . "</strong></p>";;
                                            }
                                            if (EshopHelper::isFieldPublished('company_id') && $address->company_id != '') {
                                                $addressText .= "<p class='txt-company-id'>Công ty Id: <strong>" . $address->company_id. "</strong></p>";
                                            }
                                            if (EshopHelper::isFieldPublished('company') && $address->company != '') {
                                                $addressText .= "<p class='txt-company'>Công ty : <strong>" .$address->company. "</strong></p>";
                                            }
                                            echo $addressText;
                                            ?>
                                        </div>
                                        <div class="col-md-4 col-xs-12 text-right btn-actions">
                                            <button type="button" id="button-edit-address" class="btn btn-primary" onclick="window.location.assign('<?php echo JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=address&aid=' . $address->id); ?>');">
                                               <i class="fa fa-edit"></i>
                                                <span class="txt txt-edit"><?php echo JText::_('ESHOP_EDIT'); ?></span>
                                            </button>
                                            <button type="button" id="<?php echo $address->id; ?>" class="button-delete-address btn btn-danger">
                                                <i class="fa fa-trash-o"></i>
                                                <span class="txt txt-delete"><?php echo JText::_('ESHOP_DELETE'); ?></span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            <?php
                        }
                        ?>
                        </ul>
                    </form>
                </div>
            </div>
        <?php } ?>
        <button type="button" id="button-new-address" class="btn btn-success">
            <i class="fa fa-plus-circle"></i>
            <span class="txt txt-add">
                <?php echo JText::_('ESHOP_ADD_ADDRESS'); ?>
            </span>
        </button>
    </div>
</div>

<?php /*
 <button type="button" id="button-back-address" class="btn btn-default" ><?php echo JText::_('ESHOP_BACK'); ?></button>
 */ ?>

<script type="text/javascript">
    Eshop.jQuery(function ($) {
        $(document).ready(function () {
            $('#button-back-address').click(function () {
                var url = '<?php echo JRoute::_(EshopRoute::getViewRoute('customer')); ?>';
                $(location).attr('href', url);
            });

            $('#button-new-address').click(function () {
                var url = '<?php echo str_replace('amp;', '', JRoute::_(EshopRoute::getViewRoute('customer') . '&layout=address')); ?>';
                $(location).attr('href', url);
            });

            //process user
            $('.button-delete-address').on('click', function () {
                var id = $(this).attr('id');
                var siteUrl = '<?php echo EshopHelper::getSiteUrl(); ?>';
                $.ajax({
                    url: siteUrl + 'index.php?option=com_eshop&task=customer.deleteAddress<?php echo EshopHelper::getAttachedLangLink(); ?>&aid=' + id,
                    type: 'post',
                    data: $("#adminForm").serialize(),
                    dataType: 'json',
                    success: function (json) {
                        $('.warning, .error').remove();
                        if (json['return']) {
                            window.location.href = json['return'];
                        } else {
                            $('.error').remove();
                            $('.warning, .error').remove();

                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            });
        })
    });
</script>
