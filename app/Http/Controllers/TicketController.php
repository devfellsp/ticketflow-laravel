<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $service
    ) {}

    /**
     * Lista todos os tickets
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'prioridade', 'search']);
        $tickets = $this->service->list($filters);
        
        return TicketResource::collection($tickets);
    }

    /**
     * Cria novo ticket
     */
    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['solicitante_id'] = $request->user()->id;

        $ticket = $this->service->create($data);
        
        return new TicketResource($ticket);
    }

    /**
     * Exibe ticket específico
     */
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);
        
        return new TicketResource($ticket->load(['solicitante', 'responsavel']));
    }

    /**
     * Atualiza ticket
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        
        $updatedTicket = $this->service->update($ticket->id, $request->validated());
        
        return new TicketResource($updatedTicket);
    }

    /**
     * Remove o ticket (soft delete)
     */
    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $this->service->delete($ticket->id);

        return response()->json([
            'message' => 'Ticket excluído com sucesso',
        ], 200);
    }

    /**
     * Muda status do ticket
     * PATCH /api/tickets/{id}/status
     */
  public function changeStatus(Request $request, Ticket $ticket): JsonResponse
{
    $request->validate([
        'status' => ['required', 'string', 'in:ABERTO,EM_ANDAMENTO,RESOLVIDO']
    ]);
    
    $this->authorize('update', $ticket);
    
    $status = TicketStatus::from($request->status);
    $updatedTicket = $this->service->changeStatus(
        $ticket->id, 
        $status,
        $request->user()->id  // ← PASSAR O USER_ID
    );
    
    return response()->json([
        'message' => 'Status atualizado com sucesso',
        'data' => new TicketResource($updatedTicket)
    ]);
}

    /**
     * Atribui responsável ao ticket
     * PATCH /api/tickets/{id}/assign
     */
   public function assignResponsible(Request $request, Ticket $ticket): JsonResponse
{
    $request->validate([
        'responsavel_id' => ['nullable', 'integer', 'exists:users,id']
    ]);
    
    $this->authorize('update', $ticket);
    
    $updatedTicket = $this->service->assignResponsible(
        $ticket->id, 
        $request->responsavel_id,
        $request->user()->id  // ← PASSAR O USER_ID
    );
    
    return response()->json([
        'message' => 'Responsável atribuído com sucesso',
        'data' => new TicketResource($updatedTicket)
    ]);
}

    /**
     * Lista logs de auditoria de um ticket
     */
    public function logs(Ticket $ticket): JsonResponse
    {
        $this->authorize('view', $ticket);
        
        $logs = $this->service->getLogs($ticket->id);
        
        return response()->json(['data' => $logs]);
    }

    /**
     * Dashboard - contagens e estatísticas
     */
    public function dashboard(): JsonResponse
    {
        $data = $this->service->dashboard();
        
        return response()->json($data);
    }
}