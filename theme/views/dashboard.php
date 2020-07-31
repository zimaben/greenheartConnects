<?php
use gh_connects\theme\Modules as Modules; 

Modules::open_page();
/*/-------------------------------------------------------------------------------------------------------
/*
/*/ Modules::top_logo();                      Modules::top_menu();           $userState = Modules::top_avatar();
/*
/*---------------------------------------------------------------------------------------------------------
/*
/*/ error_log('USER STATE PRIOR TO HOME HERO');
/*/  */   error_log(print_r($userState, true));
$userState = Modules::hero_section($userState);
/*
/*
/*
/*
/*----------------------------------------------------------------------------------------------------------
/*/ error_log('USER STATE LEFTCOL'); error_log(print_r($userState,true)); 
 /*/ */  Modules::left_col($userState);  /* */ Modules::right_col($userState);
/*                                  /* */
/*                                  /* */
/*                                  /* */
/*                                  /* */
/*                                  /* */
/*                                  /* */
/*
/*/ Modules::footer();              /*------------------------------------------------------------------------
/*
/*-----------------------------------------------------------------------------------------------------------*/
Modules::close_page();
