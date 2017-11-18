
This is book/index
<br><br><br>
<script>
//
send_ajax('POST', '/~mac/Vinum/book/json', null, function(data) {
  console.log("SUCESS");
  console.log(data);
}, function(data){
  console.log("ERROR");
  console.log(data);
});

</script>

<?php echo get_json_path; ?>

<br><br><br>
