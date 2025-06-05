# API de Gerenciamento de Salões de Beleza - Hyperf

## Descrição

Sistema de gerenciamento de salões de beleza onde:

* Um **salão** pode ter vários **colaboradores**.
* Cada **colaborador** pode ter vários **slots de horário**.
* Cada **agendamento** é associado a um **colaborador** e a um **slot** específico.
* Quando um **slot** é agendado, ele deve ser **removido** ou **marcado como reservado**.

## Tecnologias Utilizadas

* **PHP 8.3**
* **[Hyperf Framework](https://hyperf.io/)** (baseado em Swoole)
* **Swoole HTTP Server**
* **MySQL 8.0** (Banco de Dados)
* **Redis 7.2** (Cache e Fila Assíncrona)
* **Docker + Docker Compose** (Ambiente de Desenvolvimento)
* **phpMyAdmin** (Interface para MySQL)

## Requisitos

* Docker
* Docker Compose

## Subir o Projeto

```bash
docker-compose up -d --build
```

Acesso:

* API: [http://localhost:9501](http://localhost:9501)
* phpMyAdmin: [http://localhost:8080](http://localhost:8080)

Credenciais phpMyAdmin:

* Usuário: `root`
* Senha: `root`

## Modelos de Dados

### Salon (Salão)

| Campo     | Tipo   | Descrição               |
| --------- | ------ | ----------------------- |
| id        | bigint | Identificador do salão  |
| name      | string | Nome do salão           |
| address   | string | Endereço                |
| phone     | string | Telefone                |
| owner\_id | bigint | Dono do salão (usuário) |

### Collaborator (Colaborador)

| Campo     | Tipo   | Descrição                       |
| --------- | ------ | ------------------------------- |
| id        | bigint | Identificador do colaborador    |
| salon\_id | bigint | Relacionamento com o salão      |
| name      | string | Nome                            |
| email     | string | Email                           |
| phone     | string | Telefone                        |
| role      | string | Função (cabeleireiro, manicure) |

### Slot (Horário Disponível)

| Campo            | Tipo   | Descrição                      |
| ---------------- | ------ | ------------------------------ |
| id               | bigint | Identificador do slot          |
| collaborator\_id | bigint | Relacionamento com colaborador |
| date             | date   | Data                           |
| start\_time      | time   | Hora de início                 |
| end\_time        | time   | Hora de fim                    |
| status           | enum   | 'available' ou 'booked'        |

### Appointment (Agendamento)

| Campo            | Tipo   | Descrição                        |
| ---------------- | ------ | -------------------------------- |
| id               | bigint | Identificador do agendamento     |
| salon\_id        | bigint | Relacionamento com salão         |
| collaborator\_id | bigint | Relacionamento com colaborador   |
| client\_name     | string | Nome do cliente                  |
| client\_phone    | string | Telefone do cliente              |
| date             | date   | Data                             |
| start\_time      | time   | Hora de início                   |
| end\_time        | time   | Hora de fim                      |
| status           | enum   | 'booked', 'canceled', 'finished' |

## Endpoints Planejados

### Salon

* [ ] **POST /salons** - Criar salão
* [ ] **GET /salons/{id}** - Buscar dados do salão

### Collaborator

* [ ] **POST /collaborators** - Cadastrar colaborador
* [ ] **GET /salons/{id}/collaborators** - Listar colaboradores do salão

### Slot

* [ ] **POST /slots** - Criar slot de horário
* [ ] **GET /collaborators/{id}/slots?date=YYYY-MM-DD** - Listar slots disponíveis por colaborador e data

### Appointment

* [ ] **POST /appointments** - Criar agendamento
* [ ] **GET /collaborators/{id}/appointments?date=YYYY-MM-DD** - Listar agendamentos por colaborador e data

## Fluxo de Agendamento

1. **Cadastro de Slot**: O colaborador informa data, hora de início e fim.
2. **Consulta de Slots**: O cliente seleciona um colaborador e um dia para visualizar slots disponíveis.
3. **Agendamento**: O cliente escolhe um slot e realiza o agendamento.
4. **Reserva de Slot**: Slot é marcado como 'booked' ou removido.

## Checklist de Implementação

| Tarefa                                    | Status |
| ----------------------------------------- | ------ |
| Criação do projeto Hyperf                 | ✅      |
| Configuração do banco de dados            | ✅      |
| Criação das migrations                    | ✅      |
| Criação dos models                        | ✅    |
| Endpoints de Salão                        | \[ ]   |
| Endpoints de Colaborador                  | \[ ]   |
| Endpoints de Slot                         | \[ ]   |
| Endpoints de Agendamento                  | \[ ]   |
| Middleware de autenticação (JWT/Auth)     | \[ ]   |
| Documentação Swagger                      | \[ ]   |
| Redis para cache de slots                 | \[ ]   |
| Validação de dados de entrada (Request)   | \[ ]   |
| Configuração do Docker para MySQL e Redis | ✅      |
| Configuração do phpMyAdmin                | ✅      |
