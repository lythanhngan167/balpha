<div class="profile">
    <fieldset id="users-profile-core">
        <legend>Thông tin đăng ký</legend>
        <dl class="dl-horizontal">
            <dt>Số điện thoại</dt>
            <dd><?php echo $this->user->username ?></dd>
        </dl>
        <legend>Thông tin cá nhân</legend>
        <dl class="dl-horizontal">
            <dt>Giới tính</dt>
            <dd><?php echo $this->escape($this->user->sex)?></dd>
            <dt><?php echo JText::_('COM_USERS_PROFILE_NAME_LABEL'); ?></dt>
            <dd><?php echo $this->escape($this->user->name)?></dd>
            <dt>Ngày sinh</dt>
            <?php if($this->user->birthday != $this->db->getNullDate()) {?>
            <dd><?php echo JHtml::_('date', $this->user->birthday, JText::_('DATE_FORMAT_LC1')); ?></dd>
            <?php } else {?>
            <dd><?php echo 'Đang cập nhật'; ?></dd>
            <?php } ?>
            <dt>Email</dt>
            <dd><?php echo $this->escape($this->user->email)?></dd>
            <dt>Địa chỉ thường trú</dt>
            <dd><?php echo $this->escape($this->user->address ? $this->user->address : 'Đang cập nhật')?></dd>
            <dt>Tỉnh/TP</dt>
            <dd><?php echo $this->escape($this->user->province ? $this->user->province : 'Đang cập nhật')?></dd>
        </dl>
        <legend>Thông tin khác</legend>
        <dl class="dl-horizontal">
            <dt>Công việc</dt>
            <dd><?php echo $this->escape($this->user->job ? $this->user->job : 'Đang cập nhật')?></dd>
        </dl>
    </fieldset>
</div>