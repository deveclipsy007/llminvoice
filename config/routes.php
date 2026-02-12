<?php

/**
 * Route definitions.
 * Format: "METHOD /path" => [ControllerClass, action, [middleware]]
 *
 * Controller classes are resolved relative to App\Controllers\
 * e.g., "Admin\DashboardController" -> App\Controllers\Admin\DashboardController
 */

return [
    // =========================================================================
    // HOME PAGE
    // =========================================================================
    'GET /' => ['Client\PublicFormController', 'show', ['locale']],
    
    // =========================================================================
    // AUTH (no middleware)
    // =========================================================================
    'GET /login'  => ['Auth\LoginController', 'showForm', ['locale']],
    'POST /login' => ['Auth\LoginController', 'login', ['locale']],
    'GET /logout' => ['Auth\LogoutController', 'logout', ['auth']],

    // =========================================================================
    // ADMIN PAGES
    // =========================================================================
    'GET /admin' => ['Admin\DashboardController', 'index', ['auth', 'role:admin|user', 'locale']],

    // Kanban
    'GET /admin/kanban' => ['Admin\KanbanController', 'index', ['auth', 'role:admin|user', 'locale']],

    // Clients
    'GET /admin/clients'          => ['Admin\ClientController', 'index', ['auth', 'role:admin|user', 'locale']],
    'GET /admin/clients/create'   => ['Admin\ClientController', 'create', ['auth', 'role:admin|user', 'locale']],
    'POST /admin/clients/create'  => ['Admin\ClientController', 'store', ['auth', 'role:admin|user', 'csrf', 'locale']],
    'GET /admin/clients/{id}'     => ['Admin\ClientController', 'show', ['auth', 'role:admin|user', 'locale']],
    'GET /admin/clients/{id}/edit'  => ['Admin\ClientController', 'edit', ['auth', 'role:admin|user', 'locale']],
    'POST /admin/clients/{id}/edit' => ['Admin\ClientController', 'update', ['auth', 'role:admin|user', 'csrf', 'locale']],

    // Settings
    'GET /admin/settings'             => ['Admin\SettingsController', 'general', ['auth', 'role:admin', 'locale']],
    'POST /admin/settings'            => ['Admin\SettingsController', 'saveGeneral', ['auth', 'role:admin', 'csrf', 'locale']],
    'GET /admin/settings/services'    => ['Admin\SettingsController', 'services', ['auth', 'role:admin', 'locale']],
    'POST /admin/settings/services'   => ['Admin\SettingsController', 'saveService', ['auth', 'role:admin', 'csrf', 'locale']],
    'GET /admin/settings/branding'    => ['Admin\SettingsController', 'branding', ['auth', 'role:admin', 'locale']],
    'POST /admin/settings/branding'   => ['Admin\SettingsController', 'saveBranding', ['auth', 'role:admin', 'csrf', 'locale']],
    'GET /admin/settings/forms'       => ['Admin\FormBuilderController', 'index', ['auth', 'role:admin', 'locale']],
    'GET /admin/form-builder/new'     => ['Admin\FormBuilderController', 'create', ['auth', 'role:admin', 'locale']],
    'GET /admin/form-builder/{id}'    => ['Admin\FormBuilderController', 'edit', ['auth', 'role:admin', 'locale']],
    'POST /admin/form-builder/save'   => ['Admin\FormBuilderController', 'save', ['auth', 'role:admin', 'csrf', 'locale']],
    'POST /admin/form-builder/translate' => ['Admin\FormBuilderController', 'translate', ['auth', 'role:admin', 'csrf', 'locale']],
    'DELETE /admin/form-builder/{id}' => ['Admin\FormBuilderController', 'delete', ['auth', 'role:admin', 'csrf', 'locale']],

    // Public Forms Admin
    'GET /admin/public-forms' => ['Admin\PublicFormAdminController', 'index', ['auth', 'role:admin|user', 'locale']],

    // Audit Log
    'GET /admin/audit-log' => ['Admin\AuditLogController', 'index', ['auth', 'role:admin', 'locale']],

    // =========================================================================
    // API - KANBAN (AJAX)
    // =========================================================================
    'GET /api/kanban/columns'   => ['Admin\KanbanController', 'getColumns', ['auth', 'role:admin|user']],
    'POST /api/kanban/move'     => ['Admin\KanbanController', 'moveCard', ['auth', 'role:admin|user', 'csrf']],
    'POST /api/kanban/reorder'  => ['Admin\KanbanController', 'reorderCard', ['auth', 'role:admin|user', 'csrf']],
    'GET /api/kanban/search'    => ['Admin\KanbanController', 'search', ['auth', 'role:admin|user']],
    'GET /api/kanban/filter'    => ['Admin\KanbanController', 'filter', ['auth', 'role:admin|user']],

    // =========================================================================
    // API - CLIENTS (AJAX)
    // =========================================================================
    'POST /api/clients/{id}/assign'      => ['Admin\ClientController', 'assign', ['auth', 'role:admin|user', 'csrf']],
    'POST /api/clients/{id}/note'        => ['Admin\ClientController', 'addNote', ['auth', 'role:admin|user', 'csrf']],
    'POST /api/clients/{id}/archive'     => ['Admin\ClientController', 'archive', ['auth', 'role:admin', 'csrf']],
    'POST /api/clients/{id}/temperature' => ['Admin\ClientController', 'updateTemperature', ['auth', 'role:admin|user', 'csrf']],

    // =========================================================================
    // API - AI (AJAX)
    // =========================================================================
    'POST /api/ai/analyze/{clientId}' => ['Admin\AiController', 'analyze', ['auth', 'role:admin|user', 'csrf']],
    'GET /api/ai/status/{analysisId}' => ['Admin\AiController', 'status', ['auth', 'role:admin|user']],
    'GET /api/ai/result/{analysisId}' => ['Admin\AiController', 'result', ['auth', 'role:admin|user']],

    // =========================================================================
    // API - PROPOSALS (AJAX)
    // =========================================================================
    'POST /api/proposals/{clientId}'      => ['Admin\ProposalController', 'createFromAi', ['auth', 'role:admin|user', 'csrf']],
    'POST /api/proposals/{id}/save'       => ['Admin\ProposalController', 'save', ['auth', 'role:admin|user', 'csrf']],
    'POST /api/proposals/{id}/approve'    => ['Admin\ProposalController', 'approve', ['auth', 'role:admin', 'csrf']],
    'GET /api/proposals/{id}/versions'    => ['Admin\ProposalController', 'versions', ['auth', 'role:admin|user']],
    'GET /api/proposals/{clientId}/pdf'   => ['Admin\ProposalController', 'downloadPdf', ['auth', 'role:admin|user']],

    // =========================================================================
    // API - EMAILS (AJAX)
    // =========================================================================
    'POST /api/emails/{clientId}/generate' => ['Admin\EmailController', 'generate', ['auth', 'role:admin|user', 'csrf']],
    'POST /api/emails/{id}/send'           => ['Admin\EmailController', 'send', ['auth', 'role:admin|user', 'csrf']],
    'GET /api/emails/{clientId}/history'   => ['Admin\EmailController', 'history', ['auth', 'role:admin|user']],

    // =========================================================================
    // API - PUBLIC FORMS (AJAX)
    // =========================================================================
    'GET /api/public-forms/{id}'          => ['Admin\PublicFormAdminController', 'detail', ['auth', 'role:admin|user']],
    'POST /api/public-forms/{id}/approve' => ['Admin\PublicFormAdminController', 'approve', ['auth', 'role:admin', 'csrf']],
    'POST /api/public-forms/{id}/reject'  => ['Admin\PublicFormAdminController', 'reject', ['auth', 'role:admin', 'csrf']],

    // =========================================================================
    // API - SETTINGS (AJAX)
    // =========================================================================
    'POST /admin/settings/services/import' => ['Admin\SettingsController', 'importServices', ['auth', 'role:admin', 'csrf']],

    // =========================================================================
    // CLIENT AREA (token/UUID access)
    // =========================================================================
    'GET /form/{token}'          => ['Client\FormController', 'show', ['locale']],
    'POST /form/{token}'         => ['Client\FormController', 'submit', ['locale']],
    'GET /form/{token}/thank-you' => ['Client\FormController', 'thankYou', ['locale']],
    'POST /form/{token}/autosave' => ['Client\FormController', 'autosave', []],

    'GET /proposal/{uuid}'        => ['Client\ProposalViewController', 'show', ['locale']],
    'POST /proposal/{uuid}/accept' => ['Client\AcceptController', 'accept', ['locale']],
    'GET /proposal/{uuid}/pdf'    => ['Client\ProposalViewController', 'downloadPdf', ['locale']],

    // =========================================================================
    // PUBLIC DIAGNOSTIC FORM (no auth)
    // =========================================================================
    'GET /diagnostico'           => ['Client\PublicFormController', 'show', ['locale']],
    'POST /diagnostico'          => ['Client\PublicFormController', 'submit', ['locale']],
    'GET /diagnostico/obrigado'  => ['Client\PublicFormController', 'thankYou', ['locale']],
    'POST /diagnostico/cadastro' => ['Client\PublicFormController', 'register', ['locale']],
    'GET /diagnostico/termos'    => ['Client\PublicFormController', 'terms', ['locale']],
    'POST /api/diagnostico/analyze' => ['Client\PublicFormController', 'analyzePreview', []],

    // =========================================================================
    // LOCALE SWITCH
    // =========================================================================
    'GET /lang/{locale}' => ['Auth\LoginController', 'switchLocale', ['locale']],
];
