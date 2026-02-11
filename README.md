# 🎫 Ticket Flow - Sistema de Gerenciamento de Tickets

Sistema RESTful API para gerenciamento de tickets/chamados desenvolvido em Laravel.

---

## 🚀 **Tecnologias Utilizadas**

- **PHP 8.3** - Linguagem
- **Laravel 11** - Framework
- **MySQL** - Banco de dados
- **Sanctum** - Autenticação API
- **Enums** - Status e Prioridades tipados
- **Soft Deletes** - Exclusão lógica
- **Auditoria** - Log de todas alterações
- **Resources** - Transformação de dados
- **Policies** - Autorização
- **Service Layer** - Lógica de negócio
- **Scramble** - Documentação automática (Swagger)

---

## 📋 **Funcionalidades Implementadas**

### ✅ **Autenticação**
- [x] Registro de usuários
- [x] Login (gera token Sanctum)
- [x] Logout
- [x] Perfil do usuário (`/api/me`)

### ✅ **CRUD de Tickets**
- [x] Criar ticket
- [x] Listar tickets (com paginação)
- [x] Visualizar detalhes
- [x] Atualizar ticket
- [x] Deletar ticket (soft delete)

### ✅ **Filtros e Buscas**
- [x] Filtro por **status** (ABERTO, EM_ANDAMENTO, RESOLVIDO, FECHADO)
- [x] Filtro por **prioridade** (BAIXA, MEDIA, ALTA, CRITICA)
- [x] Busca por **texto** (título e descrição)
- [x] Filtros combinados

### ✅ **Ações Especiais**
- [x] Mudar status do ticket
- [x] Atribuir/desatribuir responsável
- [x] Ver histórico de alterações (auditoria)

### ✅ **Dashboard**
- [x] Contagem de tickets por status
- [x] Total de tickets

### ✅ **Auditoria Completa**
- [x] Log de criação
- [x] Log de atualizações (campo a campo)
- [x] Log de mudança de status
- [x] Log de atribuição de responsável
- [x] Rastreamento de usuário e data/hora

### ✅ **Autorização**
- [x] Usuário só vê tickets que criou ou foi atribuído
- [x] Admin vê todos os tickets
- [x] Políticas de acesso (view, update, delete)

### ✅ **Documentação**
- [x] Swagger/OpenAPI automático (Scramble)
- [x] Acesso via `/docs/api`

---

## 🛠️ **Instalação e Configuração**

### **1. Clonar repositório (ou descompactar)**

```bash
cd ~/ticket-flow
```

### **2. Instalar dependências**

```bash
composer install
```

### **3. Configurar ambiente**

```bash
cp .env.example .env
php artisan key:generate
```

### **4. Configurar banco de dados**

Editar `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ticket_flow
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

### **5. Criar banco de dados**

```bash
mysql -u root -p
CREATE DATABASE ticket_flow;
exit;
```

### **6. Executar migrations e seeders**

```bash
php artisan migrate:fresh --seed
```

### **7. Iniciar servidor**

```bash
php artisan serve
```

Servidor rodando em: `http://localhost:8000`

---

## 📖 **Documentação da API**

### **Swagger (Interface Interativa)**

Acesse: **http://localhost:8000/docs/api**

---

## 🧪 **Executar Testes Automatizados**

```bash
# Script completo de testes
chmod +x test_complete_api.sh
./test_complete_api.sh
```

---

## 🔑 **Usuários de Teste**

| Email | Senha | Role |
|-------|-------|------|
| `admin@teste.com` | `password` | ADMIN |
| `user@teste.com` | `password` | USER |
| `tecnico@teste.com` | `password` | USER |

---

## 📡 **Endpoints da API**

### **🔐 Autenticação**

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| POST | `/api/register` | Registrar usuário | Não |
| POST | `/api/login` | Login | Não |
| POST | `/api/logout` | Logout | Sim |
| GET | `/api/me` | Dados do usuário | Sim |

### **🎫 Tickets (CRUD)**

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| GET | `/api/tickets` | Listar tickets | Sim |
| POST | `/api/tickets` | Criar ticket | Sim |
| GET | `/api/tickets/{id}` | Ver detalhes | Sim |
| PUT/PATCH | `/api/tickets/{id}` | Atualizar | Sim |
| DELETE | `/api/tickets/{id}` | Deletar | Sim |

**Filtros disponíveis em `GET /api/tickets`:**
- `?status=ABERTO` - Filtro por status
- `?prioridade=ALTA` - Filtro por prioridade
- `?search=texto` - Busca em título/descrição
- Combinações: `?status=ABERTO&prioridade=ALTA`

### **🔧 Ações Especiais**

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| PATCH | `/api/tickets/{id}/status` | Mudar status | Sim |
| PATCH | `/api/tickets/{id}/assign` | Atribuir responsável | Sim |
| GET | `/api/tickets/{id}/logs` | Ver auditoria | Sim |

### **📊 Dashboard**

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| GET | `/api/dashboard/tickets` | Contagens por status | Sim |

---

## 📝 **Exemplos de Uso**

### **1. Login**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@teste.com",
    "password": "password"
  }'
```

**Resposta:**
```json
{
  "user": {
    "id": 2,
    "name": "Usuário Comum",
    "email": "user@teste.com"
  },
  "token": "1|abc123..."
}
```

### **2. Listar Tickets (com filtro)**

```bash
curl http://localhost:8000/api/tickets?status=ABERTO \
  -H "Authorization: Bearer SEU_TOKEN"
```

### **3. Criar Ticket**

```bash
curl -X POST http://localhost:8000/api/tickets \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "titulo": "Problema no sistema",
    "descricao": "Não consigo fazer login",
    "prioridade": "ALTA"
  }'
```

### **4. Mudar Status**

```bash
curl -X PATCH http://localhost:8000/api/tickets/1/status \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"status": "EM_ANDAMENTO"}'
```

### **5. Atribuir Responsável**

```bash
curl -X PATCH http://localhost:8000/api/tickets/1/assign \
  -H "Authorization: Bearer SEU_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"responsavel_id": 1}'
```

### **6. Ver Logs de Auditoria**

```bash
curl http://localhost:8000/api/tickets/1/logs \
  -H "Authorization: Bearer SEU_TOKEN"
```

### **7. Dashboard**

```bash
curl http://localhost:8000/api/dashboard/tickets \
  -H "Authorization: Bearer SEU_TOKEN"
```

**Resposta:**
```json
{
  "status_counts": {
    "ABERTO": 5,
    "EM_ANDAMENTO": 3,
    "RESOLVIDO": 2,
    "FECHADO": 1
  },
  "total": 11
}
```

---

## 🗂️ **Estrutura do Projeto**

```
ticket-flow/
├── app/
│   ├── Enums/
│   │   ├── TicketStatus.php      # Enum de status
│   │   └── TicketPriority.php    # Enum de prioridade
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   └── TicketController.php
│   │   ├── Requests/
│   │   │   ├── StoreTicketRequest.php
│   │   │   └── UpdateTicketRequest.php
│   │   └── Resources/
│   │       └── TicketResource.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Ticket.php
│   │   └── AuditLog.php
│   ├── Policies/
│   │   └── TicketPolicy.php
│   └── Services/
│       └── TicketService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php
├── routes/
│   └── api.php
├── tests/
├── test_complete_api.sh           # Script de testes
└── README.md
```

---

## 🎯 **Diferenciais Implementados**

✅ **Arquitetura em camadas** (Controller → Service → Model)  
✅ **Enums nativos do PHP** para status e prioridades  
✅ **Soft Deletes** - dados nunca são perdidos  
✅ **Auditoria completa** - rastreio de todas as mudanças  
✅ **Policies** - autorização granular  
✅ **Resources** - transformação de dados padronizada  
✅ **Validações** - FormRequests separados  
✅ **Documentação automática** - Swagger via Scramble  
✅ **Filtros combinados** - múltiplos filtros simultaneamente  
✅ **Timestamps automáticos** - `created_at`, `updated_at`, `resolved_at`  
✅ **Relacionamentos eloquent** - usuários e tickets  

---

## 🧑‍💻 **Desenvolvido por**

Filipe M. Henrique

---

## 📄 **Licença**

MIT License