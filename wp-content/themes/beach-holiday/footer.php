<?php global $tpinfo;?>
		</div><!-- #container -->
		<div id="footer" class="clear">
            &copy; <?php echo date("Y");?> - <?php bloginfo('name'); ?><br/>
            <?php /*Please leave 1 credit line to the theme designer. Thanks.*/ theme_credit();?>
		</div><!-- #footer -->

	</div></div> <!-- #base_btm, #base -->
</div></div><!-- #bg_btm, #bg_top  -->
<?php wp_footer();?>
<div class="hide-div"><?php echo !empty($tpinfo['templatelite_analytics'])? stripslashes($tpinfo['templatelite_analytics']):""; ?></div>
</body>
</html>
