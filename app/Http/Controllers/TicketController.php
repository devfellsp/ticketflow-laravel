<?php

namespace App\Http\Controllers;

use App\DTOs\CreateTicketDTO;
use App\DTOs\UpdateTicketDTO;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TicketCollection;
use App\Services\TicketService;
use App\Models\Ticket;
use Illuminate\Http\Request;

/**
 * Controller de Tickets (MAGRO - sem lógica de negócio)
 * Apenas recebe requests e retorna responses
 */
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
    
    $tickets = $this->service->listTickets($filters);  // ← MUDANÇA AQUI
    
    if ($request->expectsJson()) {
        return TicketResource::collection($tickets);  // ← Collection, não TicketCollection
    }
    
    return view('tickets.index', compact('tickets'));
}


    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Cria novo ticket
     */
    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['solicitante_id'] = $request->user()->id;

        $dto = CreateTicketDTO::fromArray($data);
        $ticket = $this->service->createTicket($dto);
        
        if ($request->expectsJson()) {
            return new TicketResource($ticket);
        }
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Chamado criado com sucesso!');
    }

    /**
     * Exibe ticket específico
     */
    public function show(Request $request, Ticket $ticket)
    {
        $ticket = $this->service->getTicketById($ticket->id);
        
        if ($request->expectsJson()) {
            return new TicketResource($ticket);
        }
        
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Atualiza ticket
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // Autorização via Policy
        $this->authorize('update', $ticket);
        
        $dto = UpdateTicketDTO::fromArray($request->validated());
        $updatedTicket = $this->service->updateTicket($ticket->id, $dto);
        
        if ($request->expectsJson()) {
            return new TicketResource($updatedTicket);
        }
        
        return redirect()->route('tickets.show', $updatedTicket)
            ->with('success', 'Chamado atualizado com sucesso!');
    }

    /**
     * Remove o ticket (soft delete)
     * Apenas: solicitante ou admin (POLICY)
     */
    /**
 * Remove o ticket (soft delete)
 * Apenas: solicitante ou admin (POLICY)
 */
public function destroy(Request $request, Ticket $ticket)
{
    // Autorização via Policy
    $this->authorize('delete', $ticket);

    $this->service->deleteTicket($ticket->id);  // ← CORREÇÃO AQUI

    if ($request->wantsJson()) {
        return response()->json([
            'message' => 'Ticket excluído com sucesso',
        ], 200);
    }

    return redirect()->route('tickets.index')
        ->with('success', 'Ticket excluído com sucesso!');
}
/**
 * Lista logs de auditoria de um ticket
 */
public function logs(Ticket $ticket)
{
    $this->authorize('view', $ticket);
    
    $logs = $ticket->logs()
        ->with('user')
        ->orderBy('created_at', 'desc')
        ->get();
    
    return response()->json([
        'data' => $logs->map(function($log) {
            return [
                'id' => $log->id,
                'action' => $log->action,
                'description' => $log->description,
                'user' => $log->user ? [
                    'id' => $log->user->id,
                    'name' => $log->user->name,
                ] : null,
                'before' => $log->before,
                'after' => $log->after,
                'created_at' => $log->created_at->toIso8601String(),
            ];
        })
    ]);
}
/**
 * Muda status do ticket
 * PATCH /api/tickets/{id}/status
 */
public function changeStatus(Request $request, Ticket $ticket)
{
    $request->validate([
        'status' => ['required', 'string', 'in:ABERTO,EM_ANDAMENTO,RESOLVIDO,FECHADO']
    ]);
    
    $this->authorize('update', $ticket);
    
    $status = \App\Enums\TicketStatus::from($request->status);
    $updatedTicket = $this->service->changeStatus($ticket->id, $status);
    
    return new TicketResource($updatedTicket);
}

/**
 * Atribui responsável ao ticket
 * PATCH /api/tickets/{id}/assign
 */
public function assignResponsible(Request $request, Ticket $ticket)
{
    $request->validate([
        'responsavel_id' => ['required', 'integer', 'exists:users,id']
    ]);
    
    $this->authorize('update', $ticket);
    
    $updatedTicket = $this->service->assignResponsible($ticket->id, $request->responsavel_id);
    
    return new TicketResource($updatedTicket);
}

/**
 * Dashboard - contagens e estatísticas
 * GET /api/dashboard/tickets
 */
public function dashboard()
{
    $counts = $this->service->getStatusCounts();
    
    return response()->json([
        'status_counts' => $counts,
        'total' => array_sum($counts),
    ]);
}
}