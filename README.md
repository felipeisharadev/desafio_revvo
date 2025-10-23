# Desafio Revvo – PHP MVC (Cursos)

Projeto PHP puro estruturado em **MVC**, com **autoload PSR-4** via Composer e banco de dados **SQLite**.

---

## 🧩 Requisitos

* PHP 8.1+ com extensões:

  * pdo_sqlite
  * fileinfo
* Composer 2.x
* (Opcional) Node.js 18+ para recompilar os estilos com Gulp

---

## ⚙️ Instalação

1. Instalar dependências PHP:

   ```
   composer install
   composer dump-autoload -o
   ```

---

## 🗃️ Banco de Dados

O projeto utiliza **SQLite** como banco local padrão.
O arquivo do banco já está criado em:

```
app/Database/database.sqlite
```

O caminho é definido em:

```php
<?php
return [
    'database_path' => ROOT_PATH . '/app/Database/database.sqlite',
];
```

### Criando o banco do zero (opcional)

Se quiser testar a criação completa do banco, basta:

1. Excluir o arquivo `app/Database/database.sqlite`.
2. Rodar as migrations e seeds:

   ```
   php scripts/migrate.php
   php scripts/seed.php
   ```

Esses comandos irão:

* recriar automaticamente o banco SQLite,
* executar todos os arquivos `.sql` em `app/Database/migrations/`,
* e popular os dados iniciais em `Database/seeds/`.

---

## 🖼️ Pastas de Uploads

As pastas de imagens já estão criadas e configuradas.
Se necessário, garanta permissão de escrita (em ambientes Linux/macOS):

```
chmod -R 775 public/uploads
chmod -R 775 app/Database
```

---

## 🚀 Executando o Projeto

Use o servidor embutido do PHP:

```
php -S localhost:8000 -t public public/index.php
```

Depois, acesse no navegador:

```
http://localhost:8000
```

---

## (Opcional) Compilar CSS e JS

Caso precise recompilar os estilos durante o desenvolvimento:

```
npm install
npm run start   # executa o gulp em modo watch
```

---

## 📄 Observações

* O projeto **não utiliza `.env`** — o caminho do banco está definido em `config.database.php`.
* Se mover o arquivo `database.sqlite`, atualize o campo `database_path` nesse arquivo.
* As pastas de upload e banco já vêm prontas para uso local.
* As migrations e seeds estão em:

  ```
  app/Database/migrations/
  Database/seeds/
  ```

  Use:

  ```
  php scripts/migrate.php
  php scripts/seed.php
  ```

---

✅ **Pronto para rodar!**
Após instalar as dependências e iniciar o servidor PHP, o sistema deve estar acessível em:

```
http://localhost:8000
```

---
