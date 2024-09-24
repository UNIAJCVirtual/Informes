const informesArr = document.querySelectorAll('.collapse-item');
const divCategory = document.querySelector('#div-select');
const selectCategory = document.querySelector('#select-category');
const divSelectInstitucionales = document.querySelector('#selectInstitucional');
const selectInstitucionales = document.querySelector('#selectInsti');
const titlePrograms = document.querySelector('#title-prg');
const divCard = document.querySelector('#program-card');
const checkProgram = document.querySelectorAll('.checkbox-program');
const divBtnGenerate = document.querySelector('#generate');
const selectQuerrysUser = document.querySelector('#user-querrys');
const selectQuerrysEnglish = document.querySelector('#english-querrys');

// Visualizar select categoria y select institucionales
informesArr.forEach(element => {
    element.addEventListener('click', () => {
        const visibleSelectInsti = document.getElementById("report").value;

        if (visibleSelectInsti == 5) {
            divSelectInstitucionales.classList.remove('d-none');
            selectInstitucionales.required = true;
        } else {
            document.querySelector("#select-category").classList.remove('d-none');
            divSelectInstitucionales.classList.add('d-none');
            selectInstitucionales.required = false;
        }

        if (visibleSelectInsti == 7) {
            titlePrograms.innerText = 'ID Categoria de ingles';
            titlePrograms.classList.remove('d-none');
            selectCategory.classList.add('d-none');
            selectQuerrysEnglish.classList.remove('d-none');
        } else {
            selectCategory.classList.remove('d-none');
            selectQuerrysEnglish.classList.add('d-none');
            titlePrograms.innerText = 'Programas';
            titlePrograms.classList.add('d-none');
        }


    });
});

selectCategory.addEventListener('change', () => {
    titlePrograms.classList.remove('d-none');
    divCard.classList.remove('d-none');
    divBtnGenerate.classList.remove('d-none');
});

selectQuerrysUser.addEventListener('change', () => {
    divBtnGenerate.classList.remove('d-none');
});

checkProgram.forEach(element => {
    element.addEventListener('click', () => {
        btn.classList.remove('d-none');
    });
});