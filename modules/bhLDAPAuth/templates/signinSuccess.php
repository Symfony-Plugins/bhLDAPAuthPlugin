<?php use_helper('Validation', 'I18N') ?>
<?php $LDAP_config = bhLDAP::getConfig(); ?>
<div id="sf_admin_container">

<div id="sf_guard_auth_form">
<?php echo form_tag('@bh_ldap_signin') ?>
    <h1>Log In</h1>

  <fieldset>

    <div class="form-row" id="sf_guard_auth_username">
      <?php
      echo form_error('username'), 
        label_for('username', __('Active Directory username:')),
        input_tag('username', $sf_data->get('sf_params')->get('username')),
	$LDAP_config['adLDAP']['account_suffix'] ;
      ?>
    </div>

    <div class="form-row" id="sf_guard_auth_password">
      <?php
      echo form_error('password'), 
        label_for('password', __('Password:')),
        input_password_tag('password');
      ?>
    </div>
    <div class="form-row" id="sf_guard_auth_remember">
      <?php
      echo label_for('remember', __('Remember me?')),
      checkbox_tag('remember');
      ?>
    </div>
  </fieldset>

  <?php 
  echo submit_tag(__('sign in'));
    
  ?>
</form>
</div>
  </div>