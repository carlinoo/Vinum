book/index view
<br><br><br>
<script>
//
send_ajax('POST', '/get_json', null, function(data) {
  console.log("SUCESS");
  console.log(data);
}, function(data){
  console.log("ERROR");
  console.log(data);
});

</script>
