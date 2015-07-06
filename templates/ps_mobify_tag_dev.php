<!-- Make sure you are running grunt and/or mobify preview (possibly don't need preview anymore) -->
<script>
(function(window, document, mjs) {
window.Mobify = {points: [+new Date], tagVersion: [1, 0]};
document.write('<plaintext style=display:none>');
setTimeout(function() {
	var mobifyjs = document.createElement('script');
	var script = document.getElementsByTagName('script')[0];
	mobifyjs.src = mjs;
	script.parentNode.insertBefore(mobifyjs, script);
});
 })(this, document, <?php echo '"http://localhost:8080/mobify.js"'; ?>);
</script>