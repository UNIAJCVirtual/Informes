const informesArr = document.querySelectorAll('.collapse-item');
const divCategory = document.querySelector('#select');
const selectCategory = document.querySelector('#category');
const divSelectInstitucionales = document.querySelector('#selectInstitucional');
const selectInstitucionales = document.querySelector('#selectInsti');
const titlePrograms = document.querySelector('#title-prg');
const divCard = document.querySelector('#program-card');
const checkProgram = document.querySelectorAll('.checkbox-program');
const divBtnGenerate = document.querySelector('#generate');



// Visualizar select categoria y select institucionales
informesArr.forEach(element => {
    element.addEventListener('click', () => {
        const visibleSelectInsti = document.getElementById("report").value;
        divCategory.classList.remove('d-none');
        
        if(visibleSelectInsti == 5 ){
            divSelectInstitucionales.classList.remove('d-none');
            selectInstitucionales.required = true;
        }else{
            divSelectInstitucionales.classList.add('d-none');
            selectInstitucionales.required = false;
        }
    });
});

selectCategory.addEventListener('change', () => {
    titlePrograms.classList.remove('d-none');
    divCard.classList.remove('d-none');
    divBtnGenerate.classList.remove('d-none');
});

checkProgram.forEach(element => {
    element.addEventListener('click', () => {btn.classList.remove('d-none');});
});




