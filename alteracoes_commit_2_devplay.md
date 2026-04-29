# 🚀 Documentação de Atualizações do DevPlay (Commit 2)

Este documento foi criado para ajudar qualquer desenvolvedor (mesmo os mais iniciantes) a entender **exatamente** o que foi modificado no projeto neste último pacote de atualizações. O foco foi modularizar o cabeçalho e corrigir problemas de nomes de tabelas de banco de dados.

---

## 1. Criação do Componente de Cabeçalho Único (`components/header.php`)

### ❓ O Problema
Antes dessa atualização, o código HTML da barra de navegação superior (onde fica o **Logo**, o botão de **Sair** e o **Modo Claro/Escuro**) estava sendo copiado e colado manualmente em **7 páginas diferentes**. Isso era ruim porque se a gente precisasse trocar a imagem do logo, teríamos que abrir 7 arquivos para fazer a mudança!

### 🛠️ A Solução
Criamos uma nova pasta chamada `components` e dentro dela colocamos o arquivo `header.php`. Nós removemos todo o código gigante da tag `<header>` das 7 páginas (`index.php`, `admin/listar.php`, `admin/cadastrar.php`, `admin/editar.php`, `admin/usuarios_listar.php`, `admin/usuarios_cadastrar.php`, `admin/usuarios_editar.php`) e substituímos por uma única linha:

```php
<?php include '../components/header.php'; ?>
```
*(Nas páginas que já estão na raiz, como o `index.php`, o código ficou `include 'components/header.php';` sem os pontos).*

### 🧠 Como o Componente é Inteligente?
Esse novo arquivo `header.php` foi comentado em todas as suas linhas para ficar muito fácil de estudar. Aqui vai um pedaço principal da inteligência dele:

```php
// Pega o caminho do arquivo onde o usuário está navegando agora
$script_path = $_SERVER['SCRIPT_NAME'];

// Verifica se a palavra '/admin/' existe nesse caminho. 
// Se existir, significa que o usuário entrou na área administrativa.
$is_admin_area = strpos($script_path, '/admin/') !== false;

// Base URL dinâmica:
// Se o usuário estiver na pasta admin (ex: admin/listar.php), a variável recebe '../'.
// O '../' serve para o HTML "voltar uma pasta" para conseguir achar a imagem do logo que está na raiz.
// Se não estiver no admin (ex: index.php), a base é vazia ('').
$base_url = $is_admin_area ? '../' : '';
```

Depois, lá no meio do HTML da barra de navegação, a gente carrega o logo usando essa variável `$base_url`, garantindo que a imagem **nunca vai quebrar**, não importa a página!
```html
<img src="<?php echo $base_url; ?>imagem/DEV-PLAY-LOGO-Final-correto.svg" alt="logo" width="40px" height="40px">
```

---

## 2. Correção do Erro "Undefined Variable '$conn'" no `index.php`

### ❓ O Problema
Quando estávamos com a página principal (`index.php`) aberta em alguns editores de código modernos (como o VS Code), eles marcavam a variável de conexão `$conn` com uma linha vermelha dizendo: *Undefined variable '$conn'*.

Isso acontecia porque a IDE não tem a capacidade de "ler a mente" do projeto e saber que o `$conn` foi criado dentro do arquivo `config/conexao.php` que a gente havia incluído no topo da página.

### 🛠️ A Solução
Adicionamos um comentário especial (um DocBlock) logo abaixo da importação da conexão, apenas para "ensinar" a IDE sobre o que é essa variável.

**O que mudou no topo do `index.php`:**
```diff
 <?php
 session_start();
 include 'config/conexao.php';
+/** @var mysqli $conn */
 ?>
 <!DOCTYPE html>
```
Com essa linha extra, a IDE entende perfeitamente que `$conn` existe e é um objeto do tipo `mysqli`, removendo as linhas vermelhas de falso positivo.

---

## 3. Padronização da Tabela de `clientes` para `usuarios`

### ❓ O Problema
Ao tentar acessar a página `admin/usuarios_listar.php`, o sistema explodia um erro: `Table 'devplay_db.usuarios' doesn't exist`.
Analisando o banco de dados antigo, vimos que o arquivo de instalação (`init.sql`) e os scripts de login estavam criando e buscando pessoas na tabela chamada **`clientes`**. Porém, como a plataforma é focada em jogos, as páginas de administração foram criadas para trabalhar com **`usuarios`**.

### 🛠️ A Solução
Para não ter um banco de dados bagunçado com `clientes` e páginas chamando por `usuarios`, nós **padronizamos tudo para `usuarios`**.

As seguintes modificações foram feitas e comentadas nos arquivos:

1. **Criação da Tabela Nova:**
No banco de dados local da máquina de testes, foi criada a tabela `usuarios` e nela inserido o login administrativo padrão: `admin@admin.com` com a senha `123456`.

2. **`db/init.sql` e `config/backup.php`**:
Todo o código que mandava criar a tabela "clientes" e fazer o backup dela foi trocado.
```diff
- CREATE TABLE IF NOT EXISTS clientes (
+ CREATE TABLE IF NOT EXISTS usuarios (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nome VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      senha VARCHAR(255) NOT NULL,
      data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );
```

3. **`cadastro_usuario.php`**:
O arquivo que realiza os cadastros agora aponta para a tabela correta.
```diff
- $sql = "INSERT INTO clientes (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";
+ $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha_hash')";
```

4. **`processar_login.php`**:
A consulta (SELECT) para ver se o usuário pode entrar no sistema também foi arrumada.
```diff
- $sql = "SELECT id, nome, email, senha FROM clientes WHERE email = '$email_safe'";
+ $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = '$email_safe'";
```

> **Dica para quem baixar o projeto:**
> Ao baixar os arquivos, basta rodar o instalador automático acessando a página `setup_db.php`. Ele já vai ler o novo arquivo `init.sql` e criar na sua máquina as tabelas **`jogos`** e **`usuarios`** certinhas, para você poder testar o login na hora!
