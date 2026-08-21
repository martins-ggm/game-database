<?php

return [

    'client_id' => env('IGDB_CLIENT_ID'),
    'client_secret' => env('IGDB_CLIENT_SECRET'),
    'base_url' => env('IGDB_BASE_URL', 'https://api.igdb.com/v4'),
    'token_url' => env('IGDB_TOKEN_URL', 'https://id.twitch.tv/oauth2/token'),
    'cache_key' => 'igdb_token_acesso'

];