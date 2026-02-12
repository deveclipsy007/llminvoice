-- ============================================================
-- LLMInvoice Platform - Complete SQLite Schema
-- 21 Tables + Indexes + Seed Data
-- ============================================================

PRAGMA foreign_keys = ON;
PRAGMA journal_mode = WAL;

-- ============================================================
-- 1. users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    role TEXT DEFAULT 'user' CHECK(role IN ('admin', 'user', 'client')),
    avatar TEXT,
    is_active INTEGER DEFAULT 1,
    last_login_at TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 2. sessions
-- ============================================================
CREATE TABLE IF NOT EXISTS sessions (
    id TEXT PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    payload TEXT,
    ip_address TEXT,
    user_agent TEXT,
    last_activity INTEGER,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 3. pipeline_columns
-- ============================================================
CREATE TABLE IF NOT EXISTS pipeline_columns (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slug TEXT UNIQUE NOT NULL,
    name_pt TEXT NOT NULL,
    name_en TEXT NOT NULL,
    name_es TEXT NOT NULL,
    color TEXT DEFAULT '#C8FF00',
    icon TEXT,
    sort_order INTEGER DEFAULT 0,
    is_final INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 4. pipeline_transition_rules
-- ============================================================
CREATE TABLE IF NOT EXISTS pipeline_transition_rules (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    from_column_id INTEGER REFERENCES pipeline_columns(id) ON DELETE CASCADE,
    to_column_id INTEGER REFERENCES pipeline_columns(id) ON DELETE CASCADE,
    rule_type TEXT NOT NULL,
    rule_value TEXT,
    error_message_pt TEXT,
    error_message_en TEXT,
    error_message_es TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(from_column_id, to_column_id, rule_type)
);

-- ============================================================
-- 5. clients
-- ============================================================
CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid TEXT UNIQUE NOT NULL,
    company_name TEXT,
    contact_name TEXT NOT NULL,
    contact_email TEXT,
    contact_phone TEXT,
    website TEXT,
    temperature TEXT DEFAULT 'warm' CHECK(temperature IN ('cold', 'warm', 'hot')),
    pipeline_column_id INTEGER REFERENCES pipeline_columns(id) DEFAULT 1,
    position_in_column INTEGER DEFAULT 0,
    assigned_user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    form_token TEXT UNIQUE,
    source TEXT DEFAULT 'manual',
    notes_count INTEGER DEFAULT 0,
    is_archived INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 6. form_templates
-- ============================================================
CREATE TABLE IF NOT EXISTS form_templates (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    structure TEXT NOT NULL, -- JSON
    is_active INTEGER DEFAULT 1,
    is_default INTEGER DEFAULT 0,
    created_by INTEGER REFERENCES users(id),
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 7. form_template_clients
-- ============================================================
CREATE TABLE IF NOT EXISTS form_template_clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    form_template_id INTEGER REFERENCES form_templates(id) ON DELETE CASCADE,
    client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(form_template_id, client_id)
);

-- ============================================================
-- 8. form_responses
-- ============================================================
CREATE TABLE IF NOT EXISTS form_responses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
    form_template_id INTEGER REFERENCES form_templates(id),
    responses TEXT NOT NULL, -- JSON
    completion_pct REAL DEFAULT 0,
    is_submitted INTEGER DEFAULT 0,
    submitted_at TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 9. service_catalog
-- ============================================================
CREATE TABLE IF NOT EXISTS service_catalog (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    category TEXT NOT NULL,
    description TEXT,
    base_price_min REAL,
    base_price_max REAL,
    typical_duration_days INTEGER,
    technical_difficulty TEXT DEFAULT 'medium' CHECK(technical_difficulty IN ('low', 'medium', 'high')),
    is_active INTEGER DEFAULT 1,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 10. form_service_rules
-- ============================================================
CREATE TABLE IF NOT EXISTS form_service_rules (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    form_template_id INTEGER REFERENCES form_templates(id) ON DELETE CASCADE,
    service_catalog_id INTEGER REFERENCES service_catalog(id) ON DELETE CASCADE,
    conditions TEXT NOT NULL, -- JSON
    logic_operator TEXT DEFAULT 'AND',
    priority INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 11. ai_analyses
-- ============================================================
CREATE TABLE IF NOT EXISTS ai_analyses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
    triggered_by INTEGER REFERENCES users(id),
    provider TEXT NOT NULL,
    model TEXT NOT NULL,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'processing', 'completed', 'failed')),
    diagnosis TEXT,
    recommendations TEXT,
    risks TEXT,
    proposal_structure TEXT,
    pricing_range TEXT,
    execution_plan TEXT,
    tokens_input INTEGER,
    tokens_output INTEGER,
    cost_usd REAL,
    processing_time_ms INTEGER,
    raw_response TEXT,
    error_message TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 12. proposals
-- ============================================================
CREATE TABLE IF NOT EXISTS proposals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
    uuid TEXT UNIQUE NOT NULL,
    current_version_id INTEGER,
    status TEXT DEFAULT 'draft' CHECK(status IN ('draft', 'review', 'approved', 'sent', 'accepted', 'rejected')),
    created_by INTEGER REFERENCES users(id),
    approved_by INTEGER REFERENCES users(id),
    approved_at TEXT,
    sent_at TEXT,
    accepted_at TEXT,
    rejected_at TEXT,
    rejection_reason TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 13. proposal_versions
-- ============================================================
CREATE TABLE IF NOT EXISTS proposal_versions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    proposal_id INTEGER REFERENCES proposals(id) ON DELETE CASCADE,
    version_number INTEGER DEFAULT 1,
    premises TEXT,
    risks TEXT,
    observations TEXT,
    total_value REAL,
    valid_until TEXT,
    payment_terms TEXT,
    created_by INTEGER REFERENCES users(id),
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(proposal_id, version_number)
);

-- ============================================================
-- 14. proposal_phases
-- ============================================================
CREATE TABLE IF NOT EXISTS proposal_phases (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    proposal_version_id INTEGER REFERENCES proposal_versions(id) ON DELETE CASCADE,
    title TEXT NOT NULL,
    description TEXT,
    duration_days INTEGER,
    value REAL,
    is_optional INTEGER DEFAULT 0,
    sort_order INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 15. proposal_deliverables
-- ============================================================
CREATE TABLE IF NOT EXISTS proposal_deliverables (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    proposal_phase_id INTEGER REFERENCES proposal_phases(id) ON DELETE CASCADE,
    title TEXT NOT NULL,
    description TEXT,
    sort_order INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 16. emails
-- ============================================================
CREATE TABLE IF NOT EXISTS emails (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
    subject TEXT NOT NULL,
    body_html TEXT NOT NULL,
    from_email TEXT,
    to_email TEXT,
    status TEXT DEFAULT 'draft' CHECK(status IN ('draft', 'sent', 'failed')),
    tracking_id TEXT UNIQUE,
    sent_at TEXT,
    created_by INTEGER REFERENCES users(id),
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 17. notes
-- ============================================================
CREATE TABLE IF NOT EXISTS notes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER REFERENCES clients(id) ON DELETE CASCADE,
    user_id INTEGER REFERENCES users(id),
    type TEXT DEFAULT 'note' CHECK(type IN ('note', 'follow_up', 'call', 'meeting')),
    content TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 18. audit_logs
-- ============================================================
CREATE TABLE IF NOT EXISTS audit_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    action TEXT NOT NULL,
    entity_type TEXT,
    entity_id INTEGER,
    old_values TEXT,
    new_values TEXT,
    ip_address TEXT,
    user_agent TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- 19. settings
-- ============================================================
CREATE TABLE IF NOT EXISTS settings (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    setting_key TEXT UNIQUE NOT NULL,
    setting_value TEXT,
    setting_group TEXT DEFAULT 'general',
    setting_type TEXT DEFAULT 'text',
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 20. branding
-- ============================================================
CREATE TABLE IF NOT EXISTS branding (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    company_name TEXT DEFAULT 'LLMInvoice',
    logo_light TEXT,
    logo_dark TEXT,
    primary_color TEXT DEFAULT '#C8FF00',
    tagline TEXT DEFAULT 'AI-Powered Sales Pipeline',
    email_footer_html TEXT,
    proposal_header_html TEXT,
    proposal_footer_html TEXT,
    ai_identity_context TEXT,
    landing_hero_title TEXT,
    landing_hero_subtitle TEXT,
    landing_features TEXT,
    landing_testimonials TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- 21. public_form_responses
-- ============================================================
CREATE TABLE IF NOT EXISTS public_form_responses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    uuid TEXT UNIQUE NOT NULL,
    contact_name TEXT,
    contact_email TEXT,
    contact_phone TEXT,
    company_name TEXT,
    responses TEXT NOT NULL,
    ai_analysis TEXT,
    suggested_services TEXT,
    status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'approved', 'rejected', 'converted')),
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    anxiety_level INTEGER DEFAULT 5,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT
);

-- ============================================================
-- INDEXES
-- ============================================================
CREATE INDEX IF NOT EXISTS idx_clients_pipeline ON clients(pipeline_column_id, position_in_column);
CREATE INDEX IF NOT EXISTS idx_clients_email ON clients(contact_email);
CREATE INDEX IF NOT EXISTS idx_audit_entity ON audit_logs(entity_type, entity_id);
CREATE INDEX IF NOT EXISTS idx_notes_client ON notes(client_id);
CREATE INDEX IF NOT EXISTS idx_emails_client ON emails(client_id);
CREATE INDEX IF NOT EXISTS idx_ai_analyses_client ON ai_analyses(client_id);

-- ============================================================
-- SEED DATA
-- ============================================================

-- 1. Admin user (password = "password")
INSERT INTO users (name, email, password_hash, role) VALUES (
    'Admin',
    'admin@llminvoice.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
);

-- 2. Pipeline columns
INSERT INTO pipeline_columns (slug, name_pt, name_en, name_es, color, sort_order, is_final) VALUES
    ('novo-lead',         'Novo Lead',        'New Lead',           'Nuevo Lead',           '#3B82F6', 1, 0),
    ('briefing-recebido', 'Briefing Recebido','Briefing Received',  'Briefing Recibido',    '#8B5CF6', 2, 0),
    ('analise-ia',        'Análise IA',       'AI Analysis',        'Análisis IA',          '#C8FF00', 3, 0),
    ('proposta-enviada',  'Proposta Enviada',  'Proposal Sent',     'Propuesta Enviada',    '#F59E0B', 4, 0),
    ('aceito-recusado',   'Aceito/Recusado',   'Accepted/Rejected', 'Aceptado/Rechazado',   '#10B981', 5, 1);

-- 3. Transition rules
-- novo-lead (1) -> briefing-recebido (2)
INSERT INTO pipeline_transition_rules (from_column_id, to_column_id, rule_type, rule_value, error_message_pt, error_message_en, error_message_es) VALUES (
    1, 2, 'min_responses', '60',
    'O formulário precisa estar pelo menos 60% preenchido antes de avançar.',
    'The form must be at least 60% completed before advancing.',
    'El formulario debe estar al menos 60% completado antes de avanzar.'
);

-- briefing-recebido (2) -> analise-ia (3)
INSERT INTO pipeline_transition_rules (from_column_id, to_column_id, rule_type, rule_value, error_message_pt, error_message_en, error_message_es) VALUES (
    2, 3, 'ai_completed', NULL,
    'A análise de IA deve ser concluída antes de avançar.',
    'The AI analysis must be completed before advancing.',
    'El análisis de IA debe completarse antes de avanzar.'
);

-- analise-ia (3) -> proposta-enviada (4)
INSERT INTO pipeline_transition_rules (from_column_id, to_column_id, rule_type, rule_value, error_message_pt, error_message_en, error_message_es) VALUES (
    3, 4, 'proposal_approved', NULL,
    'A proposta deve ser aprovada e o e-mail enviado antes de avançar.',
    'The proposal must be approved and the email sent before advancing.',
    'La propuesta debe ser aprobada y el correo enviado antes de avanzar.'
);

-- proposta-enviada (4) -> aceito-recusado (5)
INSERT INTO pipeline_transition_rules (from_column_id, to_column_id, rule_type, rule_value, error_message_pt, error_message_en, error_message_es) VALUES (
    4, 5, 'acceptance_confirmed', NULL,
    'A aceitação ou recusa do cliente deve ser confirmada.',
    'The client acceptance or rejection must be confirmed.',
    'La aceptación o rechazo del cliente debe ser confirmada.'
);

-- Any column -> aceito-recusado (5) for rejection (from_column_id NULL = any)
INSERT INTO pipeline_transition_rules (from_column_id, to_column_id, rule_type, rule_value, error_message_pt, error_message_en, error_message_es) VALUES (
    NULL, 5, 'archive_reason', NULL,
    'É necessário informar o motivo do arquivamento.',
    'A reason for archiving is required.',
    'Se requiere un motivo para el archivado.'
);

-- 4. Service catalog (12 entries)
INSERT INTO service_catalog (name, category, description, base_price_min, base_price_max, typical_duration_days, technical_difficulty) VALUES
    ('Discovery & Diagnostics',   'discovery',            'Comprehensive analysis of current processes, pain points, and opportunities for automation and optimization.', 5000,  15000,  15, 'medium'),
    ('Strategy & Roadmap',        'strategy',             'Strategic planning and technology roadmap aligned with business goals and priorities.',                        8000,  25000,  20, 'medium'),
    ('CRM Implementation',        'crm',                  'End-to-end CRM setup including customization, data migration, workflow automation, and team training.',        15000, 50000,  45, 'medium'),
    ('Marketing Automation',      'marketing_automation', 'Marketing automation platform setup with lead scoring, nurturing flows, campaign management, and analytics.',   12000, 40000,  30, 'medium'),
    ('ERP / Backoffice',          'erp',                  'ERP implementation covering finance, inventory, purchasing, invoicing, and back-office operations.',           30000, 120000, 90, 'high'),
    ('Service Desk & Support',    'service_desk',         'Help desk and customer support platform with ticketing, SLA management, and self-service portal.',            10000, 35000,  30, 'medium'),
    ('Integrations',              'integrations',         'System integration services connecting APIs, webhooks, middleware, and data synchronization.',                 8000,  30000,  25, 'high'),
    ('Data & BI',                 'data_bi',              'Business intelligence dashboards, data warehousing, reporting automation, and analytics.',                     15000, 50000,  40, 'high'),
    ('Data Migration & Quality',  'data_migration',       'Data migration between systems with cleansing, deduplication, validation, and quality assurance.',             10000, 40000,  30, 'high'),
    ('Security & Compliance',     'security',             'Security audits, compliance frameworks (LGPD/GDPR), access controls, and data protection policies.',          12000, 45000,  35, 'high'),
    ('Change Management',         'change_management',    'Organizational change management including training programs, adoption strategies, and stakeholder alignment.', 8000,  25000,  20, 'low'),
    ('Support & Evolution',       'support',              'Ongoing technical support, system maintenance, feature enhancements, and continuous improvement.',             5000,  15000,  NULL, 'low');

-- 5. Default form template (30 questions - 80/20 briefing)
INSERT INTO form_templates (name, description, structure, is_active, is_default, created_by) VALUES (
    'Briefing 80/20',
    'Formulário padrão com 30 perguntas essenciais para diagnóstico completo do cliente.',
    '{
  "sections": [
    {
      "title": "Objetivo e Dor",
      "title_en": "Objective & Pain",
      "title_es": "Objetivo y Dolor",
      "fields": [
        {
          "id": "q1_select",
          "type": "select",
          "label": "Qual o objetivo principal desta iniciativa?",
          "label_en": "What is the primary objective of this initiative?",
          "label_es": "¿Cuál es el objetivo principal de esta iniciativa?",
          "options": ["Aumentar Vendas / Receita", "Reduzir Custos / Retrabalho", "Melhorar Atendimento / SLA", "Decidir com Dados / BI", "Escalar Operação", "Compliance / Segurança"],
          "required": true,
          "ai_weight": "high"
        },
        {
          "id": "q1",
          "label": "Detalhes adicionais sobre o objetivo (opcional):",
          "label_en": "Additional details about the objective (optional):",
          "label_es": "Detalles adicionales sobre el objetivo (opcional):",
          "type": "textarea",
          "required": false,
          "ai_weight": "high",
          "ai_hint": "business_objective"
        },
        {
          "id": "q2_check",
          "type": "checkbox",
          "label": "O que mais te impede de chegar lá hoje?",
          "label_en": "What prevents you the most from getting there today?",
          "label_es": "¿Qué es lo que más te impide llegar allí hoy?",
          "options": ["Processos Manuais / Planilhas", "Dados Espalhados / Sem Confiança", "Falta de Integração entre Sistemas", "Equipe Sobrecarregada", "Processo não Padronizado", "Tecnologia Obsoleta"],
          "required": true,
          "ai_weight": "high"
        },
        {
          "id": "q2",
          "label": "Descreva a \"dor\" principal (opcional):",
          "label_en": "Describe the main \"pain\" (optional):",
          "label_es": "Descritiba el \"dolor\" principal (opcional):",
          "type": "textarea",
          "required": false,
          "ai_weight": "high",
          "ai_hint": "pain_point"
        },
        {
          "id": "q3",
          "label": "Se você pudesse arrumar só UMA coisa amanhã, o que seria?",
          "label_en": "If you could fix just ONE thing tomorrow, what would it be?",
          "label_es": "Si pudieras arreglar solo UNA cosa mañana, ¿qué sería?",
          "type": "textarea",
          "required": false,
          "ai_weight": "high",
          "ai_hint": "critical_process"
        }
      ]
    },
    {
      "title": "Processo e Pessoas",
      "title_en": "Process & People",
      "title_es": "Proceso y Personas",
      "fields": [
        {
          "id": "q4",
          "label": "Onde você mais perde tempo com tarefas manuais atualmente?",
          "label_en": "Where do you lose most time with manual tasks currently?",
          "label_es": "¿Dónde pierdes más tiempo con tarefas manuales atualmente?",
          "type": "checkbox",
          "options": ["Copiar/Colar entre sistemas", "Preencher Planilhas", "Caçar informações em vários lugares", "Gerar Relatórios", "Follow-up de leads", "Aprovar documentos/pedidos"],
          "required": true,
          "ai_weight": "high"
        },
        {
          "id": "q5",
          "label": "Quem participa do processo de ponta a ponta (áreas e papéis)?",
          "label_en": "Who participates in the end-to-end process (areas and roles)?",
          "label_es": "¿Quién participa en el proceso de punta a punta (áreas y roles)?",
          "type": "textarea",
          "required": false,
          "ai_weight": "medium",
          "ai_hint": "stakeholders"
        },
        {
          "id": "q6",
          "label": "Quantas pessoas vão usar o sistema/solução no dia a dia?",
          "label_en": "How many people will use the system/solution on a daily basis?",
          "label_es": "¿Cuántas personas usarán el sistema/solución a diario?",
          "type": "text",
          "required": false,
          "ai_weight": "medium",
          "ai_hint": "scale"
        },
        {
          "id": "q8",
          "label": "Em que pontos alguém precisa aprovar, revisar ou dar OK antes de seguir?",
          "label_en": "At which points does someone need to approve, review or give OK before proceeding?",
          "label_es": "¿En qué puntos alguien necesita aprobar, revisar o dar OK antes de continuar?",
          "type": "textarea",
          "required": false,
          "ai_weight": "medium",
          "ai_hint": "workflow"
        }
      ]
    },
    {
      "title": "Volume e Canais",
      "title_en": "Volume & Channels",
      "title_es": "Volumen y Canales",
      "fields": [
        {
          "id": "q7",
          "label": "Qual o volume mensal aproximado (transações, atendimentos, pedidos, etc.)?",
          "label_en": "What is the approximate monthly volume (transactions, support tickets, orders, etc.)?",
          "label_es": "¿Cuál es el volumen mensal aproximado (transacciones, atenciones, pedidos, etc.)?",
          "type": "select",
          "options": ["Menos de 100", "100-1.000", "1.000-10.000", "10.000+"],
          "required": true,
          "ai_weight": "high",
          "ai_hint": "volume"
        },
        {
          "id": "q15",
          "label": "Quais canais de venda/atendimento vocês usam?",
          "label_en": "Which sales/support channels do you use?",
          "label_es": "¿Qué canales de venta/atención utilizan?",
          "type": "checkbox",
          "options": ["Site", "WhatsApp", "Instagram", "Loja física", "Representantes", "Telefone", "Email"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "channels"
        },
        {
          "id": "q16",
          "label": "O time segue etapas claras no processo comercial/atendimento?",
          "label_en": "Does the team follow clear steps in the sales/support process?",
          "label_es": "¿El equipo sigue etapas claras en el proceso comercial/atención?",
          "type": "select",
          "options": ["Sim, processo definido", "Mais ou menos", "Cada um faz do seu jeito"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "process_maturity"
        },
        {
          "id": "q17",
          "label": "Como acompanha leads e propostas hoje?",
          "label_en": "How do you track leads and proposals today?",
          "label_es": "¿Cómo sigues los leads y propuestas hoy?",
          "type": "select",
          "options": ["Planilha", "Email", "WhatsApp", "Ferramenta específica", "Na cabeça"],
          "required": true,
          "ai_weight": "high",
          "ai_hint": "current_tools"
        },
        {
          "id": "q18",
          "label": "Como funciona o pós-venda/atendimento ao cliente hoje?",
          "label_en": "How does post-sale/customer support work today?",
          "label_es": "¿Cómo funciona el postventa/atención al cliente hoy?",
          "type": "textarea",
          "required": false,
          "ai_weight": "medium",
          "ai_hint": "post_sale"
        }
      ]
    },
    {
      "title": "Dados e Integrações",
      "title_en": "Data & Integrations",
      "title_es": "Datos e Integraciones",
      "fields": [
        {
          "id": "q9",
          "label": "Existe uma \"Fonte Única da Verdade\" para seus dados (Clientes/Produtos)?",
          "label_en": "Is there a \"Single Source of Truth\" for your data?",
          "label_es": "¿Existe una \"Fuente Única de Verdad\" para sus datos?",
          "type": "select",
          "options": ["Sim, um sistema central oficial", "Não, cada área tem o seu", "Parcialmente controlado", "Tudo em Planilhas", "Não sei"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "data_governance"
        },
        {
          "id": "q10",
          "label": "Quais decisões você gostaria de tomar com base em um painel/dashboard?",
          "label_en": "Which decisions would you like to make based on a dashboard?",
          "label_es": "¿Qué decisiones te gustaría tomar basándote en un panel/dashboard?",
          "type": "textarea",
          "required": false,
          "ai_weight": "medium",
          "ai_hint": "bi_needs"
        },
        {
          "id": "q11",
          "label": "Você consegue medir com facilidade os indicadores que importam?",
          "label_en": "Can you easily measure the indicators that matter?",
          "label_es": "¿Puedes medir con facilidad los indicadores que importan?",
          "type": "select",
          "options": ["Sim", "Mais ou menos", "Não"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "data_maturity"
        },
        {
          "id": "q12",
          "label": "Quais ferramentas/sistemas vocês já usam hoje?",
          "label_en": "Which tools/systems do you currently use?",
          "label_es": "¿Qué herramientas/sistemas usan actualmente?",
          "type": "textarea",
          "required": false,
          "ai_weight": "high",
          "ai_hint": "integrations"
        },
        {
          "id": "q14",
          "label": "Como as informações entram no sistema hoje?",
          "label_en": "How does information enter the system today?",
          "label_es": "¿Cómo ingresa la información al sistema hoy?",
          "type": "select",
          "options": ["Digitando manualmente", "Importando planilha", "Formulário online", "Automaticamente"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "data_entry"
        }
      ]
    },
    {
      "title": "Restrições e Decisão",
      "title_en": "Constraints & Decision",
      "title_es": "Restricciones y Decisión",
      "fields": [
        {
          "id": "q19",
          "label": "Vocês controlam estoque, compras ou faturamento de alguma forma?",
          "label_en": "Do you manage inventory, purchasing, or invoicing in any way?",
          "label_es": "¿Controlan inventario, compras o facturación de alguna forma?",
          "type": "select",
          "options": ["Sim", "Não", "Parcialmente"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "erp_need"
        },
        {
          "id": "q20",
          "label": "Existe exigência de privacidade, auditoria ou conformidade regulatória?",
          "label_en": "Are there privacy, audit, or regulatory compliance requirements?",
          "label_es": "¿Existen requisitos de privacidad, auditoría o cumplimiento normativo?",
          "type": "select",
          "options": ["Sim", "Não", "Não sei"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "compliance"
        },
        {
          "id": "q21",
          "label": "Em quanto tempo você precisa ver valor/resultado?",
          "label_en": "How soon do you need to see value/results?",
          "label_es": "¿En cuánto tiempo necesitas ver valor/resultados?",
          "type": "select",
          "options": ["Até 2 semanas", "1-2 meses", "3-6 meses", "Mais de 6 meses"],
          "required": true,
          "ai_weight": "high",
          "ai_hint": "timeline"
        },
        {
          "id": "q22",
          "label": "Existe alguma data inegociável (evento, lançamento, auditoria)?",
          "label_en": "Is there a non-negotiable date (event, launch, audit)?",
          "label_es": "¿Existe alguna fecha innegociable (evento, lanzamiento, auditoría)?",
          "type": "text",
          "required": false,
          "ai_weight": "medium",
          "ai_hint": "deadline"
        },
        {
          "id": "q23",
          "label": "Qual faixa de investimento faz sentido para esse projeto?",
          "label_en": "What investment range makes sense for this project?",
          "label_es": "¿Qué rango de inversión tiene sentido para este proyecto?",
          "type": "select",
          "options": ["Baixo (até R$ 15.000)", "Médio (R$ 15.000 - R$ 50.000)", "Alto (R$ 50.000 - R$ 150.000)", "Premium (acima de R$ 150.000)"],
          "required": true,
          "ai_weight": "high",
          "ai_hint": "budget"
        },
        {
          "id": "q24",
          "label": "Quem é o tomador de decisão final?",
          "label_en": "Who is the final decision maker?",
          "label_es": "¿Quién es el tomador de decisiones final?",
          "type": "select",
          "options": ["Eu mesmo", "Diretoria / CEO", "Conselho", "TI / Compras", "Comitê de várias áreas"],
          "required": true,
          "ai_weight": "high",
          "ai_hint": "decision_makers"
        }
      ]
    },
    {
      "title": "Sucesso e Riscos",
      "title_en": "Success & Risks",
      "title_es": "Éxito y Riesgos",
      "fields": [
        {
          "id": "q25",
          "label": "Quais seriam 3 sinais claros de que o projeto foi um sucesso?",
          "label_en": "What would be 3 clear signs that the project was a success?",
          "label_es": "¿Cuáles serían 3 señales claras de que el proyecto fue un éxito?",
          "type": "textarea",
          "required": false,
          "ai_weight": "high",
          "ai_hint": "success_metrics"
        },
        {
          "id": "q26",
          "label": "Quais são seus maiores medos ou receios com esse tipo de projeto?",
          "label_en": "What are your biggest fears or concerns about this type of project?",
          "label_es": "¿Cuáles son tus mayores miedos o preocupaciones con este tipo de proyecto?",
          "type": "checkbox",
          "options": ["Parar operação", "Perder dados", "Equipe não usar", "Segurança", "Custo alto", "Outro"],
          "required": true,
          "ai_weight": "high",
          "ai_hint": "risks"
        },
        {
          "id": "q27",
          "label": "Se nada mudar nos próximos 12 meses, qual o impacto no seu negócio?",
          "label_en": "If nothing changes in the next 12 months, what is the impact on your business?",
          "label_es": "Si nada cambia en los próximos 12 meses, ¿cuál es el impacto en tu negocio?",
          "type": "textarea",
          "required": false,
          "ai_weight": "high",
          "ai_hint": "cost_of_inaction"
        },
        {
          "id": "q28",
          "label": "Preferência: comprar e ajustar uma solução pronta ou construir sob medida?",
          "label_en": "Preference: buy and customize a ready-made solution or build custom?",
          "label_es": "¿Preferencia: comprar y ajustar una solución lista o construir a medida?",
          "type": "select",
          "options": ["Comprar e ajustar", "Construir sob medida", "Não sei"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "buy_vs_build"
        },
        {
          "id": "q29",
          "label": "Já tentou resolver esse problema antes? O que aconteceu?",
          "label_en": "Have you tried to solve this problem before? What happened?",
          "label_es": "¿Ya intentaste resolver este problema antes? ¿Qué pasó?",
          "type": "textarea",
          "required": false,
          "ai_weight": "medium",
          "ai_hint": "past_attempts"
        },
        {
          "id": "q30",
          "label": "Existe um ponto focal interno que vai acompanhar o projeto?",
          "label_en": "Is there an internal point of contact who will follow the project?",
          "label_es": "¿Existe un punto focal interno que va a acompañar el proyecto?",
          "type": "select",
          "options": ["Sim, já tem alguém definido", "Ainda não, mas teremos", "Não, preciso de ajuda com isso"],
          "required": true,
          "ai_weight": "medium",
          "ai_hint": "champion"
        }
      ]
    }
  ]
}',
    1,
    1,
    1
);

-- 6. Default branding
INSERT INTO branding (company_name, primary_color, tagline, ai_identity_context) VALUES (
    'LLMInvoice',
    '#C8FF00',
    'AI-Powered Sales Pipeline',
    'You are a senior technology consultant for LLMInvoice, a company that helps businesses automate and optimize their commercial processes using AI. You provide clear, actionable recommendations based on the client''s specific needs and context.'
);

-- 7. Default settings
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
    ('per_page',         '15',                    'general'),
    ('session_timeout',  '1800',                  'general'),
    ('company_email',    'admin@llminvoice.com',  'general');
