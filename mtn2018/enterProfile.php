<?php
  session_start();
  ini_set('display_errors' ,1);

  require_once dirname(__FILE__) . '/../cgi2018/sess/sess5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/logFile5C.php';

  $sessID = session_id();
  $cond   = sess5C::getSessCond($sessID);

  $mtn = '';
  $branchNo = '1';

  $_SESSION['BRANCHNO'] = $branchNo;

  $vesion = 'V=1&R=1&M=1';

  $targetDir = $_REQUEST['id'];
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

<link href="../css2018/prof.css?<?php print $vesion; ?>" rel="stylesheet">
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

<script src="../js2018/mtn/profEnter.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/profOut.js?<?php print $vesion; ?>"></script>

<script>
  var BRANCH_NO  = "<?php print $branchNo; ?>";
  var TARGET_DIR = "<?php print $targetDir; ?>";
</script>

</head>
<body>
<form id="enterProfile" name="enterProfile" data-parsley-validate data-parsley-trigger="keyup focusout change input">
  <input type="hidden" id="branchNo" name="branchNo" value="<?php print $branchNo; ?>">

  <div id="profileA">
      <div id="editLeftP">
        <input type="hidden" name="newProf" id="newProf" value="">
        <input type="checkbox" name="newFace" id="newFace" value="N"><label for="newFace">新人</label><br><br>

        <table class="profItem">
          <tr class="profItemA">
            <td class="profItemAA">識別子<span class="required">*</span></td>
            <td>
              <div id="enterProfN">  <!-- 新規 -->
                <input type="text" id="profDir" name="profDir" size="35" value="" required="" data-parsley-type="alphanum">
              </div>
              <div id="enterProfE">  <!-- 更新 -->
                <div id="profDirShow"></div><div id="editDir"><input type="button" value="識別子変更" id="editDirBtn" onclick="showEditDir();"  ></div>

                <div id="editDirDlg" class="ui-draggable editDirDlg">
                  <h1 id="popup_titleDir" style="cursor: move;">識別子変更</h1>
                  <div id="popup_contentDir" class="prompt">
                    <div id="popup_messageDir">
                      新識別子：<input type="text" name="newDir" id="newDir" class="enterDir">
                            <!-- <input type="button" value="更新" onclick="updProfDir();">&nbsp;&nbsp;<input type="button" value="閉じる" onclick="hideEditDir();"> -->
                    </div>
                    <div id="popup_panelDir">
                      <input type="button" value="&nbsp;更新&nbsp;" onclick="updProfDir()">
                      <input type="button" value="&nbsp;閉じる&nbsp;" onclick="hideEditDir();">
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>

          <tr class="profItemA">
            <td class="profItemAA">名前<span class="required">*</span></td>
            <td><input type="text" id="profName" name="profName" size="35" value="" required=""></td>
          </tr>
          <tr class="profItemA">
            <td class="profItemAA">ひとこと</td>
            <td><input type="text" id="prof1Phrase" name="prof1Phrase" size="35" value=""></td>
          </tr>

          <tr class="profItemA">
            <td class="profItemAA">年齢</td>
            <td><input type="text" id="profAge" name="profAge" size="35" value=""></td>
          </tr>

          <tr class="profItemA NOTUSE">
            <td class="profItemAA">誕生日</td>
            <td><input type="text" id="profBirthDate" name="profBirthDate" size="35" value=""></td>
          </tr>

          <tr class="profItemA">
            <td class="profItemAA">身長</td>
            <td><input type="text" id="profHeight" name="profHeight" size="35" value=""></td>
          </tr>
          <tr class="profItemA">
            <td class="profItemAA">スリーサイズ</td>
            <td><input type="text" id="profSize" name="profSize" size="35" value=""></td>
          </tr>

          <tr class="profItemA">
            <td class="profItemAA">星座</td>
            <td><input type="text" id="profZodiac" name="profZodiac" size="35" value=""></td>
          </tr>

          <tr class="profItemA">
            <td class="profItemAA">血液型</td>
            <td><input type="text" id="profBloodType" name="profBloodType" size="35" value=""></td>
          </tr>

            <!--
            <tr class="profItemA">
              <td class="profItemAA">出勤時間</td>
              <td><textarea id="profWorkTime" name="profWorkTime" cols="33" rows="3"></textarea></td>
            </tr>
            <tr class="profItemA">
              <td class="profItemAA">出勤/公休日</td>
              <td>
                <input type="radio" id="profWork" name="seleWR" value="W"><label for="profWork">出勤</label>
                <input type="radio" id="profRest" name="seleWR" value="R"><label for="profRest">公休</label><br>
                <input type="text" id="profWRDay" name="profWRDay" size="35" value="">
              </td>
            </tr>
            -->
            <!-- <input type="text" id="profWorkTime" name="profWorkTime" size="35" value=""> -->

          <tr class="profItemA">
            <td class="profItemAA">店長コメント</td>
            <td>
              <textarea id="mastComment" name="mastComment" cols="45" rows="6"></textarea>
              <div id="warnMastComment" class="parsley-errors-list filled"></div>
            </td>
          </tr>
          <tr>
            <td class="profItemAA">アピールコメント</td>
            <td>
              <textarea id="appComment" name="appComment" cols="45" rows="6"></textarea>
              <div id="warnAppComment" class="parsley-errors-list filled"></div>
            </td>
          </tr>
          <tr>
            <td colspan="2"><hr></td>
          </tr>

          <tr class="profItemA" style="display:none;">
            <td class="profItemAA">パスコード</td>
            <td><input type="text" id="profPCode" name="profPCode" size="35" value=""></td>
          </tr>
        </table>
      </div>

    <div id="editRightP">
      写真表示<br><br>
      <input type="radio" id="photoUseNP"  name="photoUSE" value="P"><label for="photoUseNP">準備中</label>
      <input type="radio" id="photoUseOK"  name="photoUSE" value="O"><label for="photoUseOK">表示可</label>
      <input type="radio" id="photoUseNG"  name="photoUSE" value="G"><label for="photoUseNG">NG</label>
      <input type="radio" id="photoUseNOT" name="photoUSE" value="N"><label for="photoUseNOT">写真ナシ</label>
      <br>
      <hr>
      <table class="photoFileSele">
        <thead>
          <tr>
            <th class="sepI">識別</th>
            <th class="sepF" colspan="2">画像ファイル</th>
            <th class="sepD">表示</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="sepI">1</td>
            <td class="sepF" id="currImg1"></td>
            <td class="sepF" id="sepFF1"><input type="file" name="attF1" id="attF1"></td>
            <td class="sepD"><input type="checkbox" name="useP1" id="useP1" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">2</td>
            <td class="sepF" id="currImg2"></td>
            <td class="sepF" id="sepFF2"><input type="file" name="attF2" id="attF2"></td>
            <td class="sepD"><input type="checkbox" name="useP2" id="useP2" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">3</td>
            <td class="sepF" id="currImg3"></td>
            <td class="sepF" id="sepFF3"><input type="file" name="attF3" id="attF3"></td>
            <td class="sepD"><input type="checkbox" name="useP3" id="useP3" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">4</td>
            <td class="sepF" id="currImg4"></td>
            <td class="sepF" id="sepFF4"><input type="file" name="attF4" id="attF4"></td>
            <td class="sepD"><input type="checkbox" name="useP4" id="useP4" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">5</td>
            <td class="sepF" id="currImg5"></td>
            <td class="sepF" id="sepFF5"><input type="file" name="attF5" id="attF5"></td>
            <td class="sepD"><input type="checkbox" name="useP5" id="useP5" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">サムネイル</td>
            <td class="sepF" id="currImgTN"></td>
            <td class="sepF" id="sepFTN"><input type="file" name="attTN" id="attTN"></td>
            <td class="sepD"><input type="checkbox" name="useTN" id="useTN" class="usePhoto" value="U"></td>
          </tr>
          <tr class="NOTUSE">
            <td class="sepI2">携帯大写真</td>
            <td class="sepF2" id="currML"></td>
            <td class="sepF2" id="sepFML"><input type="file" name="attML" id="attML"><br><div id="currML" class="currPhoto"></div></td>
            <td class="sepD2"><input type="checkbox" name="useML" id="useML" class="usePhoto" value="U"></td>
          </tr>
        </tbody>
      </table>
      <br>
    </div>
  </div>

  <div id="delDir"><input type="button" value="削除" id="delDirBtn" onclick="cfmDelDir();"></div>

  <hr>
  <div class="resetFloat">
    <div id="showBArea">
      <input type="button" value="出勤表"   class="showAreaBtn" id="showAreaBtn0" onclick="showArea(0);">
      <input type="button" value="QA"       class="showAreaBtn" id="showAreaBtn1" onclick="showArea(1);">
<!--
      <input type="button" value="特徴入力"   class="showAreaBtn" id="showAreaBtn2" onclick="showArea(2);">
      <input type="button" value="その他入力" class="showAreaBtn" id="showAreaBtn3" onclick="showArea(3);">
-->
    </div>
  </div>
  <hr>

  <div id="profArea0" style="clear:both;">
    週間出勤表<br>
    <table id="profWorkList" class="works">
    </table>
    <br>
    予定外<br>
    <table id="profWorkDiff" class="works">
    </table>
  </div>

  <div id="profArea1">
    QA入力
    <div class="resetFloat">
      <div class="qaAsk" id="qaAsk1">
        <table class="qaItem">
          <tr class="qaItemA">
            <td class="qaItemAA">前職</td>
            <td><input type="text" id="qa1" name="qa1" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">似ている芸能人</td>
            <td><input type="text" id="qa2" name="qa2" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">趣味</td>
            <td><input type="text" id="qa3" name="qa3" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">好きなタイプ</td>
            <td><input type="text" id="qa4" name="qa4" size="35" value=""></td>
          </tr>
          <tr>
            <td class="qaItemAA">責め派/受け派</td>
            <td><input type="text" id="qa5" name="qa5" size="35" value=""></td>
          </tr>
        </table>
      </div>

      <div class="qaAsk" id="qaAsk2">
        <table class="qaItem">
          <tr class="qaItemA">
            <td class="qaItemAA">得意プレイ</td>
            <td><input type="text" id="qa6" name="qa6" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">性感帯</td>
            <td><input type="text" id="qa7" name="qa7" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">お客様へ</td>
            <td><input type="text" id="qa8" name="qa8" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">可能オプション</td>
            <td><input type="text" id="qa9" name="qa9" size="35" value=""></td>
          </tr>
          <tr class="NOTUSE">
            <td class="qaItemAA">お客様へ一言</td>
            <td><input type="text" id="qa10" name="qa10" size="35" value=""></td>
          </tr>
        </table>
      </div>

      <div class="qaAsk" id="qaAsk3">
        <table class="qaItem">
          <tr class="qaItemA">
            <td class="qaItemAA">項目11</td>  <td><input type="text" id="qa11" name="qa11" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">項目12</td>  <td><input type="text" id="qa12" name="qa12" size="35" value=""></td>
          </tr>
          <tr class="qaItemA">
            <td class="qaItemAA">項目13</td>  <td><input type="text" id="qa13" name="qa13" size="35" value=""></td>
          </tr>
          <tr>
            <td class="qaItemAA">項目14</td>  <td><input type="text" id="qa14" name="qa14" size="35" value=""></td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <div id="profArea2">
    <div class="profileBFld">
      <div id="profileBAA">項目A</div>
      <div id="profileBAB">
        <input type="radio" name="profBA" id="profBA1" value="1"><label for="profBA1">項目A 1</label><br>
        <input type="radio" name="profBA" id="profBA2" value="2"><label for="profBA2">項目A 2</label><br>
        <input type="radio" name="profBA" id="profBA3" value="3"><label for="profBA3">項目A 3</label><br>
        <input type="radio" name="profBA" id="profBA4" value="4"><label for="profBA4">項目A 4</label>
      </div>
    </div>

    <div class="profileBFld">
      <div id="profileBBA">項目B</div>
      <div id="profileBBB">
        <input type="radio" name="profBB" id="profBB1" value="1"><label for="profBB1">項目B 1</label><br>
        <input type="radio" name="profBB" id="profBB2" value="2"><label for="profBB2">項目B 2</label><br>
        <input type="radio" name="profBB" id="profBB3" value="3"><label for="profBB3">項目B 3</label><br>
        <input type="radio" name="profBB" id="profBB4" value="4"><label for="profBB4">項目B 4</label>
      </div>
    </div>

    <div class="profileBFld">
      <div id="profileBCA">項目C</div>
      <div id="profileBCB">
        <input type="radio" name="profBC" id="profBC1" value="1"><label for="profBC1">項目C 1</label><br>
        <input type="radio" name="profBC" id="profBC2" value="2"><label for="profBC2">項目C 2</label><br>
        <input type="radio" name="profBC" id="profBC3" value="3"><label for="profBC3">項目C 3</label><br>
        <input type="radio" name="profBC" id="profBC4" value="4"><label for="profBC4">項目C 4</label>
      </div>
    </div>
  </div>

  <div id="profArea3" style="display:none;">
    その他入力
  </div>

  <div id="DelDirDlg" class="cfmDelPrompt ui-draggable delPrompt">
    <h1 id="popup_titleDelDir" style="cursor: move;">プロファイルの削除</h1>
    <div id="popup_contentDelDir" class="confirm">
      <div id="popup_messageDelDir">プロファイル情報を削除しますか？<br>この操作は取り消せません</div>
      <div id="popup_panelDelDir">
        <input type="button" value="&nbsp;はい&nbsp;" onclick="delProfDir()">
        <input type="button" value="&nbsp;キャンセル&nbsp;" onclick="hideDelDir();">
      </div>
    </div>
  </div>
    </form>
</body>
</html>
