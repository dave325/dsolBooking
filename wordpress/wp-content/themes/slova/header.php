<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--title><?php wp_title( '-', true, 'right' ); ?></title-->
		<?php wp_head(); ?>
	</head>
	<body <?php body_class() ?>>
		<div id="ro-main">
		<?php get_template_part('framework/headers/header', 'v1'); ?>		