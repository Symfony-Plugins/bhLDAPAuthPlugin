<?php use_helper('Validation', 'I18N', 'Form') ?>
<?php $LDAP_config = bhLDAP::getConfig(); ?>
<div id="sf_admin_container">

<div id="sf_guard_auth_form">
<?php echo form_tag('@bh_ldap_signin') ?>

    <h1>Log In</h1>

  <fieldset>

	<table>
	  <tr>
	    <th><label for="signin_username">Active Directory username</label></th>
      <td>
<?php echo $form['username']->renderError()  ?>
<?php echo $form['username']->render() ?>
<?php echo $LDAP_config['adLDAP']['account_suffix'] ;  ?></td>
	      </tr>
    <?php echo $form['password']->renderRow() ?>
    <?php echo $form['remember']->renderRow() ?>

	</table>

  </fieldset>


      <ul class="sf_admin_actions">
	<li class="float-right">
	  <?php echo submit_tag('Log In', 'class="sf_admin_action_save"'); ?>
	</li>
      </ul>

    </form>
    </div>
  </div>