# Documentação do Sistema
#### Visão Geral

Este sistema é uma aplicação web desenvolvida para gerenciar usuários. Ele permite que os usuários se cadastrem, façam login, visualizem e atualizem seus dados, além de armazenar múltiplos endereços. A aplicação inclui testes unitários para garantir a integridade e o funcionamento correto das funcionalidades.

#### Funcionalidades Principais
- Cadastro de usuários
    - Permite que novos usuários se registrem fornecendo nome, email, senha, CPF, telefone e outros dados necessários.
- Login de usuários
    - Autenticação baseada em JWT (JSON Web Token) para permitir acesso seguro às funcionalidades da aplicação.
- Exibição de Dados do Usuário
    - Funcionalidade para visualizar e atualizar os dados do usuário logado.
- Armazenamento de Endereços
    - Capacidade de adicionar, visualizar, editar e excluir múltiplos endereços associados a um usuário.
- Testes Unitários
    - Testes automatizados para garantir a estabilidade e a corretude das funcionalidades implementadas.

## Instalação
#### Pré-requisitos
- PHP 7.4 ou superior
- Composer
- MySQL

### 1. Clonar o repositório
```bash
git@github.com:LeonardoScherer/UserManager.git

cd UserManager
```

### 2. Instalar dependências
```bash
composer install
```
### 3. Configurar o ambiente
Renomeie o arquivo .env.example para .env e configure as variáveis de ambiente, como conexão com o banco de dados.
```bash
cp .env.example .env
```
### 4. Gerar Chave Criptografada
```bash
php artisan key:generate
php artisan jwt:secret
```
### 5. Configurar o banco de dados
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```
### 6. Executar migrações
```bash
php artisan migrate
```

### 7. Iniciar o servidor
```bash
php artisan serve
```
