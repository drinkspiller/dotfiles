<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $html->charset(); ?>
    <title>
        <?php __('Cannonball Advertising and Promotion: '); ?>
        <?php echo $title_for_layout; ?>
    </title>
    <!--<meta http-equiv="X-UA-Compatible" content="IE=7" />-->
    <?php

        echo $html->css('main', null, null, true);
        echo $html->css('uploadify', null, null, true);
	      echo $javascript->	link(	array	('jquery-1.4.2.min'), true);
        echo $javascript->codeBlock(//tab hilite
				    '$(document).ready(function(){
					if($("#tabs a:contains(\''. $this->params['action'] . '\')").length){
					    $("#tabs a:contains(\''. $this->params['action'] . '\')").addClass("current_tab").removeClass("tab");
					} else {
					    $("#tabs a:contains(\''. $this->viewPath . '\')").addClass("current_tab").removeClass("tab");
					}
				    });',
				    array('allowCache'=>false, 'inline'=>false));
        echo $shrink->scripts_for_layout();
    ?>
    <!--[if IE]>
    <?php echo $html->css('ie', null, null, true); ?>
    <![endif]-->

</head>
<body>
    <div id="container">
	<div id="header">

	    <?php if($session->read('currentuser')): ?>
	    <div id="tab_wrap">
    		<div id="tabs">
    		    <?php echo $html->link('collections', array('controller'=>'collections', 'action'=>'index'), array('class'=>'tab', 'target'=>'_top')); ?>
    		    <?php echo $html->link('search', array('controller'=>'assets', 'action'=>'search'), array('class'=>'tab', 'target'=>'_top')); ?>
    		    <?php echo $html->link('tags', array('controller'=>'tags', 'action'=>'index'), array('class'=>'tab', 'target'=>'_top')); ?>
    		    <?php
    			$cu = $session->read('currentuser');
    			if(intval($cu['Admin']['admin_type_id']) == intval(ADMIN_SUPERUSER)):
    		    ?>
    			<?php echo $html->link('admins', array('controller'=>'admins', 'action'=>'index'), array('class'=>'tab', 'target'=>'_top')); ?>
    		    <?php endif ?>
    		    <?php echo $html->link('logout', array('controller'=>'admins', 'action'=>'login'), array('class'=>'tab', 'target'=>'_top')); ?>
    		</div>
	    </div>
	    <?php endif ?>
	    <div id="logo"><a href="http://www.cannonballagency.com" title="www.cannonballagency.com" target="_self"><?php echo $html->image('cannonball_logo.gif'); ?></a></div>
	</div>
	<div id="content">
	    <?php $session->flash(); ?>
	    <?php echo $content_for_layout; ?>
	    <?php echo $cakeDebug; ?>
	</div>
	<div id="footer">
	    &copy;Copyright <? $today = getdate(); print $today['year'] ?> Cannonball Advertising and Promotion
	</div>
    </div>

    <script type="text/javascript">
    var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
    document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
    try {
    var pageTracker = _gat._getTracker("UA-5790323-3");
    pageTracker._trackPageview();
    } catch(err) {}</script>
</body>
</html>
