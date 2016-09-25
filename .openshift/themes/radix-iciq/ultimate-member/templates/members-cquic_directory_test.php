<?php /* Template: CQuIC member Directory test */ ?>

<div class="um <?php echo $this->get_class( $mode ); ?> um-<?php echo $form_id; ?>">

<style type="text/css">
.um-member-photo {
    padding: 0px 0 0px;
    text-align: center;
}
.um-member {
    padding-left: 2px;
    padding-right: 2px;
}
.um-members-pagi > p {
    display: inline;
   /* text-align: left; */
}
.um-18.um .um-profile-photo a.um-profile-photo-img {
 top: -60px;
 }
.um-member-card {
 top: -60px;
 align: right;
 } 
</style>


	<div class="um-form">

			<?php do_action('um_members_directory_search', $args ); ?>
			
			<?php do_action('um_members_directory_head', $args ); ?>
			
			<?php do_action('um_members_directory_display', $args ); ?>
						
			<?php do_action('um_members_directory_footer', $args ); ?>

	</div>
	
</div>