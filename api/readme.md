# 📚 Documentação da API - Clube de Heróis

## 📋 Índice
- [Visão Geral](#visão-geral)
- [Base URL](#base-url)
- [Autenticação](#autenticação)
- [Formato das Respostas](#formato-das-respostas)
- [Códigos de Status HTTP](#códigos-de-status-http)
- [API de Clubes](#api-de-clubes)
- [API de Produtos](#api-de-produtos)
- [Exemplos de Uso](#exemplos-de-uso)
- [Tratamento de Erros](#tratamento-de-erros)

---

## 🌟 Visão Geral

A API do Clube de Heróis é um Web Service RESTful que permite gerenciar clubes e produtos relacionados ao universo geek. A API oferece operações CRUD completas para ambas as entidades.

### Características:
- ✅ **RESTful**: Segue padrões REST
- ✅ **JSON**: Todas as respostas em formato JSON
- ✅ **CRUD Completo**: Create, Read, Update, Delete
- ✅ **Hard Delete**: Remoção permanente do banco de dados
- ✅ **Validações**: Campos obrigatórios e regras de negócio
- ✅ **Sem Autenticação**: Pronto para uso direto no Postman

---

## 🔗 Base URL

```
http://localhost/Clube_de_her-is-master/api/
```

---

## 🔐 Autenticação

**Não é necessária autenticação** para os endpoints principais da API. Todos os endpoints estão disponíveis para uso direto.

---

## 📄 Formato das Respostas

Todas as respostas seguem o padrão JSON:

### Resposta de Sucesso:
```json
{
    "type": "success",
    "message": "Mensagem descritiva",
    "data": { /* dados específicos */ }
}
```

### Resposta de Erro:
```json
{
    "type": "error",
    "message": "Descrição do erro"
}
```

---

## 📊 Códigos de Status HTTP

| Código | Descrição | Uso |
|--------|-----------|-----|
| `200` | OK | Operação realizada com sucesso |
| `400` | Bad Request | Dados inválidos ou campos obrigatórios ausentes |
| `404` | Not Found | Recurso não encontrado |
| `500` | Internal Server Error | Erro interno do servidor |

---

## 🏛️ API de Clubes

### 📋 Listar Todos os Clubes

**GET** `/clubs`

Lista todos os clubes ativos do sistema.

#### Exemplo de Requisição:
```bash
curl -X GET "http://localhost/Clube_de_her-is-master/api/clubs"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Lista de clubes",
    "clubs": [
        {
            "id": 1,
            "user_id": 1,
            "club_name": "Clube dos Heróis Marvel",
            "description": "Um clube dedicado aos fãs dos heróis da Marvel",
            "is_active": 1,
            "created_at": "2025-05-23 22:27:16"
        }
    ]
}
```

---

### 🔍 Buscar Clube por ID

**GET** `/clubs/club/{id}`

Retorna os detalhes de um clube específico.

#### Parâmetros:
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `id` | integer | Sim | ID do clube |

#### Exemplo de Requisição:
```bash
curl -X GET "http://localhost/Clube_de_her-is-master/api/clubs/club/1"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Dados do clube",
    "club": {
        "id": 1,
        "user_id": 1,
        "club_name": "Clube dos Heróis Marvel",
        "description": "Um clube dedicado aos fãs dos heróis da Marvel",
        "is_active": 1,
        "created_at": "2025-05-23 22:27:16"
    }
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Clube não encontrado"
}
```

---

### ➕ Criar Novo Clube

**POST** `/clubs`

Cria um novo clube no sistema.

#### Parâmetros (Form Data):
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `user_id` | integer | Sim | ID do usuário proprietário |
| `club_name` | string | Sim | Nome do clube |
| `description` | string | Não | Descrição do clube |

#### Exemplo de Requisição:
```bash
curl -X POST "http://localhost/Clube_de_her-is-master/api/clubs" \
  -d "user_id=1&club_name=Clube DC Comics&description=Clube dedicado aos heróis da DC"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Clube cadastrado com sucesso!",
    "club_id": 4
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Nome do clube é obrigatório"
}
```

```json
{
    "type": "error",
    "message": "ID do usuário é obrigatório"
}
```

---

### ✏️ Atualizar Clube

**PUT** `/clubs/club/{id}`

Atualiza os dados de um clube existente.

#### Parâmetros:
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `id` | integer | Sim | ID do clube (na URL) |
| `club_name` | string | Sim | Nome do clube |
| `description` | string | Não | Descrição do clube |
| `is_active` | boolean | Não | Status ativo/inativo |

#### Exemplo de Requisição:
```bash
curl -X PUT "http://localhost/Clube_de_her-is-master/api/clubs/club/4" \
  -d "club_name=Clube DC Comics Premium&description=Clube premium da DC"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Clube atualizado com sucesso!",
    "club": {
        "id": 4,
        "club_name": "Clube DC Comics Premium",
        "description": "Clube premium da DC",
        "is_active": true
    }
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Clube não encontrado"
}
```

---

### 🗑️ Remover Clube

**DELETE** `/clubs/club/{id}`

Remove permanentemente um clube do banco de dados.

#### Parâmetros:
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `id` | integer | Sim | ID do clube |

#### Exemplo de Requisição:
```bash
curl -X DELETE "http://localhost/Clube_de_her-is-master/api/clubs/club/4"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Clube removido com sucesso!"
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Clube não encontrado"
}
```

---

## 🛍️ API de Produtos

### 📋 Listar Todos os Produtos

**GET** `/products`

Lista todos os produtos ativos do sistema.

#### Exemplo de Requisição:
```bash
curl -X GET "http://localhost/Clube_de_her-is-master/api/products"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Lista de produtos",
    "products": [
        {
            "id": 1,
            "club_id": 1,
            "name": "Camiseta Exclusiva Marvel",
            "description": "Camiseta oficial da Marvel com estampa exclusiva do Homem de Ferro.",
            "price": "99.90",
            "stock": 150,
            "category_id": 2,
            "fandom": "Marvel",
            "rarity": "exclusive",
            "sku": "MARV-TSH-001",
            "is_physical": 1,
            "subscription_only": 0,
            "weight_grams": 250,
            "dimensions_cm": "30x20x2",
            "image_url": "https://example.com/images/marvel_tshirt.jpg",
            "is_active": 1,
            "created_at": "2025-05-23 22:45:48"
        }
    ]
}
```

---

### 🔍 Buscar Produto por ID

**GET** `/products/product/{id}`

Retorna os detalhes de um produto específico.

#### Parâmetros:
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `id` | integer | Sim | ID do produto |

#### Exemplo de Requisição:
```bash
curl -X GET "http://localhost/Clube_de_her-is-master/api/products/product/1"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Dados do produto",
    "product": {
        "id": 1,
        "club_id": 1,
        "name": "Camiseta Exclusiva Marvel",
        "description": "Camiseta oficial da Marvel com estampa exclusiva do Homem de Ferro.",
        "price": "99.90",
        "stock": 150,
        "category_id": 2,
        "fandom": "Marvel",
        "rarity": "exclusive",
        "sku": "MARV-TSH-001",
        "is_physical": 1,
        "subscription_only": 0,
        "weight_grams": 250,
        "dimensions_cm": "30x20x2",
        "image_url": "https://example.com/images/marvel_tshirt.jpg",
        "is_active": 1,
        "created_at": "2025-05-23 22:45:48"
    }
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Produto não encontrado"
}
```

---

### ➕ Criar Novo Produto

**POST** `/products`

Cria um novo produto no sistema.

#### Parâmetros (Form Data):
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `club_id` | integer | Sim | ID do clube proprietário |
| `name` | string | Sim | Nome do produto |
| `price` | decimal | Sim | Preço do produto |
| `description` | string | Não | Descrição do produto |
| `stock` | integer | Não | Quantidade em estoque (padrão: 0) |
| `category_id` | integer | Não | ID da categoria |
| `fandom` | string | Não | Universo/franquia |
| `rarity` | string | Não | Raridade (common, rare, exclusive) |
| `sku` | string | Não | Código SKU único |
| `is_physical` | boolean | Não | Produto físico (padrão: true) |
| `subscription_only` | boolean | Não | Apenas para assinantes (padrão: false) |
| `weight_grams` | integer | Não | Peso em gramas |
| `dimensions_cm` | string | Não | Dimensões em cm |
| `image_url` | string | Não | URL da imagem |

#### Exemplo de Requisição:
```bash
curl -X POST "http://localhost/Clube_de_her-is-master/api/products" \
  -d "club_id=1&name=Action Figure Batman&description=Action Figure do Batman da DC Comics&price=149.90&stock=50&fandom=DC Comics&rarity=rare&sku=DC-AF-001"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Produto cadastrado com sucesso!",
    "product_id": 2
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Campo club_id é obrigatório"
}
```

```json
{
    "type": "error",
    "message": "Campo name é obrigatório"
}
```

```json
{
    "type": "error",
    "message": "Campo price é obrigatório"
}
```

---

### ✏️ Atualizar Produto

**PUT** `/products/product/{id}`

Atualiza os dados de um produto existente.

#### Parâmetros:
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `id` | integer | Sim | ID do produto (na URL) |
| `name` | string | Sim | Nome do produto |
| `price` | decimal | Sim | Preço do produto |
| `description` | string | Não | Descrição do produto |
| `stock` | integer | Não | Quantidade em estoque |
| `category_id` | integer | Não | ID da categoria |
| `fandom` | string | Não | Universo/franquia |
| `rarity` | string | Não | Raridade |
| `sku` | string | Não | Código SKU |
| `is_physical` | boolean | Não | Produto físico |
| `subscription_only` | boolean | Não | Apenas para assinantes |
| `weight_grams` | integer | Não | Peso em gramas |
| `dimensions_cm` | string | Não | Dimensões em cm |
| `image_url` | string | Não | URL da imagem |
| `is_active` | boolean | Não | Status ativo/inativo |

#### Exemplo de Requisição:
```bash
curl -X PUT "http://localhost/Clube_de_her-is-master/api/products/product/2" \
  -d "name=Action Figure Batman Deluxe&description=Action Figure premium do Batman&price=199.90&stock=30"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Produto atualizado com sucesso!",
    "product": {
        "id": 2,
        "name": "Action Figure Batman Deluxe",
        "description": "Action Figure premium do Batman",
        "price": 199.9,
        "stock": 30,
        "is_active": true
    }
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Produto não encontrado"
}
```

---

### 🗑️ Remover Produto

**DELETE** `/products/product/{id}`

Remove permanentemente um produto do banco de dados.

#### Parâmetros:
| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `id` | integer | Sim | ID do produto |

#### Exemplo de Requisição:
```bash
curl -X DELETE "http://localhost/Clube_de_her-is-master/api/products/product/2"
```

#### Exemplo de Resposta:
```json
{
    "type": "success",
    "message": "Produto removido com sucesso!"
}
```

#### Possíveis Erros:
```json
{
    "type": "error",
    "message": "Produto não encontrado"
}
```

---

## 💡 Exemplos de Uso

### Cenário Completo: Criando um Clube e Produto

#### 1. Criar um Clube:
```bash
curl -X POST "http://localhost/Clube_de_her-is-master/api/clubs" \
  -d "user_id=1&club_name=Clube Anime&description=Clube para fãs de anime"
```

#### 2. Criar um Produto para o Clube:
```bash
curl -X POST "http://localhost/Clube_de_her-is-master/api/products" \
  -d "club_id=5&name=Figure Naruto&description=Action Figure do Naruto&price=89.90&stock=25&fandom=Naruto&rarity=common"
```

#### 3. Listar Produtos:
```bash
curl -X GET "http://localhost/Clube_de_her-is-master/api/products"
```

#### 4. Atualizar Produto:
```bash
curl -X PUT "http://localhost/Clube_de_her-is-master/api/products/product/3" \
  -d "name=Figure Naruto Sage Mode&price=129.90&stock=15"
```

#### 5. Remover Produto:
```bash
curl -X DELETE "http://localhost/Clube_de_her-is-master/api/products/product/3"
```

---

## ⚠️ Tratamento de Erros

### Tipos de Erro Comuns:

#### 1. Campos Obrigatórios Ausentes:
```json
{
    "type": "error",
    "message": "Campo [nome_do_campo] é obrigatório"
}
```

#### 2. Recurso Não Encontrado:
```json
{
    "type": "error",
    "message": "[Recurso] não encontrado"
}
```

#### 3. Dados Inválidos:
```json
{
    "type": "error",
    "message": "Preço é obrigatório e deve ser maior que zero"
}
```

#### 4. Conflito de Dados:
```json
{
    "type": "error",
    "message": "SKU já existe!"
}
```

---

## 🔧 Configuração para Postman

### Headers Recomendados:
```
Content-Type: application/x-www-form-urlencoded
```

### Variáveis de Ambiente:
```
base_url: http://localhost/Clube_de_her-is-master/api
```

### Collection Structure:
```
📁 Clube de Heróis API
├── 📁 Clubs
│   ├── GET List Clubs
│   ├── GET Club by ID
│   ├── POST Create Club
│   ├── PUT Update Club
│   └── DELETE Remove Club
└── 📁 Products
    ├── GET List Products
    ├── GET Product by ID
    ├── POST Create Product
    ├── PUT Update Product
    └── DELETE Remove Product
```

---

## 📝 Notas Importantes

1. **Hard Delete**: Todos os deletes são permanentes - os registros são removidos completamente do banco de dados.

2. **Validações**: A API possui validações robustas para campos obrigatórios e regras de negócio.

3. **Relacionamentos**: Produtos devem estar associados a clubes existentes.

4. **SKU Único**: Se fornecido, o SKU deve ser único entre todos os produtos.

5. **Filtros**: A listagem retorna apenas registros ativos (is_active = 1).

6. **Formato de Data**: Todas as datas seguem o formato `YYYY-MM-DD HH:MM:SS`.

---

## 🚀 Status da API

- ✅ **Funcional**: Todas as rotas testadas e funcionando
- ✅ **Documentada**: Documentação completa disponível
- ✅ **Validada**: Validações implementadas
- ✅ **Pronta para Produção**: Sem dependências de autenticação

---

*Documentação gerada em: 2025-01-23*
*Versão da API: 1.0.0*
