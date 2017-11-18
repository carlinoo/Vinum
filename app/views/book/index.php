
<br><br><br>
<script>
//
send_ajax('POST', '/Vinum/book/json', null, function(data) {
  console.log("SUCESS");
  console.log(data);
}, function(data){
  console.log("ERROR");
  console.log(data);
});

</script>




<?php
 $arr = [];
 $arr[0] = 'get_json';

var_dump(get_route($arr)); ?>

<br><br><br>


<?php

 ?>
