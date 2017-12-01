book/index view
<br><br><br>
<script>
//

var form_data = {
  "hello": "hey"
}

send_ajax('POST', '/get_json', form_data, function(data) {
  console.log("SUCESS");
  console.log(data);
}, function(data){
  console.log("ERROR");
  console.log(data);
});

</script>
