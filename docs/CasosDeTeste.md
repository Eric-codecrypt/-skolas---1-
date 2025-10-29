# Descritivo de Testes de Software

Ferramentas e Ambiente (Item 8.2)
- Navegador: Chrome/Firefox/Edge recentes.
- PHP: 7.4+ (compatível com 5.6+ no projeto, sem tipagem estrita) e servidor embutido do PHP ou Apache/Nginx.
- Banco: MySQL/MariaDB (ex.: MariaDB 10.5+). Script: saep_db.sql.
- Sistema Operacional: Windows 10/11, Linux ou macOS.
- Método: Testes manuais orientados por casos + verificação visual.

Casos de Teste (Item 8.1)

CT-01 Login com credenciais inválidas
- Pré-condição: Usuário não autenticado.
- Passos: Acessar /index.php?r=login, preencher e-mail inexistente e senha aleatória; enviar.
- Resultado esperado: Mensagem "Credenciais inválidas." e retorno à tela de login.

CT-02 Registro de novo usuário com dados inválidos
- Passos: /index.php?r=register; informar nome 1 caractere, e-mail inválido, senha 3 chars; salvar.
- Resultado: Mensagem de validação informando dados inválidos; permanecer na tela de registro.

CT-03 Registro de novo usuário válido e login
- Passos: /index.php?r=register; informar nome >=2, e-mail válido e senha >=6; registrar; ir ao login; autenticar.
- Resultado: Mensagem "Usuário cadastrado. Faça login."; ao logar, redirecionar para dashboard com nome exibido.

CT-04 Dashboard exibe nome e links
- Pré-condição: Usuário autenticado.
- Passos: /index.php?r=dashboard.
- Resultado: Exibe "Bem-vindo, <nome>", links para Cadastro de Produto, Gestão de Estoque e Sair.

CT-05 Listagem automática de produtos
- Passos: /index.php?r=products.
- Resultado: Tabela com os produtos seed (>=3 registros) visível.

CT-06 Busca de produtos
- Passos: Em /products, digitar termo existente (ex.: "Chave"); enviar.
- Resultado: Listagem filtrada contendo apenas registros correspondentes ao termo.

CT-07 Criar produto válido
- Passos: /index.php?r=productsCreate; preencher nome, SKU único, demais campos; salvar.
- Resultado: Mensagem de sucesso e novo produto visível na listagem.

CT-08 Criar produto com dados inválidos
- Passos: /productsCreate; informar peso negativo ou faltar SKU; salvar.
- Resultado: Mensagem de erro com a validação correspondente.

CT-09 Editar produto
- Passos: Em /products, clicar "Editar" de um item; alterar categoria; salvar.
- Resultado: Mensagem "Produto atualizado." e listagem refletindo mudanças.

CT-10 Excluir produto
- Passos: Em /products, clicar "Excluir" e confirmar.
- Resultado: Mensagem "Produto excluído." e item removido.

CT-11 Gestão de estoque: ordenação alfabética
- Passos: /index.php?r=inventory.
- Resultado: Tabela de produtos ordenada por nome (A-Z) conforme algoritmo Bubble Sort.

CT-12 Movimentação de entrada
- Passos: Em /inventory, selecionar produto, tipo "entrada", quantidade 5, data válida, salvar.
- Resultado: Mensagem com novo estoque e registro na tabela "Últimas Movimentações".

CT-13 Movimentação de saída válida
- Passos: Selecionar produto com estoque suficiente; tipo "saída", quantidade menor que estoque; salvar.
- Resultado: Mensagem com novo estoque. Sem erro.

CT-14 Movimentação de saída com quantidade maior que estoque
- Passos: Selecionar produto; tipo "saída"; quantidade maior que estoque atual; salvar.
- Resultado: Erro: "Quantidade de saída maior que o estoque atual." Não deve registrar movimento.

CT-15 Alerta de estoque abaixo do mínimo
- Passos: Fazer saídas sucessivas até o novo estoque ficar < min_stock.
- Resultado: Mensagem de sucesso incluindo alerta: "Alerta: estoque abaixo do mínimo configurado!".

CT-16 CSRF token obrigatório
- Passos: Submeter formulários de produto/movimentação sem o campo csrf (via ferramenta dev).
- Resultado: Resposta 400 "CSRF inválido".
//updated log