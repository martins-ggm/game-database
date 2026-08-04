<?php

/*
 | web.php — apenas orquestra os arquivos de rota.
 |
 |  telas.php    → navegação (tudo que devolve view)
 |  jogos.php    → ações de jogo (criar/editar/remover/buscar)
 |  plataformas.php / desenvolvedoras.php / generos.php → ações do catálogo
 |  usuarios.php → registro, login, logout, atualização
 |  colecao.php  → ações da coleção
 */

require __DIR__ . '/telas.php';

require __DIR__ . '/jogos.php';
require __DIR__ . '/plataformas.php';
require __DIR__ . '/desenvolvedoras.php';
require __DIR__ . '/generos.php';
require __DIR__ . '/usuarios.php';
require __DIR__ . '/colecao.php';
require __DIR__ . '/review.php';
