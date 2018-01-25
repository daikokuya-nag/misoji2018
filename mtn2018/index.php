<?php
  session_start();
  ini_set('display_errors' ,1);

  require_once dirname(__FILE__) . '/../cgi2018/sess/sess5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/logFile5C.php';

  $sessID = session_id();
  $cond   = sess5C::getSessCond($sessID);

  $logStr = 'use from ' . $_SERVER["REMOTE_ADDR"];
  logFile5C::general($logStr);

  if($cond == sess5C::NO_ID) {      // セッションデータナシ　ログイン画面へ
          logFile5C::general('セッションファイルナシ');
    header('Location: login.html');
  }

  if($cond == sess5C::OWN_TIMEOUT) {    // 自IDでタイムアウト　タイムアウトのダイアログ、ログイン画面へ
            logFile5C::general('自ID タイムアウト');
    header('Location: login.html');
  }

  if($cond == sess5C::OTHER_TIMEOUT) {  // 他IDでタイムアウト　ログイン画面へ
            logFile5C::general('他ID タイムアウト');
    header('Location: login.html');
  }

  if($cond == sess5C::OWN_INTIME) {    // 自IDでログイン中　メンテ画面へ
            logFile5C::general('自ID ログイン中');
  }

  if($cond == sess5C::OTHER_INTIME) {    // 他IDでログイン中　「他でログイン中」のダイアログ、ログイン不可
            logFile5C::general('他ID ログイン中');
    header('Location: login.html');
  }

  $mtn = '';
  $branchNo = '1';

  $_SESSION['BRANCHNO'] = $branchNo;

  require_once dirname(__FILE__) . '/../cgi2018/db/dbNews5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/db/dbProfile5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/db/dbMBlog5C.php';

  require_once dirname(__FILE__) . '/../cgi2018/bld/bldNews5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/bld/bldProfile5C.php';
  require_once dirname(__FILE__) . '/../cgi2018/bld/bldMBlog5C.php';

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

<link href="../css2018/parsley/parsley.css" rel="stylesheet">

<link href="../css2018/mtnCommon.css?<?php print $vesion; ?>" rel="stylesheet">

<link href="../css2018/blog.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/news.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/prof.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/photoDiary.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/decoration.css?<?php print $vesion; ?>" rel="stylesheet">

<link href="../css2018/fileSele.css" rel="stylesheet">


<script src="../js2018/jq/jquery-1.11.2.min.js?<?php print $vesion; ?>"></script>
<script src="../js2018/jq/jquery-ui-1.10.4.custom.min.js?<?php print $vesion; ?>"></script>
<script src="../js2018/jq/jquery.tablednd.js?<?php print $vesion; ?>"></script>
<script src="../js2018/jq/jquery.alerts.js"></script>
<script src="../js2018/jq/toggleSW/tinytools.toggleswitch.js" charset="utf-8"></script>
<script src="../js2018/jq/jquery-ui-timepicker-addon.js"></script>

<script src="../js2018/ckEditor/ckeditor.js"></script>

<script src="../js2018/parsley/parsley.min.js"></script>
<script src="../js2018/parsley/i18n/ja.js"></script>
<script src="../js2018/parsley/i18n/ja.extra.js"></script>

<script src="../js2018/mtn/writeFile.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/writeFileMO.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/ctrlSess.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/logoutSess.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/mtnCommon.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/imgSelector.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/news.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/fixPhrase.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/prof.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/profOut.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/top.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/system.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/recruit.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/header.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/decoration.js?<?php print $vesion; ?>"></script>
<script>
  var BRANCH_NO = "<?php print $branchNo; ?>";
</script>

</head>
<body>
<input type="hidden" id="branchNo"   name="branchNo"   value="<?php print $branchNo; ?>">
<input type="hidden" id="newNewsRec" name="newNewsRec" value="<?php print dbNews5C::NEW_REC; ?>">
<input type="hidden" id="newBlogRec" name="newBlogRec" value="<?php print dbMBlog5C::NEW_REC; ?>">
<div id="tabA">
  <ul>
    <li><a href="#tabsDecoration">装飾</a></li>

    <li><a href="#tabsHeader">ヘッダ</a></li>

    <li><a href="#tabsTop">TOP</a></li>
    <li><a href="#tabsNews">ニュース</a></li>
    <li><a href="#tabsProfile">プロファイル</a></li>
    <li><a href="#tabsRecruit">求人</a></li>
    <li><a href="#tabsSystem">システム</a></li>
    <li><a href="#tabsPhotoDiary">写メ日記</a></li>
  </ul>

  <!-- ***** タブの中身の定義 ***** -->
  <!-- ニュース -->
  <div id="tabsNews" class="tabArea">
    <div id="tabNewsUsePage" class="resetFloat">
      使用するニュースページ<br>
      <div class="selectPage">
        <label><input type="radio" name="useNews" id="useNewsOther" value="OTHER_SITE">外部</label><input type="text" name="newsOuterURL" id="newsOuterURL" class="outerURL"><br>
        <label><input type="radio" name="useNews" id="useNewsOwn" value="OWN_SITE">内部</label>
      </div>
      <div class="sendSelectPage">
        <input type="button" value="使用ページ反映" id="sendSeleNewsPage" onclick="updUsePage('NEWS');" disabled="disabled">
      </div>
    </div>
    <hr>

    <div id="tabNewsMain">
      <div id="tabNewsTop">
        <input type="button" value="新規ニュース" onclick="newNews()">&nbsp;&nbsp;
        <input type="button" value="定型文編集" onclick="editFixPhrase()">
        <br><br>
      </div>

      <div id="tabNewsMid" class="tabMid">
        <table id="newsList">
          <thead id="newsListH"></thead>
          <tbody id="newsListD"></tbody>
        </table>
      </div>
    </div>
    <div class="grayPanel" id="grayPanelNews"></div>

    <div id="tabNewsBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="表示可否反映" id="bldNewsList" onclick="updNewsDisp();" disabled="disabled">
    </div>

  </div>

  <!-- プロファイル -->
  <div id="tabsProfile" class="tabArea">
    <div id="tabProfileUsePage" class="resetFloat">
      使用するプロファイルページ<br>
      <div class="selectPage">
        <label><input type="radio" name="useProfile" id="useProfileOther" value="OTHER_SITE">外部</label><input type="text" name="profileOuterURL" id="profileOuterURL" class="outerURL"><br>
        <label><input type="radio" name="useProfile" id="useProfileOwn" value="OWN_SITE">内部</label>
      </div>
      <div class="sendSelectPage">
        <input type="button" value="使用ページ反映" id="sendSeleProfPage" onclick="updUsePage('ALBUM');" disabled="disabled">
      </div>
    </div>
    <hr>

    <div id="tabProfMain">
      <div id="tabProfTop">
        <input type="button" value="新規プロファイル" onclick="newProf()"><br><br>
      </div>

      <div id="tabProfMid" class="tabMid">
        <div id="profSeqListD">
        </div>
      </div>
    </div>
    <div class="grayPanel" id="grayPanelProf"></div>

    <div id="tabProfBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="表示順反映" id="bldProfList" onclick="updProfSeq();" disabled="disabled">
      <?php
        if(strcmp($mtn ,'Y') == 0) {
          print('&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="全プロファイル一括更新" id="rebldAllProf" onclick="updAllProf();">');
        }
      ?>
    </div>
  </div>

  <!-- システム -->
  <div id="tabsSystem" class="tabArea">
    <div id="tabSystemUsePage" class="resetFloat">
      使用するシステムページ<br>
      <div class="selectPage">
        <label><input type="radio" name="useSystemPage" id="useSystemPageOther" value="OTHER_SITE">外部</label><input type="text" name="systemOuterURL" id="systemOuterURL" class="outerURL"><br>
        <label><input type="radio" name="useSystemPage" id="useSystemPageOwn" value="OWN_SITE">内部</label>
      </div>
      <div class="sendSelectPage">
        <input type="button" value="使用ページ反映" id="sendSeleSystemPage" onclick="updUsePage('SYSTEM');" disabled="disabled">
      </div>
    </div>
    <hr>

    <div id="tabSystemMain">
      <div id="tabSystemTop">
        システム内容<span class="required">*</span>
      </div>

      <div id="tabSystemMid" class="tabMid">
        <textarea id="systemStr" name="systemStr" cols="60" rows="4"></textarea>
        <div id="warnSystemStr" class="parsley-errors-list filled"></div>
      </div>
    </div>
    <div class="grayPanel" id="grayPanelSystem"></div>

    <div id="tabSystemBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldSystemInfo" onclick="writePriceVal();">  <!--  disabled="disabled" -->
    </div>
  </div>

  <!-- 求人 -->
  <div id="tabsRecruit" class="tabArea">
    <div id="tabRecruitUsePage" class="resetFloat">
      使用する求人ページ<br>
      <div class="selectPage">
        <label><input type="radio" name="useRecruitPage" id="useRecruitPageOther" value="OTHER_SITE">外部</label><input type="text" name="recruitOuterURL" id="recruitOuterURL" class="outerURL"><br>
        <label><input type="radio" name="useRecruitPage" id="useRecruitPageOwn" value="OWN_SITE">内部</label>
      </div>
      <div class="sendSelectPage">
        <input type="button" value="使用ページ反映" id="sendSeleRecruitPage" onclick="updUsePage('RECRUIT');" disabled="disabled">
      </div>
    </div>
    <hr>

    <div id="tabRecruitMain">
      <div id="tabRecruitTop">
        求人内容<span class="required">*</span>
      </div>

      <div id="tabSystemMid" class="tabMid">
        <textarea id="recruitStr" name="recruitStr" cols="60" rows="4"></textarea>
        <div id="warnRecruitStr" class="parsley-errors-list filled"></div>
      </div>
    </div>
    <div class="grayPanel" id="grayPanelRecruit"></div>

    <div id="tabRecruitBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldRecruitInfo" onclick="writeRecruitVal();">  <!--  disabled="disabled" -->
    </div>
  </div>


  <!-- 写メ日記 -->
  <div id="tabsPhotoDiary" class="tabArea">
    <div id="tabPhotoDiaryUsePage" class="resetFloat">
      使用する写メ日記ページ<br>
      <div class="selectPage">
        <label><input type="radio" name="usePhotoDiaryPage" id="usePhotoDiaryPageOther" value="OTHER_SITE">外部</label><input type="text" name="photoDiaryOuterURL" id="photoDiaryOuterURL" class="outerURL"><br>
        <label><input type="radio" name="usePhotoDiaryPage" id="usePhotoDiaryPageOwn" value="OWN_SITE" disabled="disabled">内部</label>
      </div>
      <div class="sendSelectPage">
        <input type="button" value="使用ページ反映" id="sendSelePhotoDiaryPage" onclick="updUsePage('PHOTODIARY');">
      </div>
    </div>
    <hr>
  </div>


  <!-- ヘッダ -->
  <div id="tabsHeader" class="tabArea">
    <div class="tabHeaderEnter">
      <div id="tabHeaderTop">
        TOP
      </div>
      <div id="tabHeaderTopMid" class="tabMid">
        <table class="headerImgSele">
          <thead>
            <tr>
              <th class="headerImgTN" colspan="2">画像ファイル</th>
              <th class="headerImgDisp">表示</th>
            </tr>
          </thead>
          <tbody id="headerTopImgList">
            <tr id="headerTopImg-A">
              <td class="headerImgTN" id="headerTopImgTNA">aaa</td>
              <td class="headerImgSele"><input type="button" value="画像選択" name="attHeaderTopImgA" id="attHeaderTopImgA" onclick="showSeleImg('TOP_HEADER' ,'A')"></td>
              <td class="headerImgDisp"><input type="checkbox" name="useHeaderTopImgA" id="useHeaderTopImgA" class="useHeaderTopImg" value="U"></td>
            </tr>
            <tr id="headerTopImg-B">
              <td class="headerImgTN" id="headerTopImgTNB">aaa</td>
              <td class="headerImgSele"><input type="button" value="画像選択" name="attHeaderTopImgB" id="attHeaderTopImgB" onclick="showSeleImg('TOP_HEADER' ,'B')"></td>
              <td class="headerImgDisp"><input type="checkbox" name="useHeaderTopImgB" id="useHeaderTopImgB" class="useHeaderTopImg" value="U"></td>
            </tr>
            <tr id="headerTopImg-C">
              <td class="headerImgTN" id="headerTopImgTNC">aaa</td>
              <td class="headerImgSele"><input type="button" value="画像選択" name="attHeaderTopImgC" id="attHeaderTopImgC" onclick="showSeleImg('TOP_HEADER' ,'C')"></td>
              <td class="headerImgDisp"><input type="checkbox" name="useHeaderTopImgC" id="useHeaderTopImgC" class="useHeaderTopImg" value="U"></td>
            </tr>
            <tr id="headerTopImg-D">
              <td class="headerImgTN" id="headerTopImgTND">aaa</td>
              <td class="headerImgSele"><input type="button" value="画像選択" name="attHeaderTopImgD" id="attHeaderTopImgD" onclick="showSeleImg('TOP_HEADER' ,'D')"></td>
              <td class="headerImgDisp"><input type="checkbox" name="useHeaderTopImgD" id="useHeaderTopImgD" class="useHeaderTopImg" value="U"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="headerTopImgA" value="">
        <input type="hidden" id="headerTopImgB" value="">
        <input type="hidden" id="headerTopImgC" value="">
        <input type="hidden" id="headerTopImgD" value="">
      </div>
    </div>

    <div class="tabHeaderEnter">
      <div id="tabHeaderOther">
        TOP以外
      </div>
      <div id="tabHeaderOtherMid" class="tabMid">
        <table class="headerOtherImgSele">
          <thead>
            <tr>
              <th class="headerOtherImgTN">画像ファイル</th>
              <th class="headerOtherImgDisp">表示</th>
            </tr>
          </thead>
          <tbody id="headerOtherImgList">
            <tr id="headerOtherImgT">
              <td class="headerOtherImgTN" id="headerOtherImgTN">aaa</td>
              <td class="headerOtherImgSele"><input type="button" value="画像選択" name="attHeaderOtherImg" id="attHeaderOtherImg" onclick="showSeleImg('OTHER_HEADER')"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="headerOtherImg" value="">
      </div>
    </div>

    <div id="tabHeaderBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldHeaderImgDispSeq" onclick="updHeaderImgSeq();" disabled="disabled">
    </div>
  </div>

  <!-- TOP -->
  <div id="tabsTop" class="tabArea">
    <div class="tabTopImgEnter">
      <div id="tabTopSystem">
        システム
      </div>
      <div id="tabTopSystemMid" class="tabMid">
        <table class="topSystemImgSele">
          <thead>
            <tr>
              <th class="topSystemImgTN">画像ファイル</th>
              <th class="topSystemImgDisp">表示</th>
            </tr>
          </thead>
          <tbody id="topSystemImgList">
            <tr id="topSystemImg">
              <td class="topSystemImgTN" id="topSystemImgTN">aaa</td>
              <td class="topSystemImgSele"><input type="button" value="画像選択" name="attTopSystemImg" id="attTopSystemImg" onclick="showSeleImg('TOP' ,'SYSTEM')"></td>
              <td class="topImgDisp"><input type="checkbox" name="useTopSystemImg" id="useTopSystemImg" class="useTopImg" value="U"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="topSystemImg" value="">
      </div>

      <div id="tabTopRecruit">
        求人
      </div>
      <div id="tabTopRecruitMid" class="tabMid">
        <table class="topRecruitImgSele">
          <thead>
            <tr>
              <th class="topRecruitImgTN">画像ファイル</th>
              <th class="topRecruitImgDisp">表示</th>
            </tr>
          </thead>
          <tbody id="topRecruitImgList">
            <tr id="topRecruitImg">
              <td class="topRecruitImgTN" id="topRecruitImgTN">aaa</td>
              <td class="topRecruitImgSele"><input type="button" value="画像選択" name="attTopRecruitImg" id="attTopRecruitImg" onclick="showSeleImg('TOP' ,'RECRUIT')"></td>
              <td class="topImgDisp"><input type="checkbox" name="useTopRecruitImg" id="useTopRecruitImg" class="useTopImg" value="U"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="topRecruitImg" value="">
      </div>

      <hr>
      区画<br>
      <table>
        <tr>
          <td>タイトル文字色</td><td><input type="color" id="areaTitleStr" name="areaTitleStr"></td>
        </tr>
        <tr>
          <td>タイトル背景色</td><td><input type="color" id="areaTitleBG" name="areaTitleBG"></td>
        </tr>
        <tr>
          <td>区画背景色</td><td><input type="color" id="areaBG" name="areaBG"></td>
        </tr>
      </table>
    </div>

    <div id="tabTopBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldTopImg" onclick="updTopImg();">    <!--  disabled="disabled" -->
    </div>
  </div>

  <!-- 装飾 -->
  <div id="tabsDecoration" class="tabArea">
    <div id="tabRecruitUsePage" class="resetFloat">
      ページ背景<br>
      <div class="selectPage">
        <label><input type="radio" name="usePageBG" id="usePageBGNotuse" value="N">使用しない</label>&nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="usePageBG" id="usePageBGColor"  value="C">色</label><input type="color" id="pageBGColor" name="pageBGColor">&nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="usePageBG" id="usePageBGImage"  value="I">画像</label>

        <table class="decoBGImgSele">
          <thead>
            <tr>
              <th class="decoBGImgTN">画像ファイル</th>
              <th class="decoBGImgDisp">表示</th>
            </tr>
          </thead>
          <tbody id="decoBGImgList">
            <tr id="decoBGImg">
              <td class="decoBGImgTN" id="decoBGImgTN">aaa</td>
              <td class="decoBGImgSele"><input type="button" value="画像選択" name="attDecoBGImg" id="attDecoBGImg" onclick="showSeleImg('ALL_BG')"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="decoBGImg" value="">
      </div>


<!--
      <div class="sendSelectPage">
        <input type="button" value="使用ページ反映" id="sendSeleRecruitPage" onclick="updUsePage('RECRUIT');" disabled="disabled">
      </div>
-->


    </div>
    <hr>

<!--
    <div id="tabRecruitMain">
      <div id="tabRecruitTop">
        求人内容<span class="required">*</span>
      </div>

      <div id="tabSystemMid" class="tabMid">
        <textarea id="recruitStr" name="recruitStr" cols="60" rows="4"></textarea>
        <div id="warnRecruitStr" class="parsley-errors-list filled"></div>
      </div>
    </div>
    <div class="grayPanel" id="grayPanelRecruit"></div>
-->

    <div id="tabRecruitBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldRecruitInfo" onclick="writeDecoVal();">
    </div>
  </div>


  <div id="tabsBlog" class="tabArea NOTUSE">
    <input type="button" value="新規記事" onclick="newBlog()">&nbsp;&nbsp;
    <br><br>
    <table id="blogList">
      <thead id="blogListH">
      </thead>
      <tbody id="blogListD">
      </tbody>
      <!-- <tr class="nodrop nodrag"> -->
    </table>
    <hr>
    <input type="button" value="表示可否反映" id="bldBlogListA" onclick="updBlogDisp();">    <!--  disabled="disabled" -->
  </div>
</div>
<br>
<div id="divLogout"><input type="button" value="ログアウト" onclick="logout()"></div>&nbsp;&nbsp;&nbsp;&nbsp;<a href="../top2018.html" target="_blank">テスト表示</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="../mo/index2018.html" target="_blank">携帯用テスト表示</a>
<div id="lastDate">V1R3</div>


<!-- -- 編集ダイアログ -- -->
<!-- --ニュース編集------------------------------------------------------------- -->
<div id="editNews" title="Dialog Title">
  <form id="enterNews" data-parsley-validate data-parsley-trigger="keyup focusout change input">
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
          <td>記事概要<span class="required">*</span></td>
          <td>
            <textarea id="digest" name="digest" cols="60" rows="4" required="" data-parsley-trigger="change"></textarea>    <!--    data-parsley-trigger="change"     -->
            <div id="warnDigest" class="parsley-errors-list filled"></div>
          </td>
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

  <br class="clear">
  <input type="hidden" id="editNewsNo" name="editNewsNo" value="">
</div>


<!-- --------------------------------------------------------------------------- -->
<!-- --定型文編集--------------------------------------------------------------- -->
<div id="editFixPhrase" title="定型文編集">
  <div id="editLeftNFP">
    <textarea id="fixPhraseStr" name="fixPhraseStr" cols="60" rows="18"></textarea>
    <div id="warnFixPhraseStr" class="parsley-errors-list filled"></div>
  </div>
  <br class="clear">
</div>



<!-- --------------------------------------------------------------------------- -->
<!-- --プロファイル編集--------------------------------------------------------- -->
<div id="editProfDlg" title="Dialog Title" class="NOTUSE">
  <div id="profileA">
    <form id="enterProfile" data-parsley-validate data-parsley-trigger="keyup focusout change input">
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
            </td>
            <div id="warnAppComment" class="parsley-errors-list filled"></div>
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
    </form>

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
            <th class="sepF">画像ファイル</th>
            <th class="sepD">表示</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="sepI">1</td>
            <td class="sepF"><input type="file" name="attF1" id="attF1"><br><div id="currF1"></div></td>
            <td class="sepD"><input type="checkbox" name="useP1" id="useP1" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">2</td>
            <td class="sepF"><input type="file" name="attF2" id="attF2"><br><div id="currF2"></div></td>
            <td class="sepD"><input type="checkbox" name="useP2" id="useP2" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">3</td>
            <td class="sepF"><input type="file" name="attF3" id="attF3"><br><div id="currF3"></div></td>
            <td class="sepD"><input type="checkbox" name="useP3" id="useP3" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">4</td>
            <td class="sepF"><input type="file" name="attF4" id="attF4"><br><div id="currF4"></div></td>
            <td class="sepD"><input type="checkbox" name="useP4" id="useP4" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">5</td>
            <td class="sepF"><input type="file" name="attF5" id="attF5"><br><div id="currF5"></div></td>
            <td class="sepD"><input type="checkbox" name="useP5" id="useP5" class="usePhoto" value="U"></td>
          </tr>
          <tr>
            <td class="sepI">サムネイル</td>
            <td class="sepF"><input type="file" name="attTN" id="attTN"><br><div id="currTN"></div></td>
            <td class="sepD"><input type="checkbox" name="useTN" id="useTN" class="usePhoto" value="U"></td>
          </tr>
          <tr class="NOTUSE">
            <td class="sepI2">携帯大写真</td>
            <td class="sepF2"><input type="file" name="attML" id="attML"><br><div id="currML"></div></td>
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
</div>



<!-- --------------------------------------------------------------------------- -->
<!-- --画像選択--------------------------------------------------------------- -->
      <!-- <input type="hidden" name="imgClass" id="imgClass" value=""> -->
      <!-- <input type="hidden" name="place" id="place" value=""> -->
      <!-- <input type="hidden" name="imgParam1" id="imgParam1" value=""> -->
<div id="selectImgFile" title="画像選択">
  <div id="imgList" class="imgList resetFloat"></div>
  <input type="button" value="新規画像" onclick="seleNewImg();">

  <div id="seleNewImg">
    <form id="enterNewImgFile" data-parsley-validate data-parsley-trigger="keyup focusout change input">
      <hr>
      <input type="file" name="imgFileSele" id="imgFileSele"><br>
      <div id="warnImgFile" class="parsley-errors-list filled"></div>
      タイトル：<input type="text" name="imgTitle" id="imgTitle" required=""><br>
      <input type="button" value="追加" id="addNewImgBtn" onclick="addNewImg();" disabled="disabled">
    </form>
  </div>

</div>



<!-- --------------------------------------------------------------------------- -->
<!-- --写メ日記編集------------------------------------------------------------- -->
<div id="editPhotoDiary" title="Dialog Title">
  <div id="editLeftNPD">
    <table>
      <tr>
        <td>タイトル</td>
        <td><input type="text" id="titlePD" name="titlePD" size="35" value=""></td>
      </tr>
      <tr>
        <td>日記日付</td>
        <td><input type="text" id="newsDatePD" name="newsDatePD" size="35" value=""></td>
      </tr>

      <tr>
        <td colspan="2"><hr></td>
      </tr>

      <tr>
        <td>記事本体</td>
        <td>
          <div class="tagPanel">
            &nbsp;&nbsp;&nbsp;&nbsp;<!-- <br> -->
            <input type="button" value="太字" onclick="surroundHTML('BOLD' ,'/BOLD');">
            <input type="button" value="SIZE+1" onclick="surroundHTML('FONTSZL' ,'/FONTSZ');">
          </div>

          <textarea id="contentPD" name="contentPD" cols="60" rows="18" onblur="setEditArea('contentPD');"></textarea>
        </td>
      </tr>
    </table>
    <div class="delNews"><input type="button" value="削除" id="delPDBtn" onclick="cfmDelNews();" style="display:none;"></div>
  </div>

  <div id="DelPDiaryDlg" class="cfmDelPrompt ui-draggable" style="position: absolute; z-index: 99999; padding: 0px; margin: 0px; min-width: 310px; max-width: 310px; top: 329px; left: 436.5px;   display:none">
    <h1 id="popup_titleDelNews" style="cursor: move;">記事の削除</h1>
    <div id="popup_contentDelNews" class="confirm">
      <div id="popup_messageDelNews">記事を削除しますか？<br>この操作は取り消せません</div>
      <div id="popup_panelDelNews">
        <input type="button" value="&nbsp;はい&nbsp;" onclick="delNewsItem();">
        <input type="button" value="&nbsp;キャンセル&nbsp;" onclick="hideDelNews();">
      </div>
    </div>
  </div>


  <br class="clear">
  <input type="hidden" id="editDiaryDir" name="editDiaryDir" value="">
  <input type="hidden" id="editDiaryDT" name="editDiaryDT" value="">
</div>


<!-- --------------------------------------------------------------------------- -->
<!-- --日記表示------------------------------------------------------------- -->
<div id="showPhotoDiary" title="Dialog Title">
  <div id="editLeftN">
    <table id="showDiaryDetail">
      <tr>
        <td class="showDiaryDetailP">タイトル</td>
        <td id="showTitlePD"></td>
      </tr>
      <tr>
        <td class="showDiaryDetailP">記事日付</td>
        <td id="showDiaryDatePD"></td>
      </tr>

      <tr>
        <td class="showDiaryDetailP">記事本体</td>
        <td><textarea id="showContentPD" name="showContentPD" cols="60" rows="18"></textarea></td>
      </tr>
    </table>
  </div>

  <br class="clear">
</div>
</body>
</html>
