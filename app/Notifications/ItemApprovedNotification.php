<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ItemApprovedNotification extends Notification
{
    use Queueable;

    protected $itemName;
    protected $itemType;

    public function __construct($itemName, $itemType)
    {
        $this->itemName = $itemName;
        $this->itemType = $itemType;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Item Disetujui',
            'message' => "Selamat! {$this->itemType} '{$this->itemName}' Anda telah disetujui oleh admin.",
            'icon' => $this->itemType === 'Produk' ? 'fas fa-box' : 'fas fa-tools',
        ];
    }
}
