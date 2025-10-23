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

1. Instalar dependências PHP

   ```
   composer install
   composer dump-autoload -o
   ```

---

## 🗃️ Banco de Dados

O projeto utiliza **SQLite**.
O arquivo já está criado em:

```
app/Database/database.sqlite
```

O caminho é configurado em:

```php
<?php
return [
    'database_path' => ROOT_PATH . '/app/Database/database.sqlite',
];
```

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

## 💅 (Opcional) Compilar CSS e JS

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

---

✅ **Pronto para rodar!**
Após instalar as dependências e iniciar o servidor PHP, o sistema deve estar acessível em:

```
http://localhost:8000
```

---