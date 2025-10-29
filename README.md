# SAEP – Sistema de Gestão de Estoque

Este repositório contém uma implementação simples (PHP + MySQL) para o desafio SAEP, cobrindo autenticação, cadastro de produtos, gestão de estoque com alerta de mínimo, e documentação solicitada.

Como executar
1) Crie/importa o banco:
   - Importe o arquivo saep_db.sql no MySQL/MariaDB (cria o DB saep_db e popula dados).
2) Configure conexão:
   - Ajuste credenciais no arquivo Config.php (host, usuário e senha).
3) Suba a aplicação:
   - Coloque o projeto em um servidor compatível (Apache/Nginx) ou use o servidor embutido do PHP.
   - Acesse index.php?r=login no navegador.

Fluxo de uso
- Registre um novo usuário (ou ajuste os seeds de users).
- Faça login e navegue pelo Dashboard para:
  - Cadastro de Produto (listar/buscar/criar/editar/excluir + validações).
  - Gestão de Estoque (ordenado alfabeticamente, entrada/saída com data, alerta abaixo do mínimo, histórico com responsável e observação).

Documentação (docs/)
- Requisitos Funcionais: docs/RequisitosFuncionais.md
- DER (texto/ASCII): docs/DER.md
- Casos de Teste: docs/CasosDeTeste.md
- Requisitos de Infra: docs/RequisitosInfra.md

Observações
- O projeto utiliza PDO (Config.php) e tokens CSRF nos formulários.
- Seeds incluem 3+ registros em users, products e inventory_movements.

//updated log