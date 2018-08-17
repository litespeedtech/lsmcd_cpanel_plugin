function lsmcdLoadPlugin( doVal, post )
{
    var queryString = ( doVal != '' ) ? '?do=' + doVal : '';

    xhr = new XMLHttpRequest();
    xhr.open("POST", "lsmcd_usermgr.live.php" + queryString, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send(post);
    xhr.onreadystatechange = function () {

        if ( xhr.readyState == 4 && xhr.status == 200 ) {
            document.getElementById("lsmcdContent").innerHTML = xhr.responseText;
        }
    };
}