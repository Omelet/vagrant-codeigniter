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
$(function(){
  $("#tweet").submit(function(e) {
      e.preventDefault();
      var $form = $(this);
      $.ajax({
        type: "POST",
        url:"insert_tweet",
        data:$(this).serialize(),
        success: function(result, textStatus, xhr){
          $form[0].reset();
          var num = {num: '1'}
          $.ajax({
            type: "POST",
            url:"send_tweet",
            dataType:"json",
            data: num,
            success: function(data, dataType){
                var tweet_data = eval(data);
                $("h4").after("<li>"+tweet_data.time+"</li>"+tweet_data.substance+"</br></br>");
            }
          });
        }
      });
    })
})
</script>

<h4>過去のツイート</h4>
<?php $count=0;?>
<?php foreach ($tweet as $tweet_item): ?>

    <li><?php echo $tweet_item['time']?></li>
    <?php echo $tweet_item['substance']?></br>
    </br>
<?php $count++;
    if ($count >= 10) {
        break;
    }?>
<?php endforeach ?>




