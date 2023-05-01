document.addEventListener("DOMContentLoaded", function() {
       /*
    * event listener for info div in adm/change_pass
    */
    const PRO = document.querySelector('#p_pro');
    if (PRO) {
        PRO.addEventListener('click', function(e) {
            document.querySelector('#pro').classList.toggle('display_none');
        });
    }

    /*
    * event listener for delete, change user form in adm/change_pass/delete or change
    */
    const SUB = document.querySelector('#del_ch')
    if (SUB) {
        SUB.addEventListener('click', function(ev) {
            ev.preventDefault();
            let form_data = new FormData(document.querySelector(".form_del_ch"));
            if ( form_data.has("user_id[]"))
            {
                //document.querySelector("#chk_option_error").style.visibility = "hidden";
                document.querySelector(".form_del_ch").submit();
            }
            else
            {
                if (!document.querySelector("#ermes")) {
                    document.querySelector(".form_del_ch").insertAdjacentHTML('afterbegin','<div style="color:red;" id="ermes">Please select at least one user.</div>');
                } else {
                    document.querySelector("#ermes").innerHTML = "Please select at least one user.";
                }
                //document.getElementById("chk_option_error").style.visibility = "visible";
            }
        });
    }
        /*
    * event listener for delete, change user form paginator
    */
        const PAG = document.querySelector('#paginator')
        if (PAG && PAG.innerHTML !== '') {
            PAG.classList.add('margintb1');
            PAG.style.width = '30rem';
        }
    /*
    * event listener for change user data in adm/change_pass/change step 2 and submit form
    */
    const CHUD = document.querySelector('#db_change_users_data');
    if (CHUD) {
        CHUD.addEventListener('submit', function(eve) {
            eve.preventDefault();
            var stop = true;
            let inp_names = document.querySelectorAll(".user_name");
            let inp_status = document.querySelectorAll(".user_status");
            let inp_pass = document.querySelectorAll(".user_password");

            if (inp_names.length > 0) {
                for (let i = 0; i < inp_names.length; i++) {
                    const inp = inp_names[i].value;
                    let OKNAME = re.exec(inp);
                    // проверяем, существует ли элемент в проверяемом массиве имен data, полученном во view
                    if (data.includes(inp)) {
                        alert('Имя "' + inp + '" уже существует в базе данных.');
                        inp_names[i].focus();
                        const currentdiv = inp_names[i].parentNode.parentNode;
                        currentdiv.style.color = 'red';
                        currentdiv.parentNode.parentNode.scrollIntoView();
                        stop = false;
                        break;
                    }
                    if (!OKNAME && inp != '' &&  inp != null) {
                        alert("Имя от 3 до 25 букв, цифр, дефисов, подчёркиваний.\n");
                        inp_names[i].focus();
                        const currentdiv = inp_names[i].parentNode.parentNode;
                        currentdiv.style.color = 'red';
                        currentdiv.parentNode.parentNode.scrollIntoView();
                        stop = false;
                        break;
                    }
                }
            }

            if (inp_status.length > 0) {
                for (let i = 0; i < inp_status.length; i++) {
                    const inpstatus = inp_status[i].value;
                    let OKSTATUS = re.exec(inp_status[i].value);
                    if (!OKSTATUS && inpstatus != '' && inpstatus != null) {
                        alert("Статус от 3 до 25 букв, цифр, дефисов, подчёркиваний.\n");
                        inp_status[i].focus();
                        const currentdiv = inp_status[i].parentNode.parentNode;
                        currentdiv.style.color = 'red';
                        currentdiv.parentNode.parentNode.scrollIntoView();
                        stop = false;
                        break;
                    }
                }
            }

            if (inp_pass.length > 0) {
                for (let i = 0; i < inp_pass.length; i++) {
                    const inppass = inp_pass[i].value;
                    if ( (inppass < 4 || inppass > 120) && inppass != '' && inppass != null ) {
                        alert("Пароль от 4 до 120 символов.\n");
                        inp_pass[i].focus();
                        const currentdiv = inp_pass[i].parentNode.parentNode;
                        currentdiv.style.color = 'red';
                        currentdiv.parentNode.parentNode.scrollIntoView();
                        stop = false;
                        break;
                    }
                }
            }

            if (stop) {
                CHUD.submit();
            }
        });
    }
    /*
    * event listener for div delete templates in adm/create_delete_page
    */
    const TDEL = document.querySelector('#del_template');
    if (TDEL) {
        TDEL.addEventListener('click', function(e) {
            document.querySelector('#del_template_div').classList.toggle('display_none');
            if (TDEL.textContent.includes('Показать') ) {
                TDEL.innerText = 'Выбрать шаблоны для удаления';
            } else {
                TDEL.innerText = 'Показать шаблоны для удаления';
            }
        });
    }
    /*
    * event listener for div delete page in adm/create_delete_page
    */
   /*
    const PD = document.querySelector('#del_page_p');
    if (PD) {
        PD.addEventListener('click', function(e) {
            document.querySelector('#del_page_div').classList.toggle('display_none');
            if (PD.textContent.includes('Показать') ) {
                PD.innerText = 'Выбрать страницы для удаления';
            } else {
                PD.innerText = 'Показать страницы для удаления';
            }
        });
    }
    */
});
