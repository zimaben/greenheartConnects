<?php
use gh_connects\theme\Modules as Modules; 

Modules::open_page();
Modules::hack_in_login_enqueue();
/*/-------------------------------------------------------------------------------------------------------
/*
/*/ Modules::top_logo();                      Modules::top_menu();           $userState = Modules::top_avatar();
/*
/*---------------------------------------------------------------------------------------------------------
/*
/*/
/*/ 
/*/ require_once GreenheartConnects::get_plugin_path('/theme/views/homesplash-body.php');
/*
/*
/*
/*
/*
/*
/*
/*
/*
/*
/*
/*
/*
/*/

Modules::splash_footer();
Modules::footer(); /*------------------------------------------------------------------------
/*
/*-----------------------------------------------------------------------------------------------------------*/
Modules::close_page();