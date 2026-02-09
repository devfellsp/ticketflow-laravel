<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     * Equivalente ao @GET em Quarkus
     */
    public function index(Request $request)
    {
        // Query builder (similar ao Panache no Quarkus)
        $query = Ticket::with(['solicitante', 'responsavel']);
        
        // Filtros (como @QueryParam no Quarkus)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('prioridade')) {
            $query->where('prioridade', $request->prioridade);
        }
        
        $tickets = $query->paginate(15);
        
        // Se for requisição API, retorna JSON
        if ($request->expectsJson()) {
            return response()->json($tickets);
        }
        
        // Se for web, retorna view Blade
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new ticket.
     * Apenas para web (Blade)
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created ticket.
     * Equivalente ao @POST em Quarkus
     */
    public function store(Request $request)
    {
        // Validação inline (depois vamos mover para FormRequest)
        $validated = $request->validate([
            'titulo' => 'required|string|min:5|max:120',
            'descricao' => 'required|string|min:20',
            'prioridade' => 'required|in:BAIXA,MEDIA,ALTA,CRITICA',
        ]);
        
        // Criar ticket (Eloquent = JPA no Java)
        $ticket = new Ticket($validated);
        $ticket->solicitante_id = Auth::id(); // Usuário autenticado
        $ticket->status = 'ABERTO'; // Status inicial
        $ticket->save();
        
        if ($request->expectsJson()) {
            return response()->json($ticket, 201);
        }
        
        return redirect()->route('tickets.index')
            ->with('success', 'Chamado criado com sucesso!');
    }

    /**
     * Display the specified ticket.
     * Equivalente ao @GET @Path("/{id}") no Quarkus
     */
    public function show(Ticket $ticket)
    {
        // Route Model Binding: Laravel já busca o ticket automaticamente!
        // Carrega relacionamentos
        $ticket->load(['solicitante', 'responsavel', 'logs.usuario']);
        
        if (request()->expectsJson()) {
            return response()->json($ticket);
        }
        
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the ticket.
     */
    public function edit(Ticket $ticket)
    {
        // Verifica autorização (depois implementamos Policy)
        $this->authorize('update', $ticket);
        
        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the ticket.
     * Equivalente ao @PATCH no Quarkus
     */
    public function update(Request $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);
        
        $validated = $request->validate([
            'titulo' => 'sometimes|string|min:5|max:120',
            'descricao' => 'sometimes|string|min:20',
            'status' => 'sometimes|in:ABERTO,EM_ANDAMENTO,RESOLVIDO,FECHADO',
            'prioridade' => 'sometimes|in:BAIXA,MEDIA,ALTA,CRITICA',
            'responsavel_id' => 'nullable|exists:users,id',
        ]);
        
        // Observer vai detectar mudança de status automaticamente
        $ticket->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json($ticket);
        }
        
        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Chamado atualizado!');
    }

    /**
     * Remove the ticket (soft delete).
     * Equivalente ao @DELETE no Quarkus
     */
    public function destroy(Ticket $ticket)
    {
        // Policy vai verificar se é admin ou dono
        $this->authorize('delete', $ticket);
        
        $ticket->delete(); // Soft delete
        
        if (request()->expectsJson()) {
            return response()->json(['message' => 'Chamado excluído'], 200);
        }
        
        return redirect()->route('tickets.index')
            ->with('success', 'Chamado excluído!');
    }
}