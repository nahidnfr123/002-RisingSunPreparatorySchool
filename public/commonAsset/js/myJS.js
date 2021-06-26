




/*
Old_password.onkeyup = function() {
    let password = this.value;
    if (password.length === 0) {
        Old_pass_Status.innerHTML = "<span class='text-danger'>Old Password is required.</span>";
        if(!btn_Submit_Pass.hasAttribute('hidden')){
            btn_Submit_Pass.setAttribute('hidden', '');
        }
    }
    else if (password.length < 8) {
        Old_pass_Status.innerHTML = "<span class='text-danger'>Minimum 8 characters.</span>";
        if(!btn_Submit_Pass.hasAttribute('hidden')){
            btn_Submit_Pass.setAttribute('hidden', '');
        }
    }
    else{
        Old_pass_Status.innerHTML = "";
        if (Old_password.length !== 0 && New_password.length !== 0) {
            if (New_password.value === this.value) {
                password_strength.innerHTML = "<span class='text-danger'>Old password and new password cannot be same.</span>";
                if(!Confirm_password.hasAttribute('readonly')){
                    Confirm_password.setAttribute('readonly', '');
                }
                if(!btn_Submit_Pass.hasAttribute('hidden')){
                    btn_Submit_Pass.setAttribute('hidden', '');
                }
                Confirm_password.value = '';
            }
            else if(New_password.value !== ''){
                Confirm_password.removeAttribute('readonly');
                password_strength.innerHTML = "";
            }
        }
        else if(Old_password.value === "" || Old_password.length < 8 || New_password.value !== Confirm_password.value || New_password.value === "" || Confirm_password.value === "" || New_password.length < 8 || Confirm_password.length < 8 || Old_password.value === New_password.value){
            if(!btn_Submit_Pass.hasAttribute('hidden')){
                btn_Submit_Pass.setAttribute('hidden', '');
            }
        }
    }
}


// Password Strength checker and valisation ...
New_password.onkeyup = function() {

    let password = this.value;
    //TextBox left blank.
    if (password.length === 0) {
        password_strength.innerHTML = "<span class='text-danger'>Use (A-Z, a-z, 0-9, @#*&?!) for strong password.</span>";
        return;
    } else if (password.length < 8) {
        password_strength.innerHTML = "<span class='text-danger'>Minimum 8 characters.</span>";
        if(!Confirm_password.hasAttribute('readonly')){
            Confirm_password.setAttribute('readonly', '');
        }
        return;
    }

    //Regular Expressions.
    let regex = new Array();
    regex.push("[A-Z]"); //Uppercase Alphabet.
    regex.push("[a-z]"); //Lowercase Alphabet.
    regex.push("[0-9]"); //Digit.
    regex.push("[$@$!%*#?&]"); //Special Character.

    let passed = 0;

    //Validate for each Regular Expression.
    for (let i = 0; i < regex.length; i++) {
        if (new RegExp(regex[i]).test(password)) {
            passed++;
        }
    }

    //Validate for length of Password.
    if (passed > 2 && password.length > 8) {
        passed++;
    }

    //Display status.
    let color = "";
    let strength = "";
    switch (passed) {
        case 0:
        case 1:
            strength = "Strength: Weak";
            color = "red";
            if(!Confirm_password.hasAttribute('readonly')){
                Confirm_password.setAttribute('readonly', '');
            }
            break;
        case 2:
            strength = "Strength: Good";
            color = "darkorange";
            if(!Confirm_password.hasAttribute('readonly')){
                Confirm_password.setAttribute('readonly', '');
            }
            break;
        case 3:
        case 4:
            strength = "Strength: Strong";
            color = "green";
            Confirm_password.removeAttribute('readonly');
            break;
        case 5:
            strength = "Strength: Very Strong";
            color = "darkgreen";
            break;
    }






    let letter = document.getElementById("letter");
    let capital = document.getElementById("capital");
    let number = document.getElementById("number");
    let length = document.getElementById("length");

        // Validate lowercase letters
        let lowerCaseLetters = /[a-z]/g;
        if(New_password.value.match(lowerCaseLetters)) {
            letter.classList.remove("invalid");
            letter.classList.add("valid");
        } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
        }

        // Validate capital letters
        let upperCaseLetters = /[A-Z]/g;
        if(New_password.value.match(upperCaseLetters)) {
            capital.classList.remove("invalid");
            capital.classList.add("valid");
        } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
        }

        // Validate numbers
        let numbers = /[0-9]/g;
        if(New_password.value.match(numbers)) {
            number.classList.remove("invalid");
            number.classList.add("valid");
        } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
        }

        // Validate length
        if(New_password.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");
        } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
        }















    password_strength.innerHTML = strength;
    password_strength.style.color = color;

    if (Old_password.value !== '' && New_password.value !== '') {
        if (Old_password.value === this.value) {
            password_strength.innerHTML = "<span class='text-danger'>Old password and new password cannot be same.</span>";
            if(!Confirm_password.hasAttribute('readonly')){
                Confirm_password.setAttribute('readonly', '');
            }
            Confirm_password.value = '';
        }
    }
    if(Old_password.value === "" || Old_password.length < 8 || New_password.value !== Confirm_password.value || New_password.value === "" || Confirm_password.value === "" || New_password.length < 8 || Confirm_password.length < 8 || Old_password.value === New_password.value){
        if(!btn_Submit_Pass.hasAttribute('hidden')){
            btn_Submit_Pass.setAttribute('hidden', '');
        }
    }
    if(Old_password.value !== "" || New_password.value !== Old_password.value){
        password_status.innerHTML = "<span class='text-danger'>Passwords not matching.</span>";
        if(!btn_Submit_Pass.hasAttribute('hidden')){
            btn_Submit_Pass.setAttribute('hidden', '');
        }
    }else if(Confirm_password.value !== '' && New_password.value === Confirm_password.value){
        password_status.innerHTML = "<span class='text-success'>Passwords are matching.</span>";
    }
}



Confirm_password.onkeyup = function () {
    let password = this.value;

    if(New_password.value !== password){
        password_status.innerHTML = "<span class='text-danger'>Passwords not matching.</span>";
        if(!btn_Submit_Pass.hasAttribute('hidden')){
            btn_Submit_Pass.setAttribute('hidden', '');
        }
    }
    else if(Old_password.value === "" || Old_password.length < 8 || New_password.value !== Confirm_password.value || New_password.value === "" || Confirm_password.value === "" || New_password.length < 8 || Confirm_password.length < 8 || Old_password.value === New_password.value){
        if(!btn_Submit_Pass.hasAttribute('hidden')){
            btn_Submit_Pass.setAttribute('hidden', '');
        }
    }
    else{
        password_status.innerHTML = "<span class='text-danger'>Passwords are matching.</span>";
        btn_Submit_Pass.removeAttribute('hidden');
        document.getElementById('password_status').innerHTML = "";
    }


}
*/







let letter = document.getElementById("letter");
let capital = document.getElementById("capital");
let number = document.getElementById("number");
let length = document.getElementById("length");


// When the user starts to type something inside the password field
New_password.onkeyup = function() {
    let StrongPassword = 0;
    // Validate lowercase letters
    let lowerCaseLetters = /[a-z]/g;
    if(New_password.value.match(lowerCaseLetters)) {
        letter.classList.remove("invalid");
        letter.classList.add("valid");
        StrongPassword += 1;
    } else {
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }

    // Validate capital letters
    let upperCaseLetters = /[A-Z]/g;
    if(New_password.value.match(upperCaseLetters)) {
        capital.classList.remove("invalid");
        capital.classList.add("valid");
        StrongPassword += 1;
    } else {
        capital.classList.remove("valid");
        capital.classList.add("invalid");
    }

    // Validate numbers
    let numbers = /[0-9]/g;
    if(New_password.value.match(numbers)) {
        number.classList.remove("invalid");
        number.classList.add("valid");
        StrongPassword += 1;
    } else {
        number.classList.remove("valid");
        number.classList.add("invalid");
    }

    // Validate length
    if(New_password.value.length >= 8) {
        length.classList.remove("invalid");
        length.classList.add("valid");
        StrongPassword += 1;
    } else {
        length.classList.remove("valid");
        length.classList.add("invalid");
    }
    if(StrongPassword === 4){
        if(Confirm_password.hasAttribute('readonly')){
            Confirm_password.removeAttribute('readonly');
        }
    }else{
        if(!Confirm_password.hasAttribute('readonly')){
            Confirm_password.setAttribute('readonly', '');
        }
    }

    // Strength meter//
    let password = this.value;
    //TextBox left blank.
    if (password.length === 0) {
        password_strength.innerHTML = "<span class='text-danger'>Use (A-Z, a-z, 0-9, @#*&?!) for strong password.</span>";
        return;
    } else if (password.length < 8) {
        password_strength.innerHTML = "<span class='text-danger'>Minimum 8 characters.</span>";
        if(!Confirm_password.hasAttribute('readonly')){
            Confirm_password.setAttribute('readonly', '');
        }
        return;
    }

    //Regular Expressions.
    let regex = new Array();
    regex.push("[A-Z]"); //Uppercase Alphabet.
    regex.push("[a-z]"); //Lowercase Alphabet.
    regex.push("[0-9]"); //Digit.
    regex.push("[$@$!%*#?&]"); //Special Character.

    let passed = 0;

    //Validate for each Regular Expression.
    for (let i = 0; i < regex.length; i++) {
        if (new RegExp(regex[i]).test(password)) {
            passed++;
        }
    }

    //Validate for length of Password.
    if (passed > 2 && password.length > 8) {
        passed++;
    }

    //Display status.
    let color = "";
    let strength = "";
    switch (passed) {
        case 0:
        case 1:
            strength = "Strength: Weak";
            color = "red";
            break;
        case 2:
            strength = "Strength: Good";
            color = "darkorange";
            break;
        case 3:
        case 4:
            strength = "Strength: Strong";
            color = "green";
            break;
        case 5:
            strength = "Strength: Very Strong";
            color = "darkgreen";
            break;
    }
    password_strength.innerHTML = strength;
    password_strength.style.color = color;
}


