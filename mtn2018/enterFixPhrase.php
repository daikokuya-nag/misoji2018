<?php
  session_start();
  ini_set('display_errors' ,1);

  require_once dirname(__FILE__) . '/../cgi2018/sess/sess5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/logFile5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/db/dbNews5C.php';

  $sessID = session_id();
  $cond   = sess5C::getSessCond($sessID);

  $mtn = '';
  $branchNo = '1';

  $_SESSION['BRANCHNO'] = $branchNo;

  $vesion = 'V=1&R=1&M=1';
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>三十路 メンテ 2018年版</title>
<link href="../css2018/jq/smoothness/jquery-ui-1.10.4.custom.css" rel="stylesheet">
<link href="../css2018/jq/jquery.alerts.css" rel="stylesheet">
<link href="../css2018/jq/tinytools.toggleswitch.css" rel="stylesheet">
<link href="../css2018/jq/farbtastic/farbtastic.css" rel="stylesheet">

<link href="../css2018/parsley/parsley.css" rel="stylesheet">

<link href="../css2018/mtnCommon.css?<?php print $vesion; ?>" rel="stylesheet">

<link href="../css2018/news.css?<?php print $vesion; ?>" rel="stylesheet">
<script src="../js2018/ckEditor/ckeditor.js"></script>

<script src="../js2018/jq/jquery-1.11.2.min.js?<?php print $vesion; ?>"></script>
<script src="../js2018/jq/jquery-ui-1.10.4.custom.min.js?<?php print $vesion; ?>"></script>
<script src="../js2018/jq/jquery.alerts.js"></script>
<script src="../js2018/jq/farbtastic.js"></script>
<script src="../js2018/jq/toggleSW/tinytools.toggleswitch.js" charset="utf-8"></script>
<script src="../js2018/jq/jquery-ui-timepicker-addon.js"></script>

<script src="../js2018/parsley/parsley.min.js"></script>
<script src="../js2018/parsley/i18n/ja.js"></script>
<script src="../js2018/parsley/i18n/ja.extra.js"></script>

<script src="../js2018/mtn/fixPhraseEnter.js?<?php print $vesion; ?>"></script>

<script>
  var BRANCH_NO   = "<?php print $branchNo; ?>";
</script>

</head>
<body>
<form id="enterFixPhrase" name="enterFixPhrase" data-parsley-validate data-parsley-trigger="keyup focusout change input">
  <input type="hidden" id="branchNo"   name="branchNo"   value="<?php print $branchNo; ?>">
  <textarea id="fixPhraseStr" name="fixPhraseStr" cols="60" rows="18"></textarea>
  <div id="warnFixPhraseStr" class="parsley-errors-list filled"></div>
</form>

</body>
</html>
