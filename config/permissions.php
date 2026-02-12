<?php

return [
    'admin' => [
        'kanban.*',
        'clients.*',
        'ai.*',
        'proposals.*',
        'emails.*',
        'users.*',
        'settings.*',
        'reports.*',
        'audit.*',
        'forms.*',
        'public_forms.*',
    ],

    'user' => [
        'kanban.view',
        'kanban.move',
        'clients.view',
        'clients.edit',
        'clients.create',
        'ai.analyze',
        'proposals.view',
        'proposals.create',
        'proposals.edit',
        'emails.view',
        'emails.send',
        'forms.view',
    ],

    'client' => [
        'form.fill',
        'proposal.view',
        'proposal.accept',
    ],
];
