<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>	
	<input type="text" id="username" name="username" value=""/><br>
	<input type="text" id="age" name="age" value=""/><br>
	<input type="button" class="button" value="提交">
	<script>
	  $(function(){
          $(".button").click(function(){
               var username =$("#username").val();
               var age =$("#age").val();
               $.ajax({
                  type:"post",
                  data:{"username":username,"age":age},
                  url:"<?php echo site_url('Index/index2'); ?>",
                  //url:"http://localhost/CodeIgniter-3.0.6/index.php/Index/index2",
                  dataType : "json",
                  success : function(data){
                      alert('1111');
                      if(data.status == "success"){
                  	       alert('222');
                      }       
                  }   
              });
          });
     });
	</script>
</body>
</html>  