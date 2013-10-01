<h2><?php echo $user['name']?>さんのページ</h2>

<form id="tweet">
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
$(function(){
  display(upNum);
  add_button(upNum);
  $("#tweet").submit(function(e) {
      e.preventDefault();
      var $form = $(this);
      $.ajax({
        type: "POST",
        url:"insert_tweet",
        data:$(this).serialize(),
        success: function(result, textStatus, xhr){
          $form[0].reset();
          display(upNum);
          add_button(upNum);
        }
      });
    })
  $("button").click(function() {
    upNum = upNum + 10;
    display(upNum);
    add_button(upNum);
  })
})
function display(limit)
{
   $("li").remove();
   $("div").remove();
   send(0,limit);
}
function send(i,limit)
{
    if(i<limit){
      var num = {num: i};
      $.ajax({
        type: "POST",
        url:"send_tweet",
        dataType:"json",
        data: num,
        success: function(data, dataType){
          var tweet_data = eval(data);
          $("p#display").append("<li>"+tweet_data.time+"</li><div>"+tweet_data.substance+"</br></br></div>");
          send(i+1,limit);
        }
      });
    }
    else return;
}
function add_button(limit)
{
    $("button").remove();
    $.ajax({
      type: "POST",
      url:"tweet_num",
      success: function(data,dataType){
        if (data > limit) {
          $("a#logout").before("<button>もっとみる</button>");
        }
      }
   });
}
</script>

<h4 id="display">過去のツイート</h4>
<p id="display">
</p>

<a id="logout" href="login"></br></br>ログアウト</a>



