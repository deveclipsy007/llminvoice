<?php

return [
    // =========================================================================
    // General
    // =========================================================================
    'app_name'        => 'LLMInvoice',
    'dashboard'       => 'Panel',
    'welcome'         => 'Bienvenido',
    'welcome_back'    => 'Bienvenido de vuelta, :name',
    'save'            => 'Guardar',
    'cancel'          => 'Cancelar',
    'delete'          => 'Eliminar',
    'edit'            => 'Editar',
    'create'          => 'Crear',
    'back'            => 'Volver',
    'search'          => 'Buscar...',
    'filter'          => 'Filtrar',
    'actions'         => 'Acciones',
    'confirm'         => 'Confirmar',
    'yes'             => 'Sí',
    'no'              => 'No',
    'loading'         => 'Cargando...',
    'no_results'      => 'No se encontraron resultados.',
    'showing'         => 'Mostrando :from a :to de :total resultados',
    'server_error'    => 'Error interno del servidor. Intente nuevamente más tarde.',
    'page_not_found'  => 'Página no encontrada.',
    'forbidden'       => 'Acceso denegado.',
    'success'         => '¡Operación realizada con éxito!',
    'error'           => 'Ocurrió un error. Intente nuevamente.',
    'view_all'        => 'Ver todos',
    'export'          => 'Exportar',
    'import'          => 'Importar',
    'close'           => 'Cerrar',
    'details'         => 'Detalles',
    'status'          => 'Estado',
    'date'            => 'Fecha',
    'value'           => 'Valor',
    'total'           => 'Total',
    'of'              => 'de',
    'all'             => 'Todos',
    'none'            => 'Ninguno',
    'optional'        => 'Opcional',
    'required'        => 'Obligatorio',

    // =========================================================================
    // Auth
    // =========================================================================
    'login'           => 'Iniciar Sesión',
    'logout'          => 'Cerrar Sesión',
    'email'           => 'Correo',
    'password'        => 'Contraseña',
    'remember_me'     => 'Recuérdame',
    'login_title'     => 'Acceder a la Plataforma',
    'login_subtitle'  => 'Gestiona tu pipeline comercial con IA',
    'login_failed'    => 'Correo o contraseña incorrectos.',
    'account_disabled' => 'Tu cuenta está desactivada. Contacta al administrador.',
    'logged_out'      => 'Has cerrado sesión exitosamente.',

    // =========================================================================
    // Navigation
    // =========================================================================
    'nav_dashboard'    => 'Panel',
    'nav_kanban'       => 'Pipeline',
    'nav_clients'      => 'Clientes',
    'nav_settings'     => 'Configuración',
    'nav_public_forms' => 'Formularios Públicos',
    'nav_audit_log'    => 'Registro de Auditoría',
    'nav_general'      => 'General',
    'nav_services'     => 'Servicios',
    'nav_branding'     => 'Marca',
    'nav_forms'        => 'Plantillas de Formulario',

    // =========================================================================
    // Dashboard
    // =========================================================================
    'dashboard_title'       => 'Panel',
    'pipeline_overview'     => 'Vista del Pipeline',
    'ai_recommendations'    => 'Recomendaciones de IA',
    'leads_today'           => 'Leads Hoy',
    'proposals_sent'        => 'Propuestas Enviadas',
    'total_revenue'         => 'Ingresos Totales',
    'recent_activity'       => 'Actividad Reciente',
    'active_clients'        => 'Clientes Activos',
    'conversion_rate'       => 'Tasa de Conversión',
    'pending_analyses'      => 'Análisis Pendientes',

    // =========================================================================
    // Kanban
    // =========================================================================
    'kanban_title'          => 'Pipeline de Ventas',
    'kanban_search'         => 'Buscar leads...',
    'kanban_filter_temp'    => 'Temperatura',
    'kanban_move_blocked'   => 'Movimiento bloqueado',
    'kanban_no_clients'     => 'No hay leads en esta columna',
    'kanban_drag_hint'      => 'Arrastra para mover',
    'kan_cold'              => 'Frío',
    'kan_warm'              => 'Tibio',
    'kan_hot'               => 'Caliente',

    // =========================================================================
    // Clients
    // =========================================================================
    'clients_title'         => 'Clientes',
    'clients_new'           => 'Nuevo Cliente',
    'client_name'           => 'Nombre del Contacto',
    'client_company'        => 'Empresa',
    'client_email'          => 'Correo',
    'client_phone'          => 'Teléfono',
    'client_website'        => 'Sitio Web',
    'client_temperature'    => 'Temperatura',
    'client_column'         => 'Etapa del Pipeline',
    'client_assigned'       => 'Asignado a',
    'client_source'         => 'Origen',
    'client_created'        => 'Creado el',
    'client_detail_title'   => 'Detalle del Cliente',
    'client_form_responses' => 'Respuestas del Formulario',
    'client_ai_analysis'    => 'Análisis de IA',
    'client_proposals'      => 'Propuestas',
    'client_timeline'       => 'Timeline / Notas',
    'client_add_note'       => 'Agregar Nota',
    'client_note_placeholder' => 'Escribe una nota...',
    'client_archived'       => 'Cliente archivado exitosamente.',
    'client_created_msg'    => 'Cliente creado exitosamente.',
    'client_updated_msg'    => 'Cliente actualizado exitosamente.',
    'client_send_form'      => 'Enviar Formulario',
    'client_analyze_ai'     => 'Analizar con IA',

    // =========================================================================
    // AI Analysis
    // =========================================================================
    'ai_analyzing'          => 'Analizando con IA...',
    'ai_completed'          => '¡Análisis completado!',
    'ai_failed'             => 'Falló el análisis de IA. Intente nuevamente.',
    'ai_diagnosis'          => 'Diagnóstico',
    'ai_recommendations_t'  => 'Recomendaciones',
    'ai_risks'              => 'Riesgos Identificados',
    'ai_pricing'            => 'Rango de Precio Sugerido',
    'ai_confidence'         => 'Confianza de la IA',
    'ai_provider'           => 'Proveedor',
    'ai_cost'               => 'Costo del Análisis',
    'ai_tokens'             => 'Tokens Utilizados',
    'ai_retry'              => 'Intentar Nuevamente',

    // =========================================================================
    // Proposals
    // =========================================================================
    'proposals_title'       => 'Propuestas',
    'proposal_new'          => 'Nueva Propuesta',
    'proposal_create_ai'    => 'Generar Propuesta con IA',
    'proposal_version'      => 'Versión :num',
    'proposal_status'       => 'Estado de la Propuesta',
    'proposal_approve'      => 'Aprobar Propuesta',
    'proposal_send'         => 'Enviar al Cliente',
    'proposal_download_pdf' => 'Descargar PDF',
    'proposal_phases'       => 'Fases del Proyecto',
    'proposal_deliverables' => 'Entregables',
    'proposal_premises'     => 'Premisas',
    'proposal_observations' => 'Observaciones',
    'proposal_total'        => 'Valor Total',
    'proposal_valid_until'  => 'Válido hasta',
    'proposal_payment_terms' => 'Condiciones de Pago',
    'proposal_saved'        => 'Propuesta guardada exitosamente.',
    'proposal_approved'     => '¡Propuesta aprobada!',
    'proposal_accepted'     => '¡Propuesta aceptada por el cliente!',
    'proposal_rejected'     => 'Propuesta rechazada por el cliente.',

    'proposal_view_title'    => 'Propuesta Comercial',
    'proposal_accept'        => 'Aceptar Propuesta',
    'proposal_reject'        => 'Rechazar Propuesta',
    'proposal_reject_reason' => 'Motivo del rechazo (opcional)',
    'proposal_accept_confirm' => '¿Está seguro de que desea aceptar esta propuesta?',
    'proposal_reject_confirm' => '¿Está seguro de que desea rechazar esta propuesta?',

    // =========================================================================
    // Email
    // =========================================================================
    'email_generate'        => 'Generar Correo con IA',
    'email_send'            => 'Enviar Correo',
    'email_history'         => 'Historial de Correos',
    'email_subject'         => 'Asunto',
    'email_body'            => 'Cuerpo del Correo',
    'email_tone'            => 'Tono',
    'email_tone_formal'     => 'Formal',
    'email_tone_friendly'   => 'Amigable',
    'email_tone_urgent'     => 'Urgente',
    'email_tone_followup'   => 'Seguimiento',
    'email_sent_success'    => '¡Correo enviado exitosamente!',
    'email_sent_failed'     => 'Error al enviar el correo.',

    // =========================================================================
    // Forms
    // =========================================================================
    'form_title'            => 'Formulario de Diagnóstico',
    'form_step'             => 'Paso :step de :total',
    'form_next'             => 'Siguiente',
    'form_prev'             => 'Anterior',
    'form_submit'           => 'Enviar Respuestas',
    'form_autosave'         => 'Guardado automáticamente',
    'form_saving'           => 'Guardando...',
    'form_thank_you'        => '¡Gracias!',
    'form_thank_you_msg'    => 'Sus respuestas han sido enviadas exitosamente. Nuestro equipo se pondrá en contacto pronto.',
    'form_expired'          => 'Este formulario ya no está disponible.',
    'form_already_submitted' => 'Este formulario ya fue respondido.',
    'form_builder_title'    => 'Editor de Plantilla',

    // =========================================================================
    // Public Diagnostic
    // =========================================================================
    'diag_title'            => 'Diagnóstico Empresarial',
    'diag_subtitle'         => 'Descubre cómo optimizar tus procesos con IA',
    'diag_step1'            => 'Objetivo',
    'diag_step2'            => 'Procesos',
    'diag_step3'            => 'Escala',
    'diag_step4'            => 'Contacto',
    'diag_step5'            => 'Urgencia',
    'diag_step6'            => 'Resultado',
    'diag_anxiety_label'    => '¿Cuál es tu nivel de urgencia? (1-10)',
    'diag_ai_preview'       => 'Vista Previa IA',
    'diag_terms'            => 'Términos de Uso',
    'diag_agree_terms'      => 'He leído y acepto los términos de uso',
    'diag_thank_title'      => '¡Diagnóstico Recibido!',
    'diag_thank_msg'        => 'Nuestro equipo de especialistas de Operon está analizando sus respuestas con seriedad y gobernanza para diseñar la mejor solución. Vea una vista previa del análisis realizado por nuestra IA:',
    'diag_register_cta'     => 'Crear Cuenta para Seguimiento',
    'diag_welcome_title'    => '<span id="dynamic-title">Desbloquea</span> el potencial ilimitado de tu negocio',
    'diag_welcome_subtitle' => 'Combinamos la Inteligencia Artificial con la experiencia de nuestros desarrolladores senior para analizar su negocio con seriedad y gobernanza. Nuestro equipo evalúa cada detalle para diseñar el motor de software ideal para sus objetivos.',
    'diag_start_btn'        => 'Iniciar Diagnóstico',
    'optional'              => 'opcional',

    // =========================================================================
    // Settings
    // =========================================================================
    'settings_title'        => 'Configuración',
    'settings_general'      => 'Configuración General',
    'settings_services'     => 'Catálogo de Servicios',
    'settings_branding'     => 'Identidad Visual',
    'settings_ai'           => 'Inteligencia Artificial',
    'settings_saved'        => 'Configuración guardada exitosamente.',
    'settings_company_name' => 'Nombre de la Empresa',
    'settings_tagline'      => 'Eslogan',
    'settings_logo'         => 'Logo',
    'settings_primary_color' => 'Color Primario',
    'service_name'          => 'Nombre del Servicio',
    'service_category'      => 'Categoría',
    'service_price_range'   => 'Rango de Precio',
    'service_duration'      => 'Duración (días)',
    'service_difficulty'    => 'Complejidad',
    'service_add'           => 'Agregar Servicio',

    // AI Settings
    'ai_active_engine'      => 'Motor de IA Activo',
    'ai_active_engine_desc' => 'Elija qué proveedor de IA se usará como motor principal para análisis y diagnósticos.',
    'ai_model'              => 'Modelo',
    'ai_recommended'        => 'Recomendado',
    'ai_fastest'            => 'Más Rápido',
    'ai_smartest'           => 'Más Inteligente',
    'ai_advanced'           => 'Configuración Avanzada',
    'ai_temperature'        => 'Temperatura',
    'ai_temperature_hint'   => '0 = preciso y determinístico, 1 = creativo y variado',
    'ai_max_tokens'         => 'Máx. Tokens',
    'ai_fallback'           => 'Fallback Automático',
    'ai_fallback_desc'      => 'Si el proveedor principal falla, intentar automáticamente los otros.',
    'ai_test_connection'    => 'Probar Conexión',
    'ai_testing'            => 'Probando conexión con la IA...',
    'ai_primary_provider'   => 'Proveedor de IA Principal',

    // Blueprint Ejecutivo
    'blueprint_title'          => 'Blueprint Ejecutivo',
    'blueprint_default_title'  => 'Proyecto Personalizado',
    'blueprint_for'            => 'Preparado especialmente para :name',
    'blueprint_diagnosis'      => 'Diagnóstico',
    'blueprint_phases'         => 'Fases del Proyecto',
    'blueprint_recommendations'=> 'Recomendaciones Estratégicas',
    'blueprint_risks'          => 'Riesgos Identificados',
    'blueprint_execution'      => 'Plan de Ejecución',
    'blueprint_confidence'     => 'Confianza del Análisis',
    'blueprint_cta_schedule'       => 'Agendar Reunión Estratégica',
    'blueprint_check_email'        => 'Revisa tu correo para una copia completa de este blueprint',
    'blueprint_suggested_services' => 'Servicios Recomendados',
    'download_pdf'                 => 'Descargar PDF',
    'protocol_title'               => 'Blueprint Ejecutivo',
    'protocol_not_found'           => 'Protocolo no encontrado o expirado.',
    'protocol_processing'          => 'Análisis en Proceso',
    'protocol_processing_text'     => 'Nuestro sistema de IA aún está analizando sus datos. Inténtelo de nuevo en unos minutos.',
    'rate_limit_exceeded'          => 'Demasiados envíos recientes. Por favor, espere unos minutos antes de intentar de nuevo.',

    // =========================================================================
    // Public Forms Admin
    // =========================================================================
    'public_forms_title'    => 'Formularios Públicos',
    'public_forms_pending'  => 'Pendientes',
    'public_forms_approved' => 'Aprobados',
    'public_forms_rejected' => 'Rechazados',
    'public_form_approve'   => 'Aprobar',
    'public_form_reject'    => 'Rechazar',
    'public_form_convert'   => 'Convertir en Cliente',

    // =========================================================================
    // Temperatures
    // =========================================================================
    'temp_cold' => 'Frío',
    'temp_warm' => 'Tibio',
    'temp_hot'  => 'Caliente',

    // =========================================================================
    // Statuses
    // =========================================================================
    'status_draft'      => 'Borrador',
    'status_review'     => 'En Revisión',
    'status_approved'   => 'Aprobado',
    'status_sent'       => 'Enviado',
    'status_accepted'   => 'Aceptado',
    'status_rejected'   => 'Rechazado',
    'status_pending'    => 'Pendiente',
    'status_processing' => 'Procesando',
    'status_completed'  => 'Completado',
    'status_failed'     => 'Fallido',
    'status_converted'  => 'Convertido',

    // =========================================================================
    // Note types
    // =========================================================================
    'note_type_note'      => 'Nota',
    'note_type_follow_up' => 'Seguimiento',
    'note_type_call'      => 'Llamada',
    'note_type_meeting'   => 'Reunión',

    // =========================================================================
    // Misc
    // =========================================================================
    'powered_by'          => 'Powered by LLMInvoice',
    'copyright'           => '© :year LLMInvoice. Todos los derechos reservados.',
];
