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

  $vesion = 'V=1&R=1&M=2';
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

<link href="../css2018/blog.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/news.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/prof.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/photoDiary.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/decoration.css?<?php print $vesion; ?>" rel="stylesheet">
<link href="../css2018/ageAuth.css?<?php print $vesion; ?>" rel="stylesheet">

<link href="../css2018/fileSele.css" rel="stylesheet">


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

<script src="../js2018/mtn/writeFile.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/writeFileMO.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/ctrlSess.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/logoutSess.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/mtnCommon.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/imgSelector.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/newsList.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/fixPhrase.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/profList.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/profOut.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/top.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/system.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/recruit.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/header.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/decoration.js?<?php print $vesion; ?>"></script>
<script src="../js2018/mtn/sideBar.js?<?php print $vesion; ?>"></script>

<script src="../js2018/mtn/ageAuth.js?<?php print $vesion; ?>"></script>

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
    <li><a href="#tabsTop">TOP</a></li>
    <li><a href="#tabsNews">ニュース</a></li>
    <li><a href="#tabsProfile">プロファイル</a></li>
    <li><a href="#tabsRecruit">求人</a></li>
    <li><a href="#tabsSystem">システム</a></li>
    <li><a href="#tabsPhotoDiary">写メ日記</a></li>

    <li><a href="#tabsHeader">ヘッダ</a></li>
    <li><a href="#tabsSideBar">サイドバー</a></li>
    <li><a href="#tabsDecoration">装飾</a></li>

    <li><a href="#tabsAgeAuth">年齢認証</a></li>
  </ul>

  <!-- ***** タブの中身の定義 ***** -->
  <!-- ニュース -->
  <div id="tabsNews" class="tabArea">
    <div id="tabNewsUsePage" class="resetFloat">
      <div class="sheetTitle">使用するニュースページ</div>
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
      <div id="tabNewsUpper">
        <div class="sheetTitle">現在のニュース</div>
        <div id="tabNewsTop">
          <input type="button" value="新規ニュース" onclick="newNews()">&nbsp;&nbsp;
          <input type="button" value="定型文編集" onclick="editFixPhrase()">
          <br><br>
        </div>
      </div>

      <div id="tabNewsMid" class="tabMid">
        <div id="tabNewsListD" class="panelScroll">
          <table id="newsList">
            <thead id="newsListH"></thead>
            <tbody id="newsListD"></tbody>
          </table>
        </div>
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
      <div class="sheetTitle">使用するプロファイルページ</div>
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
      <div id="tabProfUpper">
        <div class="sheetTitle">プロファイル一覧</div>
        <div id="tabProfTop">
          <input type="button" value="新規プロファイル" onclick="newProf()"><br><br>
        </div>
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
      <div class="sheetTitle">使用するシステムページ</div>
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
      <div class="sheetTitle">システムページの内容</div>
      <div id="tabSystemTop">
        システム内容<span class="required">*</span>
      </div>

      <div id="tabSystemMid" class="tabMid">
        <table>
          <tbody id="systemImgList">
            <tr id="systemImg">
              <td class="systemImgTN"><div id="systemImgTN"></div></td>
              <td class="systemImgTN"><input type="button" value="画像選択" name="attSystemImg" id="attSystemImg" onclick="showSeleImg('SYSTEM')"></td>
              <td class="systemStr">
                <textarea id="systemStr" name="systemStr1" cols="60" rows="4" required="" data-parsley-trigger="change"></textarea>
                <div id="warnSystemStr" class="parsley-errors-list filled"></div>
              </td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="systemImg" value="">
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
      <div class="sheetTitle">使用する求人ページ</div>
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
      <div class="sheetTitle">求人ページの内容</div>
      <div id="tabRecruitTop">
        求人内容<span class="required">*</span>
      </div>

      <div id="tabRecruitMid" class="tabMid">
        <table>
          <tbody id="recruitImgList">
            <tr id="recruitImg">
              <td class="recruitImgTN"><div id="recruitImgTN"></div></td>
              <td class="recruitImgTN"><input type="button" value="画像選択" name="attRecruitImg" id="attRecruitImg" onclick="showSeleImg('RECRUIT')"></td>
              <td class="recruitStr">
                <textarea id="recruitStr" name="recruitStr1" cols="60" rows="4" required="" data-parsley-trigger="change"></textarea>
                <div id="warnRecruitStr" class="parsley-errors-list filled"></div>
              </td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="recruitImg" value="">
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
      <div class="sheetTitle">使用する写メ日記ページ</div>
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
      <div class="sheetTitle">TOP</div>
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
      <div class="sheetTitle">TOP以外</div>
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
    <div class="tabTopImgEnter panelScroll">
      <div id="tabTopSystemMid" class="tabMid">
        <div>
          <div class="sheetTitle">画像</div>
          <table class="topSystemImgSele">
            <thead>
              <tr>
                <th>区分</th>
                <th colspan="2">現在の画像</th>
                <th>文言</th>
                <th>表示</th>
              </tr>
            </thead>
            <tbody id="topSystemImgList">
              <tr id="topSystemImgT">
                <td class="topSystemImgTN">システム</td>
                <td class="topSystemImgTN" id="topSystemImgTN"></td>
                <td class="topSystemImgSele"><input type="button" value="画像選択" name="attTopSystemImg" id="attTopSystemImg" onclick="showSeleImg('TOP' ,'SYSTEM')"></td>
                <td class="topSystemStr"><input type="text" name="topSystemStr" id="topSystemStr" class="topStr"></td>
                <td class="topImgDisp"><input type="checkbox" name="useTopSystemImg" id="useTopSystemImg" class="useTopImg" value="U"></td>
              </tr>
              <tr><td colspan="5"><hr></td></tr>
              <tr id="topRecruitImgT">
                <td class="topRecruitImgTN">求人</td>
                <td class="topRecruitImgTN" id="topRecruitImgTN"></td>
                <td class="topRecruitImgSele"><input type="button" value="画像選択" name="attTopRecruitImg" id="attTopRecruitImg" onclick="showSeleImg('TOP' ,'RECRUIT')"></td>
                <td class="topRecruitStr"><input type="text" name="topRecruitStr" id="topRecruitStr" class="topStr"></td>
                <td class="topImgDisp"><input type="checkbox" name="useTopRecruitImg" id="useTopRecruitImg" class="useTopImg" value="U"></td>
              </tr>
            </tbody>
          </table>
          <input type="hidden" id="topSystemImg"  value="">
          <input type="hidden" id="topRecruitImg" value="">
        </div>

        <hr>
        <div class="sheetTitle">区画</div>
        <div class="topColorSelect">
          <table>
            <tr>
              <th>タイトル文字色</th><td><input type="text" id="areaTitleStr" name="areaTitleStr" class="topColorwell"></td>
            </tr>
            <tr>
              <th>タイトル背景色</th><td><input type="text" id="areaTitleBG" name="areaTitleBG" class="topColorwell"></td>
            </tr>
            <tr>
              <th>区画背景色</th><td><input type="text" id="areaBG" name="areaBG" class="topColorwell"></td>
            </tr>
          </table>
        </div>
        <div id="topColorPicker" class="colorSelectWheel"></div>
      </div>
    </div>

    <div id="tabTopBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldTopImg" onclick="updTopImg();">    <!--  disabled="disabled" -->
    </div>
  </div>

  <!-- 装飾 -->
  <div id="tabsDecoration" class="tabArea">
    <div id="tabRecruitUsePage" class="resetFloat">
      <div class="sheetTitle">ページ背景</div>
      <div class="selectPage">
        <div class="decoBg">
          <label><input type="radio" name="usePageBG" id="usePageBGNotuse" value="N">使用しない</label>&nbsp;&nbsp;&nbsp;
          <label><input type="radio" name="usePageBG" id="usePageBGColor"  value="C">色</label>
        </div>
        <div class="decoColorSele">
          <div>
            <input type="text" id="pageBGColor" name="pageBGColor" class="decoColorwell">
          </div>
          <div id="decoColorPicker" class="colorSelectWheel">
          </div>
        </div>
        <div class="decoBg">
          &nbsp;&nbsp;&nbsp;<label><input type="radio" name="usePageBG" id="usePageBGImage"  value="I">画像</label>
        </div>
        <table class="decoBGImgSele">
          <tbody id="decoBGImgList">
            <tr id="decoBGImg">
              <td class="decoBGImgTN" id="decoBGImgTN"></td>
              <td class="decoBGImgSele"><input type="button" value="画像選択" name="attDecoBGImg" id="attDecoBGImg" onclick="showSeleImg('ALL_BG')"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="decoBGImg" value="">
      </div>
    </div>
    <hr>

    <div id="tabRecruitBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldRecruitInfo" onclick="writeDecoVal();">
    </div>
  </div>

  <!-- サイドバー -->
  <div id="tabsSideBar" class="tabArea">
    <div class="tabSideBarImgEnter">
      <div class="sheetTitle">表示する内容</div>
      <div id="tabSideBarImg1Mid" class="tabMid">
        <table class="sideBarImgSele">
          <thead>
            <tr>
              <th>No.</th>
              <th colspan="2">画像</th>
              <th>文言</th>
              <th>表示</th>
            </tr>
          </thead>
          <tbody id="sideBarSystemImgList">
            <tr id="sideBarImg">
              <td class="sideBarImgTN">1</td>

              <td class="sideBarImgTN"><div id="sideBarImgTN1"></div></td>
              <td class="sideBarImgTN"><input type="button" value="画像選択" name="attSideBarImg1" id="attSideBarImg1" onclick="showSeleImg('SIDEBAR' ,'1')"></td>

              <td class="sideBarStr">
                <textarea id="sideBarStr1" name="sideBarStr1" cols="60" rows="4" required="" data-parsley-trigger="change"></textarea>
                <div id="warnSideBarStr1" class="parsley-errors-list filled"></div>
              </td>
              <td class="sideBarImgDisp"><input type="checkbox" name="useSideBarImg1" id="useSideBarImg1" class="useSideBarImg" value="U"></td>
            </tr>

            <tr>
              <td colspan="5"><hr></td>
            </tr>

            <tr>
              <td class="sideBarImgTN">2</td>

              <td class="sideBarImgTN"><div id="sideBarImgTN2"></div></td>
              <td class="sideBarImgTN"><input type="button" value="画像選択" name="attSideBarImg2" id="attSideBarImg2" onclick="showSeleImg('SIDEBAR' ,'2')"></td>

              <td class="sideBarStr">
                <textarea id="sideBarStr2" name="sideBarStr2" cols="60" rows="4" required="" data-parsley-trigger="change"></textarea>
                <div id="warnSideBarStr2" class="parsley-errors-list filled"></div>
              </td>
              <td class="sideBarImgDisp"><input type="checkbox" name="useSideBarImg2" id="useSideBarImg2" class="useSideBarImg" value="U"></td>
            </tr>
          </tbody>
        </table>
        <input type="hidden" id="sideBarImg1" value="">
        <input type="hidden" id="sideBarImg2" value="">
      </div>
    </div>

    <div id="tabSideBarBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldSideBarImg" onclick="updSideBarImg();">    <!--  disabled="disabled" -->
    </div>
  </div>

  <!-- 年齢認証 -->
  <div id="tabsAgeAuth" class="tabArea">
    <div class="tabAgeAuthImgEnter panelScroll">
      <div id="tabAgeAuthMid" class="tabMid">
        <div>
          <div class="sheetTitle">画像</div>
          <table class="ageAuthImgSele">
            <thead>
              <tr>
                <th colspan="2">現在の画像</th>
              </tr>
            </thead>
            <tbody id="ageAuthImgList">
              <tr id="ageAuthImgT">
                <td class="ageAuthImgTN" id="ageAuthImgTN"></td>
                <td class="ageAuthImgSele"><input type="button" value="画像選択" name="attAgeAuthImg" id="attAgeAuthImg" onclick="showSeleImg('AGE_AUTH' ,'TOP')"></td>

                <td class="ageAuthStr">
                  <textarea id="ageAuthStr" name="ageAuthStr" cols="60" rows="4" required="" data-parsley-trigger="change"></textarea>
                  <div id="warnAgeAuthStr" class="parsley-errors-list filled"></div>
                </td>

              </tr>

            </tbody>
          </table>
          <input type="hidden" id="ageAuthImg" value="">
        </div>

        <hr>
        <div class="sheetTitle">リンク</div>
          <input type="button" value="新規リンク" onclick="newAgeAuthLink()">
          <table class="ageAuthLinkList">
            <thead>
              <tr><th>サイト名</th><th>URL</th><th>バナー</th><th>編集</th></tr>
            </thead>
            <tbody id="ageAuthLink">
            </tbody>
          </table>
      </div>
    </div>

    <div id="tabAgeAuthBottom" class="tabBottomBtn">
      <hr>
      <input type="button" value="出力" id="bldAgeAuthImg" onclick="updAgeAuthImg();">    <!--  disabled="disabled" -->
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
<div id="divLogout"><input type="button" value="ログアウト" onclick="logout()"></div>&nbsp;&nbsp;&nbsp;&nbsp;テスト表示：<a href="../index2018.html" target="_blank">年齢認証</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="../top2018.html" target="_blank">PC用</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="../mo/index2018.html" target="_blank">携帯用</a>

<div id="lastDate">V1R3</div>


<!-- -- 編集ダイアログ -- -->
<!-- --ニュース編集------------------------------------------------------------- -->
<div id="editNewsDlg" title="Dialog Title">
	<iframe src="enterNews.php?id=" name="editNewsForm" id="editNewsForm" class="editNewsForm" title="ニュース編集" frameborder="0"></iframe>
</div>

<!-- --定型文編集--------------------------------------------------------------- -->
<div id="editFixPhraseDlg" title="定型文">
	<iframe src="enterFixPhrase.php" name="editFixPhraseForm" id="editFixPhraseForm" class="editFixPhraseForm" title="定型文編集" frameborder="0" height="auto"></iframe>
</div>

<!-- --プロファイル編集--------------------------------------------------------- -->
<div id="editProfDlg" title="Dialog Title">
	<iframe src="enterProfile.php?id=" name="editProfile" id="editProfile" class="editProfile" title="プロファイル編集" frameborder="0"></iframe>
</div>

<!-- --年齢認証のリンク------------------------------------------------------------- -->
<div id="editAgeAuthLinkDlg" title="リンク">
  <div id="editLeftNAA">
    <table>
      <tr>
        <td>サイト名</td>
        <td><input type="text" id="siteNameAA" name="siteNameAA" size="35" value=""></td>
      </tr>
      <tr>
        <td>URL</td>
        <td><input type="text" id="urlAA" name="urlAA" size="35" value=""></td>
      </tr>
      <tr>
        <td>バナー</td>
        <td>
          <div class="selectPage">
            <table>
              <tr>
                <td><label><input type="radio" name="ageAuthLinkImg" id="ageAuthLinkImgURL" value="URL">URL指定</label></td>
                <td><input type="text" id="imgURLAA" name="imgURLAA" size="35" value=""></td>
              </tr>
              <tr>
                <td><label><input type="radio" name="ageAuthLinkImg" id="ageAuthLinkImgFile" value="FILE">ファイル選択</label></td>
                <td><input type="button" value="画像選択" name="attAALink" id="attAALink" onclick="showSeleImg('AGE_AUTH' ,'LINK_EXCHANGE')"><input type="hidden" id="imgNOAA" name="imgNOAA" value=""></td>
              </tr>
              <tr>
                <td><label><input type="radio" name="ageAuthLinkImg" id="ageAuthLinkNoImg" value="NOBANNER">画像ナシ</label></td>
                <td></td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
    <input type="hidden" id="editAgeAuthLink" name="editAgeAuthLink" value="">
    <div class="delAALink"><input type="button" value="削除" id="delAALinkBtn" onclick="cfmDelAgeAuthLink();" style="display:none;"></div>
  </div>

  <br class="clear">
</div>

<div id="DelAALinkDlg" class="cfmDelPrompt ui-draggable">
  <h1 id="popup_titleDelAgeAuthLink" style="cursor: move;">リンクの削除</h1>
  <div id="popup_contentDelAgeAuthLink" class="confirm">
    <div id="popup_messageDelAgeAuthLink">リンクを削除しますか？<br>この操作は取り消せません</div>
    <div id="popup_panelDelAgeAuthLink">
      <input type="button" value="&nbsp;はい&nbsp;" onclick="delAgeAuthLink();">
      <input type="button" value="&nbsp;キャンセル&nbsp;" onclick="hideDelAgeAuthLink();">
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

  <div id="DelPDiaryDlg" class="cfmDelPrompt ui-draggable">
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


<div id="progressDlg" class="progressDlg ui-draggable">
  <h1 id="popup_titleProgress"></h1>
  <div id="popup_contentProgress" class="confirm">
    <div id="popup_messageProgress"></div>
    <div id="popup_panelProgress">
    </div>
  </div>
</div>

</body>
</html>
