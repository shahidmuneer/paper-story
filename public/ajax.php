<html>
</head></head>
<body>

<h1 id="loadedText">

</h1>
<h2>Count: <span id="count"></span></h2>
<button id="load">Load</button>
<script>
var count=document.getElementById("count");
count.innerHTML=0;
function loadDoc() {
    count.innerHTML++;
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("loadedText").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", "ajax_info.txt", true);
  xhttp.send();
}
document.getElementById("load").addEventListener("click",function(){

    loadDoc();
});

</script>
</body>
</html>