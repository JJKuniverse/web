<?php include  $_SERVER['DOCUMENT_ROOT']."/POST/db.php"; ?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="/POST/css/style.css?after" />    
</head>
<body>
<div id="board_area"> 
  <h1>자유게시판</h1>
  <h4>자유롭게 글을 쓸 수 있는 게시판입니다.</h4>
  <div id="search_box">
    <from action="/POST/page/board/search_result.php" method="get">
      <select name="catgo">
        <option value="title">제목</option>
        <option value="name">글쓴이</option>
        <option value="content">내용</option>
      </select>
      <input type="text" name="search" size="40" required="required"  /> <button>검색</botton>
    </form>
   </div>
    <table class="list-table">
      <thead>
          <tr>
                <th width="70">번호</th>
                <th width="500">제목</th>
                <th width="120">글쓴이</th>
                <th width="100">작성일</th>
                <th width="100">조회수</th>
                <!-- 추천수 항목 추가 -->
                <th width="100">추천수</th> 
            </tr>
        </thead>
        <?php
         if(isset($_GET['page'])){
          $page = $_GET['page'];
            }else{
              $page = 1;
            }
              $sql = query("select * from board");
              $row_num = mysqli_num_rows($sql); //게시판 총 레코드 수
              $list = 10; //한 페이지에 보여줄 개수
              $block_ct = 5; //블록당 보여줄 페이지 개수

              $block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
              $block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
              $block_end = $block_start + $block_ct - 1; //블록 마지막 번호

              $total_page = ceil($row_num / $list); // 페이징한 페이지 수 구하기
              if($block_end > $total_page) $block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
              $total_block = ceil($total_page/$block_ct); //블럭 총 개수
              $start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

              $sql2 = query("select * from board order by idx desc limit $start_num, $list");  
              while($board = $sql2->fetch_array()){
              $title=$board["title"]; 
                if(strlen($title)>30)
                { 
                  $title=str_replace($board["title"],mb_substr($board["title"],0,30,"utf-8")."...",$board["title"]);
                }
                $con_idx = $board["idx"];
                $reply_count = query("SELECT COUNT(*) as cnt FROM reply where con_num=$con_idx");
                $rep_count = $reply_count->fetch_array();
              ?>
      <tbody>
        <tr>
          <td width="70"><?php echo $board['idx']; ?></td>
          <td width="500"><?php
          $locking = "<img scr ='/POST/img/lock.png' alt='lock' title='lock' width='20' height='20' />";
          $boardtime = $board['date'];    //$boardtime변수에 board['date']값을 넣음
          $timenow = date("Y-m-d");   //$timenow변수에 현재 시간 Y-M-D를 넣음

          if($boardtime==$timenow){
            $img = "<img src='/POST/img/new.png'valt='new' />";
          }else{
            $img = "";
          }

          if($board['lock_post']=="1")
            { ?><a herf='/POST/page/ck_read.php?idx=<?php echo $board["idx"];?>'><?php echo title."[".$rep_count["cnt"]."]", $locking, $img;
              }else{ ?>
          <a herf="/POST/read.php?idx=<?php echo $board["idx"];?>"><?php echo $title."[".$rep_count["cnt"]."]",$img;} ?></a></td>
          <td width="120"><?php echo $board['name']?></td>
          <td width="100"><?php echo $board['date']?></td>
          <td width="100"><?php echo $board['hit']; ?></td>
          <!-- 추천수 표시해주기 위해 추가한 부분 -->
          <td width="100"><?php echo $board['thumbup']?></td>
        </tr>
      </tbody>
      <?php } ?>
    </table>
    <!--페이징 넘버-->
    <div id="page_num">
      <ul>
        <?php
        if($page <= 1)
        { //만약 page가 1보다 작거나 같다면
        echo "<li class='fo_re'>처음</li>";   //처음이라는 글자에 빨간 색 표시
      }else{
        echo "<li><a herf='?page=1'>처음</a></li>";   //아니라면 처음 글자에 1번페이지로 갈 수 있게 링크
      }
      if($page <= 1)
      {   //만약 page가 1보다 작거나 같다면 빈값
      
      }else{
        $pre = $page-1;   //pre면수에 page-1을 해준다. 만약 현재 페이지가 3인데 이전 버튼을 누르면 2번 페이지로 갈 수 있게 함
        echo "<li><a herf='?page=$pre'>이전</a><li>";   //이전 글자에 pre 변수를 링크한다. 이러면 이전 버튼을 누를 떄마다 현재 페이지에서 -1하게 된다
      }
      for($i=$block_start; $i<=$block_end; $i++){
        //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록 시작 번호가 마지막 블록보다 작거나 같을 때까지 $i를 반복시킨다
        if($page == $i){  //만약 page가 $i와 같다면
          echo "<li class='fo_re'>[$i]</li>"; //아니라면 $i
        }
      }
      if($block_num >= $total_block){ //만약 현재 블록이 블록 총 개수보다 크거나같다면 빈 값
      }else{
        $next = $page + 1; //next 변수에 page+1을 해준다
        echo "<li><a herf='?page=$neext'>다음<?a><li>"; //다음 글자에 next 변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다
      }
      if($page >= $total_page){   //만약 page가 페이지 수보다 크거나 같다면
        echo "<li class='fo_re'>마지막</li>"; //마지막 글자에 굵은 빨간색을 적용한다
      }else{
        echo "<li><a herf='?page=$total_page'>마지막</a></li>";   //아니라면 마지막 글자에 total_page를 링크한다
      }
      ?>
      </ul>
    </div>
    <div id="write_btn">
      <a href="/page/board/write.php"><button>글쓰기</button></a>
    </div>
  </div>
</body>
</html>