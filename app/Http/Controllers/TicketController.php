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
use Illuminate\Http\JsonResponse;

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
        $filters = $request->only(['status', 'prioridade', 'solicitante_id', 'responsavel_id', 'search']);
        $perPage = $request->get('per_page', 15);
        
        $tickets = $this->service->getAllTickets($filters, $perPage);
        
        if ($request->expectsJson()) {
            return new TicketCollection($tickets);
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
        // Adiciona o ID do usuário autenticado
        $data = $request->validated();
        // Adiciona o ID do usuário autenticado (temporário até implementar auth)
        $data['solicitante_id'] = 1; // TODO: Substituir por auth()->id() quando implementar autenticação // TODO: Remover fallback quando implementar auth
        
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
        // Route Model Binding já busca o ticket
        // Mas vamos recarregar com relacionamentos via service
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
        // TODO: Implementar TicketPolicy
        // $this->authorize('update', $ticket);
        
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Atualiza ticket
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        // TODO: Implementar TicketPolicy
        // $this->authorize('update', $ticket);
        
        $dto = UpdateTicketDTO::fromArray($request->validated());
        $updatedTicket = $this->service->updateTicket($ticket->id, $dto);
        
        if ($request->expectsJson()) {
            return new TicketResource($updatedTicket);
        }
        
        return redirect()->route('tickets.show', $updatedTicket)
            ->with('success', 'Chamado atualizado com sucesso!');
    }

    /**
     * Deleta ticket (soft delete)
     */
    public function destroy(Request $request, Ticket $ticket)
    {
        // TODO: Implementar TicketPolicy
        // $this->authorize('delete', $ticket);
        
        $this->service->deleteTicket($ticket->id);
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Chamado excluído com sucesso'
            ], 200);
        }
        
        return redirect()->route('tickets.index')
            ->with('success', 'Chamado excluído com sucesso!');
    }
}