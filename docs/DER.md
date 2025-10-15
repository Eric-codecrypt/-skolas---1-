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

ASCII ER

+-----------+        1      N        +---------------------+
|  users    |------------------------>| inventory_movements |
+-----------+                         +---------------------+
| id (PK)   |<------------------------| user_id (FK)        |
| name      |        N      1         | product_id (FK)     |
| email*    |                          | type                |
| password* |                          | quantity            |
| role      |                          | movement_date       |
| created_at|                          | note                |
+-----------+                          | created_at          |
                                       +---------------------+
          ^          
          |          
          |          
          |          
          | 1      N 
+-----------+        
| products  |--------------------------
+-----------+                          
| id (PK)   |                          
| name      |                          
| sku*      |                          
| category  |                          
| material  |                          
| size      |                          
| weight_g  |                          
| min_stock |                          
| current_st|                          
| created_at|                          
+-----------+                          

Legenda: * campos com restrição adicional (UNIQUE ou confidencial).