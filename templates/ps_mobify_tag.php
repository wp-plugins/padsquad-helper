<script>
<?php $MOBIFY_URL = get_option( 'psplugin-textfield' ) ; ?>
(function(window, document, mjs) {
window.Mobify = {points: [+new Date], tagVersion: [1, 0]};
document.write('<plaintext style=display:none>');
setTimeout(function() {
	var mobifyjs = document.createElement('script');
	var script = document.getElementsByTagName('script')[0];
	mobifyjs.src = mjs;
	script.parentNode.insertBefore(mobifyjs, script);
});
 })(this, document, <?php echo '"' . $MOBIFY_URL . '"'; ?>);
</script>