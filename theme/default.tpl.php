<html>
	<head>
		<title><?php echo $ly->template->title; ?></title>
		<?php echo $ly->getStylesheetHTML(); ?>
		<?php echo $ly->getMeta(); ?>
		<?php echo $ly->getJS(); ?>
	</head>
	<body>
		<div id="full-wrapper">
			<!-- TOP -->
			<div id="top-wrapper">
				<?php echo isset($regions->top) ? $regions->top : ""; ?>
			</div>

			<!-- HEADER -->
			<div id="header-wrapper" class="container">
				<?php echo isset($ly->cfg["site"]["header"]["site-title"]) ? "<h1>{$ly->cfg["site"]["header"]["site-title"]}</h1>" : "" ; ?>
				<?php echo isset($ly->cfg["site"]["header"]["logo"]) ? "<img src='{$ly->cfg["site"]["header"]["logo"]}' />" : "" ; ?>
				<?php echo $menu; ?>
			</div>

			<!-- PROMOTED -->
			<div id="promoted-wrapper" class="container prepend-top append-bottom">
				<div id="promoted">
					<?php echo isset($regions->promoted) ? $regions->promoted : ""; ?>
				</div>
			</div>

			<!-- CONTENT -->
			<div id="content-wrapper" class="container prepend-top append-bottom">				
				<!-- MAIN CONTENT -->
				<div id="content" class="<?php if (isset($regions->sidebar))
					echo "span-18"; ?>" >
						 <?php echo isset($regions->main) ? $regions->main : ""; ?>
				</div>

				<!-- SIDEBAR RIGHT -->
				<!-- MAKE DYNAMIC -->
				<?php
				if (isset($regions->sidebar)) {
					echo <<< EOD
				<div id="right-sidebar" class="span-6 last">
					{$regions->sidebar}
				</div>
EOD;
				}
				?>
			</div>

			<!--TRIPTYCH-->
			<div id="triptych-wrapper" class="container prepend-top append-bottom">
				<div id="triptych1" class="span-8">
					<?php echo isset($regions->triptych1) ? $regions->triptych1 : ""; ?>
				</div>
				<div id="triptych2" class="span-8">
					<?php echo isset($regions->triptych2) ? $regions->triptych2 : ""; ?>
				</div>
				<div id="triptych3" class="span-8 last">
					<?php echo isset($regions->triptych3) ? $regions->triptych3 : ""; ?>
				</div>
			</div>

			<!--FOOTER-->
			<div id="footer-wrapper" class="prepend-top container">
				<?php echo $ly->cfg["site"]["footer"]["info"]; ?>
				&copy;<?php echo $ly->cfg["site"]["footer"]["copyright"]; ?>
			</div>
		</div>
	</body>
</html>