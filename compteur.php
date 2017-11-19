<!doctype html>
<html lang="fr" >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Création Tâche">
    <link rel="shortcut icon" sizes="16x16" href="favicon_qp2.png"/>
    <link rel="stylesheet" href="style.css">

    <title>Pers_App</title>
</head>

<body>
    <div class="top"></br>
        <header>
            <ul id="menu_horizontal">
                <li><a class="link2" href="index.html">Tâche</a></li>
                <li><a class="link1" href="compteur.php">Elec</a></li>
                <li><a class="link2" href="course.html">Course</a></li>
                <li><a class="link2" href="cjoint.html">CJoint</a></li>
                <li><a class="link2" href="anniv.html">Anniv</a></li>                             
                <li><a class="link2" href="https://script.google.com/macros/s/AKfycbyVxkKu9mc1DMehLTyFyxwG6SJVdeWpGa1vE5eEtG_oilq9bHT6/exec">Liste</a></li>
                <li><a class="link2" href="https://drive.google.com/open?id=0B1dEKq5u4p-XVjZYbkdQSXZ3cHM">Ndf</a></li>
            </ul>
        </header>
    </div>
    
    <div class="bottom">        
        <form id="form_suivi" method="POST" action="https://script.google.com/macros/s/AKfycbxKWN0e2adN29p9x4TuLdqsoonGjzRpwIHeiL0OfgR-e4Il3Vc/exec">

            <label for="Compteur" class="classlabel">Compteur</label></br>
            <input id="Compteur" name="Compteur" class="classimput" placeholder="Relevé Elec (kwh)"  autocomplete="off"></br></br>

            <button class="button-success pure-button button-xlarge">
                <i></i>&nbsp;Envoyer</button>

        </form>
            
        <!-- Customise the Thankyou Message People See when they submit the form: -->
        <div style="display:none;" id="message_validation">
        <h2>Donnée correctement enregistrée </h2>
        </div>
            
        <!-- Submit the Form to Google Using "AJAX" -->
        <script type="text/javascript">
        
            // get all data in form and return object
            function getFormData() {
                var form = document.getElementById("form_suivi");
                var elements = form.elements; // all form elements
                var fields = Object.keys(elements).map(function(k) {
                    if(elements[k].name !== undefined) {
                        return elements[k].name;
                        // special case for Edge's html collection
                    }else if(elements[k].length > 0){
                        return elements[k].item(0).name;
                    }
                })
                .filter(function(item, pos, self) {
                    return self.indexOf(item) == pos && item;
                });

                var data = {};
                fields.forEach(function(k){
                    data[k] = elements[k].value;
                    var str = ""; // declare empty string outside of loop to allow it to be appended to for each item in the loop
                    var str2 ="";
                    if(elements[k].type === "checkbox"){ // special case for Edge's html collection
                        if(elements[k].checked === false){
                            str2 = ""}
                        else{str2="X"}
                        str = str + str2 + ", "; // take the string and append the current checked value to the end of it, along with a comma and a space
                        data[k] = str.slice(0, -2); // remove the last comma and space from the  string to make the output prettier in the spreadsheet
                    }else if(elements[k].length){
                        for(var i = 0; i < elements[k].length; i++){
                            if(elements[k].item(i).checked){
                                str = str + elements[k].item(i).value + ", "; // same as above
                                data[k] = str.slice(0, -2);
                            }
                        }
                    }
                });

                // add form-specific values into the data
                data.formDataNameOrder = JSON.stringify(fields);
                data.formGoogleSheetName = form.dataset.sheet || "Elec"; // default sheet name

                console.log(data);
                return data;
                }
            
            function handleFormSubmit(event) {  // handles form submit withtout any jquery
                event.preventDefault();           // we are submitting via xhr below
                var data = getFormData();         // get the values submitted in the form
                var url = event.target.action;  //
                var xhr = new XMLHttpRequest();
                xhr.open('POST', url);
                // xhr.withCredentials = true;
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    console.log( xhr.status, xhr.statusText )
                    console.log(xhr.responseText);
                    document.getElementById('form_suivi').style.display = 'none'; // hide form
                    document.getElementById('message_validation').style.display = 'block';
                    return;
                };
                // url encode form data for sending as post data
                var encoded = Object.keys(data).map(function(k) {
                    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
                }).join('&')
                xhr.send(encoded);
            }
            
            function loaded() {
                console.log('contact form submission handler loaded successfully');
                // bind to the submit event of our form
                var form = document.getElementById('form_suivi');
                form.addEventListener("submit", handleFormSubmit, false);
                };
                document.addEventListener('DOMContentLoaded', loaded, false);
                    
        </script>


        <?php
        // Clé de la spreadsheet
        $key = '1dES2_n8FPGsqZwp__v8C6dGhVRbtE2FrEutuzKOU3Nk';

        $spreadsheet = sprintf('https://spreadsheets.google.com/feeds/list/%s/1/public/values?alt=json', $key);
        $data = json_decode(file_get_contents($spreadsheet), true);
        ?>

    </div>
</body>
</html>
            