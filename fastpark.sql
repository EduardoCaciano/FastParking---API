create database fastpark;

use fastpark;

create table tbl_carros(
    idCarro int primary key auto_increment,
    nome varchar(255) not null,
    placa varchar(10) not null,
    dia date not null,
    horaEntrada time not null,
    horaSaida time not null,
    valor decimal not null
);

select * from tbl_carros;

insert into tbl_carros (nome, placa, dia, horaEntrada, horaSaida, valor) values ('Eduardo Caciano', 'ASV-1877', now(), now(), now(), 2);
UPDATE tbl_carros SET horaSaida = curtime(), valor = 1 WHERE idCarro = 1;
/*******************************************************************************************************************************************/

create table tbl_preco (
	idPreco int primary key not null auto_increment,
    primeiraHora double,
    demaisHoras double
);

insert into tbl_preco (primeiraHora, demaisHoras) values (1.0, 1.50);

select * from tbl_preco;

delete from tbl_preco where idPreco = 2;