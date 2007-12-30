<?php
/*

    Footer PHP script
    Chip Cuccio <http://chip.cuccio.us>
    $Id: footer_inc.php 3260 2007-12-17 20:30:25Z chipster $

 */
$req_uri = htmlspecialchars($_SERVER['REQUEST_URI']);
?>
  <div id="footer">
    <br class="doNotDisplay doNotPrint" />

    <div class="left">
      <strong>[ <a href="#top">Back to Top ^</a> ]</strong><br />
      [ <a href="http://validator.w3.org/check?uri=http://<?php
      print($_SERVER['SERVER_NAME'].htmlspecialchars($_SERVER['REQUEST_URI'])); ?>"
      class="footLink">site created using standards</a> ]
    </div>
    <div class="right">
      <address>
        Linux&#174; is a registered trademark of <a href=
        "http://www.linuxmark.org">Linus Torvalds</a><br />
        Slackware&#174; is a registered trademark of <a href=
        "http://slackware.com/trademark/trademark.php">Patrick
        Volkerding</a>
      </address>
    </div>
  </div>
</div>
</div>
<div id="subfoot">Site design and code by <a href="http://chip.cuccio.us">Chip
Cuccio</a></div>
<script src="http://www.google-analytics.com/urchin.js"
  type="text/javascript">
</script>
<script type="text/javascript">
  _uacct = "UA-250737-2";
  urchinTracker();
</script>
</body>
</html>

