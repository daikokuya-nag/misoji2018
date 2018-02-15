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

  $targetNews = $_REQUEST['id'];
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
<script src="../js2018/jq/jquery.tablednd.js?<?php print $vesion; ?>"></script>
<script src="../js2018/jq/jquery.alerts.js"></script>
<script src="../js2018/jq/farbtastic.js"></script>
<script src="../js2018/jq/toggleSW/tinytools.toggleswitch.js" charset="utf-8"></script>
<script src="../js2018/jq/jquery-ui-timepicker-addon.js"></script>

<script src="../js2018/parsley/parsley.min.js"></script>
<script src="../js2018/parsley/i18n/ja.js"></script>
<script src="../js2018/parsley/i18n/ja.extra.js"></script>

<!-- <script src="../js2018/mtn/mtnCommon.js?<?php print $vesion; ?>"></script> -->

<script src="../js2018/mtn/newsEnter.js?<?php print $vesion; ?>"></script>

<script>
  var BRANCH_NO   = "<?php print $branchNo; ?>";
  var TARGET_NEWS = "<?php print $targetNews; ?>";
</script>

</head>
<body>
<form id="enterNews" name="enterNews" data-parsley-validate data-parsley-trigger="keyup focusout change input">
  <input type="hidden" id="branchNo"   name="branchNo"   value="<?php print $branchNo; ?>">
  <input type="hidden" id="newNewsRec" name="newNewsRec" value="<?php print dbNews5C::NEW_REC; ?>">
  <input type="hidden" id="editNewsNo" name="editNewsNo" value="">

  <div id="editLeftN">
    <table>
      <tr>
        <td>タイトル<span class="required">*</span></td>
        <td><input type="text" id="title" name="title" size="35" value="" required="" placeholder="テキストを入力してください"></td>
      </tr>
      <tr>
        <td>記事日付<span class="required">*</span></td>
        <td><input type="text" id="newsDate" name="newsDate" size="35" value="" required=""></td>
      </tr>
      <tr class="NOTUSE">
        <td>期間</td>
        <td><input type="text" id="newsTerm" name="newsTerm" size="35" value=""></td>
      </tr>
      <tr>
        <td>期間</td>
        <td><input type="text" id="begDate" name="begDate" size="8" value="">～<input type="text" id="endDate" name="endDate" size="8" value=""></td>
      </tr>
      <tr>
        <td>記事種類</td>
        <td>
          <input type="radio" name="newsCate" id="cateE" value="E">イベント
          <input type="radio" name="newsCate" id="cateW" value="W">女性
          <input type="radio" name="newsCate" id="cateM" value="M">会員
          <input type="radio" name="newsCate" id="cateO" value="O">その他
        </td>
      </tr>
      <tr>
        <td>表示開始日時</td>
        <td><input type="text" id="dispBegDate" name="dispBegDate" size="35" value=""></td>
      </tr>
      <tr>
        <td colspan="2"><hr></td>
      </tr>
      <tr>
        <td>記事本体<span class="required">*</span></td>
        <td>
          <textarea id="content" name="content" cols="60" rows="18" required="" data-parsley-trigger="change"></textarea>
          <div id="warnContent" class="parsley-errors-list filled"></div>
        </td>
      </tr>
    </table>
    <div class="delNews"><input type="button" value="削除" id="delNewsBtn" onclick="showDelNews();"></div>
  </div>
</form>

<div id="DelNewsDlg" class="cfmDelPrompt ui-draggable delPrompt">
  <h1 id="popup_titleDelNews" style="cursor: move;">記事の削除</h1>
  <div id="popup_contentDelNews" class="confirm">
    <div id="popup_messageDelNews">記事を削除しますか？<br>この操作は取り消せません</div>
    <div id="popup_panelDelNews">
      <input type="button" value="&nbsp;はい&nbsp;" onclick="delNewsItem();">
      <input type="button" value="&nbsp;キャンセル&nbsp;" onclick="hideDelNews();">
    </div>
  </div>
</div>
</body>
</html>
