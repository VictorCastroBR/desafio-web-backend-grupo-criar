# Desafio WEB Backend - Grupo CRIAR

API RESTful desenvolvida em Laravel para gerenciar estados, cidades, clusters, campanhas, descontos e produtos.

## Tecnologias

* **Linguagem:** PHP 8.2
* **Framework:** Laravel 12
* **Banco de Dados:** MySQL 8.0
* **Servidor Web:** Nginx
* **Ambiente:** Docker & Docker Compose

## Arquitetura

Projeto estruturado seguindo princípios de **Domain-Driven Design (DDD)** e **Clean Architecture**:

* **Domain:** Entidades e interfaces de repositório (livre de framework)
* **Application:** Services com lógica de negócio
* **Infrastructure:** Implementações de repositório (Eloquent)
* **HTTP:** Controllers, Form Requests e API Resources

**Padrões aplicados:**
* Repository Pattern com interfaces
* Dependency Injection via Service Container
* Validação com Form Requests
* Formatação de resposta com API Resources
* Imutabilidade nas entidades de domínio

## Instalação e Execução

**1. Clone o repositório**

```bash
git clone https://github.com/VictorCastroBR/desafio-web-backend-grupo-criar.git
cd desafio-web-backend-grupo-criar
```

**2. Suba os containers**

```bash
docker-compose up -d --build
```

**3. Instale as dependências**

```bash
docker-compose exec app composer install
```

**4. Configure o ambiente**

```bash
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
```

**5. Execute as migrations**

```bash
docker-compose exec app php artisan migrate
```

**Pronto!** A API está rodando em: **http://localhost:8080/api/v1**

---

## Executar Testes

```bash
docker-compose exec app php artisan test
```

---

## Endpoints da API

**URL Base:** `http://localhost:8080/api/v1`

**Paginação opcional:** Adicione `?paginate=true&per_page=15` em endpoints de listagem.

### Estados

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/states` | Listar todos |
| `POST` | `/states` | Criar |
| `GET` | `/states/{id}` | Buscar por ID |
| `PUT` | `/states/{id}` | Atualizar |
| `DELETE` | `/states/{id}` | Excluir |

**Corpo da requisição (POST/PUT):**
```json
{
    "name": "São Paulo",
    "uf": "SP"
}
```

### Cidades

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/cities` | Listar todas |
| `POST` | `/cities` | Criar |
| `GET` | `/cities/{id}` | Buscar por ID |
| `PUT` | `/cities/{id}` | Atualizar |
| `DELETE` | `/cities/{id}` | Excluir |

**Corpo da requisição (POST/PUT):**
```json
{
  "name": "Ribeirão Preto",
  "state_id": 1,
  "cluster_id": 1
}
```

### Clusters (Grupos de Cidades)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/clusters` | Listar todos |
| `POST` | `/clusters` | Criar |
| `GET` | `/clusters/{id}` | Buscar por ID |
| `PUT` | `/clusters/{id}` | Atualizar |
| `DELETE` | `/clusters/{id}` | Excluir |

**Corpo da requisição (POST/PUT):**
```json
{
    "name": "Sudeste"
}
```

### Campanhas

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/campaigns` | Listar todas |
| `POST` | `/campaigns` | Criar |
| `GET` | `/campaigns/{id}` | Buscar por ID |
| `PUT` | `/campaigns/{id}` | Atualizar |
| `DELETE` | `/campaigns/{id}` | Excluir |

**Corpo da requisição (POST/PUT):**
```json
{
  "name": "Black Friday 2025",
  "active": true,
  "cluster_id": 1
}
```

**Regra de negócio:** Apenas uma campanha ativa por cluster. Ao ativar uma nova, as outras do mesmo cluster são desativadas automaticamente.

### Descontos

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/discounts` | Listar todos |
| `POST` | `/discounts` | Criar |
| `GET` | `/discounts/{id}` | Buscar por ID |
| `PUT` | `/discounts/{id}` | Atualizar |
| `DELETE` | `/discounts/{id}` | Excluir |

**Corpo da requisição (POST/PUT):**
```json
{
  "value": 50.00,
  "percent": null,
  "campaign_id": 1
}
```

**Regra de negócio:** Desconto deve ter **valor OU percentual**, nunca ambos simultaneamente.

### Produtos

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/products` | Listar todos |
| `POST` | `/products` | Criar |
| `GET` | `/products/{id}` | Buscar por ID |
| `PUT` | `/products/{id}` | Atualizar |
| `DELETE` | `/products/{id}` | Excluir |

**Corpo da requisição (POST/PUT):**
```json
{
  "name": "Notebook Dell",
  "price": 3500.00
}
```

---

## Estrutura do Projeto

```
app/
├── Application/Services/      # Lógica de negócio
├── Domain/                    # Entidades e interfaces
│   ├── Campaign/
│   ├── City/
│   ├── Cluster/
│   ├── Discount/
│   ├── Product/
│   └── State/
├── Http/                      # Camada de apresentação
│   ├── Controllers/
│   ├── Requests/              # Validação
│   └── Resources/             # Formatação JSON
└── Infrastructure/            # Implementações
    └── Persistence/Eloquent/
```

---

## Decisões Técnicas

* **Entidades imutáveis:** Uso de `readonly` properties (PHP 8.2+)
* **Separação clara:** Domain não conhece framework
* **Versionamento de API:** Prefixo `/v1` para facilitar evolução
* **Testes:** Cobertura de repositórios e regras de negócio críticas
* **Docker:** Ambiente isolado e replicável
