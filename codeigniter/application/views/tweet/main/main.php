<h2><?php echo $user['name']?>さんのページ</h2>

</br>
<h4>ツイート</h4>
<?php $attributes = array('id'=>'tweet','name'=>'tweet');
    echo form_open('tweet/insert_tweet',$attributes)?>
<textarea name="tweet" rows="4" cols ="40"></textarea></br>
<input type = "submit" name = "submit" value = "ツイートする"/>
</form>
</br>
<h4 id="display">過去のツイート</h4>
<p id="display">
</p>
<a id="logout" href="login"></br></br>ログアウト</a>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
var upNum = 10;
var tweetNum = 0;
var tweet_data;
var button_count=0;
$(function() {
  display(upNum,0);
  $("#tweet").submit(function(e) {
      e.preventDefault();
      var $form = $(this);
      if (document.tweet.tweet.value != "") {
        $.ajax({
          type: "POST",
          url:"insert_tweet",
          data:$(this).serialize(),
          success: function(data,dataType) {
            tweetNum = data['tweet_num'];
            $form[0].reset();
            $('li#'+(upNum-1)+'').remove();
            $('div#'+(upNum-1)+'').remove();
            for(var i = (upNum-2); i >= 0 ;i--){
              $('li#'+i+'').attr("id",""+(i+1)+"");
              $('div#'+i+'').attr("id",""+(i+1)+"");
            }
            $("p#display").prepend("<li id="+0+">" + data['last_tweet']['time'] + "</li><div id="+0+">"
                            + data['last_tweet']['substance'] + "</br></br></div>");
          }
        });
     }
  });
  $(document).on('click','button', function() {
    display(upNum+10,upNum);
    upNum = upNum + 10;
  });
});

function display(up,low)
{
    var output = { up:up, low:low};
    $.ajax({
      type: "GET",
      url:"send_tweet",
      dataType:"json",
      data: output,
      success: function(data, dataType) {
        tweetNum = data.tweet_num;
        $("button#"+upNum+"").remove();
        if (up>tweetNum){
          for (var i = low; i < tweetNum; i++) {
           data[i]['substance'] = data[i]['substance'].replace(/\r?\n/g, "<br />");
           $("p#display").append("<li id="+i+">" + data[i]['time'] + "</li><div id="+i+">"
              + data[i]['substance'] + "</br></br></div>");
          }
        } else {
          for (var i = low; i < up; i++) {
           data[i]['substance'] = data[i]['substance'].replace(/\r?\n/g, "<br />");
           $("p#display").append("<li id="+i+">" + data[i]['time'] + "</li><div id="+i+">"
              +  data[i]['substance'] + "</br></br></div>");
           }
           $("a#logout").before("<button id=" +(upNum+10)+ ">もっとみる</button>");
        }
     }
    });
}
</script>





