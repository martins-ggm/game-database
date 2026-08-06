<?php

namespace App\View\Components;

use App\Services\Gerenciador\Interfaces\IPatchNoteService;
use Illuminate\View\Component;
use Illuminate\View\View;

class Footer extends Component
{
    public ?string $versao;

    public function __construct(IPatchNoteService $patchNoteService)
    {
        // versão exibida no rodapé = sempre o patch note mais recente
        $this->versao = $patchNoteService->versaoAtual();
    }

    public function render(): View
    {

        return view('components.footer');
    }
}
