<?php

if ( !current_user_can( 'manage_options' ) )  {
  wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
} ?>

<div class="wrap">
  <h2>A1D Events Custom Post Type - NE CPT</h2>
  <form action="options.php" method="post">
    <?php settings_fields('a1dnecpt_cpt_options'); ?>
    <?php do_settings_sections('a1d-necpt'); ?>

    <?php submit_button(); ?>
  </form>
</div>
