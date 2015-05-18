<?php echo $header; ?>
<div class="login">
	<div id="content">
	  <div class="container-fluid">
		<div class="row">
			<div class="logo">
				<img src="<?php echo $thumb; ?>" alt="">
			</div>
		  <div class="login-center">
			<div class="panel panel-default">
			  <div class="panel-body">
				<?php if ($success) { ?>
				<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
				<?php } ?>
				<?php if ($error_warning) { ?>
				<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				  <button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
				<?php } ?>
				<form action="<?php echo $action; ?>" method="post"  class="login-form" enctype="multipart/form-data">
				   <h4 class="form-title"><i class="fa fa-lock"></i> <?php echo $text_login; ?></h4>
				   <br />
				   <div class="form-group">
					<label for="input-username"><?php echo $entry_username; ?></label>
					<div class="input-icon">
					  <i class="fa fa-user"></i>
					  <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
					</div>
				  </div>
				  <div class="form-group">
					<label for="input-password"><?php echo $entry_password; ?></label>
					<div class="input-icon">
					  <i class="fa fa-lock"></i>
					  <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
					</div>
					<?php if ($forgotten) { ?>
					<span class="help-block"><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></span>
					<?php } ?>
				  </div>
                  <?php if ($languages) { ?>
				  <div class="form-group">
					<label for="input-language"><?php echo $entry_language; ?></label>
					<div class="input-icon">
					  <i class="fa fa-flag"></i>
                        <select name="lang" id="input-language" class="form-control">
                            <?php foreach ($languages as $language) { ?>
                            <?php if ($language['code'] == $config_admin_language) { ?>
                            <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
					</div>
				  </div>
                  <?php } ?>
				  <div class="text-right">
					<button type="submit" class="btn btn-primary"><i class="fa fa-key"></i> <?php echo $button_login; ?></button>
				  </div>
				  <?php if ($redirect) { ?>
				  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
				  <?php } ?>
				</form>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	<div class="copyright">
		 <a href="<?php echo $store['href']; ?>"> ← Back to <?php echo $store['name']; ?></a>
	</div>	
</div>
<?php echo $footer; ?>