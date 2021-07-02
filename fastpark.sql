create database fastpark;

use fastpark;

create table tbl_carros(
    id int primary key auto_increment,
    nome varchar(255) not null,
    placa varchar(10) not null,
    dia date not null,
    horaEntrada time not null,
    horaSaida time not null,
    valor decimal not null
);

select * from tbl_carros;

insert into tbl_carros (nome, placa, dia, horaEntrada, horaSaida, valor) values ('Rafael Leme', 'ASD-1507', now(), now(), now(), 5);

/********************************************************************************************************************************************/

create table tbl_precos(
	id int primary key auto_increment,
    primeiraHora decimal not null,
    demaisHoras decimal not null
);

select * from tbl_precos;

insert into tbl_precos (primeiraHora, demaisHoras) values (2.00, 1.50);

delete from tbl_precos where id = 1;