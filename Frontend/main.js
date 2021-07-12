'use strict'

const openModalPrices = () => document.querySelector('#modal-Prices')
    .classList.add('active');
const closeModalPrices = () => document.querySelector('#modal-Prices')
    .classList.remove('active');

const openModalReceipt = () => document.querySelector('#modal-receipt').classList.add('active');
const closeModalReceipt = () => document.querySelector('#modal-receipt').classList.remove('active');

const openModalEdit = () => document.querySelector('#modal-edit').classList.add('active');
const closeModalEdit = () => document.querySelector('#modal-edit').classList.remove('active');

const openModalExit = () => document.querySelector('#modal-exit').classList.add('active');
const closeModalExit = () => document.querySelector('#modal-exit').classList.remove('active');


const getCarro = async (url) => {
    const response = await fetch(url);
    const json = await response.json();
    return json;
}


const criarCarro = async (carro) => {
    const url = 'http://api.fastpark.com.br/carros';
    const opitions = {
        method: 'POST',
        body: JSON.stringify(carro)
    };
    await fetch(url, opitions);
}

const criarPreco = async (preco) => {
    const url = 'http://api.fastpark.com.br/precos';
    const opitions = {
        method: 'POST',
        body: JSON.stringify(preco)
    };
    await fetch(url, opitions);
}

const atualizaCarro = async (carro, index) => {

    const url = `http://api.fastpark.com.br/carros/${index}`;
    const opitions = {
        method: 'PUT',
        body: JSON.stringify(carro)
    };
    await fetch(url, opitions);
}

const criarTabela = (carro, index) => {
    const tableCars = document.querySelector('#tableCars tbody')
    const newTr = document.createElement('tr');
    newTr.innerHTML = `                
        <td>${carro.nome}</td>
        <td>${carro.placa}</td>
        <td>${carro.dia}</td>
        <td>${carro.horaEntrada}</td>
        <td>
            <button data-index="${index+1}" id="button-receipt" class="button green" type="button">Comp.</button>
            <button data-index="${index+1}" id="button-edit" class="button blue" type="button">Editar</button>
            <button data-index="${index+1}" id="button-exit" class="button red" type="button">Saída</button>
        </td>`;

        if(carro.valor == 0){
            tableCars.appendChild(newTr);
        }
}

const limpaInputs = () => {
    const inputs = Array.from(document.querySelectorAll('input'));
    inputs.forEach(input => input.value = "");
    document.getElementById('nome').dataset.idcar = "new";
}

const limpaTabela = () => {
    const recordCar = document.querySelector('#tableCars tbody');
    while (recordCar.firstChild) {
        recordCar.removeChild(recordCar.lastChild);
    }
}

const atualizaTabela = async () => {
    limpaTabela();
    const url = 'http://api.fastpark.com.br/carros';
    const carros = await getCarro(url);
    carros.forEach(criarTabela);
}

const isValidFormRegister = () => document.querySelector('#formularioRegistro').reportValidity();

const salvaCarro = async () => {
    if (isValidFormRegister()) {
        const newCar = {
            nome: document.querySelector('#nome').value,
            placa: document.querySelector('#placa').value
        }
        const idCar = document.getElementById('nome').dataset.idcar;
        if(idCar == "new"){
            await criarCarro(newCar);
        }else{
            await atualizaCarro(newCar, idCar);
        }
        atualizaTabela();
        limpaInputs();
    }
}

const isValidFormPrice = () => document.querySelector('#form-price').reportValidity();

const salvaPreco = async () => {
    if (isValidFormPrice()) {
        const newPrice = {
            primeiraHora: document.querySelector('#primeira-hora').value,
            demaisHoras: document.querySelector('#demais-horas').value
        }
        await criarPreco(newPrice);
        limpaInputs();
        closeModalPrices();
    }
}

const setReceipt = async (index) => {
    console.log(index);
    const url = `http://api.fastpark.com.br/carros/${index}`;
    const carro = await getCarro(url);
    const input = Array.from(document.querySelectorAll('#form-receipt input'));
    input[0].value = carro.nome;
    input[1].value = carro.placa;
    input[2].value = carro.data;
    input[3].value = carro.horaEntrada;
}

const deletaCarro = async (index) => {
    const url = `http://api.fastpark.com.br/carros/${index}`;
    const opitions = {
        method: 'DELETE'
    }
    await fetch(url, opitions);
}

const saida = async (index) => {
    await deletaCarro(index);
    const carro = await getCarro(`http://api.fastpark.com.br/carros/${index}`);

    console.log(carro)

    const input = Array.from(document.querySelectorAll('#form-exit input'));
    input[0].value = carro.nome;
    input[1].value = carro.placa;
    input[2].value = carro.horaEntrada;
    input[3].value = carro.horaSaida;
    input[4].value = carro.valor;

    atualizaTabela();
}

const fillInputsEdit = async (index) => {

    const url = `http://api.fastpark.com.br/carros/${index}`;
    const carro = await getCarro(url);
    document.querySelector('#nome').value = carro.nome
    document.querySelector('#placa').value = carro.placa
    document.getElementById('nome').dataset.idcar = carro.idCarro;
   
}


const getButtons = (event) => {
    const button = event.target;
    if (button.id == "button-receipt") {
        const index = button.dataset.index;
        openModalReceipt();
        setReceipt(index);
    } else if (button.id == "button-exit") {
        const index = button.dataset.index;
        openModalExit();
        saida(index);
    } else if (button.id == "button-edit") {
        const index = button.dataset.index;
        fillInputsEdit(index);
    }
}

/*NÃO APAGAR */
// const adicionar = () => {
//     const adicionarCliente = {
//         data: getDateNow()
//     }
//     insertDB(adicionarCliente);
//     atualizaTabela();
// }

const printRecipt = () => {
    window.print();
}


const mask = (text) =>{
    
    text = text.replace(/^(\d)/g, "")
    text = text.replace(/^([a-z-\W])/g, "")
    text = text.replace(/^([A-Z]{3})(\d*)$/, "$1-$2")

    return text;
}

const applyMask = (event) => {
    event.target.value = mask(event.target.value);
}

document.querySelector('#precos').addEventListener('click', () => { openModalPrices(); limpaInputs});
document.querySelector('#close-prices').addEventListener('click', () => { closeModalPrices(); limpaInputs});
document.querySelector('#cancelar-prices').addEventListener('click', () => { closeModalPrices(); limpaInputs});
document.querySelector('#tableCars').addEventListener('click', getButtons);
document.querySelector('#close-receipt').addEventListener('click', () => { closeModalReceipt(); limpaInputs() });
document.querySelector('#cancelar-receipt').addEventListener('click', () => { closeModalReceipt(); limpaInputs() });
document.querySelector('#close-exit').addEventListener('click', () => { closeModalExit(); limpaInputs() });
document.querySelector('#cancelar-exit').addEventListener('click', () => { closeModalExit(); limpaInputs() });
document.querySelector('#adicionar').addEventListener('click', salvaCarro);
document.querySelector('#salvarPreco').addEventListener('click', salvaPreco);
document.querySelector('#imprimir-receipt').addEventListener('click', printRecipt);
document.querySelector('#imprimir-exit').addEventListener('click', printRecipt);
document.querySelector('#placa').addEventListener('keyup', applyMask);

atualizaTabela();