<?php

return [
    // =========================================================================
    // General
    // =========================================================================
    'app_name'        => 'LLMInvoice',
    'dashboard'       => 'Dashboard',
    'welcome'         => 'Bem-vindo',
    'welcome_back'    => 'Bem-vindo de volta, {name}',
    'save'            => 'Salvar',
    'cancel'          => 'Cancelar',
    'delete'          => 'Excluir',
    'edit'            => 'Editar',
    'create'          => 'Criar',
    'back'            => 'Voltar',
    'search'          => 'Buscar...',
    'filter'          => 'Filtrar',
    'actions'         => 'Ações',
    'confirm'         => 'Confirmar',
    'yes'             => 'Sim',
    'no'              => 'Não',
    'loading'         => 'Carregando...',
    'no_results'      => 'Nenhum resultado encontrado.',
    'showing'         => 'Exibindo {from} a {to} de {total} resultados',
    'server_error'    => 'Erro interno do servidor. Tente novamente mais tarde.',
    'page_not_found'  => 'Página não encontrada.',
    'forbidden'       => 'Acesso negado.',
    'success'         => 'Operação realizada com sucesso!',
    'error'           => 'Ocorreu um erro. Tente novamente.',
    'view_all'        => 'Ver todos',
    'export'          => 'Exportar',
    'import'          => 'Importar',
    'close'           => 'Fechar',
    'details'         => 'Detalhes',
    'status'          => 'Status',
    'date'            => 'Data',
    'value'           => 'Valor',
    'total'           => 'Total',
    'of'              => 'de',
    'all'             => 'Todos',
    'none'            => 'Nenhum',
    'optional'        => 'Opcional',
    'required'        => 'Obrigatório',

    // =========================================================================
    // Auth
    // =========================================================================
    'login'           => 'Entrar',
    'logout'          => 'Sair',
    'email'           => 'E-mail',
    'password'        => 'Senha',
    'remember_me'     => 'Lembrar-me',
    'login_title'     => 'Acessar Plataforma',
    'login_subtitle'  => 'Gerencie seu pipeline comercial com IA',
    'login_failed'    => 'E-mail ou senha incorretos.',
    'account_disabled' => 'Sua conta está desativada. Contate o administrador.',
    'logged_out'      => 'Você saiu com sucesso.',

    // =========================================================================
    // Navigation
    // =========================================================================
    'nav_dashboard'    => 'Dashboard',
    'nav_kanban'       => 'Pipeline',
    'nav_clients'      => 'Clientes',
    'nav_settings'     => 'Configurações',
    'nav_public_forms' => 'Formulários Públicos',
    'nav_audit_log'    => 'Log de Auditoria',
    'nav_general'      => 'Geral',
    'nav_services'     => 'Serviços',
    'nav_branding'     => 'Marca',
    'nav_forms'        => 'Templates de Formulário',

    // =========================================================================
    // Dashboard
    // =========================================================================
    'dashboard_title'       => 'Dashboard',
    'pipeline_overview'     => 'Visão do Pipeline',
    'ai_recommendations'    => 'Recomendações da IA',
    'leads_today'           => 'Leads Hoje',
    'proposals_sent'        => 'Propostas Enviadas',
    'total_revenue'         => 'Receita Total',
    'recent_activity'       => 'Atividade Recente',
    'active_clients'        => 'Clientes Ativos',
    'conversion_rate'       => 'Taxa de Conversão',
    'pending_analyses'      => 'Análises Pendentes',

    // =========================================================================
    // Kanban
    // =========================================================================
    'kanban_title'          => 'Pipeline de Vendas',
    'kanban_search'         => 'Buscar leads...',
    'kanban_filter_temp'    => 'Temperatura',
    'kanban_move_blocked'   => 'Movimentação bloqueada',
    'kanban_no_clients'     => 'Nenhum lead nesta coluna',
    'kanban_drag_hint'      => 'Arraste para mover',
    'kan_cold'              => 'Frio',
    'kan_warm'              => 'Morno',
    'kan_hot'               => 'Quente',

    // =========================================================================
    // Clients
    // =========================================================================
    'clients_title'         => 'Clientes',
    'clients_new'           => 'Novo Cliente',
    'client_name'           => 'Nome do Contato',
    'client_company'        => 'Empresa',
    'client_email'          => 'E-mail',
    'client_phone'          => 'Telefone',
    'client_website'        => 'Website',
    'client_temperature'    => 'Temperatura',
    'client_column'         => 'Etapa do Pipeline',
    'client_assigned'       => 'Responsável',
    'client_source'         => 'Origem',
    'client_created'        => 'Criado em',
    'client_detail_title'   => 'Detalhe do Cliente',
    'client_form_responses' => 'Respostas do Formulário',
    'client_ai_analysis'    => 'Análise de IA',
    'client_proposals'      => 'Propostas',
    'client_timeline'       => 'Timeline / Notas',
    'client_add_note'       => 'Adicionar Nota',
    'client_note_placeholder' => 'Escreva uma nota...',
    'client_archived'       => 'Cliente arquivado com sucesso.',
    'client_created_msg'    => 'Cliente criado com sucesso.',
    'client_updated_msg'    => 'Cliente atualizado com sucesso.',
    'client_send_form'      => 'Enviar Formulário',
    'client_analyze_ai'     => 'Analisar com IA',

    // =========================================================================
    // AI Analysis
    // =========================================================================
    'ai_analyzing'          => 'Analisando com IA...',
    'ai_completed'          => 'Análise concluída!',
    'ai_failed'             => 'Falha na análise de IA. Tente novamente.',
    'ai_diagnosis'          => 'Diagnóstico',
    'ai_recommendations_t'  => 'Recomendações',
    'ai_risks'              => 'Riscos Identificados',
    'ai_pricing'            => 'Faixa de Preço Sugerida',
    'ai_confidence'         => 'Confiança da IA',
    'ai_provider'           => 'Provedor',
    'ai_cost'               => 'Custo da Análise',
    'ai_tokens'             => 'Tokens Utilizados',
    'ai_retry'              => 'Tentar Novamente',

    // =========================================================================
    // Proposals
    // =========================================================================
    'proposals_title'       => 'Propostas',
    'proposal_new'          => 'Nova Proposta',
    'proposal_create_ai'    => 'Gerar Proposta com IA',
    'proposal_version'      => 'Versão {num}',
    'proposal_status'       => 'Status da Proposta',
    'proposal_approve'      => 'Aprovar Proposta',
    'proposal_send'         => 'Enviar para Cliente',
    'proposal_download_pdf' => 'Baixar PDF',
    'proposal_phases'       => 'Fases do Projeto',
    'proposal_deliverables' => 'Entregáveis',
    'proposal_premises'     => 'Premissas',
    'proposal_observations' => 'Observações',
    'proposal_total'        => 'Valor Total',
    'proposal_valid_until'  => 'Válido até',
    'proposal_payment_terms' => 'Condições de Pagamento',
    'proposal_saved'        => 'Proposta salva com sucesso.',
    'proposal_approved'     => 'Proposta aprovada!',
    'proposal_accepted'     => 'Proposta aceita pelo cliente!',
    'proposal_rejected'     => 'Proposta recusada pelo cliente.',

    // Client proposal view
    'proposal_view_title'   => 'Proposta Comercial',
    'proposal_accept'       => 'Aceitar Proposta',
    'proposal_reject'       => 'Recusar Proposta',
    'proposal_reject_reason' => 'Motivo da recusa (opcional)',
    'proposal_accept_confirm' => 'Tem certeza que deseja aceitar esta proposta?',
    'proposal_reject_confirm' => 'Tem certeza que deseja recusar esta proposta?',

    // =========================================================================
    // Email
    // =========================================================================
    'email_generate'        => 'Gerar E-mail com IA',
    'email_send'            => 'Enviar E-mail',
    'email_history'         => 'Histórico de E-mails',
    'email_subject'         => 'Assunto',
    'email_body'            => 'Corpo do E-mail',
    'email_tone'            => 'Tom',
    'email_tone_formal'     => 'Formal',
    'email_tone_friendly'   => 'Amigável',
    'email_tone_urgent'     => 'Urgente',
    'email_tone_followup'   => 'Follow-up',
    'email_sent_success'    => 'E-mail enviado com sucesso!',
    'email_sent_failed'     => 'Falha ao enviar e-mail.',

    // =========================================================================
    // Forms
    // =========================================================================
    'form_title'            => 'Formulário de Diagnóstico',
    'form_step'             => 'Etapa {step} de {total}',
    'form_next'             => 'Próximo',
    'form_prev'             => 'Anterior',
    'form_submit'           => 'Enviar Respostas',
    'form_autosave'         => 'Salvo automaticamente',
    'form_saving'           => 'Salvando...',
    'form_thank_you'        => 'Obrigado!',
    'form_thank_you_msg'    => 'Suas respostas foram enviadas com sucesso. Nossa equipe entrará em contato em breve.',
    'form_expired'          => 'Este formulário não está mais disponível.',
    'form_already_submitted' => 'Este formulário já foi respondido.',
    'form_builder_title'    => 'Editor de Template',

    // =========================================================================
    // Public Diagnostic
    // =========================================================================
    'diag_title'            => 'Diagnóstico Empresarial',
    'diag_subtitle'         => 'Descubra como otimizar seus processos com IA',
    'diag_step1'            => 'Objetivo',
    'diag_step2'            => 'Processos',
    'diag_step3'            => 'Escala',
    'diag_step4'            => 'Contato',
    'diag_step5'            => 'Ansiedade',
    'diag_step6'            => 'Resultado',
    'diag_anxiety_label'    => 'Qual seu nível de urgência? (1-10)',
    'diag_ai_preview'       => 'Preview da IA',
    'diag_terms'            => 'Termos de Uso',
    'diag_agree_terms'      => 'Li e aceito os termos de uso',
    'diag_thank_title'      => 'Diagnóstico Recebido!',
    'diag_thank_msg'        => 'Nosso time de especialistas da Operon está analisando suas respostas com seriedade e governança para projetar a melhor solução. Veja uma prévia da análise da nossa IA:',
    'diag_register_cta'     => 'Criar Conta para Acompanhar',
    'diag_welcome_title'    => '<span id="dynamic-title">Construa</span> o futuro do seu negócio com tecnologia inovadora',
    'diag_welcome_subtitle' => 'Unimos a Inteligência Artificial à expertise de nossos desenvolvedores seniores para analisar seu negócio com seriedade e governança. Nosso time avalia cada detalhe para projetar o motor de software ideal para seus objetivos.',
    'diag_start_btn'        => 'Iniciar Diagnóstico',
    'optional'              => 'opcional',

    // =========================================================================
    // Settings
    // =========================================================================
    'settings_title'        => 'Configurações',
    'settings_general'      => 'Configurações Gerais',
    'settings_services'     => 'Catálogo de Serviços',
    'settings_branding'     => 'Identidade Visual',
    'settings_ai'           => 'Inteligência Artificial',
    'settings_saved'        => 'Configurações salvas com sucesso.',
    'settings_company_name' => 'Nome da Empresa',
    'settings_tagline'      => 'Tagline',
    'settings_logo'         => 'Logo',
    'settings_primary_color' => 'Cor Primária',
    'service_name'          => 'Nome do Serviço',
    'service_category'      => 'Categoria',
    'service_price_range'   => 'Faixa de Preço',
    'service_duration'      => 'Duração (dias)',
    'service_difficulty'    => 'Complexidade',
    'service_add'           => 'Adicionar Serviço',

    // AI Settings
    'ai_active_engine'      => 'Motor de IA Ativo',
    'ai_active_engine_desc' => 'Escolha qual provedor de IA será usado como motor principal para análises e diagnósticos.',
    'ai_model'              => 'Modelo',
    'ai_recommended'        => 'Recomendado',
    'ai_fastest'            => 'Mais Rápido',
    'ai_smartest'           => 'Mais Inteligente',
    'ai_advanced'           => 'Configurações Avançadas',
    'ai_temperature'        => 'Temperatura',
    'ai_temperature_hint'   => '0 = preciso e determinístico, 1 = criativo e variado',
    'ai_max_tokens'         => 'Máx. Tokens',
    'ai_fallback'           => 'Fallback Automático',
    'ai_fallback_desc'      => 'Se o provedor principal falhar, tentar automaticamente os outros.',
    'ai_test_connection'    => 'Testar Conexão',
    'ai_testing'            => 'Testando conexão com a IA...',
    'ai_primary_provider'   => 'Provedor de IA Principal',
    
    // Página Simples (Fallback)
    'diag_thank_you_title'  => 'Diagnóstico Recebido!',
    'diag_thank_you_text'   => 'Recebemos suas informações com sucesso. Nossa inteligência artificial está processando seu diagnóstico e, em breve, você receberá o Blueprint Executivo completo no seu e-mail.',
    'back_home'             => 'Voltar ao Início',

    // Blueprint Executivo
    'blueprint_title'          => 'Blueprint Executivo',
    'blueprint_default_title'  => 'Projeto Personalizado',
    'blueprint_for'            => 'Preparado especialmente para {name}',
    'blueprint_diagnosis'      => 'Diagnóstico',
    'blueprint_phases'         => 'Fases do Projeto',
    'blueprint_recommendations'=> 'Recomendações Estratégicas',
    'blueprint_risks'          => 'Riscos Identificados',
    'blueprint_execution'      => 'Plano de Execução',
    'blueprint_confidence'     => 'Confiança da Análise',
    'blueprint_cta_schedule'       => 'Agendar Reunião Estratégica',
    'blueprint_check_email'        => 'Confira seu e-mail para uma cópia completa deste blueprint',
    'blueprint_suggested_services' => 'Serviços Recomendados',
    'download_pdf'                 => 'Baixar PDF',
    'protocol_title'               => 'Blueprint Executivo',
    'protocol_not_found'           => 'Protocolo não encontrado ou expirado.',
    'protocol_processing'          => 'Análise em Processamento',
    'protocol_processing_text'     => 'Nosso sistema de IA ainda está analisando seus dados. Tente novamente em alguns minutos.',
    'rate_limit_exceeded'          => 'Muitas submissões recentes. Por favor, aguarde alguns minutos antes de tentar novamente.',

    // =========================================================================
    // Public Forms Admin
    // =========================================================================
    'public_forms_title'    => 'Formulários Públicos',
    'public_forms_pending'  => 'Pendentes',
    'public_forms_approved' => 'Aprovados',
    'public_forms_rejected' => 'Rejeitados',
    'public_form_approve'   => 'Aprovar',
    'public_form_reject'    => 'Rejeitar',
    'public_form_convert'   => 'Converter em Cliente',

    // =========================================================================
    // Temperatures
    // =========================================================================
    'temp_cold' => 'Frio',
    'temp_warm' => 'Morno',
    'temp_hot'  => 'Quente',

    // =========================================================================
    // Statuses
    // =========================================================================
    'status_draft'      => 'Rascunho',
    'status_review'     => 'Em Revisão',
    'status_approved'   => 'Aprovado',
    'status_sent'       => 'Enviado',
    'status_accepted'   => 'Aceito',
    'status_rejected'   => 'Recusado',
    'status_pending'    => 'Pendente',
    'status_processing' => 'Processando',
    'status_completed'  => 'Concluído',
    'status_failed'     => 'Falhou',
    'status_converted'  => 'Convertido',

    // =========================================================================
    // Note types
    // =========================================================================
    'note_type_note'      => 'Nota',
    'note_type_follow_up' => 'Follow-up',
    'note_type_call'      => 'Ligação',
    'note_type_meeting'   => 'Reunião',

    // =========================================================================
    // Misc
    // =========================================================================
    'powered_by'          => 'Powered by LLMInvoice',
    'copyright'           => '© {year} LLMInvoice. Todos os direitos reservados.',
];
