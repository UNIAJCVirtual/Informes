const informesArr = document.querySelectorAll('.collapse-item');
const divCategory = document.querySelector('#select');
const category = document.querySelector('#select-category');
const selectCategory = document.querySelector('#category');
const titlePrograms = document.querySelector('#title-prg');
const divCard = document.querySelector('#program-card');
const checkProgram = document.querySelectorAll('.checkbox-program');
const divTable = document.querySelector('#div-table');
const btn = document.querySelector('.btn1');
const navItem = document.querySelector('#collapseTwo');


// Visualizar categorias
informesArr.forEach(element => {
    element.addEventListener('click', () => {
        divCategory.classList.remove('d-none');
    });
});

selectCategory.addEventListener('change', () => {
    titlePrograms.classList.remove('d-none');
    divCard.classList.remove('d-none');
});

category.addEventListener('click',()=>{
    btn.classList.remove('d-none');
});


checkProgram.forEach(element => {
    element.addEventListener('click', () => {btn.classList.remove('d-none');});
});

// Limita el texto de las categorias



