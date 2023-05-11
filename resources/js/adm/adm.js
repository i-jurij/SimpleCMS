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
