<script src="<?= base_url('assets/js/jquery-3.3.1.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/print.min.js') ?>"></script>
<script src="<?= base_url('assets/js/custom.js') ?>"></script>

<script>
    base_url = '<?= base_url() ?>';

    function set_tokenSession(key, kvalue) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };
        xhttp.open("GET", base_url + "Token/Booking/set_tokenSession/" + key + "/" + kvalue, true);
        xhttp.send();
    }

    function PrintHtml() {
        printJS(base_url + 'Token/Booking/generatePDF');
    }

    function PrintImage() {
        var prnsize = 3;
        var url = "https://localhost.evolute.in:8989/Values/ImagePrint";
        var xmlhttp;
        var IfBMP = true;
        //var IfBMP = document.getElementById("if64checkimage").value;
        if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.open("POST", url, true);
        xmlhttp.withCredentials = true;
        xmlhttp.setRequestHeader("Content-Type", "application/json; charset=utf8");
        xmlhttp.setRequestHeader("Accept", "application/json");
        xmlhttp.setRequestHeader("Method", "POST");
        //xmlhttp.send(JSON.stringify(txt_BMPData.value + "|" + IfBMP));
        xmlhttp.send(JSON.stringify(txt_ImageData.value + "|" + IfBMP + "|" + prnsize));
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                alert(xmlhttp.responseText);
                //PrintTextData();
            }
            xmlhttp.onerror = function() {
                alert("Check If Evolute Service/Utility is Running");
            }
        }
    }
</script>

</body>

</html>