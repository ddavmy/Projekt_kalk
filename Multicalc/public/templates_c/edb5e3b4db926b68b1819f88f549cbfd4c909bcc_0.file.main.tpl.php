<?php
/* Smarty version 4.3.0, created on 2023-06-25 13:06:01
  from 'C:\xampp\htdocs\projekty\projekt\app\views\templates\main.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.0',
  'unifunc' => 'content_64981f9937cc88_83236699',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'edb5e3b4db926b68b1819f88f549cbfd4c909bcc' => 
    array (
      0 => 'C:\\xampp\\htdocs\\projekty\\projekt\\app\\views\\templates\\main.tpl',
      1 => 1687691153,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_64981f9937cc88_83236699 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_loadInheritance();
$_smarty_tpl->inheritance->init($_smarty_tpl, false);
?>
<!DOCTYPE HTML>
<!--
	Phantom by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html lang="pl">
	<head>
		<title>Multicalc</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/css/main.css" />
		<noscript><link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/css/noscript.css" /></noscript>
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">

                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_144438864664981f9936d8f1_19825207', 'header');
?>

				<!-- Header -->
					<header id="header">
						<div class="inner">

							<!-- Logo -->
								<a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->action_root;?>
" class="logo">
									<span class="symbol"><img src="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/images/logo.png" alt="" /></span><span class="title">Multicalc</span>
								</a>

							<!-- Nav -->
								<nav>
									<ul>
										<li><a href="#menu">Menu</a></li>
									</ul>
								</nav>
							<?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_192308180164981f9936fd18_72984757', 'content');
?>

						</div>
					</header>

				<!-- Menu -->
					<nav id="menu">
						<h2>Menu</h2> 
						<h4>Rola: <?php echo $_smarty_tpl->tpl_vars['user']->value->role;?>
</h4>
						<ul>
							<li><a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
">Home</a></li>
							<?php if ($_smarty_tpl->tpl_vars['user']->value->role != "guest") {?>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/usersShow">Uzytkownicy</a></li>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/logout">Wyloguj</a></li>
							<?php }?>
							<?php if ($_smarty_tpl->tpl_vars['user']->value->role == "guest") {?>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/loginShow">Zaloguj</a></li>
								<li><a href="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/registerShow">Rejestracja</a></li>
							<?php }?>
						</ul>
					</nav>

					</div> <!-- end of main -->
                <?php 
$_smarty_tpl->inheritance->instanceBlock($_smarty_tpl, 'Block_172898635864981f993798c9_04921360', 'footer');
?>

				<!-- Footer -->
					<footer id="footer">
						<div class="inner">
							<section>
								<h2>Follow</h2>
								<ul class="icons">
									<li><a href="https://github.com/ddavmy" class="icon brands style2 fa-github"><span class="label">GitHub</span></a></li>
								</ul>
							</section>
							<ul class="copyright">
								<li>&copy; Jakub Linnert - All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>
					</footer>

			</div>

		<!-- Scripts -->
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/js/jquery.min.js"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/js/browser.min.js"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/js/breakpoints.min.js"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/js/util.js"><?php echo '</script'; ?>
>
			<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['conf']->value->app_url;?>
/js/main.js"><?php echo '</script'; ?>
>

	</body>
</html><?php }
/* {block 'header'} */
class Block_144438864664981f9936d8f1_19825207 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'header' => 
  array (
    0 => 'Block_144438864664981f9936d8f1_19825207',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'header'} */
/* {block 'content'} */
class Block_192308180164981f9936fd18_72984757 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'content' => 
  array (
    0 => 'Block_192308180164981f9936fd18_72984757',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
?>
 Domyślna treść zawartości .... <?php
}
}
/* {/block 'content'} */
/* {block 'footer'} */
class Block_172898635864981f993798c9_04921360 extends Smarty_Internal_Block
{
public $subBlocks = array (
  'footer' => 
  array (
    0 => 'Block_172898635864981f993798c9_04921360',
  ),
);
public function callBlock(Smarty_Internal_Template $_smarty_tpl) {
}
}
/* {/block 'footer'} */
}