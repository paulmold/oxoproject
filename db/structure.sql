create table company
(
    id int auto_increment
        primary key,
    name varchar(50) null,
    constraint company_pk
        unique (name)
);

create index company_name_index
    on company (name);

create table profession
(
    id int auto_increment
        primary key,
    name varchar(50) null,
    constraint profession_pk
        unique (name)
);

create table job
(
    id int auto_increment
        primary key,
    name varchar(255) not null,
    description text null,
    expiration date null,
    openings int default 1 not null,
    company_id int not null,
    profession_id int not null,
    visited int default 1 not null,
    constraint job_unique
        unique (name, company_id, profession_id),
    constraint job_company_id_fk
        foreign key (company_id) references company (id),
    constraint job_profession_id_fk
        foreign key (profession_id) references profession (id)
);

create index job_name_index
    on job (name);

create index profession_name_index
    on profession (name);

