<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to Generator CI dan ExtJS4.2</title>

	<meta name="author" content="Eko Junaidi Salam">
	<meta name="email" content="eko_junaidisalam@live.com">
	<meta name="description" content="Integrasi CI dan ExtJS4.2">
	<meta name="keywords" content="ERP">

	<script type="text/javascript">
		var base_url = '<?php echo base_url();?>';
	</script>
	<!-- <x-compile> -->
        <!-- <x-bootstrap> -->
			<link rel="stylesheet" href="<?php echo base_url();?>resources/css/icons.css"/>
            <link rel="stylesheet" href="bootstrap.css">
            <script src="<?php echo base_url();?>ext/ext-dev.js"></script>
            <script src="<?php echo base_url();?>bootstrap.js"></script>
        <!-- </x-bootstrap> -->
        <script type="text/javascript" src="<?php echo base_url();?>app.js"></script>
    <!-- </x-compile> -->
	
</head>
<body>
<div id="loading-mask" style=""></div>
    <div id="loading">
        <div class="loading-indicator">
            <img src="<?php echo base_url();?>resources/images/loading.gif" style="margin-right:8px;float:left;vertical-align:top;"/>
        </div>
    </div>
    
    <script type="text/javascript">
    Ext.onReady(function() {

        (Ext.defer(function() {
            var hideMask = function () {
                Ext.get('loading').remove();
                Ext.fly('loading-mask').animate({
                    opacity:0,
                    remove:true
                });
            };

            Ext.defer(hideMask, 250);

        },500));
    });
    </script>
</body>
</html>