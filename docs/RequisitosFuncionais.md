# Requisitos Funcionais (RF) – Sistema de Gestão de Estoque (SAEP)

RF-01 Autenticação de Usuários
- O sistema deve permitir autenticação por e-mail e senha.
- Em caso de falha de autenticação, informar o motivo e redirecionar à tela de login.

RF-02 Cadastro de Usuários
- O sistema deve permitir o registro de novos usuários com Nome, E-mail e Senha válidos.

RF-03 Painel Principal
- Exibir o nome do usuário logado.
- Disponibilizar opção de logout.
- Disponibilizar navegação para Cadastro de Produto e Gestão de Estoque.

RF-04 Cadastro de Produto
- Listar produtos automaticamente ao abrir a tela, em tabela.
- Possibilitar busca por termo (nome, SKU ou categoria), atualizando a listagem.
- Permitir criar novo produto com validações de campos obrigatórios e limites (não negativos).
- Permitir editar produto existente com as mesmas validações.
- Permitir excluir produto existente.
- Disponibilizar link de retorno ao painel.

RF-05 Gestão de Estoque
- Listar os produtos cadastrados em ordem alfabética (usando algoritmo explícito).
- Permitir selecionar o produto a movimentar, informando tipo (entrada/saída), quantidade e data.
- Registrar a movimentação com usuário responsável e observação opcional.
- Atualizar estoque do produto de forma consistente (transação).
- Ao efetuar saída, verificar estoque mínimo e emitir alerta quando novo estoque ficar abaixo do mínimo configurado.
- Exibir histórico recente de movimentações (produto, usuário, quantidade, tipo, data, observação).

RF-06 Banco de Dados
- O banco deve se chamar saep_db.
- Devem existir pelo menos 3 registros nas tabelas users, products e inventory_movements.

RF-07 Segurança e Usabilidade
- Proteger formulários com token CSRF.
- Validar os dados informados, exibindo alertas em caso de dados ausentes ou inválidos.

Observações
- Design/layout ficam a critério, mantendo responsividade simples.
