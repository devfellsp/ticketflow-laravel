<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketResolvidoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Ticket $ticket
    ) {}

    /**
     * Canais de notificação
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Notificação por email
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ticket #{$this->ticket->id} foi resolvido! 🎉")
            ->greeting("Olá, {$notifiable->name}!")
            ->line("Seu ticket **#{$this->ticket->id} - {$this->ticket->titulo}** foi marcado como RESOLVIDO.")
            ->line("**Descrição:** {$this->ticket->descricao}")
            ->line("**Prioridade:** {$this->ticket->prioridade->label()}")
            ->line("**Resolvido em:** {$this->ticket->resolved_at->format('d/m/Y H:i')}")
            ->action('Ver Ticket', url("/tickets/{$this->ticket->id}"))
            ->line('Obrigado por usar nosso sistema!');
    }

    /**
     * Notificação no banco de dados
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'titulo' => $this->ticket->titulo,
            'status' => $this->ticket->status->value,
            'resolved_at' => $this->ticket->resolved_at,
            'message' => "Ticket #{$this->ticket->id} foi resolvido!",
        ];
    }
}