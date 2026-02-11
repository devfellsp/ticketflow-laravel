#!/bin/bash

echo "========================================="
echo "   TICKET FLOW - TESTES COMPLETOS"
echo "========================================="
echo ""

# Cores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# 1. Login
echo -e "${BLUE}🔐 1. LOGIN${NC}"
LOGIN_RESPONSE=$(curl -s -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "user@teste.com", "password": "password"}')

USER_TOKEN=$(echo "$LOGIN_RESPONSE" | jq -r '.token')

if [ "$USER_TOKEN" = "null" ] || [ -z "$USER_TOKEN" ]; then
    echo -e "${RED}❌ Erro no login!${NC}"
    echo "$LOGIN_RESPONSE"
    exit 1
fi

echo -e "${GREEN}✅ Token obtido: ${USER_TOKEN:0:30}...${NC}"
echo ""

# 2. Listar todos os tickets
echo -e "${BLUE}📋 2. LISTAR TODOS OS TICKETS${NC}"
RESPONSE=$(curl -s http://localhost:8000/api/tickets \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Accept: application/json")

if echo "$RESPONSE" | jq -e . >/dev/null 2>&1; then
    echo "$RESPONSE" | jq '.data[] | {id, titulo, status: .status.value}'
else
    echo -e "${RED}❌ Erro:${NC}"
    echo "$RESPONSE"
fi
echo ""

# 3. Filtro por status
echo -e "${BLUE}🔍 3. FILTRO: STATUS = RESOLVIDO${NC}"
curl -s "http://localhost:8000/api/tickets?status=RESOLVIDO" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Accept: application/json" | jq '.data[] | {id, titulo, status: .status.value}'
echo ""

# 4. Filtro por prioridade
echo -e "${BLUE}🔍 4. FILTRO: PRIORIDADE = CRITICA${NC}"
curl -s "http://localhost:8000/api/tickets?prioridade=CRITICA" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Accept: application/json" | jq '.data[] | {id, titulo, prioridade: .prioridade.value}'
echo ""

# 5. Busca por texto
echo -e "${BLUE}🔍 5. BUSCA: 'Debug'${NC}"
curl -s "http://localhost:8000/api/tickets?search=Debug" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Accept: application/json" | jq '.data[] | {id, titulo}'
echo ""

# 6. Criar novo ticket
echo -e "${BLUE}➕ 6. CRIAR NOVO TICKET${NC}"
NEW_TICKET=$(curl -s -X POST http://localhost:8000/api/tickets \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "titulo": "Ticket de teste automático",
    "descricao": "Criado pelo script de testes",
    "prioridade": "MEDIA"
  }')

TICKET_ID=$(echo "$NEW_TICKET" | jq -r '.data.id')
echo "$NEW_TICKET" | jq '.data | {id, titulo, status: .status.value, prioridade: .prioridade.value}'
echo ""

# 7. Atualizar ticket
echo -e "${BLUE}✏️  7. ATUALIZAR TICKET #${TICKET_ID}${NC}"
curl -s -X PUT "http://localhost:8000/api/tickets/${TICKET_ID}" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "titulo": "Ticket ATUALIZADO",
    "descricao": "Descrição modificada",
    "prioridade": "ALTA"
  }' | jq '.data | {id, titulo, prioridade: .prioridade.value}'
echo ""

# 8. Mudar status
echo -e "${BLUE}🔄 8. MUDAR STATUS para EM_ANDAMENTO${NC}"
curl -s -X PATCH "http://localhost:8000/api/tickets/${TICKET_ID}/status" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status": "EM_ANDAMENTO"}' | jq '.data | {id, titulo, status: .status.value}'
echo ""

# 9. Atribuir responsável
echo -e "${BLUE}👤 9. ATRIBUIR RESPONSÁVEL${NC}"
curl -s -X PATCH "http://localhost:8000/api/tickets/${TICKET_ID}/assign" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"responsavel_id": 1}' | jq '.data | {id, titulo, responsavel: .responsavel.name}'
echo ""

# 10. Ver logs de auditoria
echo -e "${BLUE}📜 10. LOGS DE AUDITORIA${NC}"
curl -s "http://localhost:8000/api/tickets/${TICKET_ID}/logs" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Accept: application/json" | jq '.data[] | {action, description, user: .user.name}'
echo ""

# 11. Dashboard
echo -e "${BLUE}📊 11. DASHBOARD${NC}"
curl -s "http://localhost:8000/api/dashboard/tickets" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Accept: application/json" | jq '.'
echo ""

# 12. Deletar ticket
echo -e "${BLUE}🗑️  12. DELETAR TICKET #${TICKET_ID}${NC}"
curl -s -X DELETE "http://localhost:8000/api/tickets/${TICKET_ID}" \
  -H "Authorization: Bearer $USER_TOKEN" \
  -H "Accept: application/json" | jq '.'
echo ""

echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}   ✅ TODOS OS TESTES CONCLUÍDOS!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo "📚 Documentação Swagger: http://localhost:8000/docs/api"