<h2><?php echo $user['name']?>さんのページ</h2>

<form id="tweet" name="tweet">
<?php echo form_open('tweet/main')?>
</br>
<h4>ツイート</h4>
<textarea name="tweet" rows="4" cols ="40"></textarea></br>
<input type = "submit" name = "submit" value = "ツイートする"/>
</form>
</br>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
var upNum = 10;
var tweetNum = 0;
$(function(){
  display(upNum,0);
  $("#tweet").submit(function(e) {
      e.preventDefault();
      var $form = $(this);
      if(document.tweet.tweet.value!="") {
        $.ajax({
          type: "POST",
          url:"insert_tweet",
          data:$(this).serialize(),
          success: function(result, textStatus, xhr) {
            $form[0].reset();
            $("li").remove();
            $("div").remove();
            display(upNum,0);
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
    var num = {up: up, low: low};
    $.ajax({
      type: "POST",
      url:"send_tweet",
      dataType:"json",
      data: num,
      success: function(data, dataType){
        tweetNum = data.tweet_num;
        $("button#showMore").remove();
        if(up>tweetNum){
          for(var i = low; i < tweetNum; i++) {
            $("p#display").append("<li>"+data[i]['time']+"</li><div>"+data[i]['substance']+"</br></br></div>");
          }
        } else {
          for(var i = low; i < up; i++) {
             $("p#display").append("<li>"+data[i]['time']+"</li><div>"+data[i]['substance']+"</br></br></div>");
           }
          $("a#logout").before('<button id=¥"showMore¥">もっとみる</button>');
        }
     }
    });
}
</script>

<h4 id="display">過去のツイート</h4>
<p id="display">
</p>

<a id="logout" href="login"></br></br>ログアウト</a>



