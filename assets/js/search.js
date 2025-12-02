function showResult(str) {
    if (str.length==0) {
        document.getElementsByClassName("livesearch").innerHTML="";
        document.getElementsByClassName("livesearch").style.border="0px";
        return;
    }
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementsByClassName("livesearch").innerHTML=this.responseText;
            document.getElementsByClassName("livesearch").style.border="1px solid #A5ACB2";
        }
    }
    xmlhttp.open("GET","livesearch.php?q="+str,true);
    xmlhttp.send();
}