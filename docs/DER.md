# DER – Diagrama Entidade-Relacionamento (descrição textual/ASCII)

Entidades e Atributos

1) users
- id (PK, INT, AI)
- name (VARCHAR)
- email (VARCHAR, UNIQUE)
- password_hash (VARCHAR)
- role (ENUM: admin|user)
- created_at (TIMESTAMP)

2) products
- id (PK, INT, AI)
- name (VARCHAR)
- sku (VARCHAR, UNIQUE)
- category (VARCHAR)
- material (VARCHAR)
- size (VARCHAR)
- weight_grams (INT)
- min_stock (INT)
- current_stock (INT)
- created_at (TIMESTAMP)

3) inventory_movements
- id (PK, INT, AI)
- product_id (FK -> products.id)
- user_id (FK -> users.id)
- type (ENUM: entrada|saida)
- quantity (INT)
- movement_date (DATE)
- note (VARCHAR)
- created_at (TIMESTAMP)

Relacionamentos
- users (1) —— (N) inventory_movements
- products (1) —— (N) inventory_movements

Cardinalidades
- Um usuário pode registrar muitas movimentações; cada movimentação pertence a um único usuário.
- Um produto pode aparecer em muitas movimentações; cada movimentação refere-se a um único produto.

ASCII ER (melhorado)

Diagrama (Mermaid)

```mermaid
erDiagram
  USERS ||--o{ INVENTORY_MOVEMENTS : registra
  PRODUCTS ||--o{ INVENTORY_MOVEMENTS : movimenta

  USERS {
    INT id PK
    VARCHAR name
    VARCHAR email UNIQUE
    VARCHAR password_hash
    ENUM role
    TIMESTAMP created_at
  }

  PRODUCTS {
    INT id PK
    VARCHAR name
    VARCHAR sku UNIQUE
    VARCHAR category
    VARCHAR material
    VARCHAR size
    INT weight_grams
    INT min_stock
    INT current_stock
    TIMESTAMP created_at
  }

  INVENTORY_MOVEMENTS {
    INT id PK
    INT product_id FK
    INT user_id FK
    ENUM type
    INT quantity
    DATE movement_date
    VARCHAR note
    TIMESTAMP created_at
  }
```

ASCII (alternativa legível)

```
+-----------+           1        N          +---------------------+
|  users    |------------------------------>| inventory_movements |
+-----------+                               +---------------------+
| id (PK)   |<------------------------------| user_id (FK)        |
| name      |             N        1        | product_id (FK)     |
| email     |                               | type (entrada/saida)|
| password_ |                               | quantity            |
| hash      |                               | movement_date (DATE)|
| role      |                               | note                |
| created_at|                               | created_at          |
+-----------+                               +---------------------+
         ^
         |
         | 1        N
+--------------+------------------------------
| products     |
+--------------+
| id (PK)      |
| name         |
| sku          | (UNIQUE)
| category     |
| material     |
| size         |
| weight_grams |
| min_stock    |
| current_stock|
| created_at   |
+--------------+
```

Legenda:
- PK = chave primária
- FK = chave estrangeira
- UNIQUE = valor único
- type: enum('entrada','saida')