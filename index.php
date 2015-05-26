<?php
/*
	Plugin Name: Skills Plugin
	Description: Plugin to display skills in graphic form
   	Plugin URI: http://abdellahaithadji.com
	Author: Abdellah Ait Hadji
	Author URI: http://abdellahaithadji.com
	License: GPL2
	Version: 1.0.1
*/

/*  Copyright 2015 Abdellah Ait hadji (email : abdel@abdellahaithadji.com)

    This program is free software; you can redistribute it and/or modify.
  
*/

add_action("init", "skills_init");
add_action("add_meta_boxes", "skills_metaboxes");
add_action("save_post", "skills_savepost",10,2);

function skills_init(){
 

		$label = array(
			
			"name" => "Skill",
			"singular_name" => "Skill",
			"add_new" => "Ajouter un Skill",
			"add_new_item" => "Ajouter un nouveau Skill",
			"edit_item" => "Editer un skill",
			"new_item" => "Nouveau Skill",
			"view_item" => "Voir le Skill",
			"search_items" => "Rechercher un Skill",
			"not_found" => "Aucun Skill",
			"not_found_in_trash" => "Aucun Skill dans la corbeille",
			"parent_item_colon" => "",
			"menu_name" => "Skill",	

		);       
                register_post_type("skill", array(
                    "public" => true,
                    "labels" => $label,
                    "menu_position" => 9,
                    "capabality_type" => "post",
                    "supports" => array("title", "thumbnail"),
                ));

}

function skills_metaboxes(){

	add_meta_box("skills", "Valeur en pourentage", "skills_metabox", "skill", "normal", "high");
}
function skills_metabox($object){
wp_nonce_field("skills", "skills_nonce");
	?>
	<div class="meta-box-item-title">
		<h4>Valeur</h4>
	</div>
	<div class="meta-box-item-content">	
		<input type="text" name="pourcentage" style="width:100%;" value="<?= esc_attr(get_post_meta($object->ID, '_pourcentage', true)); ?>">	
	</div>
	<?php

}

function skills_savepost($post_id, $post){
   
	if(!isset($_POST['pourcentage']) || !wp_verify_nonce($_POST["skills_nonce"], "skills")){
				
		return $post_id;	
	}
	$type = get_post_type_object($post->post_type);

	if(current_user_can($type->cap->edit_post)){

		return $post_id;
		
	}
	
	update_post_meta($post_id, "_pourcentage", $_POST['pourcentage']);
       
        
}

function skills_show($limit = 10){  
	
   	//wp_enqueue_script("jquery");
	//wp_enqueue_script("jquery", "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js", array("jquery"),"5.6.4", true);
	add_action("wp_footer", "skills_script", 30);
	
    $skills = new wp_query("post_type=skill&posts_per_page=$limit");
    
    while($skills->have_posts()){
    $skills->the_post();
    
    global $post;
   echo esc_attr(get_post_meta($post->ID, '_pourcentage', true));
    echo '<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: '.esc_attr(get_post_meta($post->ID, '_pourcentage', true)).'60%">
   
    <span class="sr-only">'.the_title().'</span>60%
  </div>
</div>';
  
    }
    
   
}

function skills_script(){

	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
		jQuery('.progress').each(function(){
			//console.log(jQuery(this).find('.progress-bar-success'));
			jQuery(this).find('.progress-bar-success').animate({
				width:jQuery(this).attr('aria-valuenow')
			},6000);
		});
		});
	</script>
	<?php
}
?>